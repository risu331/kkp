<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class DestroyUserTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_delete_data_user()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah password
        $response = $this->actingAs($user)->get(route('dashboard.user.index'));

        $dummyUser = factory(User::class)->create([
            'email' => 'testing@gmail.com'
        ]);

        // 3. pengguna melakukan hit url untuk menghapus data user
        $this->post(route('dashboard.user.destroy', $dummyUser->id));
        
        // 4. proses penghapusan data User
        $dummyUser->delete();
        
        // 5. berhasil melakukan penghapusan data user
        $response->assertSuccessful();
    }
    
}
