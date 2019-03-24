<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Updates as EventUpdate;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise;

class Updates implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'listeners';
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(EventUpdate $event)
    {

        try{
            /*$client = new Client(['base_uri' => request()->server('HTTP_HOST')]);
            \Log::info("execute at:".date('Y-m-d H:i:s'));
            $promises = [
                'image' => $client->getAsync('/update/db')
            ];
            $results = Promise\unwrap($promises);*/
            return true;
        }catch (\Exception $e){
            return true;
        }


    }
}
