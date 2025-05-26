<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonProgressRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'typed_text' => 'required_without_all:typing_speed,typing_accuracy|string',
            'typing_speed' => 'required_without:typed_text|numeric|min:1',
            'typing_accuracy' => 'required_without:typed_text|numeric|min:1|max:100',
            'completion_time' => 'required|integer|min:1',
        ];
    }
}