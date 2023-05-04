<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\TypeFish;
use Tests\TestCase;
use App\User;

class UpdateTypeFishTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_edit_type_fish()
    {
         // 1. generate 1 data pengguna dengan role admin   
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // proses pembuatan data dummy jenis ikan untuk di edit
        $data = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black',
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah jenis ikan
        $response = $this->actingAs($user)->get(route('dashboard.type-fish.edit', $data->dtkn));
        
        // 3. pengguna berhasil mengakses halaman ubah jenis ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.type-fish.edit');
    }

    public function test_user_cannot_view_edit_type_fish()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        // proses pembuatan data dummy jenis ikan untuk di edit
        $data = TypeFish::create([
            'type' => 'Pengumpul'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah jenis ikan
        $response = $this->actingAs($user)->get(route('dashboard.type-fish.edit', $data->id));
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman ubah user
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.type-fish.edit');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_update_data_type_fish()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // proses pembuatan data dummy jenis ikan untuk di edit
        $data = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black',
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah jenis ikan
        $response = $this->actingAs($user)->get(route('dashboard.type-fish.edit', $data->dtkn));

        // 3. proses mengubah data jenis ikan
        $data->update([
            'type' => 'New Hiu',
            'icon' => 'New Black',
        ]);

        // 4. berhasil melakukan pengubahan data jenis ikan
        $response->assertSuccessful();
    }
    
}
