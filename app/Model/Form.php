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

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static function newDefault($customer)
    {
        $form = new self();
        $form->name = trans('messages.form.untitled');
        $form->status = self::STATUS_ACTIVE;
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

        $this->save();

        // set template
        $selectedTemplate = Template::findByUid($params['template_uid']);
        $this->setTemplate($selectedTemplate);

        return $validator;
    }
}
