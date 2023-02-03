<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
{
    const REGEX_RULES = "regex:/^[\w\s\.\\\,\-\_\/]*$/i";

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
        return [
            "name" => "required|string|min:3|max:50|".static::REGEX_RULES,
            "icon" => "present|string|nullable|max:50|".static::REGEX_RULES,
            "status" => "required|string|in:ACTIVE,INACTIVE",
            "path"  => "required|string|max:50",
            "is_show_on_dashboard"=> "required|boolean",
            "order_no"=> "nullable|numeric|max:126"
        ];
    }
}
