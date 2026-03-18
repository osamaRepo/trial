<?php

namespace App\Domain\User\Dtos;

class SignInRequestDto
{
    public function __construct(
        public string $email, 
        public string $password
    ){}
}