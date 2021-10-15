<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Calendars\DisableCutiManual as DisableCuti;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;

class CalendarController extends Controller
{
    // TODO : code disini untuk fetch data libur, boleh tidak boleh cuti, dari google api atau semacamnya.

    public function index()
    {
        $dc = new DisableCuti();

        $dateData = [];

        array_merge($dateData,$dc->extractDatesAsArray());

        return array_unique($dateData);
    }

    public function fetchJson()
    {
        $dc = new DisableCuti();

        $dateData = [];

        array_push($dateData,$dc->extractDatesAsJsonFeed());


        return array_unique($dateData);
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
