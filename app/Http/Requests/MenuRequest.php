<?php

namespace App\Http\Requests;

use App\Models\Module;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MenuRequest extends FormRequest
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
            "module_id" => [
                "required",
                "integer",
                Rule::exists("modules", "id")->where(function ($query) {
                    return $query->where("status", "=", Module::STATUS_ACTIVE)->whereNull("deleted_at");
                })
            ],
            "name"      => "required|string|min:3|max:50|".static::REGEX_RULES,
            "icon"      => "present|string|nullable|max:50|".static::REGEX_RULES,
            "path"      => "required|string|max:50",
            "order_no"  => "nullable|numeric|max:126",
            "status"    => "required|string|in:ACTIVE,INACTIVE",
        ];
    }
}
