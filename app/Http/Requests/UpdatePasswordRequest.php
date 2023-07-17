<?php

namespace App\Http\Requests;

use App\Libraries\Auth;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user()->isEmployee()) return false;
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
            'current_password'=> [
                'required',
                function($attribute,$value,$fail){
                    $user = User::find(Auth::user()->id);
                    if(!$user) $fail("Invalid Account");
                    else {
                        $check = Hash::check($value,$user->password);
                        if(!$check) $fail("Invalid Current Password");
                    }
                }
            ],
            'new_password'  => 'required|min:6|confirmed'
        ];
    }
}
