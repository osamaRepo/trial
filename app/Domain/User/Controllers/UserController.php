<?php

namespace App\Domain\User\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\User\Actions\SignInAction;
use App\Domain\User\Requests\SignInRequest;
use App\Domain\User\Actions\SignUpAction;
use App\Domain\User\Requests\SignUpRequest;
use App\Domain\User\Requests\SignOutRequest;
use App\Domain\User\Actions\SignOutAction;

class UserController extends Controller
{
    public function __construct(
        private SignInAction $signInAction,
        private SignUpAction $signUpAction,
        private SignOutAction $signOutAction
    ){}

    public function login(SignInRequest $signInRequest)
    {
        $dto = $signInRequest->toDto();
        $result = $this->signInAction->run($dto);
        return response()->json($result);   
    }

    public function register(SignUpRequest $signUpRequest)
    {
        $dto = $signUpRequest->toDto();
        $result = $this->signUpAction->run($dto);
        return response()->json($result);   
    }

    public function logout(SignOutRequest $signOutRequest)
    {
        $dto = $signOutRequest->toDto();
        $result = $this->signOutAction->run($dto);
        if (!$result['success']) {
            $status = $result['error'] === 'database' ? 503 : 500;
            return response()->json(['message' => $result['message']], $status);
        }
        return response()->json(['message' => $result['message']]);
    }

}
