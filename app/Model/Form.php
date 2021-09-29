<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;
use Acelle\Library\Traits\HasTemplate;
use Validator;
use Acelle\Model\Template;

class Form extends Model
{
    use HasFactory;
    use HasTemplate;
    use HasUid;

    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';

    public function mailList()
    {
        return $this->belongsTo('Acelle\Model\MailList');
    }

    public function template()
    {
        return $this->belongsTo('Acelle\Model\Template');
    }

    public static function newDefault($customer)
    {
        $form = new self();
        $form->name = trans('messages.form.untitled');
        $form->status = self::STATUS_DRAFT;
        $form->customer_id = $customer->id;

        return $form;
    }

    public function createFromArray($params)
    {
        $validator = Validator::make($params, [
            'name' => 'required',
            'template_uid' => 'required',
            'mail_list_uid' => 'required',
        ]);

        if ($validator->fails()) {
            return $validator;
        }

        $this->mail_list_id = \Acelle\Model\MailList::findByUid($params['mail_list_uid'])->id;
        $this->save();

        // set template
        $selectedTemplate = Template::findByUid($params['template_uid']);
        $this->setTemplate($selectedTemplate);

        return $validator;
    }
    
    public function scopeFilter($query, $params)
    {
        // Keyword
        if (!empty($params['mail_list_uid'])) {
            $query = $query->where('mail_list_id', '=', \Acelle\Model\MailList::findByUid($params['mail_list_uid'])->id);
        }
    }

    public function scopeSearch($query, $keyword)
    {
        // Keyword
        if (!empty($keyword)) {
            $query = $query->where('name', 'like', '%'.trim($keyword).'%');
        }
    }

    public function updateMetadata($data)
    {
        $metadata = (object) array_merge((array) $this->getMetadata(), $data);
        $this['metadata'] = json_encode($metadata);

        $this->save();
    }

    public function getMetadata($name=null)
    {
        if (!$this['metadata']) {
            return json_decode('{}', true);
        }

        $data = json_decode($this['metadata'], true);

        if ($name != null) {
            if (isset($data[$name])) {
                return $data[$name];
            } else {
                return null;
            }
        } else {
            return $data;
        }
    }

    public function saveSettingsFromArray($params)
    {
        $this->updateMetadata([
            'overlay_opacity' => $params['overlay_opacity'],
            'display' => $params['display'],
            'wait_time' => $params['wait_time'],
            'element_id' => $params['element_id'],
        ]);
    }

    public function connect($site)
    {
        $this->updateMetadata([
            'website_uid' => $site->uid,
        ]);
    }

    public function getWebsite()
    {
        return $this->getMetadata('website_uid') ? \Acelle\Model\Website::findByUid($this->getMetadata('website_uid')) : null;
    }
    
    public function disconnect()
    {
        $this->updateMetadata([
            'website_uid' => null,
        ]);
    }
}
