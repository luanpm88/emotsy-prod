<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class IpLocation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_code', 'country_name', 'region_code',
        'region_name', 'city', 'zipcode',
        'latitude', 'longitude', 'metro_code', 'areacode'
    ];
    
    /**
     * Add new IP.
     *
     * return Location
     */
    public static function add($ip)
    {
        $location = IpLocation::where("ip_address", "=", $ip)->first();
        if (!is_object($location)) {
            $location = new IpLocation();
        }
        $location->ip_address = $ip;
        
        // Get info
        try {
            $result = file_get_contents('http://freegeoip.net/json/' . $ip);
            $values = json_decode($result, true);
            
            $location->fill($values);
            
        } catch (\Exception $e) {
            return false;
        }
        
        $location->save();
        
        return $location;
    }
    
    /**
     * Location name.
     *
     * return Location
     */
    public function name()
    {
        $str = [];
        if (!empty($this->city)) {
            $str[] = $this->city;
        }
        if (!empty($this->region_name)) {
            $str[] = $this->region_name;
        }
        if (!empty($this->country_name)) {
            $str[] = $this->country_name;
        }
        
        return implode(", ", $str);
    }
}
