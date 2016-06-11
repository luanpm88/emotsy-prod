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
}
