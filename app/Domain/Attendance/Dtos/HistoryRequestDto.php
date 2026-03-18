<?php

namespace App\Domain\Attendance\Dtos;

class HistoryRequestDto
{
    public function __construct(
        public string $userId,
    ) {}
}   