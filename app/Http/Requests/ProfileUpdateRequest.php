<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'device_preference' => 'required|in:mobile,pc,both',
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|min:8|confirmed',
        ];
    }
}