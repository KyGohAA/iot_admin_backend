<?php

namespace App\Http\Middleware;

use Log;
use Auth;
use Closure;

use App\User;
use App\LeafAPI;

class AuthAdmin
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

        if (Auth::check()) {

            if(Auth::user()->is_admin){
                return $next($request);
            }else{
                //return redirect()->action('OpencartUsersController@getLogin');
                //dd("Apologize that you are not admin. please contact the system administrator.");
            }

        }else if($request->input('is_api') == true){
            return $next($request);
        }else{
            return redirect()->action('OpencartUsersController@getLogin');
        }

        $session_token = $request->input('session_token');

        if ($session_token) {
            $leaf_api = new LeafAPI();
            $model = new User();
            $result = $leaf_api->get_user_profile($session_token);
            print_r($result);
            if (!$result['status_code']) {
                $data['status'] = false;
                $data['status_msg'] = $result['error'];
                
            } else {
                $user = $model->get_or_create_user_account($result);
                Auth::loginUsingId($user->id, true);
                
                return $next($request);
            }

        }else{
             return $next($request);
        }

        if (!Auth::check()) {
            if (!isset($data)) {
                $data['status']     =   false;
                $data['status_msg'] =   'Authorization Failed';
            }
            print_r(json_encode($data));
            exit();
        }
    }
}
