<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    /**
     * Get all languages.
     *
     * @return collect
     */
    public static function getAll()
    {
        return Language::all();
    }
    
    /**
     * Get language by code.
     *
     * @param string $code
     * @return collect
     */
    public static function getByCode($code)
    {
        return Language::where("code", "=", $code)->first();
    }
    
    /**
     * Get select options.
     *
     * @return array
     */
    public static function getSelectOptions()
    {
        $options = Language::getAll()->map(function ($item) {
            return ['value' => $item->id, 'text' => $item->name];
        });
        return $options;
    }
    
    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = Language::select("languages.*");
        
        // Keyword
        if(!empty(trim($request->keyword))) {
            foreach(explode(" ",trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('languages.name', 'like', '%' . $keyword . '%')
                        ->orwhere('languages.code', 'like', '%' . $keyword . '%')
                        ->orwhere('languages.region_code', 'like', '%' . $keyword . '%');
                });
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
        $query = Language::filter($request);
        
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
        return Language::where("uid", "=", $uid)->first();
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
            while(Language::where('uid','=',$uid)->count() > 0)
            {
                $uid = uniqid();
            }
            $item->uid = $uid;
        });
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'region_code'
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
            'code' => 'required',
        ];
    }
}
