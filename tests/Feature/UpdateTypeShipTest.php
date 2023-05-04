<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\TypeShip;
use Tests\TestCase;
use App\User;

class UpdateTypeShipTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_edit_type_ship()
    {
         // 1. generate 1 data pengguna dengan role admin   
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // proses pembuatan data dummy jenis kapal untuk di edit
        $data = TypeShip::create([
            'type' => 'Pengumpul',
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah janis kapal
        $response = $this->actingAs($user)->get(route('dashboard.type-ship.edit', $data->dtkn));
        
        // 3. pengguna berhasil mengakses halaman ubah janis kapal
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.type-ship.edit');
    }

    public function test_user_cannot_view_edit_type_ship()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        // proses pembuatan data dummy jenis kapal untuk di edit
        $data = TypeShip::create([
            'type' => 'Pengumpul'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah janis kapal
        $response = $this->actingAs($user)->get(route('dashboard.type-ship.edit', $data->id));
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman ubah user
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.type-ship.edit');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_update_data_type_ship()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // proses pembuatan data dummy jenis kapal untuk di edit
        $data = TypeShip::create([
            'type' => 'Pengumpul',
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah janis kapal
        $response = $this->actingAs($user)->get(route('dashboard.type-ship.edit', $data->dtkn));

        // 3. proses mengubah data janis kapal
        $data->update([
            'type' => 'New Pengumpul'
        ]);

        // 4. berhasil melakukan pengubahan data janis kapal
        $response->assertSuccessful();
    }
    
}
