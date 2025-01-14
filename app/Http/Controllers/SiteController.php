<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Model\Site;
use Acelle\Model\Template;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sites = $request->user()->customer->sites()
            ->search($request->keyword);

        return view('sites.index', [
            'sites' => $sites,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $sites = $request->user()->customer->sites()->search($request->keyword)
            ->orderBy($request->sort_order, $request->sort_direction ? $request->sort_direction : 'asc')
            ->paginate($request->per_page);

        return view('sites.list', [
            'sites' => $sites,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $site = $request->user()->customer->local()->newSite();

        return view('sites.create', [
            'site' => $site,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $site = $request->user()->customer->local()->newSite();

        // try to save
        list($result, $errors) = $site->saveSite(
            $name = $request->site['name'],
            $content = $request->site['type'],
        );

        // redirect if fails
        if (!$result) {
            return response()->view('sites.create', [
                'site' => $site,
                'errors' => $errors,
            ], 400);
        }

        return redirect()->action('SiteController@content', $site->uid)
            ->with('alert-success', trans('messages.site.added.success', [
                'name' => $site->name,
            ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $site = Site::findByUid($id);

        return view('sites.edit', [
            'site' => $site,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $site = Site::findByUid($id);

        // try to save
        list($result, $errors) = $site->saveSite(
            $name = $request->site['name'],
            $content = $request->site['type'],
        );

        // redirect if fails
        if (!$result) {
            return response()->view('sites.create', [
                'site' => $site,
                'errors' => $errors,
            ], 400);
        }

        return redirect()->action('SiteController@content', $site->uid)
            ->with('alert-success', trans('messages.site.updated.success', [
                'name' => $site->name,
            ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function content(Request $request, $id)
    {
        $site = Site::findByUid($id);

        if (!$site->hasTemplate()) {
            return view('sites.selectTemplate', compact('site'));
        } else {
            return view('sites.content', compact('site'));
        }
    }

    public function selectTemplate(Request $request, $id)
    {
        $site = Site::findByUid($id);

        return view('sites.selectTemplate', compact('site'));
    }

    public function changeTemplate(Request $request, $id, $template_uid)
    {
        $site = Site::findByUid($id);
        $template = Template::findByUid($template_uid);

        //
        $site->setTemplate($template);

        //
        return redirect()->action('SiteController@builder', $site->uid);

        // pretend to be selected
        // return redirect()->action('SiteController@settings', compact('site', 'template'));
    }

    public function settings(Request $request, $id)
    {
        $site = Site::findByUid($id);

        // pretend to be selected
        return view('sites.settings', compact('site'));
    }

    public function builder(Request $request, $id)
    {
        $site = Site::findByUid($id);
        $templates = Template::shared()->notPreserved()->site()->get()->map(function($template) {
            return [
                'name' => $template->name,
                'url' => '', // action('CampaignController@templateChangeTemplate', ['uid' => $this->uid, 'template_uid' => $template->uid]),
                'thumbnail' => $template->getThumbUrl(),
            ];
        })->toArray();

        return view('sites.builder', compact('site', 'templates'));
    }

    public function builderSave(Request $request, $id)
    {
        $site = Site::findByUid($id);

        $rules = array(
            'content' => 'required',
        );

        $this->validate($request, $rules);

        $site->setTemplateContent($request->content);
        $site->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function builderContent(Request $request, $id)
    {
        $site = Site::findByUid($id);

        echo $site->template->content;
    }

    public function preview($id)
    {
        $site = Site::findByUid($id);

        return view('sites.preview', [
            'site' => $site,
        ]);
    }

    public function previewContent(Request $request, $id)
    {
        $site = Site::findByUid($id);

        echo $site->template->content;
    }
}
