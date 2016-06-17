<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content'
    ];
    
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
            while(Page::where('uid','=',$uid)->count() > 0)
            {
                $uid = uniqid();
            }
            $item->uid = $uid;
        });
    }
    
    /**
     * Find a page belong to list with layout.
     *
     * @param List  $list
     * @param string  $layout
     * @var Page
     */
    public static function findPage($list, $layout)
    {
        $page = $list->page($layout);
        if (!isset($page)) {
            $page = new \Mailixa\Model\Page();
            $page->layout_id = $layout->id;
            $page->mail_list_id = $list->id;
            $page->content = $layout->content;
        }
        return $page;
    }
}
