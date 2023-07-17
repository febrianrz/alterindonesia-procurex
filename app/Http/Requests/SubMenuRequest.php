<?php

namespace App\Http\Requests;

use App\Models\Menu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubMenuRequest extends FormRequest
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
            "menu_id" => [
                "required",
                "integer",
                Rule::exists("menus", "id")->where(function ($query) {
                    return $query->where("status", "=", Menu::STATUS_ACTIVE)->whereNull("deleted_at");
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
