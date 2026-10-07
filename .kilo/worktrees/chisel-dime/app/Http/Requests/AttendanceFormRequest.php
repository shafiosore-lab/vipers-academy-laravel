<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'player_id'  => 'required|exists:players,id',
            'session_id' => 'required|exists:training_sessions,id',
        ];
    }
}
