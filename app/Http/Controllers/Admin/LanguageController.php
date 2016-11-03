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
        $messages = $language->readLocaleFile("messages");
        $auth = $language->readLocaleFile("auth");
        $pagination = $language->readLocaleFile("pagination");
        $passwords = $language->readLocaleFile("passwords");
        $validation = $language->readLocaleFile("validation");

        // authorize
        if (\Gate::denies('translate', $language)) {
            return $this->notAuthorized();
        }
        
        // save posted data
        if ($request->isMethod('post')) {
            $rules = [];
            $this->validate($request, $rules);
            
            $bytes_written = \File::put($language->languageDir() . "messages.php", $request->messages);
            $bytes_written = \File::put($language->languageDir() . "auth.php", $request->auth);
            $bytes_written = \File::put($language->languageDir() . "pagination.php", $request->pagination);
            $bytes_written = \File::put($language->languageDir() . "passwords.php", $request->passwords);
            $bytes_written = \File::put($language->languageDir() . "validation.php", $request->validation);
        }
        
        // Get old post values
        if (!empty($request->old())) {
            $messages = $request->old()["messages"];
            $auth = $request->old()["auth"];
            $pagination = $request->old()["pagination"];
            $passwords = $request->old()["passwords"];
            $validation = $request->old()["validation"];
        }
        
        return view('admin.languages.translate', [
            'language' => $language,
            'messages' => $messages,
            'auth' => $auth,
            'pagination' => $pagination,
            'passwords' => $passwords,
            'validation' => $validation,
        ]);
    }
}
