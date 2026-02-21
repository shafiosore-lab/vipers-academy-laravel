<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminPaymentFormRequest extends FormRequest
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
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'payment_status' => 'required|in:pending,completed,failed,refunded,cancelled',
                'transaction_id' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ];
        }

        return [
            'payer_type' => 'required|in:player,partner,customer',
            'payer_id' => 'required|integer',
            'payment_type' => 'required|in:registration_fee,subscription_fee,program_fee,tournament_fee,merchandise,donation,sponsorship,other',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'payment_method' => 'required|in:mpesa,card,bank_transfer,cash,cheque',
            'payment_gateway' => 'nullable|in:mpesa,stripe,paypal,bank,cash',
            'due_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
        ];
    }
}
