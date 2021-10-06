<?php
namespace App\Http\Controllers\Calendars;

use Spatie\GoogleCalendar\GoogleCalendar;
use Spatie\GoogleCalendar\Event;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Throwable;

use function PHPUnit\Framework\isNull;

class DisableCutiManual
{
    public function index()
    {
        Config::set('google-calendar.calendar_id','2o5peemb99hhig9mruvodklg90@group.calendar.google.com');
    }

    public function fetchEvents()
    {
        $this->index();

        try
        {
            $events = Event::get();

            return $events;
        }
        
        catch(Throwable $e)
        {
            report('Disable Cuti Manual fetch event error, e : '.$e);
            error_log('Fetch event error for Disable Cuti Manual calendar');
        }
    }

    public function createEvent($startDate, $endDate = "", $description = "")
    {
        $this->index();

        try
        {
            if(isNull($endDate))
                $endDate = $startDate;

            Event::create([
                'name' => $description,
                'startDateTime' => $startDate,
                'endDateTime' => $endDate
            ]);
        }

        catch(Throwable $e)
        {
            report('Disable Cuti Manual create event error, e : '.$e);
            error_log('Create event error for Disable Cuti Manual calendar');
        }
    }

    public function getDates()
    {
        $events = $this->fetchEvents();

        $dateData = [];

        foreach($events as $event)
        {
            //$event
        }
    }
}
?>