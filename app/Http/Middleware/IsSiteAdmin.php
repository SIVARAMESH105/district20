<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AdminHelper;

/**
 * Class IsSiteAdmin
 * namespace App\Http\Middleware
 * @package Closure
 * @package Illuminate\Support\Facades\Auth
 * @package App\Helpers\AdminHelper
 * NOTE : check user is site admin
 */
class IsSiteAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (AdminHelper::checkUserIsSuperAdmin()) {
           return $next($request);
        } else {
        	return redirect(route('access-denied'));
        }
    }
}