<?php

namespace App\Domain\Attendance\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Attendance\Actions\HistoryAction;
use App\Domain\Attendance\Requests\HistoryRequest;
use App\Domain\Attendance\Actions\CheckInOutAction;
use App\Domain\Attendance\Requests\CheckInOutRequest;
use App\Domain\Attendance\Actions\CalendarAction;
use App\Domain\Attendance\Requests\CalendarRequest;

class AttendanceController extends Controller
{
    public function __construct(
        private HistoryAction $historyAction,
        private CheckInOutAction $checkInOutAction,
        private CalendarAction $calendarAction
    ) {}

    public function checkInOut(CheckInOutRequest $checkInOutRequest)
    {
        $dto = $checkInOutRequest->toDto();
        $result = $this->checkInOutAction->run($dto);
        $status = $result['attendance']->check_out_at ? 200 : 201;
        return response()->json([$result, $status]);
    }

    public function history(HistoryRequest $historyRequest)
    {
        $dto = $historyRequest->toDto();
        $attendances = $this->historyAction->run($dto);
        return response()->json([
            'user_id' => $dto->userId,
            'attendances' => $attendances
        ], 200);
    }       

    public function calendar(CalendarRequest $calendarRequest)
    {
        $dto = $calendarRequest->toDto();
        $result = $this->calendarAction->run($dto);
        if (!$result['success']) {
            $status = $result['error'] === 'database' ? 503 : 500;
            return response()->json(['message' => $result['message']], $status);
        }
        return response()->json(['calendar' => $result['data']]);
    }

}
