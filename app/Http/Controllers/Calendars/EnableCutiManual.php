<?php
namespace App\Http\Controllers\Calendars;

use App\Http\Controllers\Calendars\BaseCalendar;

class EnableCutiManual extends BaseCalendar
{
    function __construct()
    {
        $this->calendarId = 'ebe9u0cgb0a52va54qaut8rm58@group.calendar.google.com';
        $this->calendarName = 'Boleh Cuti';
        $this->color = 'peacock';
    }
}
?>