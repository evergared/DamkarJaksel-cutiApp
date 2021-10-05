<?php
namespace App\Http\Controllers\Calendars;

use Spatie\GoogleCalendar\GoogleCalendar;
use Spatie\GoogleCalendar\Event;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

class DisableCutiManual
{
    public function index()
    {
        Config::set('google-calendar.calendar_id','2o5peemb99hhig9mruvodklg90@group.calendar.google.com');

        return $this->getDates();
    }

    public function getDates()
    {
        $eventId = Event::get();

        //return Event::find($eventId)->startDateTime;
        return $eventId;
    }
}
?>