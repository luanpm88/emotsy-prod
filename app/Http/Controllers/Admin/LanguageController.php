<?php

namespace Mailixa\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Mailixa\Http\Controllers\Controller;

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

        $items = \Mailixa\Model\Language::getAll();

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

        $items = \Mailixa\Model\Language::search($request)->paginate($request->per_page);

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
        $language = new \Mailixa\Model\Language([
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
        $language = new \Mailixa\Model\Language();

        // authorize
        if (\Gate::denies('create', $language)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, \Mailixa\Model\Language::rules());

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
        //
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
        $language = \Mailixa\Model\Language::findByUid($id);

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
        $language = \Mailixa\Model\Language::findByUid($id);

        // authorize
        if (\Gate::denies('update', $language)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('patch')) {
            $this->validate($request, \Mailixa\Model\Language::rules());

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
        //
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
        $items = \Mailixa\Model\Language::whereIn('uid', explode(',', $request->uids));

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
}
