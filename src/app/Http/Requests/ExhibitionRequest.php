<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => ['required'],
            'description' => ['required', 'max:255'],
            'image_path' => ['required', 'mimes:jpeg,jpg,png'],
            'categories' => ['required'],
            'condition' => ['required'],
            'price' => ['required', 'integer', 'min:0']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '説明を入力してください',
            'description.max' => '説明を255以内で入力してください',
            'image_path.required' => '画像を入力してください',
            'image_path.mimes' => '画像が無効です',
            'category_id.required' => 'カテゴリーを入力してください',
            'condition.required' => '状態を入力してください',
            'price.required' => '価格を入力してください',
            'price.integer' => '価格を数値で入力してください',
            'price.min' => '0円以上で入力してください',
        ];
    }
}
