<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\DTOs\RegisterDTO;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'birthday' => 'required|date',
        ];
    }

    public function toDTO(): RegisterDTO
    {
        return new RegisterDTO(
            $this->input('username'),
            $this->input('email'),
            $this->input('password'),
            $this->input('birthday')
        );
    }
}
