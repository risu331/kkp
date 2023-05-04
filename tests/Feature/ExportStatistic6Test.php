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

class ExportStatistic6Test extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_export_data_statistic_6()
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

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman statistik 6
        $response = $this->actingAs($user)->get('/dashboard/statistic/6?year=' . $year . '&type_fish_id=' . $type_fish->dtkn . '&species_fish_id=' . $species_fish->dtkn. '&month=' . $arrayMonth);

        // pengguna melakukan hit url untuk mengekspor data
        $this->post('/dashboard/statistic/6/export?year=' . $year . '&type_fish_id=' . $type_fish->dtkn . '&species_fish_id=' . $species_fish->dtkn. '&month=' . $arrayMonth);

        // 4. berhasil melakukan pemanggilan data statistik 6
        $response->assertSuccessful();
    }
    
}
