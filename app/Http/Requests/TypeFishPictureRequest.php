<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypeFishPictureRequest extends FormRequest
{
    /**
     * Determine if the type fish picture is authorized to make this request.
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
            'title' => 'required|max:100',
            'type_fish_id' => 'required',
            'is_required' => 'required',
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
