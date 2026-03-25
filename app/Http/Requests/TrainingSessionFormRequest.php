<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrainingSessionFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'session_type'         => 'required|string|in:training,match,office_time,meeting',
            'team_category'        => 'required|string|in:U10,U12,U13,U14,U15,U16,U17,U18,U20,Senior,Veteran',
            'gender'               => 'nullable|string|in:male,female,mixed',
            'organization_id'      => 'nullable|exists:organizations,id',
            'scheduled_start_time' => 'required|date|after:now',
        ];
    }
}
