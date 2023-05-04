<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class UpdateUserTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_edit_user()
    {
         // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah user
        $response = $this->actingAs($user)->get(route('dashboard.user.edit', $user->id));
        
        // 3. pengguna berhasil mengakses halaman ubah user
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.user.edit');
    }

    public function test_user_cannot_view_edit_user()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah user
        $response = $this->actingAs($user)->get(route('dashboard.user.edit', $user->id));
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman ubah user
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.user.edit');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_update_data_user()
    {
        // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah password
        $response = $this->actingAs($user)->get(route('dashboard.user.edit', $user->id));

        // 3. proses mengubah data User
        $user->update([
            'name' => 'New Test User',
            'email' => 'new-user-test@gmail.com',
            'phone_number' => '085654835031',
            'password' => bcrypt('new-password'),
            'image' => 'new url photo',
        ]);

        // 4. berhasil melakukan pengubahan data user
        $response->assertSuccessful();
    }
    
}
