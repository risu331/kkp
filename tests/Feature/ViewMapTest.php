<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\DataCollection;
use App\Models\SpeciesFish;
use App\Models\FishingData;
use App\Models\TypeFish;
use App\Models\Branch;
use Tests\TestCase;
use App\User;
use Auth;
use DB;

class ViewMapTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_map()
    {
         // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data kapal dengan membawa data token jenis kapal
        $response = $this->actingAs($user)->get('/dashboard/map');

        // 3. pengguna berhasil mengakses halaman kapal
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.map.index');
    }

    public function test_user_can_get_data_map()
    {
        // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat data kapal dengan membawa data token jenis kapal
        $response = $this->actingAs($user)->get('/dashboard/map');

        // 4. berhasil melakukan pemanggilan data kapal
        $response->assertSuccessful();
    }
    
}
