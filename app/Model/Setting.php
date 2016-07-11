<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        $settings = Setting::select("*")->get();
        $result = Setting::defaultSettings();
        
        foreach($settings as $setting) {
            $result[$setting->name]['value'] = $setting->value;
        }
        
        return $result;
    }
    
    /**
     * Get setting.
     *
     * @return object
     */
    public static function get($name)
    {
        $setting = Setting::where("name", $name)->first();
        if(is_object($setting)) {
            return $setting->value;
        } else {
            return Setting::defaultSettings()[$name]["value"];
        }
    }
    
    /**
     * Set setting value.
     *
     * @return object
     */
    public static function set($name, $val)
    {
        $option = Setting::where("name", $name)->first();

        if (is_object($option)) {
            $option->value = $val;
        } else {
            $option = new Setting();
            $option->name = $name;
            $option->value = $val;
        }
        $option->save();

        return $option;
    }
    
    /**
     * Get setting rules.
     *
     * @return object
     */
    public static function rules()
    {
        $rules = [];
        $settings = Setting::getAll();
        
        foreach($settings as $name => $setting) {
            if (!isset($setting['not_required'])) {
                $rules[$name] = 'required';
            }            
        }
        
        return $rules;
    }
    
    /**
     * Default setting.
     *
     * @return object
     */
    public static function defaultSettings()
    {
        return [
            'site_name' => [
                'cat' => 'general',
                'value' => 'Mailixa',
                'type' => 'text',
            ],
            'site_keyword' => [
                'cat' => 'general',
                'value' => 'Email Marketing, Campaigns, Lists',
                'type' => 'text',
            ],            
            'site_online' => [
                'cat' => 'general',
                'value' => true,
                'type' => 'checkbox',
                'options' => [
                    'false', 'true'
                ],
            ], 
            'site_offline_message' => [
                'cat' => 'general',
                'value' => 'Mailixa application currently offline. We will come back soon!',
                'type' => 'textarea',
            ],
            'site_description' => [
                'cat' => 'general',
                'value' => 'Mailixa makes it easy for you to create, send, and optimize your email marketing campaigns.',
                'type' => 'textarea',
            ],
            'sending_campaigns_at_once' => [
                'cat' => 'sending',
                'value' => "10",
                'type' => 'text',
                'class' => 'numeric',
            ],
            'sending_change_server_time' => [
                'cat' => 'sending',
                'value' => "300",
                'type' => 'text',
                'class' => 'numeric',
            ],
            'sending_emails_per_minute' => [
                'cat' => 'sending',
                'value' => "150",
                'type' => 'text',
                'class' => 'numeric',
            ],
            'sending_pause' => [
                'cat' => 'sending',
                'value' => "10",
                'type' => 'text',
                'class' => 'numeric',
            ],
            'sending_at_once' => [
                'cat' => 'sending',
                'value' => "50",
                'type' => 'text',
                'class' => 'numeric',
            ],
            'sending_subscribers_at_once' => [
                'cat' => 'sending',
                'value' => "100",
                'type' => 'text',
                'class' => 'numeric',
            ],
            'url_unsubscribe' => [
                'cat' => 'url',
                'value' => action('CampaignController@unsubscribe', ["message_id" => trans("messages.MESSAGE_ID")]),
                'type' => 'text',
                'not_required' => true,
            ],            
            'url_open_track' => [
                'cat' => 'url',
                'value' => action('CampaignController@open', ["message_id" => trans("messages.MESSAGE_ID")]),
                'type' => 'text',
                'not_required' => true,
            ],
            'url_click_track' => [
                'cat' => 'url',
                'value' => action('CampaignController@click', ["message_id" => trans("messages.MESSAGE_ID"), "url" => trans("messages.URL")]),
                'type' => 'text',
                'not_required' => true,
            ],
            'url_campaign_notification' => [
                'cat' => 'url',
                'value' => action('HomeController@index'),
                'type' => 'text',
                'not_required' => true,
            ],
        ];
    }
}
