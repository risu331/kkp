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

class ViewStatistic3Test extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_statistic_3()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data data ikan
        $response = $this->actingAs($user)->get(route('dashboard.statistic.3.index'));
        
        // 3. pengguna berhasil mengakses halaman data ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.statistic.3.index');
    }

    public function test_user_can_get_data_statistic_3()
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
        $response = $this->actingAs($user)->get('/dashboard/statistic/3?date=' . $dateRange . '&type_fish_id=' . $type_fish->dtkn . '&category=' . $category);

        // pemanggilan  data jenis ikan sesuai data token yang didapat
        $tf = TypeFish::where('dtkn', $type_fish->dtkn)->first();
        $explodeDate = explode('/', $dateRange);

        $start_date = $explodeDate[0];
        $end_date = $explodeDate[1];
        $typeFishData = [];
        $data = [];
        // kondisi jika data jenis ikan dan lokasi pendaratan ditemukan
        if($tf != null)
        {
            // proses pengelolaan data statistik appendiks sesuai dengan filter yang ditentukan oleh pengguna
            $appendiks = DataCollection::
            whereHas('species_fish', function($q) use($tf){
                $q->where('type_fish_id', $tf->id);
                $q->where('group', 'appendiks');
            })
            ->whereHas('fishing_data', function($q) use($start_date, $end_date){
                $q->whereDate('enumeration_time', '>=',  $start_date);
                $q->whereDate('enumeration_time', '<=', $end_date);
                $q->where('status', 'disetujui');
            })
            ->sum($category);

            // proses pengelolaan data statistik  non-appendiks sesuai dengan filter yang ditentukan oleh pengguna
            $nonAppendiks = DataCollection::
            whereHas('species_fish', function($q) use($tf){
                $q->where('type_fish_id', $tf->id);
                $q->where('group', 'non-appendiks');
            })
            ->whereHas('fishing_data', function($q) use($start_date, $end_date){
                $q->whereDate('enumeration_time', '>=',  $start_date);
                $q->whereDate('enumeration_time', '<=', $end_date);
                $q->where('status', 'disetujui');
            })
            ->sum($category);

            // proses memasukkan data kedalam array
            array_push($data, $appendiks);
            array_push($data, $nonAppendiks);

        }
       
        // 4. berhasil melakukan pemanggilan data data ikan
        $response->assertSuccessful();
    }
    
}
