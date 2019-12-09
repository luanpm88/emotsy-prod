<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Acelle\Http\Requests;
use Acelle\Model\Automation2;
use Acelle\Model\MailList;
use Acelle\Model\Email;

class Automation2Controller extends Controller
{
    /**
     * Creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $customer = $request->user()->customer;
        
        // init automation
        $automation = new Automation2([
            'name' => trans('messages.automation.untitled')
        ]);
        
        // authorize
        if (\Gate::denies('create', $automation)) {
            return $this->noMoreItem();
        }
        
        // saving
        if($request->isMethod('post')) {
            // fill before save
            $automation->fill($request->all());
            
            // make validator
            $validator = Validator::make($request->all(), $automation->rules());
            
            // redirect if fails
            if ($validator->fails()) {
                return response()->view('automation2.create', [
                    'automation' => $automation,
                    'errors' => $validator->errors(),
                ], 400);
            }
            
            // pass validation and save
            $automation->mail_list_id = MailList::findByUid($request->mail_list_uid)->id;
            $automation->customer_id = $customer->id;
            $automation->save();
            
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.automation.created.redirecting'),
                'url' => action('Automation2Controller@edit', ['uid' => $automation->uid])
            ], 201);
        }
        
        return view('automation2.create', [
            'automation' => $automation,                          
        ]);
    }
    
    /**
     * Creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        return view('automation2.edit', [
            'automation' => $automation,                          
        ]);
    }
    
    /**
     * Select trigger type popup.
     *
     * @return \Illuminate\Http\Response
     */
    public function triggerSelectPupop(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        $types = [
            'welcome-new-subscriber',
            'say-happy-birthday',
            'subscriber-added-date',
            'specific-date',
            'say-goodbye-subscriber',
            'api-3-0',
        ];
        
        return view('automation2.triggerSelectPupop', [
            'types' => $types,
            'automation' => $automation,
        ]);
    }
    
    /**
     * Select trigger type confirm.
     *
     * @return \Illuminate\Http\Response
     */
    public function triggerSelectConfirm(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        return view('automation2.triggerSelectConfirm', [
            'type' => $request->type,
            'automation' => $automation,
        ]);
    }
    
    /**
     * Select trigger type.
     *
     * @return \Illuminate\Http\Response
     */
    public function triggerSelect(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        return response()->json([
            'status' => 'success',
            'message' => trans('messages.automation.trigger.added'),
            'title' => trans('messages.automation.trigger.title', [
                    'title' => trans('messages.automation.trigger.' . $request->type)
                ]),
            'options' => [
                'type' => $request->type,
            ],
        ]);
    }
    
    /**
     * Select action type popup.
     *
     * @return \Illuminate\Http\Response
     */
    public function actionSelectPupop(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        $types = [
            'send-an-email',
            'wait',
            'condition',
        ];
        
        return view('automation2.actionSelectPupop', [
            'types' => $types,
            'automation' => $automation,
        ]);
    }
    
    /**
     * Select action type confirm.
     *
     * @return \Illuminate\Http\Response
     */
    public function actionSelectConfirm(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        // if it is email
        if ($request->type == 'send-an-email') {
            return redirect()->action('Automation2Controller@emailSetup', [
                'uid' => $automation->uid,
            ]);
        }
        
        return view('automation2.actionSelectConfirm', [
            'type' => $request->type,
            'automation' => $automation,
        ]);
    }
    
