<?php

namespace App\Http\Requests;

use App\Libraries\Auth;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if($this->method() === "POST"){
            return [
                'name'  => 'required',
                'username'  => 'required|unique:users,username',
                'email'     => 'required|email|unique:users,email',
                'company_code'=> 'required|exists:companies,code',
                'status'    => 'required|in:ACTIVE,INACTIVE',
                'roles.*'     => 'nullable|array|exists:roles,id',
                'password'  => 'required|min:6'
            ];
        } else {
            return [
                'name'  => 'required',
                'username'  => 'required|unique:users,username,'.$this->route('id'),
                'email'     => 'required|email|unique:users,email,'.$this->route('id'),
                'company_code'=> 'required|exists:companies,code',
                'status'    => 'required|in:ACTIVE,INACTIVE',
                'roles.*'     => 'nullable|array|exists:roles,id',
                'password'  => 'nullable|min:6'
            ];
        }

    }
}
