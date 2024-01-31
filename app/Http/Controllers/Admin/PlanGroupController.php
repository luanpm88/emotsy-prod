<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Acelle\Http\Requests;
use Acelle\Http\Controllers\Controller;
use Acelle\Model\PlanGroup;
use Acelle\Model\Setting;
use Acelle\Model\SendingServer;

class PlanGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // authorize
        if (\Gate::denies('read', new PlanGroup())) {
            return $this->notAuthorized();
        }

        // If admin can view all sending domains
        if (!$request->user()->admin->can("readAll", new PlanGroup())) {
            $request->merge(array("admin_id" => $request->user()->admin->id));
        }

        $planGroups = \Acelle\Model\PlanGroup::search($request->keyword)
            ->filter($request);

        return view('admin.plan_groups.index', [
            'planGroups' => $planGroups,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        $planGroups = \Acelle\Model\PlanGroup::search($request->keyword)
            ->filter($request)
            ->orderBy($request->sort_order, $request->sort_direction ? $request->sort_direction : 'asc')
            ->paginate($request->per_page);

        return view('admin.plan_groups._list', [
            'planGroups' => $planGroups,
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
        $user = $request->user();
        $planGroup = new PlanGroup();

        // authorize
        if (\Gate::denies('create', $planGroup)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $planGroup->fill($request->all());
            // $planGroup->options = json_encode($request->options);
            $planGroup->fillOptions($request->options);

            $this->validate($request, $planGroup->rules());

            $rules = [];
            if (isset($request->sending_servers)) {
                foreach ($request->sending_servers as $key => $param) {
                    if ($param['check']) {
                        $rules['sending_servers.'.$key.'.fitness'] = 'required';
                    }
                }
            }
            $this->validate($request, $rules);

            $planGroup->admin_id = $user->admin->id;
            $planGroup->save();

            // check status
            $planGroup->checkStatus();

            // For sending servers
            if (isset($request->sending_servers)) {
                $planGroup->updateSendingServers($request->sending_servers);
            }

            // For email verification servers
            if (isset($request->email_verification_servers)) {
                $planGroup->updateEmailVerificationServers($request->email_verification_servers);
            }

            $request->session()->flash('alert-success', trans('messages.plan_group.created'));
            return redirect()->action('Admin\PlanGroupController@index');
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
        $planGroup = PlanGroup::findByUid($id);
        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        if (!empty($request->old())) {
            $planGroup->fill($request->old());
        }

        // For options
        if (isset($request->old()['options'])) {
            $planGroup->options = json_encode($request->old()['options']);
        }
        $options = $planGroup->getOptions();

        // Sending servers
        if (isset($request->old()['sending_servers'])) {
            $planGroup->plansSendingServers = collect([]);
            foreach ($request->old()['sending_servers'] as $key => $param) {
                if ($param['check']) {
                    $server = \Acelle\Model\SendingServer::findByUid($key);
                    $row = new \Acelle\Model\PlansSendingServer();
                    $row->plan_id = $planGroup->id;
                    $row->sending_server_id = $server->id;
                    $row->fitness = $param['fitness'];
                    $planGroup->plansSendingServers->push($row);
                }
            }
        }

        // Email verification servers
        if (isset($request->old()['email_verification_servers'])) {
            $planGroup->fillPlansEmailVerificationServers($request->old()['email_verification_servers']);
        }
        return view('admin.plan_groups.edit', [
            'planGroup' => $planGroup,
            'options' => $options
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
    public function save(Request $request, $uid)
    {
        // Get current user
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        $validator = $planGroup->saveAll($request);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator->errors());
        }

        // check plan status
        $planGroup->checkStatus();

        # redirect to sending servers page when needed
        if ($request->use_system_sending_server) {
            return redirect()->action('Admin\PlanGroupController@sendingServers', $planGroup->uid);
        }

        return redirect()->back()->with('alert-success', trans('messages.plan_group.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $items = PlanGroup::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

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
        echo trans('messages.plans.deleted');
    }

    /**
     * Custom sort items.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        echo trans('messages._deleted_');
    }

    /**
     * Select2 plan.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function select2(Request $request)
    {
        echo PlanGroup::select2($request);
    }

    /**
     * Delete confirm message.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteConfirm(Request $request)
    {
        $planGroups = PlanGroup::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );

        return view('admin.plan_groups.delete_confirm', [
            'planGroups' => $planGroups,
        ]);
    }

    /**
     * Chart pie chart.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function pieChart(Request $request)
    {
        $admin = $request->user()->admin;

        // authorize
        if (\Gate::denies('read', new PlanGroup())) {
            return $this->notAuthorized();
        }

        $result = [
            'columns' => [],
            'data' => [],
        ];

        $datas = [];
        foreach (PlanGroup::active()->get() as $planGroup) {
            $count = $admin->getAllSubscriptionsByPlan($planGroup)->count();
            // create data
            if ($count) {
                $result['bar_names'][] = $planGroup->name;
                $result['data'][] = ['value' => $count, 'name' => $planGroup->name];
            }
        }

        usort($result['data'], function ($a, $b) {
            return strcmp($a['value'], $b['value']);
        });
        $result['data'] = array_reverse($result['data']);

        return response()->json($result);
    }

    public function copy(Request $request)
    {
        $planGroup = PlanGroup::findByUid($request->copy_plan_uid);

        // authorize
        if (\Gate::denies('copy', $planGroup)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
            ]);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('admin.plan_groups.copy', [
                    'planGroup' => $planGroup,
                    'errors' => $validator->errors(),
                ], 400);
            }

            $planGroup->copy($request->name);
            return trans('messages.plan_group.copied');
        }

        return view('admin.plan_groups.copy', [
            'planGroup' => $planGroup,
        ]);
    }

    /**
     * Plan sending server setting.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendingServer(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        if ($planGroup->getOption('sending_server_option') == PlanGroup::SENDING_SERVER_OPTION_SYSTEM) {
            return redirect()->action('Admin\PlanGroupController@sendingServers', $planGroup->uid);
        } elseif ($planGroup->getOption('sending_server_option') == PlanGroup::SENDING_SERVER_OPTION_OWN) {
            return redirect()->action('Admin\PlanGroupController@sendingServerOwn', $planGroup->uid);
        } elseif ($planGroup->getOption('sending_server_option') == PlanGroup::SENDING_SERVER_OPTION_SUBACCOUNT) {
            return redirect()->action('Admin\PlanGroupController@sendingServerSubaccount', $planGroup->uid);
        }
    }

    /**
     * Plan sending server subaccount setting.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendingServerSubaccount(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $planGroup->fillAll($request);
            $planGroup->save();

            $request->session()->flash('alert-success', trans('messages.plan_group.sending_server.saved'));
            return redirect()->action('Admin\PlanGroupController@sendingServerSubaccount', $planGroup->uid);
        }

        return view('admin.plan_groups.sending_server.subaccount', [
            'planGroup' => $planGroup,
        ]);
    }

    /**
     * Plan sending server own setting.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendingServerOwn(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $planGroup->fillAll($request);
            $planGroup->save();

            $request->session()->flash('alert-success', trans('messages.plan_group.sending_server.saved'));
            return redirect()->action('Admin\PlanGroupController@sendingServerOwn', $planGroup->uid);
        }

        return view('admin.plan_groups.sending_server.own', [
            'planGroup' => $planGroup,
        ]);
    }

    /**
     * Plan sending server setting.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function emailVerification(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        // fill all
        $planGroup->fillAll($request);

        return view('admin.plan_groups.email_verification', [
            'planGroup' => $planGroup,
        ]);
    }

    /**
     * Plan sending servers.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendingServers(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        return view('admin.plan_groups.sending_server.system', [
            'planGroup' => $planGroup,
        ]);
    }

    /**
     * Add plan sending server.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function addSendingServer(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $sendingServer = SendingServer::findByUid($request->sending_server_uid);
            $existIds = $planGroup->plansSendingServers()->pluck('sending_server_id')->toArray();

            // check if sending server is already added
            if (in_array($sendingServer->id, $existIds)) {
                $request->session()->flash('alert-error', trans('messages.plan_group.sending_server.already_added'));
                return redirect()->action('Admin\PlanGroupController@sendingServers', $planGroup->uid);
            }

            $planGroup->addSendingServerByUid($sendingServer->uid);

            // check plan status
            $planGroup->checkStatus();

            $request->session()->flash('alert-success', trans('messages.plan_group.sending_server.added'));
            return redirect()->action('Admin\PlanGroupController@sendingServers', $planGroup->uid);
        }

        return view('admin.plan_groups.sending_servers_add', [
            'planGroup' => $planGroup,
            'noSendingServer' => true,
        ]);
    }

    /**
     * Remove plan sending server.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function removeSendingServer(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        // save posted data
        $planGroup->removeSendingServerByUid($request->sending_server_uid);

        // check plan status
        $planGroup->checkStatus();

        $request->session()->flash('alert-success', trans('messages.plan_group.sending_server.removed'));
        return redirect()->action('Admin\PlanGroupController@sendingServers', $planGroup->uid);
    }

    /**
     * Set primary plan sending server.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function setPrimarySendingServer(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        // save posted data
        $planGroup->setPrimarySendingServer($request->sending_server_uid);

        // check plan status
        $planGroup->checkStatus();

        $request->session()->flash('alert-success', trans('messages.plan_group.sending_server.primary.updated'));
        return redirect()->action('Admin\PlanGroupController@sendingServers', $planGroup->uid);
    }

    /**
     * Plan sending servers fitnesses.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function fitness(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        // update fitness value
        if ($request->isMethod('post')) {
            $planGroup->updateFitnesses($request->sending_servers);

            // check plan status
            $planGroup->checkStatus();

            $request->session()->flash('alert-success', trans('messages.plan_group.sending_servers.fitness.updated'));
            return redirect()->action('Admin\PlanGroupController@sendingServers', $planGroup->uid);
        }

        return view('admin.plan_groups.fitness', [
            'planGroup' => $planGroup,
        ]);
    }

    /**
     * Plan general information.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function general(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        // fill all params
        $planGroup->fillAll($request);

        return view('admin.plan_groups.general', [
            'planGroup' => $planGroup,
        ]);
    }

    /**
     * Plan quota information.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function quota(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        // fill all
        $planGroup->fillAll($request);

        return view('admin.plan_groups.quota', [
            'planGroup' => $planGroup,
            'options' => $planGroup->getOptions(),
        ]);
    }

    /**
     * Plan security information.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function security(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        // fill all
        $planGroup->fillAll($request);

        return view('admin.plan_groups.security', [
            'planGroup' => $planGroup,
        ]);
    }

    /**
     * Plan payment information.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function payment(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        // fill all
        $planGroup->fillAll($request);

        return view('admin.plan_groups.payment', [
            'planGroup' => $planGroup,
        ]);
    }

    /**
     * Plan email footer information.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $uid
     *
     * @return \Illuminate\Http\Response
     */
    public function emailFooter(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        // fill all
        $planGroup->fillAll($request);

        return view('admin.plan_groups.email_footer', [
            'planGroup' => $planGroup,
        ]);
    }

    public function tos(Request $request)
    {
        $planGroup = PlanGroup::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            $validator = $planGroup->updateTos($request->all());

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('admin.plan_groups.tos', [
                    'planGroup' => $planGroup,
                    'errors' => $validator->errors(),
                ], 400);
            }

            $request->session()->flash('alert-success', trans('messages.plan_group.tos.updated'));
        }

        return view('admin.plan_groups.tos', [
            'planGroup' => $planGroup,
        ]);
    }

    /**
     * Sending Limit Form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendingLimit(Request $request)
    {
        if (!$request->uid) {
            $planGroup = new PlanGroup();
        } else {
            $planGroup = PlanGroup::findByUid($request->uid);
        }

        // save posted data
        if ($request->isMethod('post')) {
            $planGroup->fillAll($request);
            return view('admin.plan_groups._sending_limit', [
                'planGroup' => $planGroup,
            ]);
        }

        return view('admin.plan_groups.sending_limit', [
            'planGroup' => $planGroup,
        ]);
    }

    /**
     * Billing cycle form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function billingCycle(Request $request)
    {
        if (!$request->uid || $request->uid == '00') {
            $planGroup = new PlanGroup();
            $planGroup->uid = '00';
        } else {
            $planGroup = PlanGroup::findByUid($request->uid);
        }

        // save posted data
        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), [
                'plan.general.frequency_amount' => 'required',
            ]);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('admin.plan_groups.billing_cycle', [
                    'planGroup' => $planGroup,
                    'errors' => $validator->errors(),
                ], 400);
            }

            $planGroup->fillAll($request);

            return view('admin.plan_groups._billing_cycle', [
                'planGroup' => $planGroup,
            ]);
        }

        return view('admin.plan_groups.billing_cycle', [
            'planGroup' => $planGroup,
        ]);
    }

    /**
     * Sending Limit Form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function wizard(Request $request)
    {
        if (!$request->uid) {
            $planGroup = PlanGroup::newDefaultPlan();
        } else {
            $planGroup = PlanGroup::findByUid($request->uid);
        }

        // fill all
        $planGroup->fillAll($request);

        // save posted data
        if ($request->isMethod('post')) {
            $validator = $planGroup->validate($request);

            if ($validator->fails()) {
                return redirect()
                    ->action('Admin\PlanGroupController@wizard')
                    ->withErrors($validator)
                    ->withInput();
            }

            $planGroup->saveAll($request);

            // check plan status
            $planGroup->checkStatus();

            return response()->json([
                'redirect' => action('Admin\PlanGroupController@index'),
            ]);
        }

        return view('admin.plan_groups.wizard', [
            'planGroup' => $planGroup,
        ]);
    }

    /**
     * Sending Limit Form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function wizardSendingServer(Request $request)
    {
        $planGroup = PlanGroup::findByUid($request->uid);
        // fill all
        $planGroup->fillAll($request);

        // save posted data
        if ($request->isMethod('post')) {
            $validator = $planGroup->validate($request);

            if ($validator->fails()) {
                return redirect()
                    ->action('Admin\PlanGroupController@wizardSendingServer', $planGroup->uid)
                    ->withErrors($validator)
                    ->withInput();
            }

            $planGroup->saveAll($request);

            // check plan status
            $planGroup->checkStatus();

            return 'success';
        }

        return view('admin.plan_groups.wizard_sending_server', [
            'planGroup' => $planGroup,
        ]);
    }

    /**
     * Plan sending server change option.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendingServerOption(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $planGroup)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $planGroup->fillAll($request);
            $planGroup->save();

            // check plan status
            $planGroup->checkStatus();

            return redirect()->action('Admin\PlanGroupController@sendingServer', $planGroup->uid);
        }

        return view('admin.plan_groups.sending_server_option', [
            'planGroup' => $planGroup,
        ]);
    }

    /**
     * Show item.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function visibleOn(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('visibleOn', $planGroup)) {
            //
            return response()->json([
                'status' => 'error',
                'message' => trans('messages.plan_group.show.denied'),
            ], 201);
        }

        //
        $planGroup->visibleOn();

        // check plan status
        $planGroup->checkStatus();

        // Redirect to my lists page
        return response()->json([
            'status' => 'success',
            'message' => trans('messages.plan_group.showed'),
        ], 201);
    }

    /**
     * Show item.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function visibleOff(Request $request, $uid)
    {
        $planGroup = PlanGroup::findByUid($uid);

        // authorize
        if (\Gate::denies('visibleOff', $planGroup)) {
            //
            return response()->json([
                'status' => 'error',
                'message' => trans('messages.plan_group.hide.denied'),
            ], 201);
        }

        //
        $planGroup->visibleOff();

        // check plan status
        $planGroup->checkStatus();

        // Redirect to my lists page
        return response()->json([
            'status' => 'success',
            'message' => trans('messages.plan_group.hidden'),
        ], 201);
    }

    public function publicView(Request $request)
    {
        // authorize
        if (\Gate::denies('read', new PlanGroup())) {
            return $this->notAuthorized();
        }

        $planGroups = PlanGroup::getAvailableGeneralPlans();

        $style = $request->style ?? 'default';

        return view('admin.plan_groups.publicView', [
            'planGroups' => $planGroups,
            'style' => $style,
        ]);
    }
}
