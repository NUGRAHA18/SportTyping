<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PracticeResultRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'typing_speed' => 'required|numeric|min:1',
            'typing_accuracy' => 'required|numeric|min:1|max:100',
            'completion_time' => 'required|integer|min:1',
        ];
    }
}