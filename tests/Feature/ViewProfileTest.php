<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class ViewProfileTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_profile()
    {
         // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah profile
        $response = $this->actingAs($user)->get("/dashboard/profile/{$user->id}/edit");
        
        // 3. pengguna berhasil mengubah profile dan melihat halaman ubah profile
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.profile.edit');
    }

    public function test_user_cannot_view_profile()
    {
        // 1. generate 1 data pengguna
        $user = factory(User::class)->create([
            'email' => 'test@gmail.com',
        ]);

        // 2. pengguna berusaha untuk mengakses halaman ubah profile
        $response = $this->get("/dashboard/profile/{$user->id}/edit");
        
        // 3. pengguna dilempar ke halaman login, karena belum melakukan login
        $response->assertRedirect('/login');
    }

    public function test_user_can_get_data_profile()
    {
        // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah profile
        $response = $this->actingAs($user)->get("/dashboard/profile/{$user->id}/edit");

        // 3. pemanggilan data User
        $data = User::where('id', $user->id)->first();

        // 4. berhasil melakukan pemanggilan data user
        $response->assertSuccessful();
    }
    
}
