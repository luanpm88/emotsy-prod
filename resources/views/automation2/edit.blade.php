@extends('layouts.core.frontend_dark')

@section('title', trans('messages.automation.create'))

@section('head')
    <script type="text/javascript" src="{{ AppUrl::asset('core/datetime/anytime.min.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/datetime/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/datetime/pickadate/picker.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/datetime/pickadate/picker.date.js') }}"></script>

    <!-- Dropzone -->
	<script type="text/javascript" src="{{ AppUrl::asset('core/dropzone/dropzone.js') }}"></script>
    @include('helpers._dropzone_lang')
	<link href="{{ AppUrl::asset('core/dropzone/dropzone.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ AppUrl::asset('core/css/automation.css') }}">
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/automation.js') }}"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"></link>

    <script type="text/javascript" src="{{ AppUrl::asset('core/echarts/echarts.min.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/echarts/dark.js') }}"></script> 

    <script type="text/javascript" src="{{ AppUrl::asset('core/js/group-manager.js') }}"></script>

    <link href="{{ AppUrl::asset('core/emojionearea/emojionearea.min.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{ AppUrl::asset('core/emojionearea/emojionearea.min.js') }}"></script>
@endsection

@section('menu_title')
    <li class="d-flex align-items-center">
        <div class="d-inline-block d-flex mr-auto align-items-center ml-1">
            <h4 class="my-0 me-2 automation-title">{{ $automation->name }}</h4>
            <i class="material-symbols-rounded">alarm</i>
        </div>
    </li>
@endsection

@section('menu_right')
    <li class="d-flex align-items-center">
        <div class="d-flex align-items-center automation-top-actions">
            <span class="me-4"><i class="last_save_time" data-url="{{ action('Automation2Controller@lastSaved', $automation->uid) }}">{{ trans('messages.automation.designer.last_saved', ['time' => $automation->updated_at->diffForHumans()]) }}</i></span>
            <a href="{{ action('Automation2Controller@index') }}" class="action me-4">
                <i class="material-symbols-rounded me-2">arrow_back</i>
                {{ trans('messages.automation.go_back') }}
            </a>

            @if ($automation->getSwitchAutomations(Auth::user()->customer)->count())
                <div class="switch-automation d-flex me-2">
                    <select class="select select2 top-menu-select" name="switch_automation">
                        <option value="--hidden--"></option>
                        @foreach($automation->getSwitchAutomations(Auth::user()->customer)->get() as $auto)
                            <option value='{{ action('Automation2Controller@edit', $auto->uid) }}'>{{ $auto->name }}</option>
                        @endforeach
                    </select>

                    <a href="javascript:'" class="action">
                        <i class="material-symbols-rounded me-2">horizontal_split</i>
                        {{ trans('messages.automation.switch_automation') }}
                    </a>
                </div>
            @endif
        </div>
    </li>

    @include('layouts.core._menu_frontend_user')
@endsection

