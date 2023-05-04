<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use App\Models\DataCollection;
use App\Models\SpeciesFish;
use App\Models\FishingData;
use App\Models\FishingGear;
use App\Models\LandingSite;
use App\Models\TypeShip;
use App\Models\TypeFish;
use App\Models\Ship;

class DataImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        // dd(rand(999999, 1));
        foreach ($rows as $row) 
        {
            // dd($row);
            if($row[0] != 'no' && $row[0] != null && $row[0] != 'No')
            {
                // dd($row);

                // 0
                $checkTypeShip = TypeShip::where('type', $row[24])->first();
                $checkShip = Ship::where('name', $row[23])->first();
                if($checkShip == null)
                {
                    $checkShip = Ship::create([
                       'type_ship_id' => $checkTypeShip->id,
                       'name' => $row[23],
                       'owner' => $row[23],
                       'dtkn' => sha1('#' . rand(999999, 1) . date('Y-m-d H:i'))
                    ]);
                }
                
                // 1
                $checkLanding = LandingSite::where('name', $row[4])->first();
                if($checkLanding == null)
                {
                    $checkLanding = LandingSite::create([
                       'branch_id' => 1,
                       'name' => $row[4],
                       'code' => '-',
                       'dtkn' => sha1('#' . rand(999999, 1) . date('Y-m-d H:i'))
                    ]);
                }
                
                // 2
                $checkFishingGear = FishingGear::where('name', $row[25])->first();
                if($checkFishingGear == null)
                {
                    $checkFishingGear = FishingGear::create([
                       'branch_id' => 1,
                       'name' => $row[25],
                       'dtkn' => sha1('#' . rand(999999, 1) . date('Y-m-d H:i'))
                    ]);
                }
                
                
                if($row[2] == 'Januari') {
                    $month = 1;
                } else if($row[2] == 'Februari') {
                    $month = 2;
                } else if($row[2] == 'Maret') {
                    $month = 3;
                } else if($row[2] == 'April') {
                    $month = 4;
                } else if($row[2] == 'Mei') {
                    $month = 5;
                } else if($row[2] == 'Juni') {
                    $month = 6;
                } else if($row[2] == 'Juli') {
                    $month = 7;
                } else if($row[2] == 'Agustus') {
                    $month = 8;
                } else if($row[2] == 'September') {
                    $month = 9;
                } else if($row[2] == 'Oktober') {
                    $month =10;
                } else if($row[2] == 'November') {
                    $month = 11;
                } else if($row[2] == 'Desember') {
                    $month = 12;
                }
                
                $combineDate = $row[3] . '-' . $month . '-' . $row[1];
                $enumerationDate = date('Y-m-d', strtotime($combineDate));
                
                $checkFishingDataDate = FishingData::where('enumeration_time', $enumerationDate)->where('ship_id', $checkShip->id)->first();
                
                if($checkFishingDataDate == null)
                {
                    $fishingData = FishingData::create([
                       'branch_id' => 1,
                       'user_id' => 6,
                       'ship_id' => $checkShip->id,
                       'landing_site_id' => $checkLanding->id,
                       'fishing_gear_id' => $checkFishingGear->id,
                       'user_name' => $row[6],
                       'operational_day' => $row[28] ?? 0,
                       'travel_day' => $row[29] ?? 0,
                       'setting' => $row[30] ?? 0,
                       'area' => $row[27],
                       'lat' => 0,
                       'lng' => 0,
                       'flat' => 0,
                       'flng' => 0,
                       'miles' => 0,
                       'enumeration_time' => $enumerationDate,
                       'gt' => $row[26] ?? 0,
                       'total_other_fish' => $row[37] ?? 0,
                       'is_htu' => 0,
                       'status' => 'disetujui',
                       
                       'dtkn' => sha1('#' . rand(999999, 1) . date('Y-m-d H:i')),
                    ]);
                    
                    // 0
                    $explodeType = explode(' ', $row[7]);
                    $checkTypeFish = TypeFish::where('type', 'like', '%' . $explodeType[0] . '%')->first();
                    $checkSpecies = SpeciesFish::where('type_fish_id', $checkTypeFish->id ?? 2)->where('species', $row[9])->where('local', $row[7])->where('general', $row[8])->first();
                    
                    if($checkSpecies == null)
                    {
                        $checkSpecies = SpeciesFish::create([
                           'type_fish_id' => $checkTypeFish->id ?? 2,
                           'species' => $row[9],
                           'local' => $row[7],
                           'general' => $row[8],
                           'group' => 'non-appendiks',
                           
                           'dtkn' => sha1('#' . rand(999999, 1) . date('Y-m-d H:i')),
                        ]);
                    }
                    
                    if($row[20] == null)
                    {
                        $gender = 'j';
                    } else {
                        if($row[20] == 'Jantan')
                        {   
                            $gender = 'j';
                        } else {
                            $gender = 'b';
                        }
                    }
                    
                    DataCollection::create([
                       'fishing_data_id' => $fishingData->id,
                       'species_fish_id' => $checkSpecies->id,
                       'amount_fish' => $row[18],
                       'weight' => $row[19],
                       'amount_fish' => $row[18],
                       'gender' => $gender,
                       'clasp_length' => $row[21] ?? 0,
                       'gonad' => $row[22] ?? 0,
                       'amount_child' => 0,
                       'length_min_child' => 0,
                       'length_max_child' => 0,
                       'weight_child' => 0,
                       'sl' => $row[10] ?? 0,
                       'fl' => $row[11] ?? 0,
                       'tl' => $row[12] ?? 0,
                       'dw' => $row[13] ?? 0,
                       'm' => $row[14] ?? 0,
                       'p' => $row[15] ?? 0,
                       't' => $row[16] ?? 0,
                       'mp' => $row[17] ?? 0,
                       'economy_price' => $row[39] ?? 0,
                       'total_economy_price' => $row[19] * $row[39],
                       'description' => $row[40],
                       'status' => 'disetujui',
                       'dtkn' => sha1('#' . rand(999999, 1) . date('Y-m-d H:i')),
                    ]);
                } else {
                    // 0
                    $explodeType = explode(' ', $row[7]);
                    $checkTypeFish = TypeFish::where('type', 'like', '%' . $explodeType[0] . '%')->first();
                    $checkSpecies = SpeciesFish::where('type_fish_id', $checkTypeFish->id ?? 2)->where('species', $row[9])->where('local', $row[7])->where('general', $row[8])->first();
                    
                    if($checkSpecies == null)
                    {
                        $checkSpecies = SpeciesFish::create([
                           'type_fish_id' => $checkTypeFish->id ?? 2,
                           'species' => $row[9],
                           'local' => $row[7],
                           'general' => $row[8],
                           'group' => 'non-appendiks',
                           
                           'dtkn' => sha1('#' . rand(999999, 1) . date('Y-m-d H:i')),
                        ]);
                    }
                    
                    if($row[20] == null)
                    {
                        $gender = 'j';
                    } else {
                        if($row[20] == 'Jantan' || $row[20] == 'J')
                        {   
                            $gender = 'j';
                        } else {
                            $gender = 'b';
                        }
                    }
                    
                    $checkDataCollection = DataCollection::where('fishing_data_id', $checkFishingDataDate->id)
                    ->where('species_fish_id', $checkSpecies->id)
                    ->where('amount_fish', $row[18])
                    ->where('weight', $row[19])
                    ->where('gender', $gender)
                    ->where('clasp_length', $row[21] ?? 0)
                    ->where('gonad', $row[22] ?? 0)
                    ->where('sl', $row[10] ?? 0)
                    ->where('fl', $row[11] ?? 0)
                    ->where('tl', $row[12] ?? 0)
                    ->where('dw', $row[13] ?? 0)
                    ->where('m', $row[14] ?? 0)
                    ->where('p', $row[15] ?? 0)
                    ->where('t', $row[16] ?? 0)
                    ->where('mp', $row[17] ?? 0)
                    ->where('economy_price', $row[39] ?? 0)
                    ->where('total_economy_price', $row[19] * $row[39])
                    ->where('description', $row[40])
                    ->first();
                    
                    // if($checkDataCollection == null)
                    // {
                        DataCollection::create([
                           'fishing_data_id' => $checkFishingDataDate->id,
                           'species_fish_id' => $checkSpecies->id,
                           'amount_fish' => $row[18],
                           'weight' => $row[19],
                           'gender' => $gender,
                           'clasp_length' => $row[21] ?? 0,
                           'gonad' => $row[22] ?? 0,
                           'amount_child' => 0,
                           'length_min_child' => 0,
                           'length_max_child' => 0,
                           'weight_child' => 0,
                           'sl' => $row[10] ?? 0,
                           'fl' => $row[11] ?? 0,
                           'tl' => $row[12] ?? 0,
                           'dw' => $row[13] ?? 0,
                           'm' => $row[14] ?? 0,
                           'p' => $row[15] ?? 0,
                           't' => $row[16] ?? 0,
                           'mp' => $row[17] ?? 0,
                           'economy_price' => $row[39] ?? 0,
                           'total_economy_price' => $row[19] * $row[39],
                           'description' => $row[40],
                           'status' => 'disetujui',
                           'dtkn' => sha1('#' . rand(999999, 1) . date('Y-m-d H:i')),
                        ]);
                    // }
                }
            }
            
        }
    }
}
