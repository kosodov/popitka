<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\DTOs\RoleDTO;

class StoreRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|unique:roles',
            'description' => 'nullable',
            'code' => 'required|unique:roles',
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
