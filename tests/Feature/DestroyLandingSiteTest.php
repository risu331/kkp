<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\LandingSite;
use Tests\TestCase;
use App\User;

class DestroyLandingSiteTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_delete_data_landing_site()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat lokasi pendaratan
        $response = $this->actingAs($user)->get(route('dashboard.landing-site.index'));

        // 3. proses pembuatan data lokasi pendaratan untuk dihapus
        $data = LandingSite::create([
            'name' => 'PPI MANGGAR BARU',
            'dtkn' => 'token'
         ]);

        // 4. pengguna melakukan hit url untuk menghapus data
        $this->post(route('dashboard.landing-site.destroy', $data->dtkn));
        
        // 5. proses penghapusan data
        $data->delete();
        
        // 6. berhasil melakukan penghapusan data
        $response->assertSuccessful();
    }
    
}
