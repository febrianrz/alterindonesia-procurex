<?php

namespace App\Http\Requests;

use App\Models\AuthClient;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $grant_type = $this->grant_type;
        if($grant_type !== "password") return false;
        $client_id = $this->client_id;
        $client_secret = $this->client_secret;
        $client = AuthClient::where('id',$client_id)
                ->where('secret',$client_secret)
                ->where('is_active',true)
                ->first();
        return $client ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username'  => 'required',
            'password'  => 'required'
        ];
    }
}
