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

    }

    public function create(Request $request)
    {
        try
        {
            if($request['calendar'] === "disable")
            {
                $event = DisableCuti
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

}
