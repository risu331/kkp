<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\TypeShip;
use Tests\TestCase;
use App\User;

class DestroyTypeShipTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_delete_data_type_ship()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat jenis kapal
        $response = $this->actingAs($user)->get(route('dashboard.type-ship.index'));

        // 3. proses pembuatan data jenis kapal untuk dihapus
        $data = TypeShip::create([
            'type' => 'Pengumpul',
            'dtkn' => 'token'
         ]);

        // 4. pengguna melakukan hit url untuk menghapus data user
        $this->post(route('dashboard.user.destroy', $data->dtkn));
        
        // 5. proses penghapusan data User
        $data->delete();
        
        // 6. berhasil melakukan penghapusan data user
        $response->assertSuccessful();
    }
    
}
