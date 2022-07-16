<?php

namespace Teamprodev\LaravelPayment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientCheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'key' => 'required',
            'amount' => 'required|numeric',
            'payment_system' => 'required|in:payme,click,paynet',
        ];
    }
}
