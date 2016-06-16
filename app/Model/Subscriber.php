<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];
    
    /**
     * The rules for validation.
     *
     * @var array
     */
    public static $fields_rules = array(
        
    );
    
    /**
     * Associations.
     *
     * @var object | collect
     */ 
    public function mailList()
    {
        return $this->belongsTo('Mailixa\Model\MailList');
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
            // Create new uid
            $uid = uniqid();
            while(Subscriber::where('uid','=',$uid)->count() > 0)
            {
                $uid = uniqid();
            }
            $item->uid = $uid;
        });
    }
    
    /**
     * Get rules.
     *
     * @var array
     */ 
    public function getRules()
    {
        $rules = $this->mailList->getFieldRules();
        $item_id = isset($this->id) ? $this->id : "NULL";
        $rules['EMAIL'] = $rules['EMAIL'] . "|unique:subscribers,email," . $item_id . ",id,mail_list_id,".$this->mailList->id;
        
        return $rules;
    }
    
    /**
     * Update fields from request
     * 
     * @return void
     */
    public function updateFields($params)
    {
        foreach($params as $tag => $value) {
            $field = $this->mailList->getFieldByTag(str_replace("[]","",$tag));
            if (is_object($field)) {
                $fv = FieldValue::where("subscriber_id", "=", $this->id)->where("field_id", "=", $field->id)->first();
                if(!is_object($fv)) {
                    $fv = new FieldValue();
                    $fv->subscriber_id = $this->id;
                    $fv->field_id = $field->id;
                }
                if(is_array($value)) {
                    $fv->value = implode(",", $value);
                } else {
                    $fv->value = $value;
                }
                $fv->save();
            }
        }
    }
    
    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = Subscriber::select("subscribers.*");
        $query = $query->where("user_id", "=", $user->id);
        $query = $query->rightJoin('field_values', 'subscribers.id', '=', 'field_values.subscriber_id');
        
        // Keyword
        if(!empty(trim($request->keyword))) {
            foreach(explode(" ",trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('subscribers.email', 'like', '%' . $keyword . '%')
                        ->orWhere('field_values.value', 'like', '%' . $keyword . '%');
                });
            }                
        }
        
        return $query->distinct();
    }
    
    /**
     * Get all languages.
     *
     * @return collect
     */
    public static function search($request)
    {
        $query = Subscriber::filter($request);
        
        $query = $query->orderBy("subscribers.".$request->sort_order, $request->sort_direction);
        
        return $query;
    }
    
    /**
     * Get field value by list field.
     *
     * @return value
     */
    public function getValueByField($field) {
        $fv = FieldValue::where("subscriber_id", "=", $this->id)->where("field_id", "=", $field->id)->first();
        if(is_object($fv)) {
            return $fv->value;
        } else {
            return "";
        }
    }
    
    /**
     * Find item by uid.
     *
     * @return object
     */
    public static function findByUid($uid)
    {
        return Subscriber::where("uid", "=", $uid)->first();
    }
}
