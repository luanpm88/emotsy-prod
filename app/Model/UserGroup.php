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
        $query = UserGroup::select("*");
        
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
                'segment_conditions_max' => '3',
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
                'user_group_create' => 'yes',
                'user_group_update' => 'own',
                'user_group_delete' => 'own',
                'user_create' => 'yes',
                'user_update' => 'own',
                'user_delete' => 'own',
            ]
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
        var_dump($this->sending_servers()->count());
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
