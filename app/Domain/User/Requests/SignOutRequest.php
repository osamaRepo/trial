<?php

namespace App\Domain\User\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Domain\User\Dtos\SignOutRequestDto;

class SignOutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()->currentAccessToken(); 
    }

    public function rules(): array
    {
        return [];
    }

    public function toDto(): SignOutRequestDto
    {
        return new SignOutRequestDto(
            token: $this->user()->currentAccessToken()
        );
    }
}