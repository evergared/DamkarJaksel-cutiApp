<?php
namespace App\Http\Controllers\Calendars;

use Spatie\GoogleCalendar\Event;

use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Throwable;


abstract class BaseCalendar
{
    // SUMMARY UNTUK PEMBUATAN EVENT

    // dibawah ini nilai dari tgl mulai dan tgl akhir ketika dicek

    // 1. Pembuatan melalui Spatie / dari web ini :
    //     a. event 1 hari  : - Start date = End date, atau
    //                        - End date = Start date + 1 hari
    //     b. event >1 hari : End date = inputan end date + 1 hari
        
    // 2. Pembuatan melalui Google Calendar : 
    //     a. event 1 hari  : End date = Start date + 1 hari
    //     b. event >1 hari : End date = inputan end date + 1 hari

    // Berdampak pada data yang ditampilkan di web ini dan di google calendar jika tidak diingat

    protected $calendarName,
              $calendarId,
              $display = "auto",
              $editable = true,
              $color = "black",
              $textColor = "black";

    public function index()
    {
        Config::set('google-calendar.calendar_id', $this->calendarId);
    }

    public function fetchEvents()
    {
        $this->index();

        try
        {
            // Akan trigger (invalid_grant, JWT Error : Token must be shortlived) jika jam tidak sama
            // Solusi : samakan dengan jam server, set date/time automatic (GMT+7)
            // TODO : prevent user from pick date when this happen
            $events = Event::get();

            return $events;
        }
        
        catch(Throwable $e)
        {
            report($this->calendarName.' fetch event error, e : '.$e);
            error_log('Fetch event error for : '.$this->calendarName.' error : '.$e);
        }
    }

    public function createEvent($startDate, $endDate = "", $name = "test")
    {
        
        $this->index();

        try
        {
            $sd = Carbon::parse($startDate);
            if($endDate === "")
                $ed = $startDate;
            else
                $ed = Carbon::parse($endDate)->addDay();

            error_log($sd." and ".$ed);

            Event::create([
                'name' => $name,
                'startDate' => $sd,
                'endDate' => $ed
            ]);
        }

        catch(Throwable $e)
        {
            report($this->calendarName.' create event error, e : '.$e);
            error_log('Create event error for '.$this->calendarName.' on : '.$e);
        }
    }

    public function extractDatesAsArray()
    {
        $events = $this->fetchEvents();

        $dateData = [];

        foreach($events as $event)
        {
            if($event->startDate->eq($event->endDate) || $event->endDate->eq($event->startDate->addDay(1)))
            {
                array_push($dateData,Carbon::parse($event->startDate)->toDateString());
            }

            else if($event->endDate->gt($event->startDate->addDay(1)))
            {
                $sd = $event->startDate;
                $ed = $event->endDate;
                $nd = new Carbon($sd);

                while($nd->ne($ed))
                {
                    array_push($dateData,$nd->toDateString());
                    $nd->addDay(1);
                }
                
            }
        }

        return array_unique($dateData);
    }

    public function extractDatesAsJsonFeed()
    {
        $events = $this->fetchEvents();

        $dateData = [];

        foreach($events as $event)
        {
            if($event->startDate->eq($event->endDate) || $event->endDate->eq($event->startDate->addDay(1)))
            {
                // satu hari
                $sd = $event->startDate;
                $ed = $sd;
                //error_log($event->name." is same day");
            }
            else if($event->endDate->gt($event->startDate->addDay(1)))
            {
                // lebih dari satu hari
                $sd = $event->startDate;
                $ed = $event->endDate;
                //error_log($event->name." is not same day");
            }
            
            $newData = [
                
                'id' => $event->id,
                'title'  => $event->name,
                'start' => $sd,
                'end' => $ed,
                'allDay' => true,
                'display'=>$this->display,
                'color'=>$this->color,
                'editable'=>$this->editable,
                'eventTextColor' =>$this->textColor,
                'extendedProps' => [
                    'calId' => $this->calendarId,
                    'calName'=>$this->calendarName,
                ]
            ];

            

            $dateData[] = $newData;

        }

        return $dateData;
    }

    public function findEvent($eventId)
    {
        try
        {
            $event = Event::find($eventId,$this->calendarId);
            return $event;
            
        }

        catch(Throwable $e)
        {
            report($this->calendarName.' find event error, e : '.$e);
            error_log('Find event error for '.$this->calendarName.' calendar with id : '.$eventId.' on : '.$e);
        }
    }

    public function getCalendarId()
    {
        return $this->calendarId;
    }
}
?>