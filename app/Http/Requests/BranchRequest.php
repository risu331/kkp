<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
{
    /**
     * Determine if the branch is authorized to make this request.
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
        $rules = [
            'name' => 'required|max:100',
            'lat' => 'required',
            'lng' => 'required',
        ];
        
        return $rules;
    }
    
    public function messages() {
        $customMessages = [
            'required' => 'Kolom wajib di isi.',
            'max' => 'Panjang text tidak boleh lebih dari 100 karakter.',
        ];
        
        return $customMessages;
    }
}
