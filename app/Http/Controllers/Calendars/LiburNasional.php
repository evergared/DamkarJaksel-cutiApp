<?php
namespace App\Http\Controllers\Calendars;

use App\Http\Controllers\Calendars\BaseCalendar;

class LiburNasional extends BaseCalendar
{
    function __construct()
    {
        $this->calendarId = 'b3b7oep5kkvhn1joqqq93je72igi7j6v@import.calendar.google.com';
        $this->calendarName = 'Libur Nasional';
        $this->editable = false;
        $this->color = "tomato";
        $this->display = "background";
    }
}
?>