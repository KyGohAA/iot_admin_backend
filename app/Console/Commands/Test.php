<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use Log;

use App\LeafAPI;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test command work.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // get current hour
        $hour_started   =   date('Y-m-d H', strtotime('-1 hour')).':00:00';
        $hour_ended     =   date('Y-m-d H', strtotime('-1 hour')).':59:59';
        // check from -1hour to current hour, get total count of meter reading record.
        $total          =   DB::table('meter_readings')
                                ->whereBetween('created_at', [$hour_started, $hour_ended])
                                ->count();

        if (!$total) {
	        // send email notification to peter/priya
	        $emails =   ['peterooi83@gmail.com'];
	        $title  =   'Sunway Med. Centre Meter Reading';
	        $html   =   '<html><body><p>Total Record Of Meter Reading : '.$total.'</p>';
	        $html   .=  '<p>Date Started : '.$hour_started.'</p>';
	        $html   .=  '<p>Date Ended : '.$hour_ended.'</p></body></html>';
	        foreach ($emails as $email) {
	            $leaf_api       =   new LeafAPI();
	            $leaf_api->send_email($email, $title, $html);
	        }
        }

        Log::info('message test cronjob');
    }
}