    /**
     * Select trigger type.
     *
     * @return \Illuminate\Http\Response
     */
    public function actionSelect(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        if ($request->type == 'wait') {
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.automation.action.added'),
                'title' => trans('messages.automation.action.wait.title', [
                    'number' => explode('_', $request->after)[0],
                    'time' => explode('_', $request->after)[1],
                ]),
                'options' => [
                    'type' => $request->type,
                    'after' => $request->after,
                ],
            ]);
        } else if ($request->type == 'condition') {
            if ($request->criterion == 'read_email') {
                return response()->json([
                    'status' => 'success',
                    'message' => trans('messages.automation.action.added'),
                    'title' => trans('messages.automation.action.condition.read_email.title'),
                    'options' => [
                        'type' => $request->type,
                        'criterion' => $request->criterion,
                        'read_email' => $request->read_email,
                    ],
                ]);
            } else if ($request->criterion == 'click_link') {
                return response()->json([
                    'status' => 'success',
                    'message' => trans('messages.automation.action.added'),
                    'title' => trans('messages.automation.action.condition.click_link.title'),
                    'options' => [
                        'type' => $request->type,
                        'criterion' => $request->criterion,
                        'click_link' => $request->click_link,
                    ],
                ]);
            }
        } else {
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.automation.action.added'),
                'title' => trans('messages.automation.action.title', [
                    'title' => trans('messages.automation.action.' . $request->type)
                ]),
                'options' => [
                    'type' => $request->type,
                    'after' => $request->after,
                ],
            ]);
        }
    }
    
    /**
     * Edit trigger.
     *
     * @return \Illuminate\Http\Response
     */
    public function triggerEdit(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        return view('automation2.triggerEdit', [
            'type' => $request->type,
            'automation' => $automation,
        ]);
    }
    
    /**
     * Edit action.
     *
     * @return \Illuminate\Http\Response
     */
    public function actionEdit(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        return view('automation2.actionEdit', [
            'type' => $request->type,
            'automation' => $automation,
        ]);
    }
    
    /**
     * Email setup.
     *
     * @return \Illuminate\Http\Response
     */
    public function emailSetup(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        if ($request->email_uid) {
            $email = Email::findByUid($request->email_uid);
        } else {
            $email = new Email();
        }
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        // saving
        if($request->isMethod('post')) {
            // fill before save
            $email->fill($request->all());
            
            // make validator
            $validator = Validator::make($request->all(), $email->rules());
            
            // redirect if fails
            if ($validator->fails()) {
                return response()->view('automation2.email.setup', [
                    'automation' => $automation,
                    'email' => $email,
                    'errors' => $validator->errors(),
                ], 400);
            }
            
            // pass validation and save
            $email->automation2_id = $automation->id;
            $email->save();
            
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.automation.email.created.redirecting'),
                'url' => action('Automation2Controller@emailTemplate', ['uid' => $automation->uid, 'email_uid' => $email->uid])
            ], 201);
        }
        
        return view('automation2.email.setup', [
            'automation' => $automation,
            'email' => $email,
        ]);
    }
    
    /**
     * Email template.
     *
     * @return \Illuminate\Http\Response
     */
    public function emailTemplate(Request $request, $uid, $email_uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        $email = Email::findByUid($email_uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        if (!$email->content) {
            return redirect()->action('Automation2Controller@templateCreate', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]);
        }
        
        return view('automation2.email.template', [
            'automation' => $automation,
            'email' => $email,
        ]);
    }
    
    /**
     * Email show.
     *
     * @return \Illuminate\Http\Response
     */
    public function email(Request $request, $uid, $email_uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        $email = Email::findByUid($email_uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        return view('automation2.email.index', [
            'automation' => $automation,
            'email' => $email,
        ]);
    }
    
    /**
     * Email confirm.
     *
     * @return \Illuminate\Http\Response
     */
    public function emailConfirm(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        $email = Email::findByUid($request->email_uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        // saving
        if($request->isMethod('post')) {
            
        }
        
        return view('automation2.email.confirm', [
            'automation' => $automation,
            'email' => $email,
        ]);
    }
    
    /**
     * Create template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateCreate(Request $request, $uid, $email_uid)
    {
        $automation = Automation2::findByUid($uid);
        $email = Email::findByUid($request->email_uid);

        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }

        return view('automation2.email.template.create', [
            'automation' => $automation,
            'email' => $email,
        ]);
    }

    /**
     * Select template type.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateSelect(Request $request)
    {
        $user = $request->user();
        $campaign = \Acelle\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.template_select', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Create template from layout.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateLayout(Request $request, $uid, $email_uid)
    {
        $automation = Automation2::findByUid($uid);
        $email = Email::findByUid($request->email_uid);

        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }

        // add layout to campaign template
        if ($request->isMethod('post')) {
            if ($request->layout) {
                $email->addTemplateFromLayout($request->layout);
            }
            
            return redirect()->action('Automation2Controller@emailTemplate', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]);
        }

        return view('automation2.email.template.layout', [
            'automation' => $automation,
            'email' => $email,
        ]);
    }

    /**
     * Edit campaign template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateEdit(Request $request, $uid, $email_uid)
    {
        $automation = Automation2::findByUid($uid);
        $email = Email::findByUid($request->email_uid);

        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }

        // save campaign html
        if ($request->isMethod('post')) {
            $rules = array(
                'content' => 'required',
            );
            
            $this->validate($request, $rules);
            
            $email->content = $request->content;
            $email->save();
            
            return response()->json([
                'status' => 'success',
            ]);
        }

        return view('automation2.email.template.edit', [
            'automation' => $automation,
            'list' => $automation->mailList,
            'email' => $email,
            'templates' => $email->getBuilderTemplates($request->user()->customer),
        ]);
    }

    /**
     * Upload asset to builder.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function templateAsset(Request $request, $uid, $email_uid)
    {
        $automation = Automation2::findByUid($uid);
        $email = Email::findByUid($request->email_uid);

        $path = $request->file('file')->storeAs(
            'email/assets/' . $email->uid, md5($request->file('file')), 'public'
        );

        return response()->json([
            'url' => \Storage::url($path)
        ]);
    }

    /**
     * Campaign html content.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateContent(Request $request, $uid, $email_uid)
    {
        $automation = Automation2::findByUid($uid);
        $email = Email::findByUid($request->email_uid);

        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }

        return view('automation2.email.template.content', [
            'content' => $email->content,
        ]);
    }

    /**
     * Create template from theme.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateTheme(Request $request, $uid, $email_uid)
    {
        $automation = Automation2::findByUid($uid);
        $email = Email::findByUid($request->email_uid);

        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            $template = \Acelle\Model\Template::findByUid($request->template);
            $campaign->copyFromTemplate($template);

            return redirect()->action('CampaignController@templateEdit', $campaign->uid);
        }

        return view('automation2.email.template.theme', [
            'automation' => $automation,
            'email' => $email,
        ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function templateThemeList(Request $request)
    {
        $campaign = \Acelle\Model\Campaign::findByUid($request->uid);
        $request->merge(array("customer_id" => $request->user()->customer->id));
        $templates = Template::search($request)->paginate($request->per_page);

        return view('campaigns.template.themeList', [
            'campaign' => $campaign,
            'templates' => $templates,
        ]);
    }

    /**
     * Upload template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateUpload(Request $request)
    {
        $campaign = \Acelle\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            $campaign->uploadTemplate($request);

            return redirect()->action('CampaignController@templateEdit', $campaign->uid);
        }

        return view('campaigns.template.upload', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Choose an existed template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateChoose(Request $request)
    {
        $user = $request->user();
        $campaign = \Acelle\Model\Campaign::findByUid($request->uid);
        $template = \Acelle\Model\Template::findByUid($request->template_uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        $campaign->html = $template->content;
        $campaign->template_source = $template->source;
        // $campaign->plain = preg_replace('/\s+/',' ',preg_replace('/\r\n/',' ',strip_tags($campaign->html)));
        $campaign->save();

        if (!$campaign->is_auto) {
            return redirect()->action('CampaignController@templatePreview', ['uid' => $campaign->uid]);
        } else {
            return redirect()->action('AutoEventController@templatePreview', ['uid' => $campaign->autoEvent()->uid, 'campaign_uid' => $campaign->uid]);
        }
    }

    /**
     * Template preview.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templatePreview(Request $request)
    {
        $user = $request->user();
        $campaign = \Acelle\Model\Campaign::findByUid($request->uid);

        $rules = [];

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.template_preview', [
            'campaign' => $campaign,
            'rules' => $rules,
        ]);
    }

    /**
     * Template preview iframe.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateIframe(Request $request)
    {
        $user = $request->user();
        $campaign = \Acelle\Model\Campaign::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $campaign)) {
            return $this->notAuthorized();
        }

        return view('campaigns.preview', [
            'campaign' => $campaign,
        ]);
    }
}