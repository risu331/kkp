<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpeciesFish;

class ApiSpeciesFishController extends Controller
{
    public function getSpeciesFish(Request $request)
    {
        $data['species_fish'] = SpeciesFish::whereHas('type_fish', function($q) use($request){
            $q->where('dtkn', $request->id);
        })->orderBy('species')->get();
        
        return response()->json($data);
    }
}
