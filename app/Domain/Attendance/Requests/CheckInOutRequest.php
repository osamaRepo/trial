<?php

namespace App\Domain\Attendance\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Domain\Attendance\Dtos\CheckInOutRequestDto;

class CheckInOutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }   


    public function rules()
    {
        return [
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ];
    }

    public function toDto(): CheckInOutRequestDto
    {
        return new CheckInOutRequestDto(
            userId: $this->user()->id,
            latitude: $this->input('latitude'),
            longitude: $this->input('longitude'),
        );
    }
}