<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\FishingGear;
use Tests\TestCase;
use App\User;

class DestroyFishingGearTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_delete_data_type_ship()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat alat tangkap ikan
        $response = $this->actingAs($user)->get(route('dashboard.fishing-gear.index'));

        // 3. proses pembuatan data alat tangkap ikan untuk dihapus
        $data = FishingGear::create([
            'type' => 'Pengumpul',
            'dtkn' => 'token'
         ]);

        // 4. pengguna melakukan hit url untuk menghapus data
        $this->post(route('dashboard.user.destroy', $data->dtkn));
        
        // 5. proses penghapusan data
        $data->delete();
        
        // 6. berhasil melakukan penghapusan data
        $response->assertSuccessful();
    }
    
}
