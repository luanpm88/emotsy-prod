<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Acelle\Http\Requests;
use Acelle\Model\Automation2;
use Acelle\Model\MailList;
use Acelle\Model\Email;
use Acelle\Model\Attachment;
use Acelle\Model\Template;
use Acelle\Model\Subscriber;
use Illuminate\Support\Facades\Storage;

class Automation2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $automations = $user->customer->automation2s();

        return view('automation2.index', [
            'automations' => $automations,
        ]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        $automations = Automation2::search($request)->paginate($request->per_page);

        return view('automation2._list', [
            'automations' => $automations,
        ]);
    }
    
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
            'name' => trans('messages.automation.untitled'),            
        ]);
        $automation->status = Automation2::STATUS_INACTIVE;
        
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
     * Update automation.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uid)
    {
        $customer = $request->user()->customer;
        
        // find automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        // fill before save
        $automation->fill($request->all());
            
        // make validator
        $validator = Validator::make($request->all(), $automation->rules());
            
        // redirect if fails
        if ($validator->fails()) {
            return response()->view('automation2.settings', [
                'automation' => $automation,
                'errors' => $validator->errors(),
            ], 400);
        }
            
        // pass validation and save
        $automation->mail_list_id = MailList::findByUid($request->mail_list_uid)->id;
        $automation->save();
            
        return response()->json([
            'status' => 'success',
            'message' => trans('messages.automation.updated'),
        ], 201);
    }
    
    /**
     * Update automation.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveData(Request $request, $uid)
    {
        // find automation
        $automation = Automation2::findByUid($uid);
        $automation->saveData(json_encode($request->data));
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
     * Automation settings in sidebar.
     *
     * @return \Illuminate\Http\Response
     */
    public function settings(Request $request, $uid)
    {
        // init automation
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        return view('automation2.settings', [
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
            'key' => $request->key,
            'automation' => $automation,
            'trigger' => $automation->getTrigger(),
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
                    'title' => trans('messages.automation.trigger.' . $request->options["key"])
                ]),
            'options' => $request->options,
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
        if ($request->key == 'send-an-email') {
            return redirect()->action('Automation2Controller@emailSetup', [
                'uid' => $automation->uid,
            ]);
        }
        
        return view('automation2.actionSelectConfirm', [
            'key' => $request->key,
            'automation' => $automation,
            'element' => $automation->getElement(),
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
        
        if ($request->key == 'wait') {
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.automation.action.added'),
                'title' => trans('messages.automation.action.wait.title', [
                    'number' => explode(' ', $request->time)[0],
                    'time' => explode(' ', $request->time)[1],
                ]),
                'options' => [
                    'key' => $request->key,
                    'time' => $request->time,
                ],
            ]);
        } else if ($request->key == 'condition') {
            if ($request->type == 'open') {
                return response()->json([
                    'status' => 'success',
                    'message' => trans('messages.automation.action.added'),
                    'title' => trans('messages.automation.action.condition.read_email.title'),
                    'options' => [
                        'key' => $request->key,
                        'type' => $request->type,
                        'read_email' => $request->read_email,
                    ],
                ]);
            } else if ($request->type == 'click') {
                return response()->json([
                    'status' => 'success',
                    'message' => trans('messages.automation.action.added'),
                    'title' => trans('messages.automation.action.condition.click_link.title'),
                    'options' => [
                        'key' => $request->key,
                        'type' => $request->type,
                        'click_link' => $request->click_link,
                    ],
                ]);
            }
        } else {
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.automation.action.added'),
                'title' => trans('messages.automation.action.title', [
                    'title' => trans('messages.automation.action.' . $request->key)
                ]),
                'options' => [
                    'key' => $request->key,
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
        
        if($request->isMethod('post')) {
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.automation.trigger.updated'),
                'title' => trans('messages.automation.trigger.title', [
                    'title' => trans('messages.automation.trigger.' . $request->options["key"])
                ]),
                'options' => $request->options,
            ]);
        }
        
        return view('automation2.triggerEdit', [
            'key' => $request->key,
            'automation' => $automation,
            'trigger' => $automation->getTrigger(),
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
        
        // saving
        if($request->isMethod('post')) {
            if ($request->key == 'wait') {
                return response()->json([
                    'status' => 'success',
                    'message' => trans('messages.automation.action.updated'),
                    'title' => trans('messages.automation.action.wait.title', [
                        'number' => explode(' ', $request->time)[0],
                        'time' => explode(' ', $request->time)[1],
                    ]),
                    'options' => [
                        'key' => $request->key,
                        'time' => $request->time,
                    ],
                ]);
            } else if ($request->key == 'condition') {
                if ($request->type == 'open') {
                    return response()->json([
                        'status' => 'success',
                        'message' => trans('messages.automation.action.updated'),
                        'title' => trans('messages.automation.action.condition.read_email.title'),
                        'options' => [
                            'key' => $request->key,
                            'type' => $request->type,
                            'read_email' => $request->read_email,
                        ],
                    ]);
                } else if ($request->type == 'click') {
                    return response()->json([
                        'status' => 'success',
                        'message' => trans('messages.automation.action.updated'),
                        'title' => trans('messages.automation.action.condition.click_link.title'),
                        'options' => [
                            'key' => $request->key,
                            'type' => $request->type,
                            'click_link' => $request->click_link,
                        ],
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => trans('messages.automation.action.updated'),
                    'title' => trans('messages.automation.action.title', [
                        'title' => trans('messages.automation.action.' . $request->key)
                    ]),
                    'options' => [
                        'key' => $request->key,
                        'after' => $request->after,
                    ],
                ]);
            }
        }
        
        return view('automation2.actionEdit', [
            'key' => $request->key,
            'automation' => $automation,
            'element' => $automation->getElement($request->id),
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
            $email = new Email([
                'sign_dkim' => true,
                'track_open' => true,
                'track_click' => true,
            ]);
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
            
            if (empty($request->email_uid)) {
                return response()->json([
                    'status' => 'success',
                    'title' => trans('messages.automation.send_a_email', ['title' => $email->subject]),
                    'message' => trans('messages.automation.email.created'),
                    'url' => action('Automation2Controller@emailTemplate', [
                        'uid' => $automation->uid,
                        'email_uid' => $email->uid,
                    ]),
                    'is_new' => true,
                    'options' => [
                        'edit_url' => action('Automation2Controller@email', [
                            'uid' => $automation->uid,
                            'email_uid' => $email->uid,
                        ]),
                    ],
                ], 201);
            } else {
                return response()->json([
                    'status' => 'success',
                    'title' => trans('messages.automation.send_a_email', ['title' => $email->subject]),
                    'message' => trans('messages.automation.email.updated'),
                    'url' => action('Automation2Controller@emailTemplate', [
                        'uid' => $automation->uid,
                        'email_uid' => $email->uid,
                    ]),
                    'is_new' => false,
                    'options' => [
                        'edit_url' => action('Automation2Controller@email', [
                            'uid' => $automation->uid,
                            'email_uid' => $email->uid,
                        ]),
                    ],
                ], 201);
            }
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
            $template = Template::findByUid($request->template_uid);
            $email->copyFromTemplate($template);
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
    public function templateThemeList(Request $request, $uid, $email_uid)
    {
        sleep(1);
        $automation = Automation2::findByUid($uid);
        $email = Email::findByUid($request->email_uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        $request->merge(array("customer_id" => $request->user()->customer->id));
        list($templates, $pagination) = pagination($request, Template::search($request));
        
        return view('automation2.email.template.themeList', [
            'automation' => $automation,
            'email' => $email,
            'templates' => $templates,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Upload template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function templateUpload(Request $request, $uid, $email_uid)
    {
        $automation = Automation2::findByUid($uid);
        $email = Email::findByUid($request->email_uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            
            list($result, $validator) = $email->uploadTemplate($request);
            
            if (!$result) {
                return response()->view('automation2.email.template.upload', [
                    'automation' => $automation,
                    'email' => $email,
                    'errors' => $validator->errors(),
                ], 400);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => trans('messages.automation.email.template.uploaded'),
                ], 201);
            }
        }

        return view('automation2.email.template.upload', [
            'automation' => $automation,
            'email' => $email,
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
        if (\Gate::denies('update', $automation)) {
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
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }

        return view('campaigns.template_preview', [
            'campaign' => $campaign,
            'rules' => $rules,
        ]);
    }
    
    /**
     * Attachment upload.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function emailAttachmentUpload(Request $request, $uid, $email_uid)
    {
        $automation = Automation2::findByUid($uid);
        $email = Email::findByUid($request->email_uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        foreach($request->file as $file) {
            $email->uploadAttachment($file);
        }
    }
    
    /**
     * Attachment remove.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function emailAttachmentRemove(Request $request, $uid, $email_uid, $attachment_uid)
    {
        $automation = Automation2::findByUid($uid);
        $email = Email::findByUid($request->email_uid);
        $attachment = Attachment::findByUid($request->attachment_uid);
        
        // authorize
        if (\Gate::denies('update', $automation)) {
            return $this->notAuthorized();
        }
        
        $attachment->remove();
        
        return response()->json([
            'status' => 'success',
            'message' => trans('messages.automation.email.attachment.removed'),
        ], 201);
    }
    
    /**
     * Attachment download.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function emailAttachmentDownload(Request $request, $uid, $email_uid, $attachment_uid)
    {
        $automation = Automation2::findByUid($uid);
        $email = Email::findByUid($request->email_uid);
        $attachment = Attachment::findByUid($request->attachment_uid);
        
        // authorize
        if (\Gate::denies('read', $automation)) {
            return $this->notAuthorized();
        }
        
        return response()->download(storage_path('app/' . $attachment->file), $attachment->name);
    }
    
    /**
     * Enable automation.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request)
    {
        $automations = Automation2::whereIn('uid', explode(',', $request->uids));

        foreach ($automations->get() as $automation) {
            // authorize
            if (\Gate::allows('enable', $automation)) {
                $automation->enable();
            }
        }
        
        return response()->json([
            'status' => 'success',
            'message' => trans_choice('messages.automation.enabled', $automations->count()),
        ]);
    }
    
    /**
     * Disable event.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request)
    {
        $automations = Automation2::whereIn('uid', explode(',', $request->uids));

        foreach ($automations->get() as $automation) {
            // authorize
            if (\Gate::allows('disable', $automation)) {
                $automation->disable();
            }
        }
        
        return response()->json([
            'status' => 'success',
            'message' => trans_choice('messages.automation.disabled', $automations->count()),
        ]);
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
        if (isSiteDemo()) {
            echo trans('messages.operation_not_allowed_in_demo');
            return;
        }
        
        $automations = Automation2::whereIn('uid', explode(',', $request->uids));

        foreach ($automations->get() as $automation) {
            // authorize
            if (\Gate::allows('delete', $automation)) {
                $automation->delete();
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => trans_choice('messages.automation.deleted', $automations->count()),
        ]);
    }
    
    /**
     * Automation insight page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function insight(Request $request, $uid)
    {
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('view', $automation)) {
            return $this->notAuthorized();
        }

        return view('automation2.insight', [
            'automation' => $automation,
        ]);
    }
    
    /**
     * Automation contacts list.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function contacts(Request $request, $uid)
    {
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('view', $automation)) {
            return $this->notAuthorized();
        }

        return view('automation2.contacts.index', [
            'automation' => $automation,
        ]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contactsList(Request $request, $uid)
    {
        sleep(1);
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('view', $automation)) {
            return $this->notAuthorized();
        }
        
        list($contacts, $pagination) = pagination($request, Subscriber::gSearch($automation->mailList->subscribers(), $request));
        
        return view('automation2.contacts.list', [
            'automation' => $automation,
            'contacts' => $contacts,
            'pagination' => $pagination,
        ]);
    }
    
    /**
     * Automation timeline.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function timeline(Request $request, $uid)
    {
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('view', $automation)) {
            return $this->notAuthorized();
        }

        return view('automation2.timeline', [
            'automation' => $automation,
        ]);
    }
    
    /**
     * Automation contact profile.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request, $uid)
    {
        $automation = Automation2::findByUid($uid);
        
        // authorize
        if (\Gate::denies('view', $automation)) {
            return $this->notAuthorized();
        }

        return view('automation2.profile', [
            'automation' => $automation,
        ]);
    }
}