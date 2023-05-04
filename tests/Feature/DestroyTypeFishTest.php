<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\TypeFish;
use Tests\TestCase;
use App\User;

class DestroyTypeFishTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_delete_data_type_fish()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat jenis ikan
        $response = $this->actingAs($user)->get(route('dashboard.type-ship.index'));

        // 3. proses pembuatan data jenis ikan untuk dihapus
        $data = TypeFish::create([
            'type' => 'Pengumpul',
            'dtkn' => 'token'
         ]);

        // 4. pengguna melakukan hit url untuk menghapus data jenis ikan
        $this->post(route('dashboard.type-ship.destroy', $data->dtkn));
        
        // 5. proses penghapusan data jenis ikan
        $data->delete();
        
        // 6. berhasil melakukan penghapusan data jenis ikan
        $response->assertSuccessful();
    }
    
}
