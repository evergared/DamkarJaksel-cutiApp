<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CalendarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'calendarId' => $this->calendarId,
            'eventId' => $this->eventId,
            'start' => $this->startDate,
            'end' => $this->endDate,
            'title' => $this->title
        ];
    }
}
