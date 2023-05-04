<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\DataCollection;
use App\Models\SpeciesFish;
use App\Models\FishingData;
use App\Models\TypeFish;
use App\Models\Branch;
use Tests\TestCase;
use App\User;
use Auth;
use DB;

class DetailMapTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_map()
    {
         // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data kapal dengan membawa data token jenis kapal
        $response = $this->actingAs($user)->get('/dashboard/map');

        // 3. pengguna berhasil mengakses halaman kapal
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.map.index');
    }

    public function test_user_can_get_data_detail_map()
    {
        // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data kapal dengan membawa data token jenis kapal
        $response = $this->actingAs($user)->get('/dashboard/map');

        $type_fish = TypeFish::create([
            'type' => 'Hiu'
        ]);

        $speciesArray = SpeciesFish::create([
            'type_fish_id' => $type_fish->id,
            'species' => 'test',
            'dtkn' => 'token'
        ]);

        $start_date = date('Y-m-d');
        $end = date('Y-m-d');

        // proses pemanggilan data pendataan ikan berdasarkan date range dan spesies ikan yang dipilih
        $datas = DataCollection::with('fishing_data', 'species_fish.type_fish')->whereHas('fishing_data', function($q) use($start_date, $end_date){
            $q->whereDate('enumeration_time', '>=', $start_date)->whereDate('enumeration_time', '<=', $end_date);
            $q->where('status', 'disetujui');
        })->whereIn('species_fish_id', $speciesArray)
        ->get();

        $arrayIds = [];
        $arrayAreas = [];
        // proses pengelolaan data berdasarkan radius dibawah 500 meter, maka data akan digabungkan
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
                        'species' => [
                            [
                                'name' => $nearby->species_fish->species,
                                'type_name' => $nearby->species_fish->type_fish->type,
                                'pt' => $nearby->pt,
                                'ps' => $nearby->ps,
                                'lt' => $nearby->lt,
                                'weight' => $nearby->weight,
                                // 'gender' => $data->gender,
                                // 'clasp_length' => $data->clasp_length,
                                // 'gonad' => $data->gonad ?? 1,
                                'amount' => $nearby->amount_fish
                            ]
                        ]
                    ];
                    array_push($arrayGroup, ['location' => $array]);
                    array_push($arrayIds, $nearby->id);
                } else {
                    $checkSpeciesAvailable = $this->array_recursive_search_key_map($nearby->species_fish->species, $arrayGroup);
                    if($checkSpeciesAvailable)
                    {
                        $arrayGroup[$checkSpeciesAvailable[0]][$checkSpeciesAvailable[1]]['radius'] += 50;
                        $arrayGroup[$checkSpeciesAvailable[0]][$checkSpeciesAvailable[1]][$checkSpeciesAvailable[2]][$checkSpeciesAvailable[3]]['weight'] += $data->weight * $data->amount_fish;
                        $arrayGroup[$checkSpeciesAvailable[0]][$checkSpeciesAvailable[1]][$checkSpeciesAvailable[2]][$checkSpeciesAvailable[3]]['amount'] += $data->amount_fish;
                        $arrayGroup[$checkSpeciesAvailable[0]][$checkSpeciesAvailable[1]][$checkSpeciesAvailable[2]][$checkSpeciesAvailable[3]]['pt'] = ($arrayGroup[$checkSpeciesAvailable[0]][$checkSpeciesAvailable[1]][$checkSpeciesAvailable[2]][$checkSpeciesAvailable[3]]['pt'] + $data->pt)/$arrayGroup[$checkSpeciesAvailable[0]][$checkSpeciesAvailable[1]][$checkSpeciesAvailable[2]][$checkSpeciesAvailable[3]]['amount'];
                        $arrayGroup[$checkSpeciesAvailable[0]][$checkSpeciesAvailable[1]][$checkSpeciesAvailable[2]][$checkSpeciesAvailable[3]]['ps'] = ($arrayGroup[$checkSpeciesAvailable[0]][$checkSpeciesAvailable[1]][$checkSpeciesAvailable[2]][$checkSpeciesAvailable[3]]['ps'] + $data->ps)/$arrayGroup[$checkSpeciesAvailable[0]][$checkSpeciesAvailable[1]][$checkSpeciesAvailable[2]][$checkSpeciesAvailable[3]]['amount'];
                        $arrayGroup[$checkSpeciesAvailable[0]][$checkSpeciesAvailable[1]][$checkSpeciesAvailable[2]][$checkSpeciesAvailable[3]]['lt'] = ($arrayGroup[$checkSpeciesAvailable[0]][$checkSpeciesAvailable[1]][$checkSpeciesAvailable[2]][$checkSpeciesAvailable[3]]['lt'] + $data->lt)/$arrayGroup[$checkSpeciesAvailable[0]][$checkSpeciesAvailable[1]][$checkSpeciesAvailable[2]][$checkSpeciesAvailable[3]]['amount'];
                        array_push($arrayIds, $nearby->id);
                    } else {
                        $array = [
                            'name' => $nearby->species_fish->species,
                            'type_name' => $nearby->species_fish->type_fish->type,
                            'pt' => $nearby->pt,
                            'ps' => $nearby->ps,
                            'lt' => $nearby->lt,
                            'weight' => $nearby->weight,
                            'amount' => $nearby->amount_fish
                        ];
                        array_push($arrayGroup[0]['location']['species'], $array);
                        array_push($arrayIds, $nearby->id);
                    }
                }
                    
            }
            if($arrayGroup != null)
            {
                array_push($arrayAreas, $arrayGroup);
            }
        }

        // 4. berhasil melakukan pemanggilan data kapal
        $response->assertSuccessful();
    }
    
}
