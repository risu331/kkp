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

class ViewStatistic4Test extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_statistic_4()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data data ikan
        $response = $this->actingAs($user)->get(route('dashboard.statistic.4.index'));
        
        // 3. pengguna berhasil mengakses halaman data ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.statistic.4.index');
    }

    public function test_user_can_get_data_statistic_4()
    {
        // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        $dateRange = '2023-04-6/2023-04-12';
        $category = 'weight_fish';

        $type_fish = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black',
            'dtkn' => 'token1'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman data ikan
        $response = $this->actingAs($user)->get('/dashboard/statistic/4?date=' . $dateRange . '&type_fish_id=' . $type_fish->dtkn . '&category=' . $category);

        // pemanggilan  data jenis ikan sesuai data token yang didapat
        $tf = TypeFish::where('dtkn', $type_fish)->first();
        $explodeDate = explode('/', $dateRange);

        $start_date = $explodeDate[0];
        $end_date = $explodeDate[1];
        $typeFishData = [];
        // variabel dengan value bebrapa array sesuai dengan kebutuhan data yang ingin ditampilkan
        $data = [
            'labels' => [],
            'colors' => [],
            'non-labels' => [],
            'non-colors' => [],
            'appendiks' => [],
            'non-appendiks' => [],
        ];
        // kondisi jika data jenis ikan ditemukan
        if($tf != null)
        {   
            // proses pemanggilan alat tangkap
            $fishinGears = FishingGear::orderBy('name')->get();
            // proses perulangan alat tangkap
            foreach($fishinGears as $fishinGear)
            {
                // proses pengelolaan data statistik berdasarkan alat tangkap dan status appendiks
                $appendiks = DataCollection::
                whereHas('species_fish', function($q) use($tf){
                    $q->where('type_fish_id', $tf->id);
                    $q->where('group', 'appendiks');
                })
                ->whereHas('fishing_data', function($q) use($start_date, $end_date, $fishinGear){
                    $q->where('fishing_gear_id', $fishinGear->id);
                    $q->whereDate('enumeration_time', '>=',  $start_date);
                    $q->whereDate('enumeration_time', '<=', $end_date);
                    $q->where('status', 'disetujui');
                })->sum($category);

                // proses pengelolaan data statistik berdasarkan alat tangkap dan status appendiks
                $nonAppendiks = DataCollection::
                whereHas('species_fish', function($q) use($tf){
                    $q->where('type_fish_id', $tf->id);
                    $q->where('group', 'non-appendiks');
                })
                ->whereHas('fishing_data', function($q) use($start_date, $end_date, $fishinGear){
                    $q->where('fishing_gear_id', $fishinGear->id);
                    $q->whereDate('enumeration_time', '>=',  $start_date);
                    $q->whereDate('enumeration_time', '<=', $end_date);
                    $q->where('status', 'disetujui');
                })->sum($category);
                
                // kondisi jika data appendiks lebih dari 0
                if($appendiks > 0)
                {
                    // proses penggabungan data kedalam array berdasarkan key yang telah ditentukan
                    array_push($data['appendiks'], $appendiks);
                    array_push($data['labels'], $fishinGear->name);
                    array_push($data['colors'], '#' . substr(md5(rand()), 0, 6));
                }
                // kondisi jika data non-appendiks lebih dari 0
                if($nonAppendiks > 0)
                {
                    // proses penggabungan data kedalam array berdasarkan key yang telah ditentukan
                    array_push($data['non-appendiks'], $nonAppendiks);
                    array_push($data['non-labels'], $fishinGear->name);
                    array_push($data['non-colors'], '#' . substr(md5(rand()), 0, 6));
                }
            }

            
        }
       
        // 4. berhasil melakukan pemanggilan data data ikan
        $response->assertSuccessful();
    }
    
}
