<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
{
    const REGEX_RULES = "regex:/^[\w\s\.\,\-]*$/i";

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
        return ["name" => "required|string|min:3|max:50|".static::REGEX_RULES]
        +
        ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    /**
     * @return string[]
     */
    protected function store(): array
    {
        return [
            "icon" => "present|string|max:50|".static::REGEX_RULES
        ];
    }

    /**
     * @return array
     */
    protected function update(): array
    {
        return [
            "icon" => "present|string|max:50|".static::REGEX_RULES,
            "status" => "string|in:ACTIVE,INACTIVE"
        ];
    }
}
