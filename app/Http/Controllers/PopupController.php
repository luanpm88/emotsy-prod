<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Model\TemplateCategory;
use Acelle\Model\Template;

class PopupController extends Controller
{
    public function index(Request $request)
    {
        // form fields for Editor
        $formFields = \Acelle\Model\MailList::findByUid('611f20f5d4eff')->getFields->map(function ($field) {
            return [
                'type' => $field->type,
                'label' => $field->label,
                'name' => $field->tag,
                'visible' => $field->visible,
                'required' => $field->required,
                'default' => $field->default_value,
                'options' => $field->fieldOptions->map(function ($option) {
                    return ['value' => $option->value, 'text' => $option->label];
                })->toArray(),
            ];
        })->toArray();

        $category = TemplateCategory::where('name', 'Form')->first();
        $templates = $category->templates;

        return view('popup.index', [
            'formFields' => $formFields,
            'templates' => $templates,
        ]);
    }

    public function content(Request $request)
    {
        // Generate info
        $user = $request->user();
        $template = Template::findByUid($request->template_uid);

        return view('popup.content', [
            'content' => $template->content,
        ]);
    }
}
