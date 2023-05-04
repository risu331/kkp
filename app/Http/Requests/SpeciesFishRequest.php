<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpeciesFishRequest extends FormRequest
{
    /**
     * Determine if the species fish is authorized to make this request.
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
            'type_fish_id' => 'required',
            'group' => 'required|max:100',
            'species' => 'required|max:100',
            'local' => 'required|max:100',
            'general' => 'required|max:100',
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
