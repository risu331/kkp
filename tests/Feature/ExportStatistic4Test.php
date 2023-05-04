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

class ExportStatistic4Test extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_export_data_statistic_4()
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

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman statistk 4
        $response = $this->actingAs($user)->get('/dashboard/statistic/4?date=' . $dateRange . '&type_fish_id=' . $type_fish->dtkn . '&category=' . $category);

        // pengguna melakukan hit url untuk mengekspor data
        $this->post('/dashboard/statistic/4/export?date=' . $dateRange . '&type_fish_id=' . $type_fish->dtkn . '&category=' . $category);

        // 4. berhasil melakukan pemanggilan data statistik 4
        $response->assertSuccessful();
    }
    
}
