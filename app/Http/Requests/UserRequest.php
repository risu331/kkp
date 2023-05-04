<?php

namespace App\Http\Requests;

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
     * @return array
     */
    public function rules()
    {
        if(request()->routeIs('dashboard.user.update') || request()->routeIs('dashboard.profile.update')){
            $user_id = \Request::segments()[2];
            $emailRule = 'required|email|max:100|unique:users,email,' . $user_id;
            $passwordRule = '';
        } else {
            $emailRule = 'required|email|max:100|unique:users,email';
            $passwordRule = 'required';
        }
        $rules = [
            'name' => 'required|max:100',
            'phone_number' => 'required|max:100',
            'email' => $emailRule,
            'password' => $passwordRule
        ];
        
        return $rules;
    }
    
    public function messages() {
        $customMessages = [
            'required' => 'Kolom wajib di isi.',
            'max' => 'Panjang text tidak boleh lebih dari 100 karakter.',
            'unique' => 'Email telah digunakan.',
            'mimes' => 'Format yang anda masukkan tidak sesuai'
        ];
        
        return $customMessages;
    }
}
