<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class LogoutTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_logout()
    {
        // 1. membuat 1 data pengguna dummy
        $user = factory(User::class)->make();
        
        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumny dan pengguna melakukan submit method get logout 
        $response = $this->actingAs($user)->get('/logout');
        
        // 3. pengguna akan menuju ke halaman login
        $response->assertRedirect('/login');

        // 4. pengguna dianggap sebagai guest
        $this->assertGuest();
    }
    
}
