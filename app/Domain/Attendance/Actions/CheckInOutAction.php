<?php

namespace App\Domain\Attendance\Actions;

// use App\Domain\Attendance\Dtos\HistoryRequestDto;
// use App\Domain\Attendance\Tasks\HistoryTask;

use App\Domain\Attendance\Dtos\CheckInOutRequestDto;
use App\Domain\Attendance\Tasks\CheckInOutTask;

class CheckInOutAction
{
    public function __construct(
        private CheckInOutTask $checkInOutTask,
    ) {}

    public function run(CheckInOutRequestDto $checkInOutRequestDto)
    {
        return $this->checkInOutTask->run($checkInOutRequestDto);   
    }
}