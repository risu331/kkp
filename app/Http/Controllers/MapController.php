<?php

namespace App\Http\Controllers;

use App\Models\DataCollection;
use Illuminate\Http\Request;
use App\Models\SpeciesFish;
use App\Models\FishingData;
use App\Models\TypeFish;
use App\Models\Branch;
use Auth;
use DB;

class MapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $start_date = $request->start_date ?? date('Y-m-d');
        $end_date = $request->end_date ?? date('Y-m-d');

        $data['branches'] = Branch::with('type_fishs')->where('deleted_at', null)->orderBy('name')->get();
        
        $data['json'] = $this->jsonData(Auth::user()->role);

        $data['speciesArray'] = $request->checkbox ?? [];

        $data['dataCollection'] = $this->dataCollection($start_date, $end_date, $data['speciesArray']);
        // dd($data['dataCollection']);
        
        return view('dashboard.map.index', $data);
    }

    public function dataCollection($start_date, $end_date, $speciesArray)
    {
        $datas = DataCollection::with('fishing_data', 'species_fish.type_fish')->whereHas('fishing_data', function($q) use($start_date, $end_date){
            $q->whereDate('enumeration_time', '>=', $start_date)->whereDate('enumeration_time', '<=', $end_date);
            $q->where('status', 'disetujui');
        })->whereIn('species_fish_id', $speciesArray)
        ->get();

        $arrayIds = [];
        $arrayAreas = [];
        foreach($datas as $data)
        {
            $latitude = $data->lat;
            $longitude = $data->lng;
            $getDataNearby = DataCollection::
            select(DB::raw('*, ( 6371000 * acos( cos( radians('.$latitude.') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( lat ) ) ) ) AS distance'))
            ->with('fishing_data', 'species_fish.type_fish')->whereHas('fishing_data', function($q) use($start_date, $end_date){
                $q->whereDate('enumeration_time', '>=', $start_date)->whereDate('enumeration_time', '<=', $end_date);
                $q->where('status', 'disetujui');
            })
            ->whereIn('species_fish_id', $speciesArray)
            ->whereNotIn('id', $arrayIds)
            ->having('distance', '<', 500)
            ->orderBy('distance')
            ->get();

            $arrayGroup = [];
            foreach($getDataNearby as $key => $nearby)
            {
                if($key == 0)
                {
                    $array = [
                        'type_id' => $nearby->species_fish->type_fish_id,
                        'type_name' => $nearby->species_fish->type_fish->type,
                        'lat' => $nearby->fishing_data->lat,
                        'lng' => $nearby->fishing_data->lng,
                        'color' => $nearby->species_fish->type_fish->icon,
                        'radius' => 200,
                        'ship' => [
                            'name' => $nearby->fishing_data->ship->name,
                            'owner' => $nearby->fishing_data->ship->owner,
                            'fishing_gear' => $nearby->fishing_data->fishing_gear->name,
                            'species' => [
                                [
                                    'name' => $nearby->species_fish->species,
                                    'type_name' => $nearby->species_fish->type_fish->type,
                                    'weight' => $nearby->weight,
                                    'amount' => $nearby->amount_fish
                                ]
                            ]
                        ],
                    ];
                    array_push($arrayGroup, ['location' => $array]);
                    array_push($arrayIds, $nearby->id);
                } else {
                    $checkSpeciesAvailable = $this->array_recursive_search_key_map($nearby->species_fish->species, $arrayGroup);
                    if($checkSpeciesAvailable)
                    {
                    // dd($checkSpeciesAvailable);
                        $arrayGroup[$checkSpeciesAvailable[0]][$checkSpeciesAvailable[1]]['radius'] += 25 * $nearby->amount_fish;
                        $arrayGroup[$checkSpeciesAvailable[0]][$checkSpeciesAvailable[1]][$checkSpeciesAvailable[2]][$checkSpeciesAvailable[3]][$checkSpeciesAvailable[4]]['weight'] += $nearby->weight;
                        $arrayGroup[$checkSpeciesAvailable[0]][$checkSpeciesAvailable[1]][$checkSpeciesAvailable[2]][$checkSpeciesAvailable[3]][$checkSpeciesAvailable[4]]['amount'] += $nearby->amount_fish;
                        array_push($arrayIds, $nearby->id);
                    } else {
                        $array = [
                            'name' => $nearby->species_fish->species,
                            'type_name' => $nearby->species_fish->type_fish->type,
                            'weight' => $nearby->weight,
                            'amount' => $nearby->amount_fish
                        ];
                        array_push($arrayGroup[0]['location']['ship']['species'], $array);
                        array_push($arrayIds, $nearby->id);
                    }
                }
                    
            }
            if($arrayGroup != null)
            {
                array_push($arrayAreas, $arrayGroup);
            }
        }

        // dd($arrayAreas);

        return $arrayAreas;
    }

    public function array_recursive_search_key_map($needle, $haystack) {
        foreach($haystack as $first_level_key=>$value) {
            if ($needle === $value) {
                return array($first_level_key);
            } elseif (is_array($value)) {
                $callback = $this->array_recursive_search_key_map($needle, $value);
                if ($callback) {
                    return array_merge(array($first_level_key), $callback);
                }
            }
        }
        return false;
    }

    /**
    * Calculates the great-circle distance between two points, with
    * the Vincenty formula.
    * @param float $latitudeFrom Latitude of start point in [deg decimal]
    * @param float $longitudeFrom Longitude of start point in [deg decimal]
    * @param float $latitudeTo Latitude of target point in [deg decimal]
    * @param float $longitudeTo Longitude of target point in [deg decimal]
    * @param float $earthRadius Mean earth radius in [m]
    * @return float Distance between points in [m] (same as earthRadius)
    */
    public static function vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
    
        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
        pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);
    
        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }

    public function jsonData()
    {
        if(Auth::user()->role == 'superadmin')
        {
            $branches = Branch::with('type_fishs')->where('deleted_at', null)->orderBy('name')->get();
            $merged = collect($branches)->transform(function ($branch) {
                $mergedType = collect($branch->type_fishs)->transform(function ($type_fish) {
                    $mergedSpecies = collect($type_fish->species_fishs)->transform(function ($species_fish) {
                        return [
                            'id' => $species_fish->id,
                            'title' => $species_fish->species
                        ];
                    });

                    return [
                        'id' => $type_fish->id,
                        'title' => $type_fish->type,
                        'subs' => $mergedSpecies
                    ];                    

    
                });

                return [
                    'id' => $branch->id,
                    'title' => $branch->name,
                    'subs' => $mergedType
                ];                 

            });
    
            $data = json_decode($merged);
        } else {
            $type_fishes = TypeFish::with('species_fishs')->where('branch_id', Auth::user()->branch_id)->get();
                $merged = collect($type_fishes)->transform(function ($type_fish) {
                    $mergedSpecies = collect($type_fish->species_fishs)->transform(function ($species_fish) {
        
                        return [
                            'id' => $species_fish->id,
                            'title' => $species_fish->species,
                        ];                    
    
                    });
        
                    if(count($mergedSpecies) > 0)
                    {
                        return [
                            'id' => $type_fish->id,
                            'title' => $type_fish->type,
                            'subs' => $mergedSpecies
                        ];                 
                    } else {
                        return [
                            'id' => $type_fish->id,
                            'title' => $type_fish->type,
                        ];                  
                    }
                });
        
                $data = json_decode($merged);
        }
        
        return $data;
    }
}
