<?php 


namespace App\Domain\Attendance\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Domain\Attendance\Dtos\HistoryRequestDto;

class HistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [];
    }

    public function toDto(): HistoryRequestDto
    {
        return new HistoryRequestDto(
            userId: $this->user()->id
        );
    }
}