<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Model\TemplateCategory;

class PopupController extends Controller
{
    public function index(Request $request)
    {
        // form fields for Editor
        $formFields = \Acelle\Model\MailList::findByUid('5966ef47e6598')->getFields->map(function ($field) {
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
}
