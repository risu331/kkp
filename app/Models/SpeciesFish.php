<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeciesFish extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function type_fish()
    {
        return $this->belongsTo('App\Models\TypeFish');
    }

    public function data_collections()
    {
        return $this->hasMany('App\Models\DataCollection');
    }
}
