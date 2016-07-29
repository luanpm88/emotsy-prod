<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class BounceHandler extends Model
{
    public function processMessage($mbox, $message) {
       $header = imap_headerinfo($mbox, $message);
                
       $output = $header->subject." : ";
       $output .= $header->toaddress." : ";
       $output .= $header->Msgno." : ";
       echo $output . "\n...............................\n";

       imap_delete($mbox, $header->Msgno);
    }

    public function getBouncedAddress($to) {
       preg_match('/(?<=\+)[^\+]+=[^@]+(?=@)/', $to, $matched);
       if (sizeof($matched) == 0) {
           return;
       } else {
           return $matched[0];
       }
    }
    
    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return BounceHandler::select("*");
    }
    
    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = BounceHandler::select("bounce_handlers.*");
        
        if ($request->user()->getOption("backend", "bounce_handler_read") == 'own') {
            $query = $query->where("bounce_handlers.user_id", "=", $request->user()->id);
        }
        
        // Keyword
        if(!empty(trim($request->keyword))) {
            foreach(explode(" ",trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('bounce_handlers.name', 'like', '%' . $keyword . '%')
                        ->orWhere('bounce_handlers.type', 'like', '%' . $keyword . '%')
                        ->orWhere('bounce_handlers.host', 'like', '%' . $keyword . '%');
                });
            }                
        }
        
        // filters
        $filters = $request->filters;
        if(!empty($filters)) {
            if(!empty($filters["type"])) {
                $query = $query->where("bounce_handlers.type", "=", $filters["type"]);     
            }
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
        $query = BounceHandler::filter($request);
        
        $query = $query->orderBy($request->sort_order, $request->sort_direction);
        
        return $query;
    }
    
    /**
     * Find item by uid.
     *
     * @return object
     */
    public static function findByUid($uid)
    {
        return BounceHandler::where("uid", "=", $uid)->first();
    }
    
    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;
    
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
            // Create new uid
            $uid = uniqid();
            while(BounceHandler::where('uid','=',$uid)->count() > 0)
            {
                $uid = uniqid();
            }
            $item->uid = $uid;
            
            // BounceHandler custom order
            BounceHandler::getAll()->increment('custom_order', 1);
            $item->custom_order = 0;
        });
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'host', 'port', 'username', 'password', 'protocol'
    ];
    
    /**
     * Get validation rules.
     *
     * @return object
     */
    public static function rules()
    {
        return [
            'name' => 'required',
            'host' => 'required',
            'port' => 'required',
            'username' => 'required',
            'password' => 'required',
            'protocol' => 'required',
        ];
    }
    
    /**
     * Get select options.
     *
     * @return array
     */
    public static function getSelectOptions()
    {
        $query = BounceHandler::getAll();
        
        $options = $query->orderBy("name", "asc")->get()->map(function ($item) {
            return ['value' => $item->id, 'text' => $item->name];
        });
        return $options;
    }
}
