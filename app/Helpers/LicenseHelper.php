<?php

namespace Acelle\Helpers;

class LicenseHelper
{   
    /**
    * Check is valid extend license
    *
    * @return boolean
    */
    public static function isExtended($license)
    {
        return LicenseHelper::isValid($license) && $license == 'bbb';
    }
    
    /**
    * Check license is valid
    *
    * @return boolean
    */
    public static function isValid($license)
    {
        return $license == 'aaa' || $license == 'bbb';
    }
    
    /**
     * Check when show not extended popup.
     *
     * @var boolean
     */
    public static function showNotExtendedLicensePopup()
    {
        return (\Request::is('*subscriptions*') && !LicenseHelper::isExtended(\Acelle\Model\Setting::get('license')));
    }
}