<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class LoginTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_a_login_form()
    {
        // 1. pengguna mengakses halaman login
        $response = $this->get('/login');
        
        // 2. pengguna berhasil mengakses halaman login
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    public function test_user_cannot_view_a_login_form_when_authenticated()
    {
        // 1. membuat 1 data pengguna dummy
        $user = factory(User::class)->make();

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan berusaha untuk mengakses halaman login
        $response = $this->actingAs($user)->get('/login');

        // 3. pengguna akan kembali ke halaman dashboard
        $response->assertSee('Dashboard');
    }

    public function test_user_cannot_login_with_incorrect_email()
    {
        // 1. membuat 1 data pengguna dummy dengan email kostumisasi
        $user = factory(User::class)->create([
            'email' => 'test@gmail.com',
        ]);
        
        // 2. pengguna melakukan submit method post login dengan email yang salah
        $response = $this->from('/login')->post('/login', [
            'email' => 'wrong@gmail.com',
            'password' => $user->password,
        ]);
        
        // 3. pengguna akan kembali ke halaman login
        $response->assertRedirect('/login');

        // 4. pengguna akan tetap dianggap sebagai guest
        $this->assertGuest();
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        // 1. membuat 1 data pengguna dummy dengan password kostumisasi
        $user = factory(User::class)->create([
            'password' => bcrypt('i-love-laravel'),
        ]);
        
        // 2. pengguna melakukan submit method post login dengan email yang salah
        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);
        
        // 3. pengguna akan kembali ke halaman login
        $response->assertRedirect('/login');

        // 4. pengguna akan tetap dianggap sebagai guest
        $this->assertGuest();
    }

    public function test_user_can_login_using_correct_email_and_password()
    {
        // 1. membuat 1 data pengguna dummy dengan password kostumisasi
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'i-love-laravel'),
        ]);

        // 2. pengguna melakukan submit method post login dengan email dan password yang benar
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        // 3. pengguna akan menuju ke halaman dashboard
        $response->assertRedirect('/dashboard');

        // 4. pengguna akan dianggap sebagai user yang telah ter autentikasi
        $this->assertAuthenticatedAs($user);
    }
    
}
