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
        $templates = Template::popup()->shared()
            ->search($request->keyword)
            ->orderBy($request->sort_order, $request->sort_direction)
            ->paginate(8);

        return view('forms.templates', [
            'templates' => $templates,
        ]);
    }

    public function list(Request $request)
    {
        // sort, pagination
        $forms = Form::search($request->keyword)
            ->filter($request->all())
            ->orderBy($request->sort_order, $request->sort_direction)
            ->paginate($request->per_page);


        return view('forms.list', [
            'forms' => $forms,
        ]);
    }

    public function delete(Request $request)
    {
        if (isSiteDemo()) {
            return response()->json([
                'status' => 'notice',
                'message' => trans('messages.operation_not_allowed_in_demo'),
            ], 403);
        }

        $forms = Form::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        $total = $forms->count();
        $deleted = 0;
        foreach ($forms->get() as $form) {
            // authorize
            if ($request->user()->customer->can('delete', $form)) {
                $form->delete();
                $deleted += 1;
            }
        }

        return response()->json([
            'message' => trans('messages.forms.deleted', [ 'deleted' => $deleted, 'total' => $total]),
        ]);
    }

    public function edit(Request $request)
    {
        $form = Form::findByUid($request->uid);

        $templates = Template::popup()->shared()->get();

        return view('forms.edit', [
            'form' => $form,
            'templates' => $templates,
        ]);
    }

    public function builder(Request $request)
    {
        $form = Form::findByUid($request->uid);

        // form fields for Editor
        $formFields = $form->mailList->getFields->map(function ($field) {
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

        return view('forms.builder', [
            'form' => $form,
            'formFields' => $formFields,
        ]);
    }

    public function content(Request $request)
    {
        // Generate info
        $form = Form::findByUid($request->uid);

        $content = view('popup.content', [
            'template' => $form->template,
            'content' => $form->template->content,
        ]);

        return response($content)->header('Access-Control-Allow-Origin', '*');
    }

    public function popup(Request $request)
    {
        $form = Form::findByUid($request->uid);

        $content = view('forms.popup', [
            'form' => $form,
        ]);

        return response($content)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Content-Type', 'application/javascript');
    }

    public function settings(Request $request)
    {
        $form = Form::findByUid($request->uid);

        $form->saveSettingsFromArray($request->all());
    }

    public function connect(Request $request)
    {
        $form = Form::findByUid($request->uid);

        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), [
                'website_uid' => 'required',
            ]);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('forms.connect', [
                    'form' => $form,
                    'errors' => $validator->errors(),
                ], 400);
            }

            $site = \Acelle\Model\Website::findByUid($request->website_uid);
            $form->connect($site);

            return response()->json([
                'message' => trans('messages.form.connected', [
                    'site' => $site->title,
                ]),
            ]);
        }

        return view('forms.connect', [
            'form' => $form,
        ]);
    }

    public function disconnect(Request $request)
    {
        $form = Form::findByUid($request->uid);

        if ($request->isMethod('post')) {
            $form->disconnect();

            return response()->json([
                'message' => trans('messages.form.disconnected'),
            ]);
        }

        return view('forms.connect', [
            'form' => $form,
        ]);
    }
}
