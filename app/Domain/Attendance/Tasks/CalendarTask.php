<?php

namespace App\Domain\Attendance\Tasks;


use App\Domain\Attendance\Models\Attendance;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;

class CalendarTask
{
    public function run($dto): array
    {
        try {
            $attendances = Attendance::where('user_id', $dto->userId)
                ->whereMonth('check_in_at', $dto->month)
                ->whereYear('check_in_at', $dto->year)
                ->get();

            $grouped = $attendances->groupBy(function ($a) {
                return Carbon::parse($a->check_in_at)->toDateString();
            });

            $result = [];

            foreach ($grouped as $date => $sessions) {
                $totalMinutes = 0;

                foreach ($sessions as $session) {
                    if ($session->check_out_at) {
                        $in = Carbon::parse($session->check_in_at);
                        $out = Carbon::parse($session->check_out_at);
                        $totalMinutes += $in->diffInMinutes($out);
                    }
                }

                $totalHours = $totalMinutes / 60;

                if ($totalHours >= 0.5) {
                    $status = 'present';
                } elseif ($totalMinutes > 0) {
                    $status = 'partial';
                } else {
                    $status = 'checkin_only';
                }

                $result[$date] = [
                    'date'        => $date,
                    'total_hours' => round($totalHours, 2),
                    'status'      => $status
                ];
            }

            return ['success' => true, 'data' => $result];

        } catch (QueryException $e) {
            // Database error (MySQL down, query fail, etc.)
            return [
                'success' => false,
                'error'   => 'database',
                'message' => 'Database error occurred.'
            ];
        } catch (Exception $e) {
            // Any other unexpected error
            return [
                'success' => false,
                'error'   => 'server',
                'message' => 'Something went wrong.'
            ];
        }
    }
}