<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\FishingData;
use Tests\TestCase;
use App\User;

class ViewFishingDataTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_fishing_data()
    {
         // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data pendataan ikan
        $response = $this->actingAs($user)->get(route('dashboard.fishing-data.index'));
        
        // 3. pengguna berhasil mengakses halaman pendataan ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.fishing-data.index');
    }

    public function test_user_cannot_view_fishing_data()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data pendataan ikan
        $response = $this->actingAs($user)->get(route('dashboard.fishing-data.index'));
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman pendataan ikan
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.fishing-data.index');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_get_data_fishing_data()
    {
        // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman pendataan ikan
        $response = $this->actingAs($user)->get(route('dashboard.fishing-data.index'));

        // 3. pemanggilan data pendataan ikan
        $data = FishingData::get();

        // 4. berhasil melakukan pemanggilan data pendataan ikan
        $response->assertSuccessful();
    }
    
}
