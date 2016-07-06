<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'type', 'name', 'data'
    ];
    
    /**
     * Associations.
     *
     * @var object | collect
     */        
    public function user()
    {
        return $this->belongsTo('Mailixa\Model\User');
    }
    
    /**
     * Display log content.
     *
     * @var string
     */    
    public function message()
    {
        $data = (array) json_decode($this->data);
        
        // Campaign
        if ($this->type == 'campaign') {            
            if(isset($data["list_id"])) {
                $list = MailList::find($data["list_id"]);
                $list_link = is_object($list) ? action('MailListController@show', $list->uid) : "#deleted";
                $data["list_link"] = $list_link;
            }
            
            $item = Campaign::find($data["id"]);
            $link = is_object($item) ? action('CampaignController@overview', $item->uid) : "#deleted";
            
            $data["link"] = $link;
        }
        // Subscriber
        elseif ($this->type == 'subscriber') {
            $list = MailList::find($data["list_id"]);
            $list_link = is_object($list) ? action('MailListController@overview', $list->uid) : "#deleted";
            $data["list_link"] = $list_link;
            
            $item = Subscriber::find($data["id"]);
            $link = is_object($item) ? action('SubscriberController@edit', ['uid' => $item->uid, 'list_uid' => $list->uid]) : "#deleted";
            $data["link"] = $link;
        }
        // Segment
        elseif ($this->type == 'segment') {
            $list = MailList::find($data["list_id"]);
            $list_link = is_object($list) ? action('MailListController@overview', $list->uid) : "#deleted";
            $data["list_link"] = $list_link;
            
            $item = Segment::find($data["id"]);
            $link = is_object($item) ? action('SegmentController@edit', ['uid' => $item->uid, 'list_uid' => $list->uid]) : "#deleted";
            $data["link"] = $link;
        }
        // Page
        elseif ($this->type == 'page') {
            $list = MailList::find($data["list_id"]);
            $list_link = is_object($list) ? action('MailListController@overview', $list->uid) : "#deleted";
            $data["list_link"] = $list_link;
            
            $item = Page::find($data["id"]);
            $link = is_object($item) ? action('PageController@update', ['list_uid' => $list->uid, 'alias' => $data["alias"]]) : "#deleted";
            $data["link"] = $link;
            $data["name"] = trans('messages.' . $data["alias"]);
        }
        // List
        elseif ($this->type == 'list') {
            $item = MailList::find($data["id"]);
            $link = is_object($item) ? action('MailListController@overview', $item->uid) : "#deleted";
            $data["link"] = $link;
        }
        
        $message = trans('messages.log.' . $this->type . "." . $this->name, $data);
        return $message;
    }
}
