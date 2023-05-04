<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\FishingData;
use App\Models\LandingSite;
use App\Models\FishingGear;
use App\Models\TypeShip;
use App\Models\Ship;
use Tests\TestCase;
use App\User;

class DestroyFishingDataTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_delete_data_fishing_data()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat pendataan ikan
        $response = $this->actingAs($user)->get(route('dashboard.fishing-data.index'));

        // 3. proses pembuatan data pendataan ikan untuk dihapus
        $type_ship = TypeShip::create([
            'type' => 'Pengumpul'
        ]);

        $ship = Ship::create([
            'type_ship_id' => $type_ship->id,
            'name' => 'Nama Kapal',
            'owner' => 'Nama Pemilik',
        ]);

        $fishing_data = LandingSite::create([
            'name' => 'PPI MANGGAR BARU'
        ]);

        $fishing_gear = FishingGear::create([
            'name' => 'Long Line'
        ]);

        // 3. proses pembuatan data pendataan ikan
        $data = FishingData::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'ship_id' => $ship->id,
            'fishing_data_id' => $fishing_data->id,
            'fishing_gear_id' => $fishing_gear->id,
            'operational_day' => 0,
            'travel_day' => 0,
            'setting' => 0,
            'area' => 'balikpapan',
            'lat' => 0,
            'lng' => 0,
            'flat' => 0,
            'flng' => 0,
            'miles' => 0,
            'enumeration_time' => 3,
            'gt' => 5,
            'total_other_fish' => 100,
            'is_htu' => 1,
            'status' => 'menunggu persetujuan',
            'dtkn' => 'token'
         ]);

        // 4. pengguna melakukan hit url untuk menghapus data
        $this->post(route('dashboard.fishing-data.destroy', $data->dtkn));
        
        // 5. proses penghapusan data
        $data->delete();
        
        // 6. berhasil melakukan penghapusan data
        $response->assertSuccessful();
    }
    
}
