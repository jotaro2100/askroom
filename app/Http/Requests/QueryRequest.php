<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QueryRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:5|max:100',
            'content' => 'required|min:10|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => "題名を入力してください",
            'title.min' => "題名は :min 文字以上で入力してください",
            'title.max' => "題名は :max 文字以下で入力してください",
            'content.required' => "本文を入力してください",
            'content.min' => "本文は :min 文字以上で入力してください",
            'content.max' => "本文は :max 文字以下で入力してください",
        ];
    }
}
