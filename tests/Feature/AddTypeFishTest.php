<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\TypeFish;
use Tests\TestCase;
use App\User;

class AddTypeFishTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_create_type_fish()
    {
         // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah jenis ikan
        $response = $this->actingAs($user)->get(route('dashboard.type-fish.create'));
        
        // 3. pengguna berhasil mengakses halaman tambah jenis ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.type-fish.create');
    }

    public function test_user_cannot_view_create_type_fish()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah jenis ikan
        $response = $this->actingAs($user)->get(route('dashboard.type-fish.create'));
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman tambah jenis ikan
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.type-fish.create');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_store_data_type_fish()
    {
        // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah jenis ikan
        $response = $this->actingAs($user)->get(route('dashboard.type-fish.create'));

        // 3. proses pembuatan data jenis ikan
        $data = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black'
         ]);

        // 4. berhasil melakukan pemanggilan data jenis ikan
        $response->assertSuccessful();
    }
    
}
