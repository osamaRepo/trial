<?php

namespace App\Domain\Attendance\Tasks;

use App\Domain\Attendance\Models\Attendance;
use App\Domain\Attendance\Dtos\CheckInOutRequestDto;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\QueryException;
use Throwable;
use Illuminate\Support\Facades\Log;

class CheckInOutTask
{

    private float $officeLat = 33.747978;
    private float $officeLon = 72.789429;

    // private float $officeLat = 33.74660587488031;
    // private float $officeLon = 72.79006720427705;

    private float $allowedDistance = 300; // meters

    public function run(CheckInOutRequestDto $dto)
    {
        try {
            // Distance validation
            $distance = $this->haversineDistance($this->officeLat, $this->officeLon, $dto->latitude, $dto->longitude);

            if ($distance > $this->allowedDistance) {
                throw new HttpResponseException(response()->json([
                    'message' => 'You are too far from the allowed location.',
                    'distance_m' => round($distance, 2)
                ], 403));
            }

            $today = Carbon::today();

            // Get latest open attendance
            $openAttendance = Attendance::where('user_id', $dto->userId)
                ->whereDate('check_in_at', $today)
                ->whereNull('check_out_at')
                ->latest('check_in_at')
                ->first();

            if ($openAttendance) {
                // Check-out
                $openAttendance->update(['check_out_at' => Carbon::now()]);

                return [
                    'message' => 'Checked out successfully!',
                    'attendance' => $openAttendance
                ];
            }

            // Check-in
            $attendance = Attendance::create([
                'id' => Uuid::uuid4()->toString(),
                'user_id' => $dto->userId,
                'latitude' => $dto->latitude,
                'longitude' => $dto->longitude,
                'check_in_at' => Carbon::now(),
            ]);

            return [
                'message' => 'Checked in successfully!',
                'attendance' => $attendance
            ];

        } catch (QueryException $e) {
            // Database errors like connection lost
            Log::error('Database error in CheckInOutTask', ['exception' => $e]);
            throw new HttpResponseException(response()->json([
                'message' => 'Database error: ' . ($e->getMessage() ?: 'Unable to connect to the database')
            ], 500));

        } catch (HttpResponseException $e) {
            // Re-throw custom HTTP exceptions (like distance check)
            throw $e;

        } catch (Throwable $e) {
            // Any other exception
            Log::error('Unexpected error in CheckInOutTask', ['exception' => $e]);
            throw new HttpResponseException(response()->json([
                'message' => 'Something went wrong: ' . ($e->getMessage() ?: 'Internal server error')
            ], 500));
        }
    }

    private function haversineDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000; // meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2 +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}