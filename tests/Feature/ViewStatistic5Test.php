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

class ViewStatistic5Test extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_statistic_5()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data data ikan
        $response = $this->actingAs($user)->get(route('dashboard.statistic.5.index'));
        
        // 3. pengguna berhasil mengakses halaman data ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.statistic.5.index');
    }

    public function test_user_can_get_data_statistic_5()
    {
        // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        $dateRange = '2023-04-6/2023-04-12';

        $type_fish = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black',
            'dtkn' => 'token1'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman data ikan
        $response = $this->actingAs($user)->get('/dashboard/statistic/5?date=' . $dateRange . '&type_fish_id=' . $type_fish->dtkn);

        // pemanggilan  data jenis ikan sesuai data token yang didapat
        $tf = TypeFish::where('dtkn', $type_fish->dtkn)->first();
        $explodeDate = explode('/', $dateRange);

        $start_date = $explodeDate[0];
        $end_date = $explodeDate[1];
        // variabel dengan value bebrapa array sesuai dengan kebutuhan data yang ingin ditampilkan
        $data = [
            'gender' => [],
        ];
        // kondisi jika data jenis ikan ditemukan
        if($tf != null)
        {   
            // proses pengelolaan data statistik berdasarkan jenis kelamin jantan
            $male = DataCollection::
            whereHas('species_fish', function($q) use($tf){
                $q->where('type_fish_id', $tf->id);
            })
            ->where('gender', 'j')
            ->whereHas('fishing_data', function($q) use($start_date, $end_date){
                $q->whereDate('enumeration_time', '>=',  $start_date);
                $q->whereDate('enumeration_time', '<=', $end_date);
                $q->where('status', 'disetujui');
            })->sum('amount_fish');

            // proses pengelolaan data statistik berdasarkan jenis kelamin betina
            $female = DataCollection::
            whereHas('species_fish', function($q) use($tf){
                $q->where('type_fish_id', $tf->id);
            })
            ->where('gender', 'b')
            ->whereHas('fishing_data', function($q) use($start_date, $end_date){
                $q->whereDate('enumeration_time', '>=',  $start_date);
                $q->whereDate('enumeration_time', '<=', $end_date);
                $q->where('status', 'disetujui');
            })->sum('amount_fish');

            // proses penggabungan data kedalam array berdasarkan key yang telah ditentukan
            array_push($data['gender'], $male, $female);
        }
       
        // 4. berhasil melakukan pemanggilan data data ikan
        $response->assertSuccessful();
    }
    
}
