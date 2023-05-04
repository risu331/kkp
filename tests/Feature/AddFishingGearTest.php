<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\FishingGear;
use Tests\TestCase;
use App\User;

class AddFishingGearTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_create_fishing_gear()
    {
         // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah alat tangkap ikan
        $response = $this->actingAs($user)->get(route('dashboard.fishing-gear.create'));
        
        // 3. pengguna berhasil mengakses halaman tambah alat tangkap ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.fishing-gear.create');
    }

    public function test_user_cannot_view_create_fishing_gear()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah alat tangkap ikan
        $response = $this->actingAs($user)->get(route('dashboard.fishing-gear.create'));
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman tambah alat tangkap ikan
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.fishing-gear.create');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_store_data_fishing_gear()
    {
        // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah alat tangkap ikan
        $response = $this->actingAs($user)->get(route('dashboard.fishing-gear.create'));

        // 3. proses pembuatan data alat tangkap ikan
        $data = FishingGear::create([
            'name' => 'Long Line'
         ]);

        // 4. berhasil melakukan pemanggilan data alat tangkap ikan
        $response->assertSuccessful();
    }
    
}
