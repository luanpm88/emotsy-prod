<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

class LanguageController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->getOption('backend', 'language_read') == 'no') {
            return $this->notAuthorized();
        }

        $items = \Acelle\Model\Language::getAll();

        return view('admin.languages.index', [
            'items' => $items,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        if ($request->user()->getOption('backend', 'language_read') == 'no') {
            return $this->notAuthorized();
        }

        $items = \Acelle\Model\Language::search($request)->paginate($request->per_page);

        return view('admin.languages._list', [
            'items' => $items,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $language = new \Acelle\Model\Language([
            'signing_enabled' => true,
        ]);
        $language->status = 'active';
        $language->uid = '0';
        $language->fill($request->old());

        // authorize
        if (\Gate::denies('create', $language)) {
            return $this->notAuthorized();
        }

        return view('admin.languages.create', [
            'language' => $language,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get current user
        $current_user = $request->user();
        $language = new \Acelle\Model\Language();

        // authorize
        if (\Gate::denies('create', $language)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, \Acelle\Model\Language::rules());

            // Save current user info
            $language->fill($request->all());
            $language->status = 'active';

            if ($language->save()) {
                $request->session()->flash('alert-success', trans('messages.language.created'));

                return redirect()->action('Admin\LanguageController@index');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $language = \Acelle\Model\Language::findByUid($id);

        // authorize
        if (\Gate::denies('update', $language)) {
            return $this->notAuthorized();
        }

        $language->fill($request->old());

        return view('admin.languages.edit', [
            'language' => $language,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Get current user
        $current_user = $request->user();
        $language = \Acelle\Model\Language::findByUid($id);

        // authorize
        if (\Gate::denies('update', $language)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('patch')) {
            $this->validate($request, \Acelle\Model\Language::rules());

            // rename locale folder
            if($language->code != $request->code) {
                rename(base_path("resources/lang/") . $language->code, base_path("resources/lang/") . $request->code);
            }

            // Save current user info
            $language->fill($request->all());

            if ($language->save()) {
                $request->session()->flash('alert-success', trans('messages.language.updated'));

                return redirect()->action('Admin\LanguageController@index');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $items = \Acelle\Model\Language::whereIn('uid', explode(',', $request->uids));

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::denies('delete', $item)) {
                return;
            }
        }

        foreach ($items->get() as $item) {
            $item->delete();
        }

        // Redirect to my lists page
        echo trans('messages.languages.deleted');
    }
    
    /**
     * Translate.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function translate(Request $request, $id)
    {
        $language = \Acelle\Model\Language::findByUid($id);
        $parse_error = $request->session()->get("parse_error");
        $filename = $request->file;
        
        // authorize
        if (\Gate::denies('translate', $language)) {
            return $this->notAuthorized();
        }
        
        // save posted data
        if ($request->isMethod('post')) {
            $rules = [];
            try {
                $language->updateFromYaml($filename, $request->all()[$filename]);
            } catch (\Symfony\Component\Yaml\Exception\ParseException $e) {
                $request->session()->set("parse_error", $e->getMessage());
                $rules = ['yaml_parse_error' => 'required'];
            }
            $this->validate($request, $rules);
            
            $request->session()->flash('alert-success', trans('messages.language.updated'));
            return redirect()->action('Admin\LanguageController@translate', ["id" => $language->uid, "file" => $filename]);
        }
        
        // Get current values
        $content = \Yaml::dump($language->getLocaleArrayFromFile($filename));
        
        // Get old post values
        if (!empty($request->old())) {
            $content = $request->old()[$filename];
        }
        
        return view('admin.languages.translate', [
            'language' => $language,
            'content' => $content,
            'filename' => $filename,
            'parse_error' => $parse_error
        ]);
    }
    
    /**
     * Disable language.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request)
    {
        $items = \Acelle\Model\Language::whereIn('uid', explode(',', $request->uids));

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::allows('disable', $item)) {
                $item->disable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.languages.disabled');
    }
    
    /**
     * Disable language.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request)
    {
        $items = \Acelle\Model\Language::whereIn('uid', explode(',', $request->uids));

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::allows('enable', $item)) {
                $item->enable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.languages.enabled');
    }
}
