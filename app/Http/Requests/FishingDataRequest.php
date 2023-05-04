<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FishingDataRequest extends FormRequest
{
    /**
     * Determine if the fishing data is authorized to make this request.
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
            'ship_id' => 'required',
            'landing_site_id' => 'required',
            'fishing_gear_id' => 'required',
            'area' => 'required|max:100',
            'lat' => 'required',
            'lng' => 'required',
            'enumeration_time' => 'required',
            'gt' => 'required',
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
