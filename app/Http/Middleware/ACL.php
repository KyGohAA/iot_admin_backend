<?php

namespace App\Http\Middleware;

use Auth;
use Route;
use Closure;

use App\Company;
use App\UserGroup;
use App\UserAssign;

class ACL
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // get group id
         if ($assign = UserAssign::where('user_id','=',Auth::id())->where('leaf_group_id','=',Company::get_group_id())->first()) {
             if ($user_group = UserGroup::find($assign->user_group_id)) {
                 if ($allowlist = json_decode($user_group->json_permissions, true)) {
                     // get permisssion
                     $route = Route::currentRouteAction();
                     $pre_route = str_replace('app\http\controllers\\', '', strtolower($route));
                     $controller = str_replace('controller', '', strstr($pre_route, '@', true));
                     $action = snake_case(str_replace('@get', '', strstr($route, '@')));
                  
                     if (isset($allowlist[$controller])) {
                     // check controller
                         if (in_array($action, $allowlist[$controller])) {
                            // check action
                            return $next($request);
                         }
                     }
                 }
             }
         }

         return redirect()->action('DashboardsController@getError', ['commons','403']);
    }
}