@section('content')
    <style>
        rect.selected {
            stroke-width: 4 !important;;
        }

        rect.element {
            stroke:black;
            stroke-width:0;
        }

        rect.action {
            fill: rgb(101, 117, 138);
            stroke:  rgb(154 181 214);
        }

        rect.trigger {
            fill: rgba(12, 12, 12, 0.49);
            stroke: #c5c0c0;
        }

        rect.wait {
            fill: #fafafa;
            stroke: #94a0d4;
            stroke-width: 1;
        }

        rect.operation {
            fill: #2fa268;
            stroke: #a6d6ac;
        }

        rect.webhook {
            fill: #cf70ab;
            stroke: #f1c0f2;
        }

        g.wait > g > a tspan {
            fill: #666;
        }

        rect.condition {
            fill: #e47a50;
            stroke: #ffb091;
        }

        g text:hover, g tspan:hover {
            fill: pink !important;
        }
    </style>
    
    <main role="main">
        <div class="automation2">
            <div class="diagram text-center scrollbar-inner">                
                <svg id="svg" style="overflow: auto" width="3800" height="12800" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <text x="475" y="30" alignment-baseline="middle" text-anchor="middle">{{ trans('messages.automation.designer.intro') }}</text>

                </svg>

                <div class="history">
                    <a id="HistoryUndoButton" class="history-action history-undo" href="javascript:;">
                        <i class="material-symbols-rounded">undo</i>
                    </a>
                    <a id="HistoryRedoButton" class="history-action history-redo disabled" href="javascript:;">
                        <i class="material-symbols-rounded">redo</i>
                    </a>
                    <a id="HistoryListButton" class="history-action history-list" href="javascript:;">
                        <i class="material-symbols-rounded">history</i>
                    </a>
                    <ul id="HistoryListItems" class="history-list-items">
                        <li>
                            <a href="" class="d-flex align-items-center current">
                                <i class="material-symbols-rounded me-2">refresh</i>
                                <span class="content mr-auto">Reset current flow</span>
                                {{-- <time class="mini text-muted">1 minute</time> --}}
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="" class="d-flex align-items-center">
                                <i class="material-symbols-rounded me-2">alarm</i>
                                <span class="content mr-auto">Wait activity added</span>
                                {{-- <time class="mini text-muted">3 hours</time> --}}
                            </a>
                        </li>
                        <li>
                            <a href="" class="d-flex align-items-center">
                                <i class="material-symbols-rounded me-2">email</i>
                                <span class="content mr-auto">Send email activity added</span>
                                {{-- <time class="mini text-muted">4 days</time> --}}
                            </a>
                        </li>
                        <li>
                            <a href="" class="d-flex align-items-center">
                                <i class="material-symbols-rounded me-2">call_split</i>
                                <span class="content mr-auto">Condition activity added</span>
                                {{-- <time class="mini text-muted">20 Aug</time> --}}
                            </a>
                        </li>
                        <li>
                            <a href="" class="d-flex align-items-center">
                                <i class="material-symbols-rounded me-2">play_circle_outline</i>
                                <span class="content mr-auto">Trigger criteria setup</span>
                                {{-- <time class="mini text-muted">11 Aug</time> --}}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="sidebar scrollbar-inner">
                <div class="sidebar-content">
                    
                </div>
            </div>
        </div>
    </main>
        
    <script>
        // timeline popup
        var timelinePopup = new Popup(undefined, undefined, {
            onclose: function() {
                // sidebar.load();
            }
        });
        // Automation main popup
        var automationPopup = new Popup(undefined, undefined, {
            onclose: function() {
                sidebar.load();
            }
        });
        var sidebar = new Box($('.sidebar-content'));
        var lastSaved = new Box($('.last_save_time'), $('.last_save_time').attr('data-url'));
        var tree;
        var MyAutomation = {
            // Set the AddElement function
            // It depends on the currently selected element
            addToTree: null
        };

        // init EVENTs
        function initEvents() {
            // Event: Click on history toogle button
            $('#HistoryListButton').click(function() {
                toggleHistoryPanel();
            });

            // Event: Click on history undo button
            $('#HistoryListItems a, #HistoryUndoButton').click(function(e) {
                e.preventDefault();

                var dialog = new Dialog('alert', {
                    message: '{{ trans('messages.automation.rollback.warning') }}',
                });
            });

            // Event: Hide history items when click outside
            $(document).mouseup(function(e) 
            {
                var container = $("#HistoryListItems");

                // if the target of the click isn't the container nor a descendant of the container
                if (!container.is(e.target) && container.has(e.target).length === 0) 
                {
                    container.fadeOut();
                }
            });

            // Event: switch automation
            $('[name=switch_automation]').change(function() {
                var val = $(this).val();
                var text = $('[name=switch_automation] option:selected').text();
                var confirm = "{{ trans('messages.automation.switch_automation.confirm') }} <span class='font-weight-semibold'>" + text + "</span>"; 

                var dialog = new Dialog('confirm', {
                    message: confirm,
                    ok: function(dialog) {
                        window.location = val; 
                    },
                    cancel: function() {
                        $('[name=switch_automation]').val('');
                    },
                    close: function() {
                        $('[name=switch_automation]').val('');
                    },
                });
            });
            
            // quota view
            $('.quota-view').click(function(e) {
                e.preventDefault();

                var url = $(this).attr('href');

                automationPopup.load(url, function() {
                    console.log('quota popup loaded!');
                });
            });
        }

        // init main automation tree
        function initAutomationTree() {
            var tree;

            // INIT/SETUP AUTOMATION TREE
            @if ($automation->data)
                var json = {!! $automation->getData() !!};
            @else
                var json = [
                    {title: "Click to choose a trigger", id: "trigger", type: "ElementTrigger", options: {init: "false"}}
                ];
            @endif
            // init tree
            tree = AutomationElement.fromJson(json, document.getElementById('svg'), {
                onclick: function(e) {
                    doSelectTreeElement(e);
                },

                onhover: function(e) {
                    console.log(e.title + " hovered!");
                },

                onadd: function(e) {
                    e.select();

                    MyAutomation.addToTree = function(element) {
                        e.insert(element);
                        e.getTrigger().organize();

                        // select new element
                        // doSelectTreeElement(element);
                    };

                    openActionSelectPopup();
                },

                onaddyes: function(e) {
                    e.select();

                    MyAutomation.addToTree = function(element) {
                        e.insertYes(element);
                        e.getTrigger().organize();

                        // select new element
                        // doSelectTreeElement(element);
                    };

                    openActionSelectPopup('yes');
                },

                onaddno: function(e) {
                    e.select();

                    MyAutomation.addToTree = function(element) {
                        e.insertNo(element);
                        e.getTrigger().organize();

                        // select new element
                        // doSelectTreeElement(element);
                    };

                    openActionSelectPopup('no');
                },

                validate: function(e) {
                    if (e.getType() == 'ElementTrigger') {                        
                        if (e.getOptions()['init'] == null || !(e.getOptions()['init'] == "true" || e.getOptions()['init'] == true)) {
                            e.showNotice('{{ trans('messages.automation.trigger.is_not_setup') }}');
                            e.setTitle('{{ trans('messages.automation.trigger.is_not_setup.title') }}');
                        } else if (e.getOptions()["key"] == 'say-happy-birthday') {
                            if (!e.getOptions()["field"] || e.getOptions()["field"] == 'date_of_birth') {
                                e.showNotice('{{ trans('messages.automation.trigger.no_date_of_birth_field') }}');
                            } else {
                                // check if current field belongs to automation mail list
                                var cField = e.getOptions()["field"];
                                var fields = {!! json_encode($automation->mailList->getDateOrDateTimeFields()->get()->map(function($field) {
                                    return $field->uid;
                                })->toArray()) !!};

                                if (!fields.includes(cField)) {
                                    e.setOptions($.extend(e.getOptions(), {field: 'date_of_birth'}));
                                    e.showNotice('{{ trans('messages.automation.trigger.no_date_of_birth_field') }}');
                                } else {
                                    e.hideNotice();
                                }
                            }
                        } else {
                            e.hideNotice();
                            // e.setTitle('Correct title goes here');
                        }
                    }

                    if (e.getType() == 'ElementAction') {
                        if (e.getOptions()['init'] == null || !(e.getOptions()['init'] == "true" || e.getOptions()['init'] == true)) {
                            e.showNotice('{{ trans('messages.automation.email.is_not_setup') }}');
                            e.setTitle('{{ trans('messages.automation.email.is_not_setup.title') }}');
                        } else if (e.getOptions()['template'] == null || !(e.getOptions()['template'] == "true" || e.getOptions()['template'] == true)) {
                            e.showNotice('{{ trans('messages.automation.email.has_no_content') }}');
                        } else {
                            e.hideNotice();
                            // e.setTitle('Correct title goes here');
                        }
                    }

                    if (e.getType() == 'ElementCondition') {
                        if     (      e.getOptions()['type'] == null || 
                                 (e.getOptions()['type'] == 'click' && e.getOptions()['email_link'] == null ) ||
                                (e.getOptions()['type'] == 'open' && e.getOptions()['email'] == null ) || 
                                (e.getOptions()['type'] == 'cart_buy_item' && !e.getOptions()['item_id'] )
                            ) {
                            e.showNotice('Condition not set up yet');
                            e.setTitle('Condition not set up yet');
                        } else {
                            e.hideNotice();
                            // e.setTitle('Correct title goes here');
                        }
                    }
                }
            });

            @if (request()->auto_popup)
                doSelectTreeElement(tree.child);
                setTimeout(function() {
                    automationPopup.load(getEmailSetupUrl(tree.child.getOptions().email_uid),
                    function() {
                        $('.email_title').html('{{ trans('messages.source.abandoned_cart_email') }}');
                    });
                }, 100);

                automationPopup.onHide = function() {
                    if (parent && parent.$('.trans_frame')) {
                        parent.$('.trans_frame').fadeOut();
                    }
                    parent.hidden = true;

                    // parent
                    parent.$('html').css('overflow', 'auto');

                    doSelectTreeElement(tree.child);
                    setTimeout(function() {
                        automationPopup.load(getEmailSetupUrl(tree.child.getOptions().email_uid),
                        function() {
                            $('.email_title').html('{{ trans('messages.source.abandoned_cart_email') }}');
                        });
                    }, 100);

                    parent.jReload();
                };
            @endif

            return tree;
        }

        function toggleHistoryPanel() {
            var his = $('#HistoryListItems');

            if (his.is(":visible")) {
                his.fadeOut();
            } else {
                his.fadeIn();
            }
        }

        function openBuilder(url) {
            var div = $('<div class="full-iframe-popup">').html('<iframe scrolling="no" class="builder d-none" src="'+url+'"></iframe>');
            
            $('body').append(div);

            // open builder effects
            addMaskLoading("{{ trans('messages.automation.template.opening_builder') }}");
            $('.builder').on("load", function() {
                removeMaskLoading();

                $(this).removeClass("d-none");
            });
        }

        function openBuilderClassic(url) {
            var div = $('<div class="full-iframe-popup">').html('<iframe scrolling="yes" class="builder d-none" src="'+url+'"></iframe>');
            
            $('body').append(div);

            // open builder effects
            addMaskLoading("{{ trans('messages.automation.template.opening_builder') }}");
            $('.builder').on("load", function() {
                removeMaskLoading();

                $(this).removeClass("d-none");
            });
        }
        
        function saveData(callback, extra = {}) {
            if (!(extra instanceof Object)) {
                alert("A hash is required");
                return false;
            }

            if ('data' in extra) {
                alert("data key is not allowed");
                return false;
            }

            var url = '{{ action('Automation2Controller@saveData', $automation->uid) }}';
        
            var postContent = {
                _token: CSRF_TOKEN,
                data: JSON.stringify(tree.toJson()),
            }

            postContent = {...extra, ...postContent};

            $.ajax({
                url: url,
                type: 'POST',
                data: postContent
            }).always(function() {
                if (callback != null) {
                    callback();
                }

                // update last saved
                lastSaved.load();
            });
        }
        
        function setAutomationName(name) {
            $('.navbar h1').html(name);
        }

        function openSelectActionConfirmPopup(key) {
            var url = '{{ action('Automation2Controller@actionSelectConfirm', $automation->uid) }}' + '?key=' + key;
            
            automationPopup.load(url);
        }

        function openEmailSetupPopup(id) {
            var url = '{{ action('Automation2Controller@emailSetup', $automation->uid) }}' + '?action_id=' + id;
            
            automationPopup.load(url);
        }

        function openActionSelectPopup(conditionBranch = null) {
            var hasChildren = false;
            if (conditionBranch == null) {
                hasChildren = tree.getSelected().hasChildren();
            } else if (conditionBranch == 'yes') {
                hasChildren = tree.getSelected().hasChildYes();
            } else if (conditionBranch == 'no') {
                hasChildren = tree.getSelected().hasChildNo();
            }

            automationPopup.load('{{ action('Automation2Controller@actionSelectPupop', $automation->uid) }}?hasChildren=' + hasChildren);
        }
        
        function openTriggerSelectPopup() {
            automationPopup.load('{{ action('Automation2Controller@triggerSelectPupop', $automation->uid) }}', function() {
                console.log('Select trigger popup loaded!');
                
                // when click on trigger type
                automationPopup.popup.find('.trigger-select-but').click(function() {
                    var key = $(this).attr('data-key');
                    
                    // show select trigger confirm box
                    openSelectTriggerConfirmPopup(key);
                });
            });
        }
        
        function openSelectTriggerConfirmPopup(key) {
            var url = '{{ action('Automation2Controller@triggerSelectConfirm', $automation->uid) }}' + '?key=' + key;
            
            automationPopup.load(url, function() {
                console.log('Confirm trigger type popup loaded!');
            });
        }
        
        function loadSidebarEditTrigger(url) {
            sidebar.load(url);
        }
        
        function loadSidebarEditAction(url) {
            sidebar.load(url);
        }

        function getEmailSetupUrl(email_uid) {
            var url = `{{ action('Automation2Controller@emailSetup', [
                'uid' => $automation->uid,
                'email_uid' => '___email_uid___',
            ]) }}`;
             
            return url.replace('___email_uid___', email_uid);
        }

        function doSelectTreeElement(e) {
            // TODO 1:
            // Gọi Ajax to Automation2@action
            // Prams: e.getId()
            // Trả về thông tin chi tiết của action để load nội dung bên phải
            // Trên server: gọi hàm model: Automation2::getActionInfo(id)
            
            e.select(); // highlight
            
            // if click on a trigger
            if (e.getType() == 'ElementTrigger') {
                var options = e.getOptions();
                
                // check if trigger is not init
                if (options.init == "false") {
                    openTriggerSelectPopup();
                }
                // trigger was init
                else {
                    var url = '{{ action('Automation2Controller@triggerEdit', $automation->uid) }}' + '?key=' + e.getOptions().key + '&id=' + e.getId();
                    
                    // Open trigger types select list
                    loadSidebarEditTrigger(url);
                }
            }
            // is WAIT
            else if (e.getType() == 'ElementWait') {
                    var url = '{{ action('Automation2Controller@actionEdit', $automation->uid) }}' + '?key=' + e.getOptions().key + '&id=' + e.getId();
                    
                    // Open trigger types select list
                    loadSidebarEditAction(url);
            }
            // is Condition
            else if (e.getType() == 'ElementCondition') {
                    var url = '{{ action('Automation2Controller@actionEdit', $automation->uid) }}' + '?key=' + e.getOptions().key + '&id=' + e.getId();
                    
                    // Open trigger types select list
                    loadSidebarEditAction(url);
            }
            // is Email
            else if (e.getType() == 'ElementAction') {
                if (e.getOptions().init == "true") {
                    var type = $(this).attr('data-type');
                    var url = '{{ action('Automation2Controller@email', $automation->uid) }}?email_uid=' + e.getOptions().email_uid;
                    
                    // Open trigger types select list
                    loadSidebarEditAction(url);
                } else {
                    var type = $(this).attr('data-type');
                    var url = '{{ action('Automation2Controller@email', $automation->uid) }}?email_uid=' + e.getOptions().email_uid;
                    
                    // Open trigger types select list
                    loadSidebarEditAction(url);
                    
                    // show select trigger confirm box
                    openEmailSetupPopup(e.getId());
                }
            }
            // is Operation
            else if (e.getType() == 'ElementOperation') {
                var type = $(this).attr('data-type');
                var url = '{{ action('Automation2Controller@operationShow', $automation->uid) }}?operation=' + e.getOptions().operation_type + '&id=' + e.getId();
                
                // Open trigger types select list
                sidebar.load(url);
            }
            // ElementWebhook
            else if (e.getType() == 'ElementWebhook') {
                var type = $(this).attr('data-type');
                var url = '{{ action('Automation2Controller@outgoingWebhookShow', $automation->uid) }}?operation=' + e.getOptions().operation_type + '&id=' + e.getId();
                
                // Open trigger types select list
                sidebar.load(url);
            }
        }
        
        // Document ready initialization
        (function() {
            // Reload last saved information
            lastSaved.load();

            // Load init sidebar default page (Automation Seetings Page)
            sidebar.load('{{ action('Automation2Controller@settings', $automation->uid) }}');

            // events
            initEvents();

            // automation tree init
            tree = initAutomationTree();
        })();
    </script>
@endsection
