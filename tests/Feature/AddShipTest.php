<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\TypeShip;
use App\Models\Ship;
use Tests\TestCase;
use App\User;

class AddShipTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_create_ship()
    {
         // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // membuat 1 data dummy jenis kapal
        $data = TypeShip::create([
            'type' => 'Pengumpul',
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah data kapal dengan membawa data token jenis kapal
        $response = $this->actingAs($user)->get('/dashboard/ship/create?type_ship_id=' . $data->dtkn);
        
        // 3. pengguna berhasil mengakses halaman tambah jenis kapal
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.ship.create');
    }

    public function test_user_cannot_view_create_ship()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        // membuat 1 data dummy jenis kapal
        $data = TypeShip::create([
            'type' => 'Pengumpul',
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah data kapal dengan membawa data token jenis kapal
        $response = $this->actingAs($user)->get('/dashboard/ship/create?type_ship_id=' . $data->dtkn);
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman tambah jenis kapal
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.ship.create');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_store_data_ship()
    {
        // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // membuat 1 data dummy jenis kapal
        $data = TypeShip::create([
            'type' => 'Pengumpul',
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah data kapal dengan membawa data token jenis kapal
        $response = $this->actingAs($user)->get('/dashboard/ship/create?type_ship_id=' . $data->dtkn);

        // 3. proses pembuatan data kapal
        $data = Ship::create([
            'type_ship_id' => $data->id,
            'name' => 'Nama Kapal',
            'owner' => 'Nama Pemilik',
         ]);

        // 4. berhasil melakukan pemanggilan data kapal
        $response->assertSuccessful();
    }
    
}
