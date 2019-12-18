<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use File;
use Sunra\PhpSimple\HtmlDomParser;
use Acelle\Library\Tool;
use Acelle\Model\Attachment;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Validation\ValidationException;
use ZipArchive;
use League\Csv\Writer;

class Email extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject', 'from', 'from_name', 'reply_to', 'sign_dkim', 'track_open', 'track_click', 'action_id'
    ];

    /**
     * Association with mailList through mail_list_id column.
     */
    public function automation()
    {
        return $this->belongsTo('Acelle\Model\Automation2', 'automation2_id');
    }

    /**
     * Association with attachments.
     */
    public function attachments()
    {
        return $this->hasMany('Acelle\Model\Attachment');
    }

    /**
     * Association with email links.
     */
    public function emailLinks()
    {
        return $this->hasMany('Acelle\Model\EmailLink');
    }

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating automation.
        static::creating(function ($item) {
            // Create new uid
            $uid = uniqid();
            while (self::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;
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
     * Create automation rules.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => 'required',
            'from' => 'required|email',
            'from_name' => 'required',
        ];
    }

    /**
     * Get public campaign upload uri.
     */
    public function getUploadUri()
    {
        return 'app/email/template_' . $this->uid;
    }

    /**
     * Get public campaign upload dir.
     */
    public function getUploadDir()
    {
        return storage_path('app/email/template_' . $this->uid);
    }

    /**
     * Create template from layout.
     *
     * All availabel template tags
     */
    public function addTemplateFromLayout($layout)
    {
        $sDir = database_path('layouts/' . $layout);
        $dDir = $this->getUploadDir();

        // Copy source code
        \Acelle\Library\Tool::xcopy($sDir, $dDir);

        // get layout content
        $html = file_get_contents($sDir . '/index.html');

        // change content
        $document = HtmlDomParser::str_get_html($html);
        foreach($document->find('img, script') as $element) {
            if (preg_match('/^http/', $element->src) == 0) {
                $element->src = '/' . $this->getUploadUri() . '/' . $element->src;
            }
        }
        foreach($document->find('link') as $element) {
            if (preg_match('/^http/', $element->href) == 0) {
                $element->href = '/' . $this->getUploadUri() . '/' . $element->href;
            }
        }

        // set document as campaign html
        $this->content =  $document;

        // save template
        $this->save();
    }

    /**
     * Get builder templates.
     *
     * @return mixed
     */
    public function getBuilderTemplates($customer)
    {
        $result = [];

        // Gallery
        $templates = Template::where('customer_id', '=', null)
            ->orWhere('customer_id', '=', $customer->id)
            ->orderBy('customer_id')
            ->get();

        foreach($templates as $template) {
            $result[] = [
                'name' => $template->name,
                'url' => action('Automation2Controller@templateChangeTemplate', [
                    'uid' => $this->automation->uid,
                    'email_uid' => $this->uid,
                    'change_uid' => $template->uid
                ]),
                'thumbnail' => action('TemplateController@image', $template->uid),
            ];
        }

        return $result;
    }

    /**
     * Upload attachment.
     *
     * @return mixed
     */
    public function uploadAttachment($file)
    {
        $att = new Attachment();
        $att->email_id = $this->id;
        $att->size = $file->getSize();
        $att->name = $file->getClientOriginalName();
        $att->file = Storage::disk('local')->put('attachments/' . $this->uid, $file);

        $att->save();
    }

    /**
     * Copy new template.
     */
    public function copyFromTemplate($template)
    {
        \Acelle\Library\Tool::xcopy($template->getUploadDir(), $this->getUploadDir());

        // replace image url
        $this->content = $template->content;
        $this->content = str_replace($template->getUploadUri(), $this->getUploadUri(), $this->content);
        $this->save();
    }

    /**
     * Upload a template.
     */
    public function uploadTemplate($request)
    {
        $rules = array(
            'file' => 'required|mimetypes:application/zip,application/octet-stream,application/x-zip-compressed,multipart/x-zip',
        );

        $validator = Validator::make($request->all(), $rules, [
            'file.mimetypes' => 'Input must be a valid .zip file',
        ]);

        if ($validator->fails()) {
            return [false, $validator];
        }

        // move file to temp place
        $tmp_path = storage_path('tmp/uploaded_template_'.$this->uid.'_'.time());
        $file_name = $request->file('file')->getClientOriginalName();
        $request->file('file')->move($tmp_path, $file_name);
        $tmp_zip = join_paths($tmp_path, $file_name);

        // read zip file check if zip archive invalid
        $zip = new ZipArchive();
        if ($zip->open($tmp_zip, ZipArchive::CREATE) !== true) {
            // @todo hack
            // $validator = Validator::make([], []); // Empty data and rules fields
            $validator->errors()->add('file', 'Cannot open .zip file');
            return [false, $validator];
        }

        // unzip template archive and remove zip file
        $zip->extractTo($tmp_path);
        $zip->close();
        unlink($tmp_zip);

        // try to find the main file, index.html | index.html | file_name.html | ...
        $archive_name = str_replace(array('../', './', '..\\', '.\\', '..'), '', basename($file_name, '.zip'));
        $main_file = null;
        $sub_path = "";
        $possible_main_file_names = array('index.html', 'index.htm', $archive_name.'.html', $archive_name.'.htm');

        $possible_main_file_names = array('index.html', 'index.htm', $archive_name.'.html', $archive_name.'.htm');
        foreach ($possible_main_file_names as $name) {
            if (is_file($file = $tmp_path.'/'.$name)) {
                $main_file = $file;
                break;
            }
            $dirs = array_filter(glob($tmp_path.'/'.'*'), 'is_dir');
            foreach($dirs as $sub) {
                if (is_file($file = $sub.'/'.$name)) {
                    $main_file = $file;
                    $sub_path = explode("/",$sub)[count(explode("/",$sub))-1]."/";
                    break;
                }
            }
        }
        // try to find first htm|html file
        if ($main_file === null) {
            $objects = scandir($tmp_path);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (!is_dir($tmp_path.'/'.$object)) {
                        if (preg_match('/\.html?$/i', $object)) {
                            $main_file = $tmp_path.'/'.$object;
                            break;
                        }
                    }
                }
            }
            $dirs = array_filter(glob($tmp_path.'/'.'*'), 'is_dir');
            foreach($dirs as $sub) {
                $objects = scandir($sub);
                foreach ($objects as $object) {
                    if ($object != '.' && $object != '..') {
                        if (!is_dir($sub.'/'.$object)) {
                            if (preg_match('/\.html?$/i', $object)) {
                                $main_file = $sub.'/'.$object;
                                $sub_path = explode("/",$sub)[count(explode("/",$sub))-1]."/";
                                break;
                            }
                        }
                    }
                }
            }
        }

        // main file not found
        if ($main_file === null) {
            $validator->errors()->add('file', 'Cannot find index file (index.html) in the template folder');
            throw new ValidationException($validator);
        }

        // read main file content
        $html_content = trim(file_get_contents($main_file));

        // Save new html
        $public_dir = $this->getUploadUri();
        $public_upload_path = $this->getUploadDir();

        $this->content = $html_content;
        $this->content = \Acelle\Library\Tool::replaceHtmlUrl($this->content, $request->root().'/'.$public_dir.'/'.$sub_path);
        $this->save();

        // copy all folder to public path
        if (!file_exists($public_upload_path)) {
            mkdir($public_upload_path, 0777, true);
        }
        // exec("cp -r {$tmp_path}/* {$public_upload_path}/");
        Tool::xcopy($tmp_path, $public_upload_path);

        // remove tmp folder
        // exec("rm -r {$tmp_path}");
        Tool::xdelete($tmp_path);

        return [true, $validator];
    }

    /**
     * Find and update email links.
     */
    public function updateLinks()
    {
        if (!$this->content) {
            return false;
        }

        $links = [];

        // find all links from contents
        $document = HtmlDomParser::str_get_html($this->content);
        foreach($document->find('a') as $element) {
            if (preg_match('/^http/', $element->href) != 0) {
                $links[] = trim($element->href);
            }
        }

        // delete al bold links
        $this->emailLinks()->whereNotIn('link', $links)->delete();

        foreach($links as $link) {
            $exist = $this->emailLinks()->where('link', '=', $link)->count();

            if(!$exist) {
                $this->emailLinks()->create([
                    'link' => $link,
                ]);
            }
        }
    }

    public function deliverTo($subscriber)
    {
        // send to this subscriber
    }
}
