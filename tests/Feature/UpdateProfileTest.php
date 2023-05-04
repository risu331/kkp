<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class UpdateProfileTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_a_change_profile_form()
    {
        // 1. generate 1 data pengguna
        $user = factory(User::class)->create([
            'email' => 'test@gmail.com',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah profile
        $response = $this->actingAs($user)->get("/dashboard/profile/{$user->id}/edit");
        
        // 3. pengguna berhasil mengubah profile dan melihat halaman ubah profile
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.profile.edit');
    }

    public function test_user_cannot_view_a_change_profile_form()
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

    public function test_user_can_update_profile()
    {
        // generate 1 data pengguna
        $user = factory(User::class)->create([
            'email' => 'test@gmail.com',
        ]);

        // sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah profile
        $response = $this->actingAs($user)->get("/dashboard/profile/{$user->id}/edit");

        // pengguna melakukan perubahan profile
        $user->update([
            'name' => 'new-name',
            'email' => 'new-email',
            'password' => 'new-password'
        ]);

        // berhasil melakukan update profile
        $response->assertSuccessful();
    }   
}
