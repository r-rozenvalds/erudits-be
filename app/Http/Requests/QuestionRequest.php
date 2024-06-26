<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
//        return [
//            'text' => 'required',
//            'image' => 'nullable',
//            'question_group_id' => 'required|exists:question_groups,id',
//        ];

        return [
            'text' => '',
            'image' => 'nullable',
            'is_open_answer' => 'boolean',
            'guidelines' => 'text',
            'correct_answer' => 'text',
            'question_group_id' => 'required|exists:question_groups,id',
        ];
    }
}
