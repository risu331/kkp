<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\TypeShip;
use App\Models\Ship;
use Tests\TestCase;
use App\User;

class ViewShipTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_ship()
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

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data kapal dengan membawa data token jenis kapal
        $response = $this->actingAs($user)->get('/dashboard/ship?id=' . $data->dtkn);

        // 3. pengguna berhasil mengakses halaman kapal
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.ship.index');
    }

    public function test_user_cannot_view_ship()
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

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data kapal dengan membawa data token jenis kapal
        $response = $this->actingAs($user)->get('/dashboard/ship?id=' . $data->dtkn);
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman kapal
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.ship.index');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_get_data_ship()
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

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data kapal dengan membawa data token jenis kapal
        $response = $this->actingAs($user)->get('/dashboard/ship?id=' . $data->dtkn);

        // 3. pemanggilan data kapal
        $data = Ship::get();

        // 4. berhasil melakukan pemanggilan data kapal
        $response->assertSuccessful();
    }
    
}
