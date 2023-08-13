<?php

namespace Acelle\Http\Controllers\Store;

use Acelle\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Acelle\Model\SmsFunnel;

class FunnelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // init
        $perPage    =   $request->perPage ?? 10;
        $keyword    =   $request->keyword ?? '' ;
        $view       =   $request->view ?? '' ;

        // Get sending servers
        $smsFunnels = SmsFunnel::search($keyword)
            ->paginate($perPage);

        return view('funnels.index', [
            'smsFunnels'    => $smsFunnels,
            'statuslist'    => SmsFunnel::select('status')->distinct()->get(),
            'keyword'       => $keyword,
            'view'          => $view,
            'perPage'       => $perPage,
        ]);
    }
    public function list(Request $request)
    {
        // init
        $perPage    =   $request->perPage ?? 10;
        $keyword    =   $request->keyword ?? '' ;
        $filters    =   $request->filters ?? '' ;
        $status     =   $request->status ?? 'all';
        $view       =   $request->view ?? 'list' ;

        // Get sending servers
        $smsFunnels = SmsFunnel::search($keyword);

        // filter by status
        if ($status!='all') {
            $smsFunnels->where('status', 'like', $status);
        }
        if ($request->filters) {
            $smsFunnels->whereIn('type', $filters);
        }

        if ($request->sort) {
            $smsFunnels->orderBy($request->sort['by'], $request->sort['direction']);
        }
        return view('funnels.'.$view, [
            'smsFunnels'   => $smsFunnels->paginate($perPage),
            'keyword'     => $keyword,
            'perPage'     => $perPage,
            'sort_by'     => $request->sort['by'] ?? '',
            'sort_direction' => $request->sort['direction'] ?? '',
        ]);
    }

    public function deleteSelected(Request $request)
    {
        // find and delete record
        $smsFunnels = SmsFunnel::whereIn('id', $request->ids)->get(); // collection map filter
        foreach ($smsFunnels as $smsFunnel) {
            $smsFunnel->delete();
        }
        // retur alert
        return response()->json([
            'status' => 'success',
            'message' => trans('store.sms_functional.delete.success'),
        ]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // init
        $smsFunnel = new SmsFunnel();

        return view('funnels.create', [
            'smsFunnel' => $smsFunnel,
            'tags' => SmsFunnel::getTags(),
        ]);
    }

    public function store(Request $request)
    {
        // init
        $smsFunnel = new SmsFunnel();

        // set active
        $smsFunnel->status = 'Active';

        // Try to save
        $validator = $smsFunnel->saveFromParams($request->all());

        // if error
        if ($validator->fails()) {
            return response()->view('funnels.create', [
                'smsFunnel' => $smsFunnel,
                'tags' => SmsFunnel::getTags(),
                'errors' => $validator->errors(),
            ], 400);
        }

        // Send messenge
        $request->session()->flash('alert-success', trans('store.sms_functional.create.success'));

        // redirect
        return redirect()->action('Sms\FunnelController@index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function edit(Request $request, $id)
     {
         $smsFunnel = SmsFunnel::find($id);

         return view('funnels.edit', [
            'smsFunnel'    =>  $smsFunnel,
            'tags' => SmsFunnel::getTags(),
         ]);
     }

    /**
     * Get message content for create sms campaign
     */

    public function getmessage(Request $request)
    {
        $smsFunnel = SmsFunnel::find($request->id);
        return response()->json([
            'status' => 'success',
            'message' =>  $smsFunnel->message,
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
        // init
        $smsFunnel = SmsFunnel::find($id);

        // Try to save
        $validator = $smsFunnel->saveFromParams($request->all());

        // errors
        if ($validator->fails()) {
            return response()->view('funnels.edit', [
                'smsFunnel' => $smsFunnel,
                'tags' => SmsFunnel::getTags(),
                'errors' => $validator->errors(),
            ], 400);
        }

        // Send messenge if change
        $request->session()->flash('alert-success', trans('store.sms_funnel.update.success'));

        // redirect
        return redirect()->action('Sms\FunnelController@index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function multiltask(Request $request)
    {
        $mesenger   =   "";
        $keyword    =   isset($request->keyword) ? $request->keyword : '' ;
        $solu       =   isset($request->solu) ? $request->solu : '' ;
        if ($solu == 'activemany') {
            if ($request->ids) {
                try {
                    SmsFunnel::whereIn("id", $request->ids)
                        ->update([
                            'status' => 'Active',
                        ]);
                    $mesenger   =   " Active sucessful";
                } catch(\Exception $e) {
                    report($e);
                }
            }
        }
        if ($solu == 'delmany') {
            if ($request->ids) {
                try {
                    SmsFunnel::destroy($request->ids);
                    $mesenger   =   " Delete sucessful";
                } catch(\Exception $e) {
                    report($e);
                }
            }
        }
        return redirect()->action('Sms\FunnelController@index', [
                            'keyword'   => $request->keyword,
                            'page'      => $request->page,
                        ])->with([  'mesenger'  => $mesenger, ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(request $request, $id)
    {
        $smsFunnel = SmsFunnel::find($id);
        return view('funnels.show', [
            'smsFunnel'    =>  $smsFunnel,
            'page'      =>  $request->page
        ]);
    }


    /**
     * Delete the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete(Request $request)
    {
        // find and delete record
        $smsFunnel = SmsFunnel::find($request->id);

        // delete record
        $smsFunnel->delete();

        // retur alert
        return response()->json([
            'status' => 'success',
            'message' => trans('store.sms_functional.delete.success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $smsFunnel = SmsFunnel::findOrFail($id)->first();
        $smsFunnel->delete();
        return redirect()->action('Sms\FunnelController@index', [
            'page'      =>  $request->page,
            'perPage'   => $request->perPage,
        ]);
    }
    /**
     * Tongo On/off Status
     */
    public function updateStatus(Request $request)
    {
        $smsFunnel = SmsFunnel::find($request->id);
        $smsFunnel->status = $request->status;
        $smsFunnel->save();
        return response()->json(['success'=> trans('store.sms_functional.status.updatesuccess')]);
    }
}
