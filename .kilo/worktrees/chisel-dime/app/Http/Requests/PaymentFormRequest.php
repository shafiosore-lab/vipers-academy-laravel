<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'guardian_id' => 'required|exists:guardians,id',
            'player_id' => 'required|exists:players,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,paybill,transfer',
            'month_applied_to' => 'required|date_format:Y-m',
            'notes' => 'nullable|string',
        ];
    }
}
