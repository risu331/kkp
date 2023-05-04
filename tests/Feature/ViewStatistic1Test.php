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

class ViewStatistic1Test extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_statistic_1()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data data ikan
        $response = $this->actingAs($user)->get(route('dashboard.statistic.1.index'));
        
        // 3. pengguna berhasil mengakses halaman data ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.statistic.1.index');
    }

    public function test_user_can_get_data_statistic_1()
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

        $landingSite = LandingSite::create([
            'name' => 'PPI MANGGAR BARU',
            'dtkn' => 'token2'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman data ikan
        $response = $this->actingAs($user)->get('/dashboard/statistic/1?date=' . $dateRange . '&landing_site_id=' . $landingSite->dtkn . '&type_fish_id=' . $type_fish->dtkn . '&category=' . $category);

        // pemanggilan  data jenis ikan sesuai data token yang didapat
        $tf = TypeFish::where('dtkn', $type_fish->dtkn)->first();
        // pemanggilan  data lokasi pendaratan sesuai data token yang didapat
        $ls = LandingSite::where('dtkn', $landingSite->dtkn)->first();
        
        $explodeDate = explode('/', $dateRange);

        $start_date = $explodeDate[0];
        $end_date = $explodeDate[1];

        $speciesFishes = [];
        // kondisi jika data jenis ikan dan lokasi pendaratan ditemukan
        if($tf != null && $ls != null)
        {
            // proses pengelolaan data statistik sesuai dengan filter yang ditentukan oleh pengguna
            $speciesFishes = SpeciesFish::
            with(['data_collections' => function($q) use($start_date, $end_date, $ls){
                // proses filter pada pendataan ikan, sesuai dengan lokasi pendaratan, date range, dan status yang terverifikasi oleh admin
                $q->whereHas('fishing_data', function($q1) use($start_date, $end_date, $ls){
                    $q1->where('landing_site_id', $ls->id);
                    $q1->whereDate('enumeration_time', '>=', $start_date);
                    $q1->whereDate('enumeration_time', '<=', $end_date);
                    $q1->where('status', 'disetujui');
                });
            }])
            ->where('type_fish_id', $tf->id)
            ->get()
            ->map(function($data, $key) use($category)
            {		
                // proses pembuatan kolom baru pada collection data sesuai dengan kategori satuan yang dipilih pengguna, hal ini untuk memudahkan dalam pengelolaan data statistik
                if($category == 'amount_fish')
                {
                    $data['amount_fish'] = $data->data_collections->sum('amount_fish');
                } else {
                    $data['amount_fish'] = $data->data_collections->sum('weight');
                }
                $data['color'] = '#' . substr(md5(rand()), 0, 6);
                return $data;
            })->sortByDesc('amount_fish')->take(10);
        }

        // variabel data dengan 3 index array didalamnya
        $data = [
            'labels' => [],
            'colors' => [],
            'datas' => []
        ];

        // proses penggabungan data menuju array, agar data yang dibawa tidak banyak
        foreach($speciesFishes as $key => $speciesFish)
        {
            // kondisi jika jumlah ikan diatas 0
            if($speciesFish->amount_fish > 0)
            {
                // proses memasukkan data kedalam array
                array_push($data['labels'], $speciesFish->species);
                array_push($data['colors'], $speciesFish->color);
                array_push($data['datas'], $speciesFish->amount_fish);
            }
        }

        // 4. berhasil melakukan pemanggilan data data ikan
        $response->assertSuccessful();
    }
    
}
