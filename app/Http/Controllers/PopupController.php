<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

class PopupController extends Controller
{
    public function index(Request $request)
    {
        // form fields for Editor
        $formFields = \Acelle\Model\MailList::first()->getFields->map(function ($field) {
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

        return view('popup.index', [
            'formFields' => $formFields,
        ]);
    }
}
