<?php 

namespace App\Domain\User\Tasks;

use App\Domain\User\Models\User;
use App\Domain\USer\Dtos\SignUpRequestDto;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SignUpTask
{
    public function run(SignUpRequestDto $dto)
    {
        try {
            $user = User::create([
                'id' => Uuid::uuid4()->toString(),
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
            ]);

            $token = $user->createToken('api-token')->plainTextToken;
            return [
                'user' => $user,
                'token' => $token
            ];

        } catch (\Illuminate\Database\QueryException $e) {
            // Example: duplicate email or DB error
            throw new HttpException(400, 'User registration failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Any other unexpected error
            throw new HttpException(500, 'Something went wrong during registration');
        }
    }

}