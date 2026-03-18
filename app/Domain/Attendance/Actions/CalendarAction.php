<?php

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Tasks\CalendarTask;
use App\Domain\Attendance\Dtos\CalendarRequestDto;


class CalendarAction
{
    

    public function __construct(
        private CalendarTask $calendarTask
    ){}
   

    public function run(CalendarRequestDto $calendarRequestDto): array
    {
        return $this->calendarTask->run($calendarRequestDto);
    }
}