<?php

namespace App\Domain\User\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Domain\User\Dtos\SignInRequestDto;

class SignInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }   

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function toDto(): SignInRequestDto
    {
        return new SignInRequestDto(
            email: $this->input('email'),
            password: $this->input('password')
        );
    }

}
