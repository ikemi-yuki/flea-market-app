<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PurchaseRequest extends FormRequest
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
            'payment_method' => ['required'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if (session()->has('purchase_address')) {
                return;
            }

            $user = $this->user();
            if (
                $user &&
                $user->profile &&
                $user->profile->post_code &&
                $user->profile->address
            ) {
                return;
            }

            $validator->errors()->add('address', '');
        });
    }
}
