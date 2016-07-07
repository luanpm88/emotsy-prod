<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'backend_access', 'frontend_access', 'options'
    ];
    
    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return UserGroup::select("*");
    }
    
    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;
    
    /**
     * The rules for validation.
     *
     * @var array
     */
    public static $rules = array(
        'name' => 'required',
    );
    
    /**
     * Rules.
     *
     * @return array
     */
    public static function rules()
    {
        $rules = [
            'name' => 'required',
        ];
        
        $options = UserGroup::defaultOptions();
        foreach($options as $type => $option) {
            foreach($option as $name => $value) {
                $rules["options." . $type . "." . $name] = 'required';
            }
        }
        
        return $rules;
    }
    
    /**
     * Associations.
     *
     * @var object | collect
     */ 
    public function users()
    {
        return $this->hasMany('Mailixa\Model\User');
    }
    
    public function user_group_sending_servers()
    {
        return $this->hasMany('Mailixa\Model\UserGroupSendingServer');
    }
    
    public function sending_servers()
    {
        return $this->belongsToMany('Mailixa\Model\SendingServer', 'user_group_sending_servers');
    }
    
    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = UserGroup::select("user_groups.*");
        
        if ($request->user()->getOption("backend", "user_group_read") == 'own') {
            $query = $query->where("user_id", "=", $request->user()->id);
        }
        
        // Keyword
        if(!empty(trim($request->keyword))) {
            $query = $query->where('name', 'like', '%' . $request->keyword . '%');
        }
        
        return $query;
    }
    
    /**
     * Search items.
     *
     * @return collect
     */
    public static function search($request)
    {
        $query = UserGroup::filter($request);
        
        $query = $query->orderBy($request->sort_order, $request->sort_direction);
        
        return $query;
    }
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();        
        
        // Create uid when creating list.
        static::creating(function($item)
        {
            // Update custom order
            UserGroup::getAll()->increment('custom_order', 1);
            $item->custom_order = 0;
        });
    }
    
    /**
     * Get select options.
     *
     * @return array
     */
    public static function getSelectOptions()
    {
        $options = UserGroup::getAll()->get()->map(function ($item) {
            return ['value' => $item->id, 'text' => $item->name];
        });
        return $options;
    }
    
    /**
     * Default options for new groups.
     *
     * @return array
     */
    public static function defaultOptions()
    {
        return [
            "frontend" => [
                'list_max' => '-1',
                'subscriber_max' => '-1',
                'subscriber_per_list_max' => '-1',
                'segment_per_list_max' => '3',
                'campaign_max' => '-1',
                'campaign_subscribers_at_once' => '100',
                'campaign_send_at_once' => '50',
                'campaign_pause' => '10',
                'campaign_emails_per_minute' => '100',
                'campaign_change_server_time' => '200',
                'sending_quota' => '-1',
                'sending_quota_time' => '-1',
                'sending_quota_time_unit' => 'month',
            ],
            "backend" => [
                'user_group_read' => 'all',
                'user_group_create' => 'yes',
                'user_group_update' => 'all',
                'user_group_delete' => 'own',
                'user_read' => 'own',
                'user_create' => 'yes',
                'user_update' => 'own',
                'user_delete' => 'own',
                'sending_server_read' => 'all',
                'sending_server_create' => 'yes',
                'sending_server_update' => 'own',
                'sending_server_delete' => 'own',
                'bounce_handler_read' => 'own',
                'bounce_handler_create' => 'yes',
                'bounce_handler_update' => 'own',
                'bounce_handler_delete' => 'own',
                'fbl_handler_read' => 'own',
                'fbl_handler_create' => 'yes',
                'fbl_handler_update' => 'own',
                'fbl_handler_delete' => 'own',
                'sending_domain_read' => 'own',
                'sending_domain_create' => 'no',
                'sending_domain_update' => 'own',
                'sending_domain_delete' => 'own',
                'template_read' => 'own',
                'template_create' => 'yes',
                'template_update' => 'own',
                'template_delete' => 'own',
                'layout_read' => 'yes',
                'layout_update' => 'yes',
                'setting_general' => 'yes',
                'setting_sending' => 'yes',
                'setting_system_urls' => 'yes',
                'language_read' => 'yes',
                'language_create' => 'yes',
                'language_update' => 'yes',
                'language_delete' => 'yes',
                'report_blacklist' => 'yes',
                'report_tracking_log' => 'yes',
                'report_bounce_log' => 'yes',
                'report_feedback_log' => 'yes',
                'report_click_log' => 'yes',
                'report_open_log' => 'yes',
            ]
        ];
    }
    
    /**
     * Backend roles.
     *
     * @return array
     */
    public static function backendPermissions()
    {
        return [
            "user_group" => [
                "read" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                "create" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "update" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                "delete" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            "user" => [
                "read" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                "create" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "update" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                "delete" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            "sending_server" => [
                "read" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                "create" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "update" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                "delete" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            "bounce_handler" => [
                "read" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                "create" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "update" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                "delete" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            "fbl_handler" => [
                "read" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                "create" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "update" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                "delete" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            "sending_domain" => [
                "read" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                "create" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "update" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                "delete" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            "template" => [
                "read" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                "create" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "update" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                "delete" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
						['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            "layout" => [
                "read" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "update" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
            ],
            "setting" => [
                "general" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "sending" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "system_urls" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
            ],
            "language" => [
                "read" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "create" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "update" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "delete" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
            ],
            "report" => [
                "blacklist" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "tracking_log" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "bounce_log" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "feedback_log" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "open_log" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                "click_log" => [
                    "options" => [
                        ['value' => 'no', 'text' => trans('messages.no')],
						['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
            ],
        ];
    }
    
    /**
     * Get options.
     *
     * @return array
     */
    public function getOptions()
    {
        if (empty($this->options)) {
            return UserGroup::defaultOptions();
        } else {
            return json_decode($this->options, true);
        }
    }
    
    /**
     * Get option.
     *
     * @return string
     */
    public function getOption($cat, $name)
    {
        if (empty($this->options)) {
            return UserGroup::defaultOptions()[$cat][$name];
        } else {
            return json_decode($this->options, true)[$cat][$name];
        }
    }
    
    /**
     * Save options.
     *
     * @return array
     */
    public function saveOptions($options)
    {
        return true;
    }
    
    /**
     * Quota time unit options.
     *
     * @return array
     */
    public static function timeUnitOptions()
    {
        return [
            ['value' => 'minute', 'text' => 'Minute'],
            ['value' => 'hour', 'text' => 'Hour'],
            ['value' => 'day', 'text' => 'Day'],
            ['value' => 'week', 'text' => 'Week'],
            ['value' => 'month', 'text' => 'Month'],
            ['value' => 'year', 'text' => 'Month']
        ];
    }
    
    /**
     * Get sending servers ids.
     *
     * @return array
     */
    public function getSendingServerIds()
    {
        $arr = [];
        foreach($this->sending_servers as $server) {
            $arr[] = $server->uid;
        }

        return implode(",", $arr);
    }
    
    /**
     * Update sending servers.
     *
     * @return array
     */
    public function updateSendingServers($ids)
    {
        $this->user_group_sending_servers()->delete();
        foreach($ids as $id) {
            $server = SendingServer::findByUid($id);
            $row = new UserGroupSendingServer();
            $row->user_group_id = $this->id;
            $row->sending_server_id = $server->id;
            $row->save();
        }
    }
}
