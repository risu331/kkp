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

class UpdateDataCollectionTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_edit_data_collection()
    {
         // 1. generate 1 data pengguna dengan role admin   
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        $type_ship = TypeShip::create([
            'type' => 'Pengumpul'
        ]);

        $ship = Ship::create([
            'type_ship_id' => $type_ship->id,
            'name' => 'Nama Kapal',
            'owner' => 'Nama Pemilik',
        ]);

        $data_collection = LandingSite::create([
            'name' => 'PPI MANGGAR BARU'
        ]);

        $fishing_gear = FishingGear::create([
            'name' => 'Long Line'
        ]);

        // 3. proses pembuatan data data ikan
        $fishing_data = FishingData::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'ship_id' => $ship->id,
            'data_collection_id' => $data_collection->id,
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

        $type_fish = TypeFish::create([
            'type' => 'Hiu'
        ]);

        $species_fish = SpeciesFish::create([
            'type_fish_id' => $type_fish->id,
            'species' => 'Hiu Batu'
        ]);

        $data  = DataCollection::create([
            'fishing_data_id' => $fishing_data->id,
            'species_fish_id' => $species_fish->id,
            'amount' => 1,
            'weight' => 100,
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah data ikan
        $response = $this->actingAs($user)->get(route('dashboard.fishing-data.data-collection.edit', $data->dtkn));
        
        // 3. pengguna berhasil mengakses halaman ubah data ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.fishing-data.data-collection.edit');
    }

    public function test_user_cannot_view_edit_data_collection()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        $type_ship = TypeShip::create([
            'type' => 'Pengumpul'
        ]);

        $ship = Ship::create([
            'type_ship_id' => $type_ship->id,
            'name' => 'Nama Kapal',
            'owner' => 'Nama Pemilik',
        ]);

        $data_collection = LandingSite::create([
            'name' => 'PPI MANGGAR BARU'
        ]);

        $fishing_gear = FishingGear::create([
            'name' => 'Long Line'
        ]);

        // 3. proses pembuatan data data ikan
        $fishing_data = FishingData::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'ship_id' => $ship->id,
            'data_collection_id' => $data_collection->id,
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

        $type_fish = TypeFish::create([
            'type' => 'Hiu'
        ]);

        $species_fish = SpeciesFish::create([
            'type_fish_id' => $type_fish->id,
            'species' => 'Hiu Batu'
        ]);

        $data  = DataCollection::create([
            'fishing_data_id' => $fishing_data->id,
            'species_fish_id' => $species_fish->id,
            'amount' => 1,
            'weight' => 100
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah data ikan
        $response = $this->actingAs($user)->get(route('dashboard.fishing-data.data-collection.edit', $data->id));
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman ubah user
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.fishing-data.data-collection.edit');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_update_data_data_collection()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        $type_ship = TypeShip::create([
            'type' => 'Pengumpul'
        ]);

        $ship = Ship::create([
            'type_ship_id' => $type_ship->id,
            'name' => 'Nama Kapal',
            'owner' => 'Nama Pemilik',
        ]);

        $data_collection = LandingSite::create([
            'name' => 'PPI MANGGAR BARU'
        ]);

        $fishing_gear = FishingGear::create([
            'name' => 'Long Line'
        ]);

        // 3. proses pembuatan data data ikan
        $fishing_data = FishingData::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'ship_id' => $ship->id,
            'data_collection_id' => $data_collection->id,
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

        $type_fish = TypeFish::create([
            'type' => 'Hiu'
        ]);

        $species_fish = SpeciesFish::create([
            'type_fish_id' => $type_fish->id,
            'species' => 'Hiu Batu'
        ]);

        $data  = DataCollection::create([
            'fishing_data_id' => $fishing_data->id,
            'species_fish_id' => $species_fish->id,
            'amount' => 1,
            'weight' => 100,
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah data ikan
        $response = $this->actingAs($user)->get(route('dashboard.fishing-data.data-collection.edit', $data->dtkn));

        // 3. proses mengubah data data ikan
        $data->update([
            'amount' => 30,
            'weight' => 50
        ]);

        // 4. berhasil melakukan pengubahan data data ikan
        $response->assertSuccessful();
    }
    
}
