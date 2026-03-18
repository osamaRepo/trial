<?php 

namespace App\Domain\Attendance\Requests;

use App\Domain\Attendance\Dtos\CalendarRequestDto;
use Illuminate\Foundation\Http\FormRequest;

class CalendarRequest extends FormRequest
{

    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
        ];
    }

    public function toDto(): CalendarRequestDto
    {
        return new CalendarRequestDto(
            userId: $this->user()->id,
            month: $this->input('month'),
            year: $this->input('year')
        );
    }
}