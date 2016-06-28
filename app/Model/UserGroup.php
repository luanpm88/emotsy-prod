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
        'name'
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
     * Associations.
     *
     * @var object | collect
     */ 
    public function users()
    {
        return $this->hasMany('Mailixa\Model\User');
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
}
