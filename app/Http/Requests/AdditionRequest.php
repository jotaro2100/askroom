<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AdditionRequest extends FormRequest
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
            "addition_content{$this->answer->id}" => 'required|min:5|max:300',
        ];
    }

    public function messages()
    {
        return [
            "addition_content{$this->answer->id}.required" => "補足を入力してください",
            "addition_content{$this->answer->id}.min" => "補足は :min 文字以上で入力してください",
            "addition_content{$this->answer->id}.max" => "補足は :max 文字以下で入力してください",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->session()->flash('ansId', $this->answer->id);

        parent::failedValidation($validator);
    }
}
