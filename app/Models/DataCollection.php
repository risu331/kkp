<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCollection extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function fishing_data()
    {
        return $this->belongsTo('App\Models\FishingData');
    }

    public function species_fish()
    {
        return $this->belongsTo('App\Models\SpeciesFish');
    }

    public function data_images()
    {
        return $this->hasMany('App\Models\DataImage');
    }
}
