<?php

namespace App\Domain\User\Actions;
use App\Domain\User\Dtos\SignUpRequestDto;
use App\Domain\User\Tasks\SignUpTask;

class SignUpAction
{

    public function __construct(
        private SignUpTask $signUpTask
    )
    {}   

    public function run(SignUpRequestDto $signUpRequestDto)
    {
        return $this->signUpTask->run($signUpRequestDto);
    }

}