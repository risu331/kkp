<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class ChangePasswordTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_a_change_password_form()
    {
        // 1. generate 1 data pengguna
        $user = factory(User::class)->create([
            'email' => 'test@gmail.com',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah password
        $response = $this->actingAs($user)->get("/dashboard/profile/{$user->id}/edit");
        
        // 3. pengguna berhasil mengubah password dan melihat halaman ubah password
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.profile.edit');
    }

    public function test_user_cannot_view_a_change_password_form()
    {
        // 1. generate 1 data pengguna
        $user = factory(User::class)->create([
            'email' => 'test@gmail.com',
        ]);

        // 2. pengguna berusaha untuk mengakses halaman ubah password
        $response = $this->get("/dashboard/profile/{$user->id}/edit");
        
        // 3. pengguna dilempar ke halaman login, karena belum melakukan login
        $response->assertRedirect('/login');
    }

    public function test_user_can_update_password()
    {
         // generate 1 data pengguna
         $user = factory(User::class)->create([
            'email' => 'test@gmail.com',
        ]);

        // sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah password
        $response = $this->actingAs($user)->get("/dashboard/profile/{$user->id}/edit");

        // pengguna melakukan perubahan password
        $user->update([
            'password' => 'new-password'
        ]);

        // berhasil melakukan update password
        $response->assertSuccessful();
    }
    
}
