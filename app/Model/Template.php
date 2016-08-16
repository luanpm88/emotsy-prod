<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'content',
    ];

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating item.
        static::creating(function ($item) {
            // Create new uid
            $uid = uniqid();
            while (Template::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;

            // Update custom order
            Template::getAll()->increment('custom_order', 1);
            $item->custom_order = 0;
        });

        static::deleted(function ($item) {
            $uploaded_dir = 'upload/templates/template_'.$item->uid;
            \Mailixa\Library\Tool::xdelete($uploaded_dir);
        });
    }

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
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return self::select('*');
    }

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = self::select('templates.*');

        // Keyword
        if (!empty(trim($request->keyword))) {
            $query = $query->where('name', 'like', '%'.$request->keyword.'%');
        }

        // filters
        $filters = $request->filters;
        if (!empty($filters)) {
            if (!empty($filters['from'])) {
                if ($filters['from'] == 'mine') {
                    $query = $query->where('user_id', '=', $user->id);
                } elseif ($filters['from'] == 'gallery') {
                    $query = $query->where('shared', '=', true)->where('user_id', '!=', $user->id);
                } else {
                    $query = $query->where('shared', '=', true)->orWhere('user_id', '=', $user->id);
                }
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
        $query = self::filter($request);

        $query = $query->orderBy($request->sort_order, $request->sort_direction);

        return $query;
    }

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function adminFilter($request)
    {
        $user = $request->user();
        $query = self::where('shared', '=', true);

        // Keyword
        if (!empty(trim($request->keyword))) {
            $query = $query->where('name', 'like', '%'.$request->keyword.'%');
        }

        return $query;
    }

    /**
     * Search items.
     *
     * @return collect
     */
    public static function adminSearch($request)
    {
        $query = self::adminFilter($request);

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
        return self::where('uid', '=', $uid)->first();
    }
}
