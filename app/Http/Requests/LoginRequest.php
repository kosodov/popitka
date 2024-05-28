<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\DTOs\LoginDTO;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ];
    }

    public function toDTO(): LoginDTO
    {
        return new LoginDTO(
            $this->input('username'),
            $this->input('password')
        );
    }
}
