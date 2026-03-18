<?php

namespace App\Domain\User\Dtos;

class SignOutRequestDto
{
    public function __construct(
        public $token
    ) {}
}