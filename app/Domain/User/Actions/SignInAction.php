<?php

namespace App\Domain\User\Actions;
use App\Domain\User\Dtos\SignInRequestDto;
use App\Domain\User\Tasks\SignInTask;

class SignInAction
{
    public function __construct(
        private SignInTask $signInTask
     ){}

    public function run(SignInRequestDto $dto)
    {
        return $this->signInTask->run($dto);
    }
    
}