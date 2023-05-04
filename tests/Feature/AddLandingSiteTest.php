<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\LandingSite;
use Tests\TestCase;
use App\User;

class AddLandingSiteTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_create_landing_site()
    {
         // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah lokasi pendaratan
        $response = $this->actingAs($user)->get(route('dashboard.landing-site.create'));
        
        // 3. pengguna berhasil mengakses halaman tambah lokasi pendaratan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.landing-site.create');
    }

    public function test_user_cannot_view_create_landing_site()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah lokasi pendaratan
        $response = $this->actingAs($user)->get(route('dashboard.landing-site.create'));
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman tambah lokasi pendaratan
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.landing-site.create');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_store_data_landing_site()
    {
        // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah lokasi pendaratan
        $response = $this->actingAs($user)->get(route('dashboard.landing-site.create'));

        // 3. proses pembuatan data lokasi pendaratan
        $data = LandingSite::create([
            'name' => 'PPI MANGGAR BARU'
         ]);

        // 4. berhasil melakukan pemanggilan data lokasi pendaratan
        $response->assertSuccessful();
    }
    
}
