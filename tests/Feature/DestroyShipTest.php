<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\TypeShip;
use App\Models\Ship;
use Tests\TestCase;
use App\User;

class DestroyShipTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_delete_data_ship()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        
        // proses pembuatan data jenis kapal untuk dihapus
        $typeShip = TypeShip::create([
            'type' => 'Pengumpul',
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat kapal
        $response = $this->actingAs($user)->get(route('dashboard.ship.index', ['id' => $typeShip->dtkn]));
         
         // 3. proses pembuatan data kapal untuk dihapus
         $data = Ship::create([
            'type_ship_id' => $typeShip->id,
            'name' => 'Nama Kapal',
            'owner' => 'Nama Pemilik',
            'dtkn' => 'token'
         ]);

        // 4. pengguna melakukan hit url untuk menghapus data kapal
        $this->post(route('dashboard.ship.destroy', $data->dtkn));
        
        // 5. proses penghapusan data kapal
        $data->delete();
        
        // 6. berhasil melakukan penghapusan data kapal
        $response->assertSuccessful();
    }
    
}
