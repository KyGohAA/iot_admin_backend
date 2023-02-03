<?php

namespace App\Exceptions;

use DB;
use Exception;
use Request;
use Log;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use App\LeafAPI;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        $debug_mode = env('ALERT_DEBUG', true);
        dd($exception);
        Log::info('Error :'.json_encode($exception));

       /* if ($debug_mode && !method_exists($exception,'getStatusCode')) {
            $developers = DB::table('developers')->where('is_main','=',true)->get();
            $title = "webview.leaf.com.my error handler report";
            $body = '<!DOCTYPE html><html><head><title></title></head><body>';
            $body .= '<p>Datetime : '.date('d-m-Y H:i:s').'</p>';
            $body .= '<p>Url : '.Request::fullUrl().'</p>';
            $body .= '<p>Error : '.(string) $exception.'</p>';
            $body .= '</body></html>';
            foreach ($developers as $developer) {
                $leaf_api = new LeafAPI();
                $leaf_api->send_email($developer->email, $title, $body);
            }
        }*/

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }
}
