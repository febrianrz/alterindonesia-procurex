<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    const REGEX_RULES = "regex:/^[\w\s\.\\\,\-\_\/]*$/i";
    const NAME_RULES = "required|string|min:3|max:255|".self::REGEX_RULES;

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
        $rules = [
            "name" => static::NAME_RULES,
            "guard_name" => static::NAME_RULES,
        ];
        if($this->method() === "PUT"){
            $rules['code'] = 'required|unique:roles,code,'.$this->route('id');
        }
        return $rules;
    }
}
