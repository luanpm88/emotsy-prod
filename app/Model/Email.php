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

class Email extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject', 'from', 'from_name', 'reply_to', 'sign_dkim', 'track_open', 'track_click'
    ];
    
    /**
     * Association with mailList through mail_list_id column.
     */
    public function automation()
    {
        return $this->belongsTo('Acelle\Model\Automation2', 'automation2_id');
    }
    
    /**
     * Association with mailList through mail_list_id column.
     */
    public function attachments()
    {
        return $this->hasMany('Acelle\Model\Attachment');
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
}
