<?php

namespace App\Domain\Attendance\Dtos;

class CheckInOutRequestDto
{
    public function __construct(
        public string $userId,
        public float $latitude,
        public float $longitude,
    ) {}
}