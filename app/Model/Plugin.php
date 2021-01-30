<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use JsonPath\JsonObject;
use Acelle\Library\QuotaTrackerFile;
use Acelle\Library\Log as MailLog;
use Validator;
use ZipArchive;
use Illuminate\Validation\ValidationException;
use Acelle\Library\Tool;

class Plugin extends Model
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    protected $fillable = ['name', 'title', 'description', 'version'];
    protected $requiredKeys = [
        'name', 'version', 'app_version', 'xxxx'
    ];

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = self::select('plugins.*');

        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('plugins.name', 'like', '%'.$keyword.'%')
                        ->orWhere('plugins.description', 'like', '%'.$keyword.'%')
                        ->orWhere('plugins.version', 'like', '%'.$keyword.'%');
                });
            }
        }

        // filters
        $filters = $request->filters;
        if (!empty($filters)) {
            if (!empty($filters['type'])) {
                $query = $query->where('plugins.type', '=', $filters['type']);
            }
        }

        return $query;
    }

    /**
     * Search items.
     *
     * @return collect
     */
    public static function search($request)
    {
        $query = self::filter($request);

        if (!empty($request->sort_order)) {
            $query = $query->orderBy($request->sort_order, $request->sort_direction);
        }

        return $query;
    }

    /**
     * Get campaign validation rules.
     */
    public function rules()
    {
        $rules = array(
            'name' => 'required',
            'version' => 'required',
        );

        return $rules;
    }

    /**
     * Get all items.
     *
     * @return collect
     */
    public static function scopeActive($query)
    {
        return $query->where('status', '=', self::STATUS_ACTIVE);
    }

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating list.
        static::creating(function ($item) {
            // Create new uid
            $uid = uniqid();
            while (self::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;
        });

        // While deleting
        static::deleting(function ($item) {
            // remove source code
            Tool::xdelete($item->getPluginPath());
        });
    }

    /**
     * Find item by uid.
     *
     * @return object
     */
    public static function findByUid($uid)
    {
        return self::where('uid', '=', $uid)->first();
    }

    /**
     * Disable verification server.
     *
     * @return array
     */
    public function disable()
    {
        $this->status = self::STATUS_INACTIVE;
        $this->save();
    }

    /**
     * Enable verification server.
     *
     * @return array
     */
    public function enable()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->save();
    }

    /**
     * Upload a template.
     */
    public static function uploadAndSave($request)
    {
        $pluginsPath = storage_path('app/plugins');

        $rules = array(
            'file' => 'required|mimetypes:application/zip,application/octet-stream,application/x-zip-compressed,multipart/x-zip',
        );

        $validator = Validator::make($request->all(), $rules, [
            'file.mimetypes' => 'Input must be a valid .zip file',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // move file to temp place
        $tmp_path = storage_path('tmp/uploaded_plugin_'.time());
        $file_name = $request->file('file')->getClientOriginalName();
        $request->file('file')->move($tmp_path, $file_name);
        $tmp_zip = join_paths($tmp_path, $file_name);

        // read zip file check if zip archive invalid
        $zip = new ZipArchive();
        if ($zip->open($tmp_zip, ZipArchive::CREATE) !== true) {
            $validator->errors()->add('file', 'Cannot open plugin package');
            throw new ValidationException($validator);
        }

        // unzip template archive and remove zip file
        $zip->extractTo($tmp_path);
        $zip->close();
        unlink($tmp_zip);

        // read plugin file
        if (!is_file($configFile = $tmp_path.'/composer.json')) {
            throw new \Exception('Invalid plugin package. No meta data found');
        }
        
        try {
            $config = json_decode(file_get_contents($configFile), true);
        } catch (\Exception $ex) {
            throw new \Exception('Invalid plugin package. Cannot parse meta data');
        }

        $names = explode('/', $config['name']);
        // check valid plugin name
        if (count($names) != 2) {
            throw new \Exception('Invalid plugin package. No meta data found');
        }
        $pluginVendor = $names[0];
        $pluginName = $names[1];

        // chek if plugin exist
        $pluginsVendorPath = $pluginsPath . '/' . $pluginVendor;
        $pluginsSourcePath = $pluginsVendorPath . '/' . $pluginName;

        // create folders
        $paths = [
            $pluginsPath,
            $pluginsVendorPath,
            $pluginsSourcePath,
        ];
        foreach ($paths as $path) {
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
        }

        // check if overwrite plugin
        if ($request->overwrite) {
            $config['overwrite'] = true;
        }
        
        // throw exception if any required key is missing
        self::validateMetaData($config);

        // new plugin
        $plugin = self::where('name', '=', $config['name'])->first();
        // ->where('type', '=', $config['type'])
        if (!$plugin) {
            $plugin = new \Acelle\Model\Plugin();
            $plugin->status = \Acelle\Model\Plugin::STATUS_ACTIVE;
        }
        $plugin->name = $config['name'];
        $plugin->title = $config['title'];
        $plugin->description = (array_key_exists('description', $config)) ? $config['description'] : null;
        $plugin->version = $config['version'];

        // remove tmp folder
        // exec("rm -r {$tmp_path}");
        Tool::xdelete($pluginsSourcePath);
        if (!file_exists($pluginsSourcePath)) {
            mkdir($pluginsSourcePath, 0777, true);
        }
        Tool::xcopy($tmp_path, $pluginsSourcePath);
        Tool::xdelete($tmp_path);

        $plugin->save();

        return $plugin;
    }

    /**
     * Get plugin path.
     *
     * @return string
     */
    public function getPluginPath()
    {
        $pluginsPath = storage_path('app/plugins');

        $names = explode('/', $this->name);

        $pluginVendor = $names[0];
        $pluginName = $names[1];

        // chek if plugin exist
        $pluginsVendorPath = $pluginsPath . '/' . $pluginVendor;
        $pluginsSourcePath = $pluginsVendorPath . '/' . $pluginName;

        return $pluginsSourcePath;
    }

    /**
     * Get plugin type select options.
     *
     * @return array
     */
    public static function typeSelectOptions()
    {
        $options = [
            ['value' => 'payment', 'text' => trans('messages.plugin.type.payment')],
            ['value' => 'sending_server', 'text' => trans('messages.plugin.type.sending_server')],
            ['value' => 'email_verification', 'text' => trans('messages.plugin.type.email_verification')],
        ];

        return $options;
    }

    /**
     * Check if plugin is installed.
     *
     * @return array
     */
    public static function isInstalled($name)
    {
        return self::where('name', '=', $name)->count();
    }

    /**
     * Check if plugin is active.
     *
     * @return array
     */
    public static function isActive($name)
    {
        return self::where('name', '=', $name)->where('status', '=', self::STATUS_ACTIVE)->count();
    }

    /**
     * Get plugin by name.
     *
     * @return array
     */
    public static function getByName($name)
    {
        return self::where('name', '=', $name)->first();
    }

    public static function validateMetaData($config = [])
    {
        // Check required keys
        $requiredKeys = ['name', 'title', 'version', 'app_version']; // ['name', 'title', 'version', 'app_version'];
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $config)) {
                throw new \Exception("Invalid plugin package. Unknown '{$key}'");
            }
        }

        $exist = self::where('name', $config['name'])->first();
        if ($exist && !isset($config['overwrite']))  {
            if (version_compare($exist->version, $config['version'], '>=')) {
                throw new \Exception("An newer version of the {$confif['name']} plugin already exists (version : {$exist->version}), do you want to replace it?");
            } else {
                throw new \Exception("An older version of the {$confif['name']} plugin already exists (version : {$exist->version}), do you want to replace it?");
            }
                
        }

        // Check app version
        $currentVersion = app_version();
        $requiredVersion = $config['app_version'];
        $checked = version_compare($currentVersion, $requiredVersion, '>=');
        if (!$checked) {
            throw new \Exception("The uploaded plugin requires Acelle platform version {$requiredVersion} or higher. The current version is {$currentVersion}");
        }

        // // Check license type
        // if (empty(Setting::get('license_type'))) {
        //     throw new \Exception("The uploaded plugin requires a valid license of Acelle to proceed");
        // }

        // // Check license type
        // if (Setting::get('license_type') != 'extended') {
        //     throw new \Exception("The uploaded plugin requires an 'Extended' license of Acelle to proceed, your current license is 'Regular'");
        // }
    }

    /**
     * Get data.
     *
     * @var object | collect
     */
    public function getData()
    {
        if (!$this['data']) {
            return json_decode('{}', true);
        }

        return json_decode($this['data'], true);
    }

    /**
     * Get data.
     *
     * @var object | collect
     */
    public function updateData($data)
    {
        $data = (object) array_merge((array) $this->getData(), $data);
        $this['data'] = json_encode($data);

        $this->save();
    }
}
