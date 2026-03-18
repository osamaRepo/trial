<?php

namespace App\Domain\Attendance\Tasks;

use App\Domain\Attendance\Models\Attendance;
use App\Domain\Attendance\Dtos\HistoryRequestDto;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\QueryException;

class HistoryTask
{
    public function run(HistoryRequestDto $dto)
    {
        try {
            $attendances = Attendance::where('user_id', $dto->userId)
                ->orderBy('check_in_at', 'desc')
                ->get();

            // Optional: if no records found, you can throw 404
            // if ($attendances->isEmpty()) {
            //     throw new HttpException(404, 'No attendance records found');
            // }

            return $attendances;

        } catch (QueryException $e) {
            // Database problem
            throw new HttpException(500, 'Failed to fetch attendance history');
        } catch (\Exception $e) {
            // Any unexpected error
            throw new HttpException(500, $e->getMessage());
        }
    }
}