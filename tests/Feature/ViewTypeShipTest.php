<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\TypeShip;
use Tests\TestCase;
use App\User;

class ViewTypeShipTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_type_ship()
    {
         // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data jenis kapal
        $response = $this->actingAs($user)->get(route('dashboard.type-ship.index'));
        
        // 3. pengguna berhasil mengakses halaman jenis kapal
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.type-ship.index');
    }

    public function test_user_cannot_view_type_ship()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data jenis kapal
        $response = $this->actingAs($user)->get(route('dashboard.type-ship.index'));
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman jenis kapal
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.type-ship.index');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_get_data_type_ship()
    {
        // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman jenis kapal
        $response = $this->actingAs($user)->get(route('dashboard.type-ship.index'));

        // 3. pemanggilan data jenis kapal
        $data = TypeShip::get();

        // 4. berhasil melakukan pemanggilan data jenis kapal
        $response->assertSuccessful();
    }
    
}
