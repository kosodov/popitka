<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\DTOs\RoleDTO;

class UpdateRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Измените это на проверку авторизации, если необходимо
    }

    public function rules()
    {
        return [
            'name' => 'required|unique:roles,name,' . $this->route('role'),
            'description' => 'nullable',
            'code' => 'required|unique:roles,code,' . $this->route('role'),
        ];
    }

    public function toDTO()
    {
        return new RoleDTO(
            $this->name,
            $this->description,
            $this->code
        );
    }
}
