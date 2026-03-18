<?php

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Dtos\HistoryRequestDto;
use App\Domain\Attendance\Tasks\HistoryTask;

class HistoryAction
{
    public function __construct(
        protected HistoryTask $historyTask
    ) {}

    public function run(HistoryRequestDto $historyRequestDto)
    {
        return $this->historyTask->run($historyRequestDto);
    }
}