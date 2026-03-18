<?php

namespace App\Domain\User\Actions;
use App\Domain\User\Dtos\SignOutRequestDto;
use App\Domain\User\Tasks\SignOutTask;

class SignOutAction
{
    public function __construct(
        protected SignOutTask $signOutTask
    ){}

    public function run(SignOutRequestDto $signOutRequestDto)
    {
        return $this->signOutTask->run($signOutRequestDto);
    }
}