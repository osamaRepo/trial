<?php

namespace App\Domain\User\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Domain\User\Dtos\SignUpRequestDto;

class SignUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }   

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [ 'required', 'email', 'unique:users', 'regex:/^[\w.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,}$/'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    public function toDto(): SignUpRequestDto
    {
        return new SignUpRequestDto(
            name: $this->input('name'),
            email: $this->input('email'),
            password: $this->input('password')
        );
    }

}