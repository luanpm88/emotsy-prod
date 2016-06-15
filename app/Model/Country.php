<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * Get all languages.
     *
     * @return collect
     */
    public static function getAll()
    {
        return Country::all();
    }
    
    /**
     * Get select options.
     *
     * @return array
     */
    public static function getSelectOptions()
    {
        $options = Country::getAll()->map(function ($item) {
            return ['value' => $item->id, 'text' => $item->name];
        });
        return $options;
    }
}
