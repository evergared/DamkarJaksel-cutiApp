<?php

namespace App\Listeners;

use App\Events\CutiSubmitEvent;

class CutiEventSubscriber
{

    public function handleCutiSubmit($event)
    {
        error_log('Cuti is submited by :'.$event->nip." on ".$event->no_cuti);
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
    }

    

}

?>