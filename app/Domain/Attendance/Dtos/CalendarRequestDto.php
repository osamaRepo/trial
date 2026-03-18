<?php

namespace App\Domain\Attendance\Dtos;

class CalendarRequestDto
{
    public function __construct(
        public string $userId,
        public int $month,
        public int $year
    ) {
    }
}