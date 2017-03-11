<?php

namespace Acelle\Helpers;

class LicenseHelper
{
    // license type
    const TYPE_NORMAL = 'normal';
    const TYPE_EXTENDED = 'extended';
    
    /**
     * Get license type: normal / extended.
     *
     * @var string
     */
    public static function getLicenseType($license)
    {
        return LicenseHelper::isValid($license) ? ($license == 'bbb' ? LicenseHelper::TYPE_EXTENDED : LicenseHelper::TYPE_NORMAL) : '';
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
        return $license == 'aaa' || $license == 'bbb';
    }
    
    /**
     * Check when show not extended popup.
     *
     * @var boolean
     */
    public static function showNotExtendedLicensePopup()
    {
        return \Acelle\Model\Setting::get('license_type') != LicenseHelper::TYPE_EXTENDED;
    }
}