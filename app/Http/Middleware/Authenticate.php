<?php

namespace App\Http\Middleware;

use Log;
use Auth;
use Route;
use Closure;

use App\User;
use App\LeafAPI;

class Authenticate
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
            return $next($request);
        }
        $session_token = $request->input('session_token');
        if ($request->has('session_token')) {
            $leaf_api = new LeafAPI();
            $model = new User();
            $result = $leaf_api->get_user_profile($session_token);
            if (!$result['status_code']) {
                $data['status'] = false;
                $data['status_msg'] = $result['error'];
                return json_encode($data);
            } else {
                $user = $model->get_or_create_user_account($result);
                Auth::loginUsingId($user->id, true);
                return $next($request);
            }
        } else {
            $input              =   $request->input();
            $input['redirect']  =   $request->url();

            return redirect()->action('OpencartUsersController@getLogin');
            //return redirect()->action('AppsWebStoresController@getLogin', $input);
        }
    }
}
