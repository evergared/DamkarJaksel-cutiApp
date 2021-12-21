<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;


use App\Events\CutiSubmitEvent;
use App\Events\CutiPrintEvent;
use App\Http\Controllers\FormCutiController;
use Throwable;

class CutiEventSubscriber
{

    public function handleCutiSubmit($event)
    {
        error_log('Cuti is submited by :'.$event->nip." on ".$event->no_cuti);
    }

    public function handleCutiPrint($event)
    {
        $test = new FormCutiController();
        $test->sisaCuti($event->nip,$event->no_cuti);
    }

    /**
     * Subscriber, mendengarkan banyak event sekaligus
     * 
     * @param \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(CutiSubmitEvent::class,
        [CutiEventSubscriber::class,'handleCutiSubmit']);
        
        $events->listen(CutiPrintEvent::class,
        [CutiEventSubscriber::class,'handleCutiPrint']);
    }

    

}

?>