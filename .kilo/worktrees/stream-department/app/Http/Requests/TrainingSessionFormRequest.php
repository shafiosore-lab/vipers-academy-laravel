<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrainingSessionFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'session_type' => 'required|in:training,match,office_time,meeting',
            'team_category' => 'required|string',
            'scheduled_start_time' => 'required|date',
        ];
    }

    public function prepareForValidation()
    {
        // No special preparation needed
    }
}
