<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class FeedbackLoopHandler extends Model
{
    public function processMessage($mbox, $message) {
        $header = imap_headerinfo($mbox, $message);
                
        $output = $header->subject." : ";
        $output .= $header->toaddress." : ";
        $output .= $header->Msgno." : ";
        $headers = imap_fetchheader($mbox, $header->Msgno);
        preg_match('/Received\s*:\s*[^\n]*/im', $headers, $matched);
        
        if (!empty($matched)) {
            $type = preg_replace('/Received\s*:\s*/im', '', $matched[0]);
            echo "\n".$type."\n";
        } else {
            // not an ARF email
        }
        
        echo "\n...............................\n";
    }
    
    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return FeedbackLoopHandler::select("*");
    }
    
    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = FeedbackLoopHandler::where("user_id", "=", $user->id);
        
        // Keyword
        if(!empty(trim($request->keyword))) {
            foreach(explode(" ",trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('feedback_loop_handlers.name', 'like', '%' . $keyword . '%')
                        ->orWhere('feedback_loop_handlers.type', 'like', '%' . $keyword . '%')
                        ->orWhere('feedback_loop_handlers.host', 'like', '%' . $keyword . '%');
                });
            }                
        }
        
        // filters
        $filters = $request->filters;
        if(!empty($filters)) {
            if(!empty($filters["type"])) {
                $query = $query->where("feedback_loop_handlers.type", "=", $filters["type"]);     
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
        $query = FeedbackLoopHandler::filter($request);
        
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
        return FeedbackLoopHandler::where("uid", "=", $uid)->first();
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
            while(FeedbackLoopHandler::where('uid','=',$uid)->count() > 0)
            {
                $uid = uniqid();
            }
            $item->uid = $uid;
            
            // FeedbackLoopHandler custom order
            FeedbackLoopHandler::getAll()->increment('custom_order', 1);
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
}
