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

class ViewStatistic7Test extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_statistic_7()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data data ikan
        $response = $this->actingAs($user)->get(route('dashboard.statistic.7.index'));
        
        // 3. pengguna berhasil mengakses halaman data ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.statistic.7.index');
    }

    public function test_user_can_get_data_statistic_7()
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
        $response = $this->actingAs($user)->get('/dashboard/statistic/7?year=' . $year . '&type_fish_id=' . $type_fish->dtkn . '&species_fish_id=' . $species_fish->dtkn. '&month=' . $arrayMonth);

        // pemanggilan  data jenis ikan sesuai data token yang didapat
        $tf = TypeFish::where('dtkn', $type_fish->dtkn)->first();
        // pemanggilan  data spesies ikan sesuai data token yang didapat
        $sf = SpeciesFish::where('dtkn', $species_fish->dtkn)->first();
        // variabel untuk menyimpan data dari masing-masing kategori panjang
        $born_start = $sf->born_start ?? 0;
        $born_end = $sf->born_end ?? 0;
        $mature_male_start = $sf->mature_male_start ?? 0;
        $mature_female_start = $sf->mature_female_start ?? 0;
        $mega_spawner = $sf->mega_spawner ?? 0;
        // variabel data yang memiliki value untuk keperluan data pada statistik
        $data = [
            'labels' => [
                'Born ' . $born_start . ' cm - ' . $born_end . ' cm (ekor)', 
                'Mature Male ' . $mature_male_start . ' cm - ' . $mega_spawner . ' cm (ekor)',
                'Mature Female ' . $mature_female_start . ' cm - ' . $mega_spawner . ' cm (ekor)',
                'Mega Spawner ' . $mega_spawner . ' cm (ekor)',
                'Tidak Terkategori (ekor)'
            ],
            'born' => [],
            'mature_male' => [],
            'mature_female' => [],
            'mega_spawner' => [],
            'uncategory' => [],
            'month' => []
        ];

        // kondisi jika data jenis data ditemukan
        if($tf != null)
        {
            $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            foreach($months as $month)
            {
                // proses pengelolaan data statistik berdasarkan kategori panjang born
                $born = DataCollection::whereHas('species_fish', function($q1) use($sf){
                    $q1->where('id', $sf->id);
                })
                ->where('tl', '>=', $sf->born_start ?? 0)
                ->where('tl', '<=', $sf->born_end ?? 0)
                ->whereHas('fishing_data', function($q) use($year, $month){
                    
                    $q->whereDate('enumeration_time', '>=', $year . '-' . $month . '-01');
                    $q->whereDate('enumeration_time', '<=', $year . '-' . $month . '-31');
                    $q->where('status', 'disetujui');
                })
                ->sum('amount_fish');
                // proses pengelolaan data statistik berdasarkan kategori panjang mature male
                $mature_male = DataCollection::whereHas('species_fish', function($q1) use($sf){
                    $q1->where('id', $sf->id);
                })
                ->where('tl', '>=', $sf->mature_male_start ?? 0)
                ->where('tl', '<', $sf->mega_spawner ?? 0)
                ->where('gender', 'j')
                ->whereHas('fishing_data', function($q) use($year, $month){
                    
                    $q->whereDate('enumeration_time', '>=', $year . '-' . $month . '-01');
                    $q->whereDate('enumeration_time', '<=', $year . '-' . $month . '-31');
                    $q->where('status', 'disetujui');
                })
                ->sum('amount_fish');
                // proses pengelolaan data statistik berdasarkan kategori panjang mature female
                $mature_female = DataCollection::whereHas('species_fish', function($q1) use($sf){
                    $q1->where('id', $sf->id);
                })
                ->where('tl', '>=', $sf->mature_female_start ?? 0)
                ->where('tl', '<', $sf->mega_spawner ?? 0)
                ->where('gender', 'b')
                ->whereHas('fishing_data', function($q) use($year, $month){
                    
                    $q->whereDate('enumeration_time', '>=', $year . '-' . $month . '-01');
                    $q->whereDate('enumeration_time', '<=', $year . '-' . $month . '-31');
                    $q->where('status', 'disetujui');
                })
                ->sum('amount_fish');
                // proses pengelolaan data statistik berdasarkan kategori panjang mega spawner
                $mega_spawner = DataCollection::whereHas('species_fish', function($q1) use($sf){
                    $q1->where('id', $sf->id);
                })
                ->where('tl', '>=', $sf->mega_spawner ?? 0)
                ->whereHas('fishing_data', function($q) use($year, $month){
                    
                    $q->whereDate('enumeration_time', '>=', $year . '-' . $month . '-01');
                    $q->whereDate('enumeration_time', '<=', $year . '-' . $month . '-31');
                    $q->where('status', 'disetujui');
                })
                ->sum('amount_fish');
                
                $mature_female = DataCollection::whereHas('species_fish', function($q1) use($sf){
                    $q1->where('id', $sf->id);
                })
                ->where('tl', '>=', $sf->mature_female_start ?? 0)
                ->where('tl', '<', $sf->mega_spawner ?? 0)
                ->where('gender', 'b')
                ->whereHas('fishing_data', function($q) use($year, $month){
                    
                    $q->whereDate('enumeration_time', '>=', $year . '-' . $month . '-01');
                    $q->whereDate('enumeration_time', '<=', $year . '-' . $month . '-31');
                    $q->where('status', 'disetujui');
                })
                ->sum('amount_fish');
                
                // proses pengelolaan data statistik berdasarkan kategori panjang tidak terkategori
                $uncategory = DataCollection::whereHas('species_fish', function($q1) use($sf){
                    $q1->where('id', $sf->id);
                })
                ->whereHas('fishing_data', function($q) use($year, $month){
                    
                    $q->whereDate('enumeration_time', '>=', $year . '-' . $month . '-01');
                    $q->whereDate('enumeration_time', '<=', $year . '-' . $month . '-31');
                    $q->where('status', 'disetujui');
                })
                ->sum('amount_fish');
                
                if($uncategory - $born - $mature_male - $mature_female - $mega_spawner > 0)
                {
                    array_push($data['uncategory'], $uncategory);    
                } else {
                    array_push($data['uncategory'], 0);
                }
                // proses penggabungan data ke dalam array
                array_push($data['born'], $born);
                array_push($data['mature_male'], $mature_male);
                array_push($data['mature_female'], $mature_female);
                array_push($data['mega_spawner'], $mega_spawner);
                array_push($data['month'], date('F', strtotime('2023-' . $month)));
            }
        }
       
        // 4. berhasil melakukan pemanggilan data data ikan
        $response->assertSuccessful();
    }
    
}
