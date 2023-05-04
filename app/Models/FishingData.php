<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FishingData extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }

    public function ship()
    {
        return $this->belongsTo('App\Models\Ship');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function landing_site()
    {
        return $this->belongsTo('App\Models\LandingSite');
    }

    public function fishing_gear()
    {
        return $this->belongsTo('App\Models\FishingGear');
    }

    public function data_collections()
    {
        return $this->hasMany('App\Models\DataCollection');
    }
}
