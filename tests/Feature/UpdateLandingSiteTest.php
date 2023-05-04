<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\LandingSite;
use Tests\TestCase;
use App\User;

class UpdateLandingSiteTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_edit_landing_site()
    {
         // 1. generate 1 data pengguna dengan role admin   
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // proses pembuatan data dummy lokasi pendaratan untuk di edit
        $data = LandingSite::create([
            'name' => 'PPI MANGGAR BARU',
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah lokasi pendaratan
        $response = $this->actingAs($user)->get(route('dashboard.landing-site.edit', $data->dtkn));
        
        // 3. pengguna berhasil mengakses halaman ubah lokasi pendaratan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.landing-site.edit');
    }

    public function test_user_cannot_view_edit_landing_site()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        // proses pembuatan data dummy lokasi pendaratan untuk di edit
        $data = LandingSite::create([
            'name' => 'PPI MANGGAR BARU',
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah lokasi pendaratan
        $response = $this->actingAs($user)->get(route('dashboard.landing-site.edit', $data->dtkn));
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman ubah user
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.landing-site.edit');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_update_data_landing_site()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // proses pembuatan data dummy lokasi pendaratan untuk di edit
        $data = LandingSite::create([
            'name' => 'PPI MANGGAR BARU',
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah lokasi pendaratan
        $response = $this->actingAs($user)->get(route('dashboard.landing-site.edit', $data->dtkn));

        // 3. proses mengubah data lokasi pendaratan
        $data->update([
            'type' => 'New PPI MANGGAR BARU'
        ]);

        // 4. berhasil melakukan pengubahan data lokasi pendaratan
        $response->assertSuccessful();
    }
    
}
