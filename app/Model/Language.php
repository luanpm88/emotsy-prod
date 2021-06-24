<?php

/**
 * Language class.
 *
 * Model class for languages
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   MVC Model
 *
 * @author     N. Pham <n.pham@acellemail.com>
 * @author     L. Pham <l.pham@acellemail.com>
 * @copyright  Acelle Co., Ltd
 * @license    Acelle Co., Ltd
 *
 * @version    1.0
 *
 * @link       http://acellemail.com
 */

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Facades\Hook;

class Language extends Model
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * Get users.
     *
     * @return mixed
     */
    public function users()
    {
        return $this->hasMany('Acelle\Model\User');
    }

    /**
     * Customer association.
     *
     * @return mixed
     */
    public function customers()
    {
        return $this->hasMany('Acelle\Model\Customer');
    }

    /**
     * Admin association.
     *
     * @return mixed
     */
    public function admins()
    {
        return $this->hasMany('Acelle\Model\Admin');
    }

    /**
     * Language folder path.
     *
     * @return string
     */
    public function languageDir()
    {
        return base_path('resources/lang/'.$this->code.'/');
    }

    /**
     * Get all languages.
     *
     * @return collect
     */
    public static function getAll()
    {
        return self::where('status', '=', 'active')->get();
    }

    /**
     * Get language by code.
     *
     * @param string $code
     *
     * @return collect
     */
    public static function getByCode($code)
    {
        return self::where('code', '=', $code)->first();
    }

    /**
     * Get select options.
     *
     * @return array
     */
    public static function getSelectOptions()
    {
        $options = self::getAll()->map(function ($item) {
            return ['value' => $item->id, 'text' => $item->name];
        });

        return $options;
    }

    /**
     * Search items.
     *
     * @return collect
     */
    public static function scopeSearch($query, $keyword)
    {
        // Keyword
        if (!empty(trim($keyword))) {
            $keyword = trim($keyword);
            foreach (explode(' ', $keyword) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('languages.name', 'like', '%'.$keyword.'%')
                        ->orwhere('languages.code', 'like', '%'.$keyword.'%')
                        ->orwhere('languages.region_code', 'like', '%'.$keyword.'%');
                });
            }
        }
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
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

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
            $item->uid = $uid;
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'region_code',
    ];

    /**
     * Get validation rules.
     *
     * @return object
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required|unique:languages,code,'.$this->id,
        ];
    }

    /**
     * Get default language.
     *
     * @var object
     */
    public static function getDefaultLanguage()
    {
        return new \Acelle\Model\Language([
            'name' => 'Default',
            'code' => 'default',
        ]);
    }

    /**
     * Get is default language.
     *
     * @var object
     */
    public static function getIsDefaultLanguage()
    {
        return \Acelle\Model\Language::where('is_default', '=', true)->first();
    }

    /**
     * Get locale array from file.
     *
     * @var array
     */
    public function getLocaleArrayFromFile($filename)
    {
        clearstatcache();
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($this->languageDir().$filename.'.php');
        }

        $arr = self::fileToArray($this->languageDir().$filename.'.php');
        return $arr;
    }

    /**
     * Read locale file.
     *
     * @var text
     */
    public function readLocaleFile($filename)
    {
        $text = \File::get($this->languageDir().$filename.'.php');

        return $text;
    }

    /**
     * Read locale file.
     *
     * @var text
     */
    public function localeToYaml($filename)
    {
        $text = $this->readLocaleFile($filename);

        return yaml_parse($text);
    }

    /**
     * Update language file from yaml.
     *
     * @var text
     */
    public function updateFromYaml($filename, $yaml)
    {
        self::yamlToFile($this->languageDir().$filename.'.php', $yaml);
    }

    /**
     * Update language file from yaml.
     *
     * @var text
     */
    public function getBuilderLang()
    {
        return include $this->languageDir().'builder.php';
    }

    /**
     * all language code.
     *
     * @return array
     */
    public static function languageCodes()
    {
        $arr = array(
            'aa' => 'Afar',
            'ab' => 'Abkhaz',
            'ae' => 'Avestan',
            'af' => 'Afrikaans',
            'ak' => 'Akan',
            'am' => 'Amharic',
            'an' => 'Aragonese',
            'ar' => 'Arabic',
            'as' => 'Assamese',
            'av' => 'Avaric',
            'ay' => 'Aymara',
            'az' => 'Azerbaijani',
            'ba' => 'Bashkir',
            'be' => 'Belarusian',
            'bg' => 'Bulgarian',
            'bh' => 'Bihari',
            'bi' => 'Bislama',
            'bm' => 'Bambara',
            'bn' => 'Bengali',
            'bo' => 'Tibetan Standard, Tibetan, Central',
            'br' => 'Breton',
            'bs' => 'Bosnian',
            'ca' => 'Catalan; Valencian',
            'ce' => 'Chechen',
            'ch' => 'Chamorro',
            'co' => 'Corsican',
            'cr' => 'Cree',
            'cs' => 'Czech',
            'cu' => 'Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic',
            'cv' => 'Chuvash',
            'cy' => 'Welsh',
            'da' => 'Danish',
            'de' => 'German',
            'dv' => 'Divehi; Dhivehi; Maldivian;',
            'dz' => 'Dzongkha',
            'ee' => 'Ewe',
            'el' => 'Greek, Modern',
            'en' => 'English',
            'eo' => 'Esperanto',
            'es' => 'Spanish; Castilian',
            'et' => 'Estonian',
            'eu' => 'Basque',
            'fa' => 'Persian',
            'ff' => 'Fula; Fulah; Pulaar; Pular',
            'fi' => 'Finnish',
            'fj' => 'Fijian',
            'fo' => 'Faroese',
            'fr' => 'French',
            'fy' => 'Western Frisian',
            'ga' => 'Irish',
            'gd' => 'Scottish Gaelic; Gaelic',
            'gl' => 'Galician',
            'gn' => 'GuaranÃ­',
            'gu' => 'Gujarati',
            'gv' => 'Manx',
            'ha' => 'Hausa',
            'he' => 'Hebrew (modern)',
            'hi' => 'Hindi',
            'ho' => 'Hiri Motu',
            'hr' => 'Croatian',
            'ht' => 'Haitian; Haitian Creole',
            'hu' => 'Hungarian',
            'hy' => 'Armenian',
            'hz' => 'Herero',
            'ia' => 'Interlingua',
            'id' => 'Indonesian',
            'ie' => 'Interlingue',
            'ig' => 'Igbo',
            'ii' => 'Nuosu',
            'ik' => 'Inupiaq',
            'io' => 'Ido',
            'is' => 'Icelandic',
            'it' => 'Italian',
            'iu' => 'Inuktitut',
            'ja' => 'Japanese (ja)',
            'jv' => 'Javanese (jv)',
            'ka' => 'Georgian',
            'kg' => 'Kongo',
            'ki' => 'Kikuyu, Gikuyu',
            'kj' => 'Kwanyama, Kuanyama',
            'kk' => 'Kazakh',
            'kl' => 'Kalaallisut, Greenlandic',
            'km' => 'Khmer',
            'kn' => 'Kannada',
            'ko' => 'Korean',
            'kr' => 'Kanuri',
            'ks' => 'Kashmiri',
            'ku' => 'Kurdish',
            'kv' => 'Komi',
            'kw' => 'Cornish',
            'ky' => 'Kirghiz, Kyrgyz',
            'la' => 'Latin',
            'lb' => 'Luxembourgish, Letzeburgesch',
            'lg' => 'Luganda',
            'li' => 'Limburgish, Limburgan, Limburger',
            'ln' => 'Lingala',
            'lo' => 'Lao',
            'lt' => 'Lithuanian',
            'lu' => 'Luba-Katanga',
            'lv' => 'Latvian',
            'mg' => 'Malagasy',
            'mh' => 'Marshallese',
            'mi' => 'Maori',
            'mk' => 'Macedonian',
            'ml' => 'Malayalam',
            'mn' => 'Mongolian',
            'mr' => 'Marathi (Mara?hi)',
            'ms' => 'Malay',
            'mt' => 'Maltese',
            'my' => 'Burmese',
            'na' => 'Nauru',
            'nb' => 'Norwegian BokmÃ¥l',
            'nd' => 'North Ndebele',
            'ne' => 'Nepali',
            'ng' => 'Ndonga',
            'nl' => 'Dutch',
            'nn' => 'Norwegian Nynorsk',
            'no' => 'Norwegian',
            'nr' => 'South Ndebele',
            'nv' => 'Navajo, Navaho',
            'ny' => 'Chichewa; Chewa; Nyanja',
            'oc' => 'Occitan',
            'oj' => 'Ojibwe, Ojibwa',
            'om' => 'Oromo',
            'or' => 'Oriya',
            'os' => 'Ossetian, Ossetic',
            'pa' => 'Panjabi, Punjabi',
            'pi' => 'Pali',
            'pl' => 'Polish',
            'ps' => 'Pashto, Pushto',
            'pt' => 'Portuguese',
            'qu' => 'Quechua',
            'rm' => 'Romansh',
            'rn' => 'Kirundi',
            'ro' => 'Romanian, Moldavian, Moldovan',
            'ru' => 'Russian',
            'rw' => 'Kinyarwanda',
            'sa' => 'Sanskrit (Sa?sk?ta)',
            'sc' => 'Sardinian',
            'sd' => 'Sindhi',
            'se' => 'Northern Sami',
            'sg' => 'Sango',
            'si' => 'Sinhala, Sinhalese',
            'sk' => 'Slovak',
            'sl' => 'Slovene',
            'sm' => 'Samoan',
            'sn' => 'Shona',
            'so' => 'Somali',
            'sq' => 'Albanian',
            'sr' => 'Serbian',
            'ss' => 'Swati',
            'st' => 'Southern Sotho',
            'su' => 'Sundanese',
            'sv' => 'Swedish',
            'sw' => 'Swahili',
            'ta' => 'Tamil',
            'te' => 'Telugu',
            'tg' => 'Tajik',
            'th' => 'Thai',
            'ti' => 'Tigrinya',
            'tk' => 'Turkmen',
            'tl' => 'Tagalog',
            'tn' => 'Tswana',
            'to' => 'Tonga (Tonga Islands)',
            'tr' => 'Turkish',
            'ts' => 'Tsonga',
            'tt' => 'Tatar',
            'tw' => 'Twi',
            'ty' => 'Tahitian',
            'ug' => 'Uighur, Uyghur',
            'uk' => 'Ukrainian',
            'ur' => 'Urdu',
            'uz' => 'Uzbek',
            've' => 'Venda',
            'vi' => 'Vietnamese',
            'vo' => 'VolapÃ¼k',
            'wa' => 'Walloon',
            'wo' => 'Wolof',
            'xh' => 'Xhosa',
            'yi' => 'Yiddish',
            'yo' => 'Yoruba',
            'za' => 'Zhuang, Chuang',
            'zh' => 'Chinese',
            'zu' => 'Zulu',
        );

        $result = [];
        foreach ($arr as $key => $name) {
            $result[] = [
                'text' => strtoupper($key).' / '.$name,
                'value' => $key,
            ];
        }

        return $result;
    }

    /**
     * Disable language.
     *
     * @return array
     */
    public function disable()
    {
        $this->status = self::STATUS_INACTIVE;
        $this->save();
    }

    /**
     * Enable language.
     *
     * @return array
     */
    public function enable()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->save();
    }

    public static function fileToArray($pathToFile)
    {
        return \File::getRequire($pathToFile);
    }

    public static function arrayToYaml($array)
    {
        return \Yaml::dump($array);
    }

    public static function fileToYaml($path)
    {
        return self::arrayToYaml(self::fileToArray($path));
    }

    public static function yamlToFile($pathToFile, $yaml) {
        $content = '<?php return '.var_export(\Yaml::parse($yaml), true).' ?>';
        $bytes_written = \File::put($pathToFile, $content);
    }

    public function getAllLanguageFiles()
    {
        $paths = [];

        $files = Hook::execute('add_translation_file');
        foreach ($files as $file) {
            $path = join_paths($file['translation_folder'], $this->code, $file['file_name']);

            if (in_array($file['id'], array_keys($paths)) )
            {
                throw new \Exception('Translation file id already exists: ' . $file['id']);
            }

            if (!file_exists($path)) {
                \Acelle\Library\Tool::xcopy(
                    join_paths($file['translation_folder'], $file['default']),
                    join_paths($file['translation_folder'], $this->code),
                );
            }

            $paths[$file['id']] = [
                'id' => $file['id'],
                'type' => isset($file['type']) ? $file['type'] : 'plugin',
                'path' => $path,
                'file_title' => $file['file_title'],
            ];
        }

        return $paths;
    }

    public function getLanguageFilesByType($type)
    {
        $langFiles = $this->getAllLanguageFiles();
        foreach ($langFiles as $key => $langFile) {
            if ($langFile['type'] != $type) {
                unset($langFiles[$key]);
            }
        }

        return $langFiles;
    }

    public static function newDefaultLanguage()
    {
        $language = new self();
        $language->status = self::STATUS_ACTIVE;

        return $language;
    }

    public static function createFromRequest($request)
    {
        $language = self::newDefaultLanguage();

        $language->fill($request->all());
        $language->status = self::STATUS_INACTIVE;

        // make validator
        $validator = \Validator::make($request->all(), $language->rules());

        // redirect if fails
        if ($validator->fails()) {
            return [$language, $validator];
        }

        // save
        $language->save();

        // copy from default language folder
        $des = $language->languageDir();
        if (!file_exists($des)) {
            $oldmask = umask(0);
            mkdir($des, 0775, true);
            $sou = self::getDefaultLanguage()->languageDir();
            \Acelle\Library\Tool::xcopy($sou, $des);
            umask($oldmask);
        }

        return [$language, true];
    }
    
    public static function updateFromRequest($language, $request)
    {
        // make validator
        $validator = \Validator::make($request->all(), $language->rules());

        // redirect if fails
        if ($validator->fails()) {
            return [$language, $validator];
        }

        // rename locale folder
        if ($language->code != $request->code) {
            rename(base_path("resources/lang/") . $language->code, base_path("resources/lang/") . $request->code);
        }

        $language->fill($request->all());

        // save
        $language->save();

        return [$language, true];
    }

    public function deleteAndCleanup()
    {
        // Change deleting language's users to the default langauge
        $default_language = Language::getIsDefaultLanguage();
        $this->customers()->update(['language_id' => $default_language->id]);
        $this->admins()->update(['language_id' => $default_language->id]);

        // delete language folder
        $des = $this->languageDir();
        if (file_exists($des)) {
            \Acelle\Library\Tool::xdelete($des);
        }

        $this->delete();
    }

    public function translateFile($file, $content)
    {
        // make validator
        $validator = \Validator::make(['content' => $content], [
            'content' => 'required',
        ]);

        // test amazon api connection
        $validator->after(function ($validator) use ($file, $content) {
            try {
                var_export(\Yaml::parse($content), true);
            } catch (\Exception $e) {
                $validator->errors()->add('content', $e->getMessage());
            }
        });

        // redirect if fails
        if ($validator->fails()) {
            return [$file, $validator];
        }

        // save
        self::yamlToFile($file['path'], $content);

        \Artisan::call('cache:clear');

        // @todo
        // make sure language file updated.
        sleep(5);

        return [$file, $validator];
    }

    public function findFileById($id)
    {
        if (!isset($this->getAllLanguageFiles()[$id]))
        {
            throw new \Exception('Can not find translation file with id: ' . $id);
        }

        return $this->getAllLanguageFiles()[$id];
    }

    public function getDefaultFile()
    {
        $files = $this->getAllLanguageFiles();
        return array_shift($files);
    }

    public function upload($request)
    {
        // make validator
        $validator = \Validator::make($request->all(), [
            'file' => 'required',
        ]);

        // test amazon api connection
        $validator->after(function ($validator) use ($request) {
            $zip = new \ZipArchive();

            // check if file is zip achive
            $file_ext = $request->file('file')->guessExtension();
            if ($file_ext != 'zip') {
                $validator->errors()->add('content', 'Upload file is not zip file');
                return;
            }
            
            // move file to temp place
            $tmp_path = storage_path('tmp');
            $file_name = 'language-package';
            $request->file('file')->move($tmp_path, $file_name);
            
            // after moving, request['file'] will no longer be there
            $rules = [];
            $tmp_zip = storage_path("tmp/{$file_name}");
            $openZip = $zip->open($tmp_zip, \ZipArchive::CREATE);

            // read zip file check if zip archive invalid
            if ($openZip !== true) {
                $validator->errors()->add('content', 'Upload file is not valide archive file');
                return;
            }

            // unzip template archive and remove zip file
            $zip->extractTo($this->languageDir());
            $zip->close();
            unlink($tmp_zip);
        });

        return [$this, $validator];
    }
}
