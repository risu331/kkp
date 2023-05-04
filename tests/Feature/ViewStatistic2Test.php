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

class ViewStatistic2Test extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_statistic_2()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data data ikan
        $response = $this->actingAs($user)->get(route('dashboard.statistic.2.index'));
        
        // 3. pengguna berhasil mengakses halaman data ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.statistic.2.index');
    }

    public function test_user_can_get_data_statistic_2()
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

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman data ikan
        $response = $this->actingAs($user)->get('/dashboard/statistic/2?year' . $year . '&month=' . $arrayMonth . '&type_fish_id=' . $type_fish->dtkn);
       
        // pemanggilan  data jenis ikan sesuai data token yang didapat
        $tf = TypeFish::where('dtkn', $type_fish->dtkn)->first();
        $typeFishData = [];
        // variabel data dengan 3 index array didalamnya
        $data = [
            'bar' => [],
            'line' => [],
            'month' => []
        ];
        // kondisi jika data jenis ikan ditemukan
        if($tf != null)
        {
            $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            // proses perulangan data sesuai dengan array bulan dari variabel months
            foreach($months as $month)
            {
                // proses pengelolaan data statistik sesuai dengan filter yang ditentukan oleh pengguna
                $typeFishData = DataCollection::
                whereHas('species_fish', function($q) use($tf){
                    $q->where('type_fish_id', $tf->id);
                })
                // proses filter pada pendataan ikan, bulan, tahun, dan status yang terverifikasi oleh admin
                ->whereHas('fishing_data', function($q) use($year, $month){
                    $q->whereDate('enumeration_time', '>=', $year .'-' . $month . '-01');
                    $q->whereDate('enumeration_time', '<=', $year .'-' . $month . '-31');
                    $q->where('status', 'disetujui');
                })
                ->get();  

                $weight = 0;
                $amountFish = 0;
                // proses penjumlahan dari hasil  total berat dan total individu data
                foreach($typeFishData as $typeFish)
                {
                    $weight += $typeFish->weight;
                    $amountFish += $typeFish->amount_fish;
                }
                // proses memasukkan data kedalam array
                array_push($data['bar'], $weight);
                array_push($data['line'], $amountFish);
                array_push($data['month'], date('F', strtotime('2023-' . $month)));
            }

        }

        // 4. berhasil melakukan pemanggilan data data ikan
        $response->assertSuccessful();
    }
    
}
