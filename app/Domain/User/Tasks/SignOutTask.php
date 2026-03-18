<?php

namespace App\Domain\User\Tasks;
use App\Domain\User\Dtos\SignOutRequestDto;
use Illuminate\Database\QueryException;
use Exception;

class SignOutTask
{
    public function run(SignOutRequestDto $dto): array
    {
        try
        {
            $dto->token->delete();
            return ['success' => true, 'message' => 'Logged out successfully'];

        } catch (QueryException $e) {
            return ['success' => false, 'error' => 'database', 'message' => 'Database error occurred.'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'server', 'message' => 'Something went wrong.'];
        }
    }
    
}