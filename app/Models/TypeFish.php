<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeFish extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }

    public function species_fishs()
    {
        return $this->hasMany('App\Models\SpeciesFish');
    }

    public function appendiks_species_fishs()
    {
        return $this->hasMany('App\Models\SpeciesFish')->where('group', 'appendiks');
    }

    public function nonappendiks_species_fishs()
    {
        return $this->hasMany('App\Models\SpeciesFish')->where('group', 'non-appendiks');
    }

    public function type_fish_pictures()
    {
        return $this->hasMany('App\Models\TypeFishPicture');
    }
}
