<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class ResellerGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'options', 'permissions'
    ];

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
     * Rules.
     *
     * @return array
     */
    public static function rules()
    {
        $rules = [
            'name' => 'required',
        ];

        $options = self::defaultOptions();
        foreach ($options as $type => $option) {
            $rules['options.' . $type] = 'required';
        }
        
        $permissions = self::defaultPermissions();
        foreach ($permissions as $type => $value) {
            $rules['permissions.' . $type] = 'required';
        }

        return $rules;
    }

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function resellers()
    {
      return $this->hasMany('Acelle\Model\Reseller');
    }

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = self::select('reseller_groups.*');

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
    public static function search($request)
    {
        $query = self::filter($request);

        $query = $query->orderBy($request->sort_order, $request->sort_direction);

        return $query;
    }

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating list.
        static::creating(function ($item) {
            // Update custom order
            ResellerGroup::getAll()->increment('custom_order', 1);
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
        $options = self::getAll()->get()->map(function ($item) {
            return ['value' => $item->id, 'text' => $item->name];
        });

        return $options;
    }

    /**
     * Default options for new groups.
     *
     * @return array
     */
    public static function defaultOptions()
    {
        return [
            
        ];
    }
    
    /**
     * Default permissions for new groups.
     *
     * @return array
     */
    public static function defaultPermissions()
    {
        return [
            'customer_group_read' => 'all',
            'customer_group_create' => 'yes',
            'customer_group_update' => 'all',
            'customer_group_delete' => 'own',
            'customer_read' => 'own',
            'customer_create' => 'yes',
            'customer_update' => 'own',
            'customer_delete' => 'own',
            'customer_login_as' => 'own',
        ];
    }
    
    /**
     * Backend roles.
     *
     * @return array
     */
    public static function allPermissions()
    {
        return [
            'customer_group' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'create' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'delete' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
            'customer' => [
                'read' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'create' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'yes', 'text' => trans('messages.yes')],
                    ],
                ],
                'update' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'delete' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
                'login_as' => [
                    'options' => [
                        ['value' => 'no', 'text' => trans('messages.no')],
                        ['value' => 'own', 'text' => trans('messages.own')],
                        ['value' => 'all', 'text' => trans('messages.all')],
                    ],
                ],
            ],
        ];
    }
    
    /**
     * Get options.
     *
     * @return array
     */
    public function getOptions()
    {
        if (empty($this->options)) {
            return self::defaultOptions();
        } else {
            $defaul_options = self::defaultOptions();
            $saved_options = json_decode($this->options, true);
            foreach($defaul_options as $x => $group) {
                if(isset($saved_options[$x])) {
                    $defaul_options[$x] = $saved_options[$x];
                }
            }
            return $defaul_options;
        }
    }
    
    /**
     * Get options.
     *
     * @return array
     */
    public function getPermissions()
    {
        if (empty($this->permissions)) {
            return self::defaultPermissions();
        } else {
            $defaul_permissions = self::defaultPermissions();
            $saved_permissions = json_decode($this->permissions, true);
            foreach($defaul_permissions as $x => $permission) {
                if(isset($saved_permissions[$x])) {
                    $saved_permissions[$x] = $saved_permissions[$x];
                }
            }
            return $saved_permissions;
        }
    }

    /**
     * Get option.
     *
     * @return string
     */
    public function getOption($name)
    {
        return $this->getOptions()[$name];
    }
    
    /**
     * Get permissions.
     *
     * @return string
     */
    public function getPermission($name)
    {
        return $this->getPermissions()[$name];
    }

    /**
     * Save options.
     *
     * @return array
     */
    public function saveOptions($options)
    {
        return true;
    }
}
