<?php

namespace Acelle\Helpers;

class LicenseHelper
{
    // license type
    const TYPE_REGULAR = 'regular';
    const TYPE_EXTENDED = 'extended';
    
    /**
     * Get license type: normal / extended.
     *
     * @var string
     */
    public static function getLicense($license)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"http://verify.acellemail.com/");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query(array(
                'purchase-code' => $license,
                'item-id' => '17796082'
            )
        ));
        curl_setopt($ch,CURLOPT_USERAGENT,md5($license));
        
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $server_output = curl_exec($ch);
        
        return json_decode($server_output, true);
    }
    
    /**
     * Get license type: normal / extended.
     *
     * @var string
     */
    public static function getLicenseType($license)
    {
        $result = self::getLicense($license);
        
        # return '' if not valid
        if ($result['status'] != 'valid') {
            return '';
        }
        
        return $result['data']['verify-purchase']['licence'] == 'Regular License' ? self::TYPE_REGULAR : self::TYPE_EXTENDED;
    }
    
    /**
    * Check is valid extend license
    *
    * @return boolean
    */
    public static function isExtended($license)
    {
        return LicenseHelper::isValid($license) && LicenseHelper::getLicenseType($license) == LicenseHelper::TYPE_EXTENDED;
    }
    
    /**
    * Check license is valid
    *
    * @return boolean
    */
    public static function isValid($license)
    {
        $result = self::getLicense($license);
        
        return $result['status'] == 'valid';
    }
    
    /**
     * Check when show not extended popup.
     *
     * @var boolean
     */
    public static function showNotExtendedLicensePopup()
    {
        return (\Request::is('*subscriptions*') || \Request::is('*payment_methods*') || \Request::is('*plans*') || \Request::is('*currencies*'))
            && \Acelle\Model\Setting::get('license_type') != LicenseHelper::TYPE_EXTENDED;
    }
}