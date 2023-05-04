<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\LandingSite;
use Tests\TestCase;
use App\User;

class ViewLandingSiteTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_landing_site()
    {
         // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data lokasi pendaratan
        $response = $this->actingAs($user)->get(route('dashboard.landing-site.index'));
        
        // 3. pengguna berhasil mengakses halaman lokasi pendaratan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.landing-site.index');
    }

    public function test_user_cannot_view_landing_site()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data lokasi pendaratan
        $response = $this->actingAs($user)->get(route('dashboard.landing-site.index'));
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman lokasi pendaratan
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.landing-site.index');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_get_data_landing_site()
    {
        // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lokasi pendaratan
        $response = $this->actingAs($user)->get(route('dashboard.landing-site.index'));

        // 3. pemanggilan data lokasi pendaratan
        $data = LandingSite::get();

        // 4. berhasil melakukan pemanggilan data lokasi pendaratan
        $response->assertSuccessful();
    }
    
}
