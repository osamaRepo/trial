<?php

namespace App\Domain\User\Dtos;

class SignUpRequestDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ){}
}