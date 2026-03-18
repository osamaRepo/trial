<?php 

namespace App\Domain\User\Tasks;
use App\Domain\User\Models\User;
use App\Domain\USer\Dtos\SignInRequestDto;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SignInTask
{
    public function run(SignInRequestDto $dto)
    {
        $user = User::where('email', $dto->email)->first();

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw new HttpException(401, 'Invalid credentials');
        }

        $token = $user->createToken('api-token')->plainTextToken;
        return[
            'user' => $user,
            'token' => $token
        ];
    }
}
