<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerRequest extends FormRequest
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
            'answer_content' => 'required|min:10|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'answer_content.required' => "回答を入力してください",
            'answer_content.min' => "回答は :min 文字以上で入力してください",
            'answer_content.max' => "回答は :max 文字以下で入力してください",
        ];
    }
}
