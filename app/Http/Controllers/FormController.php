<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Model\Form;
use Acelle\Model\Template;

class FormController extends Controller
{
    public function index(Request $request)
    {
        return view('forms.index');
    }

    public function create(Request $request)
    {
        $form = Form::newDefault($request->user()->customer);

        if ($request->isMethod('post')) {
            $validator = $form->createFromArray($request->all());

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('forms.create', [
                    'form' => $form,
                    'errors' => $validator->errors(),
                ], 400);
            }

            
            return redirect()->action('FormController@index')
                ->with('alert-success', trans('messages.form.created', [
                    'name' => $form->name,
                ]));
        }

        return view('forms.create', [
            'form' => $form,
        ]);
    }

    public function templates(Request $request)
    {
        $templates = Template::shared()
            ->search($request->keyword)
            ->orderBy($request->sort_order, $request->sort_direction)
            ->paginate($request->per_page);

        return view('forms.templates', [
            'templates' => $templates,
        ]);
    }
}
