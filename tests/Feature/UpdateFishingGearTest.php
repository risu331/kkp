<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\FishingGear;
use Tests\TestCase;
use App\User;

class UpdateFishingGearTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_edit_fishing_gear()
    {
         // 1. generate 1 data pengguna dengan role admin   
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // proses pembuatan data dummy alat tangkap ikan untuk di edit
        $data = FishingGear::create([
            'name' => 'Long Line',
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah alat tangkap ikan
        $response = $this->actingAs($user)->get(route('dashboard.fishing-gear.edit', $data->dtkn));
        
        // 3. pengguna berhasil mengakses halaman ubah alat tangkap ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.fishing-gear.edit');
    }

    public function test_user_cannot_view_edit_fishing_gear()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        // proses pembuatan data dummy alat tangkap ikan untuk di edit
        $data = FishingGear::create([
            'name' => 'Long Line',
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah alat tangkap ikan
        $response = $this->actingAs($user)->get(route('dashboard.fishing-gear.edit', $data->dtkn));
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman ubah user
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.fishing-gear.edit');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_update_data_fishing_gear()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // proses pembuatan data dummy alat tangkap ikan untuk di edit
        $data = FishingGear::create([
            'name' => 'Long Line',
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah alat tangkap ikan
        $response = $this->actingAs($user)->get(route('dashboard.fishing-gear.edit', $data->dtkn));

        // 3. proses mengubah data alat tangkap ikan
        $data->update([
            'type' => 'New Long Line'
        ]);

        // 4. berhasil melakukan pengubahan data alat tangkap ikan
        $response->assertSuccessful();
    }
    
}
