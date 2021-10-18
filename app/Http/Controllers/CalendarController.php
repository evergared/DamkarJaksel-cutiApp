<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Calendars\DisableCutiManual as DisableCuti;
use App\Http\Controllers\Calendars\LiburNasional;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;

class CalendarController extends Controller
{
    // TODO : code disini untuk fetch data libur, boleh tidak boleh cuti, dari google api atau semacamnya.

    public function index()
    {
        $dc = new DisableCuti();
        $ln = new LiburNasional();

        $dateData = array_merge($dc->extractDatesAsArray(),$ln->extractDatesAsArray());

        return $dateData;
    }

    public function fetchJson()
    {
        $dc = new DisableCuti();
        $ln = new LiburNasional();


        $dateData = array_merge($ln->extractDatesAsJsonFeed(),$dc->extractDatesAsJsonFeed());
        
        return $dateData;
    }

    public function create(Request $request)
    {
        try
        {
            $newEvent = $request->all();
            if($request['calendar'] === "disable")
            {
                $event = new DisableCuti();
            }
        }

        catch(Throwable $e)
        {
            report($e);
        }
    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function getCalendarById($celandarId)
    {
        // switch($celandarId)
        // {
        //     case
        // }
    }

}
