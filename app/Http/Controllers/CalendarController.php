<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Calendars\DisableCutiManual as DisableCuti;
use App\Http\Controllers\Calendars\EnableCutiManual as EnableCuti;
use App\Http\Controllers\Calendars\LiburNasional;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Throwable;

use function PHPUnit\Framework\isNull;

class CalendarController extends Controller
{
    // TODO : code disini untuk fetch data libur, boleh tidak boleh cuti, dari google api atau semacamnya.

    public function index()
    {
        $dc = new DisableCuti();
        $ec = new EnableCuti();
        $ln = new LiburNasional();

        $dateData = array_merge($dc->extractDatesAsArray(),$ln->extractDatesAsArray());
        $dateData = array_diff($dateData,$ec->extractDatesAsArray());

        return array_values($dateData);
    }

    public function fetchJson()
    {
        $dc = new DisableCuti();
        $ec = new EnableCuti();


        $dateData = $dc->extractDatesAsJsonFeed();
        $dateData = array_merge($dateData,$ec->extractDatesAsJsonFeed());
        
        return $dateData;
    }

    // dipisah agar bisa di aktifan/nonaktifkan lewat fullcalendar
    // TODO : fetchPiket
    public function fetchLibur()
    {
        $ln = new LiburNasional();
        return $ln->extractDatesAsJsonFeed();
    }


    public function createEvent(Request $request)
    {
        try
        {
            error_log("event is ".$request->event_calendar);
            switch($request->event_calendar)
            {
                case "Tidak Boleh Cuti": $cal = new DisableCuti();break;
                case "Boleh Cuti": $cal = new EnableCuti();break;
                default:$cal = null;break;
            }

            $cal->createEvent($request->event_start,$request->event_end,$request->event_name);
            
        }

        catch(Throwable $e)
        {
            error_log("create event error : ".$e);
            report($e);
        }
    }

    public function updateEvent(Request $request)
    {
        

        try
        {
            //error_log($request);
        
            $cal = $this->getCalendar($request->event_calendarId);

            $event = $cal->findEvent($request->event_id);
            $event->name = $request->event_name;
            $event->startDate = $this->formatDate($request->event_start);

            if($request->event_end==="Invalid date")
                $event->endDate = $this->formatDate($request->event_start);
            else
            $event->endDate = $this->formatDate($request->event_end,1);

            $event->save();
        }

        catch(Throwable $e)
        {
            error_log("update event error : ".$e);
            report($e);
        }
    }

    public function deleteEvent($calId,$eventId)
    {
        //error_log($calId.'&'.$eventId);

        $cal = $this->getCalendar($calId);
        

        $eventIt = $cal->findEvent($eventId);
        $eventIt->delete();
    }

    function getCalendar($id)
    {
        switch($id)
        {
            case '2o5peemb99hhig9mruvodklg90@group.calendar.google.com':$cal = new DisableCuti();break;
            case 'ebe9u0cgb0a52va54qaut8rm58@group.calendar.google.com':$cal = new EnableCuti();break;
            default : $cal = null;break;
        }

        return $cal;
    }

    function formatDate($date,$mod = 0)
    {
        if($mod < 0)
        {
            $ndate = Carbon::parse($date)->subDays($mod);
        }
        else if($mod > 0)
        {
            $ndate = Carbon::parse($date)->addDays($mod);
        }
        else if($mod === 0)
        {
            $ndate = Carbon::parse($date);
        }
        
        return $ndate;
    }

}
