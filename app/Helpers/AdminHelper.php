<?php
namespace App\Helpers;

use Auth;
use DB;

/**
 * Class AdminHelper
 * namespace App\Helpers
 * @package Auth
 * @package DB
 */
class AdminHelper
{    
    /**
     * This function is used to check user is super admin
     *
     * @return bool
     * @author vinothcl
     */
    public static function checkUserIsSuperAdmin() {
        if(!Auth::check()) return false;
        if(Auth::user()->user_role == 1)
            return true;
        else
            return false;
    }
    /**
     * This function is used to check user is chapter admin
     *
     * @return bool
     * @author vinothcl
     */
    public static function checkUserIsChapterAdmin() {
        if(!Auth::check()) return false;
        if(Auth::user()->user_role == 1 || Auth::user()->user_role == 2)
            return true;
        else
            return false;
    }
    /**
     * This function is used to check user is chapter admin
     *
     * @return bool
     * @author vinothcl
     */
    public static function checkUserIsOnlyChapterAdmin() {
        if(!Auth::check()) return false;
        if(Auth::user()->user_role == 2)
            return true;
        else
            return false;
    }
    /**
     * This function is used to check user is super admin
     *
     * @return bool
     * @author sivaramesh
     */
    public static function checkUserIsOnlyUserAdmin() {
        if(!Auth::check()) return false;
        if(Auth::user()->user_role == 3)  
            return true;
        else
            return false;
    }
}