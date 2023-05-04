<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\DataCollection;
use App\Models\FishingData;
use App\Models\LandingSite;
use App\Models\FishingGear;
use App\Models\SpeciesFish;
use App\Models\TypeShip;
use App\Models\TypeFish;
use App\Models\Ship;
use Tests\TestCase;
use App\User;

class ViewStatistic6Test extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_statistic_6()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data data ikan
        $response = $this->actingAs($user)->get(route('dashboard.statistic.6.index'));
        
        // 3. pengguna berhasil mengakses halaman data ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.statistic.6.index');
    }

    public function test_user_can_get_data_statistic_6()
    {
        // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        $year = '2021';
        $arrayMonth = '01';

        $type_fish = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black',
            'dtkn' => 'token1'
        ]);

        $species_fish = SpeciesFish::create([
            'type_fish_id' => $type_fish->id,
            'species' => 'Hiu',
            'dtkn' => 'token1'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman data ikan
        $response = $this->actingAs($user)->get('/dashboard/statistic/6?year=' . $year . '&type_fish_id=' . $type_fish->dtkn . '&species_fish_id=' . $species_fish->dtkn. '&month=' . $arrayMonth);

        $tf = TypeFish::where('dtkn', $type_fish->dtkn)->first();
        $sf = SpeciesFish::where('dtkn', $species_fish->dtkn)->first();
        $data = [
            'line' => [],
            'month' => []
        ];
        if($tf != null)
        {
            $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            foreach($months as $month)
            {
                if($sf != null)
                {
                    $typeFishData = DataCollection::whereHas('species_fish', function($q1) use($sf){
                        $q1->where('id', $sf->id);
                    })
                    ->whereHas('fishing_data', function($q) use($year, $month){
                        $q->whereDate('enumeration_time', '>=', $year . '-' . $month . '-01');
                        $q->whereDate('enumeration_time', '<=', $year . '-' . $month . '-31');
                        $q->where('status', 'disetujui');
                    })
                    ->get();
                } else {
                    $typeFishData = DataCollection::whereHas('species_fish', function($q1) use($tf){
                        $q1->where('type_fish_id', $tf->id);
                    })
                    ->whereHas('fishing_data', function($q) use($year, $month){
                        $q->whereDate('enumeration_time', '>=', $year . '-' . $month . '-01');
                        $q->whereDate('enumeration_time', '<=', $year . '-' . $month . '-31');
                        $q->where('status', 'disetujui');
                    })
                    ->get();
                }

                $price = 0;
                foreach($typeFishData as $typeFish)
                {
                    $price += $typeFish->total_economy_price;
                }
                
                array_push($data['line'], $price);
                array_push($data['month'], date('F', strtotime('2023-' . $month)));
            }
        }
       
        // 4. berhasil melakukan pemanggilan data data ikan
        $response->assertSuccessful();
    }
    
}
