<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\SpeciesFish;
use App\Models\TypeFish;
use Tests\TestCase;
use App\User;

class AddSpeciesFishTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_create_species_fish()
    {
         // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah species ikan
        $response = $this->actingAs($user)->get(route('dashboard.species-fish.create'));
        
        // 3. pengguna berhasil mengakses halaman tambah species ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.species-fish.create');
    }

    public function test_user_cannot_view_create_species_fish()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah species ikan
        $response = $this->actingAs($user)->get(route('dashboard.species-fish.create'));
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman tambah species ikan
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.species-fish.create');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_store_data_species_fish()
    {
        // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah species ikan
        $response = $this->actingAs($user)->get(route('dashboard.species-fish.create'));

        // proses pembuatan data jenis ikan
        $typeFish = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black'
         ]);

        //  3. proses pembuatan data species ikan
         $data = SpeciesFish::create([
            'type_fish_id' => $typeFish->id,
            'species' => 'Hiu Batu',
            'group' => 'Non Appendiks',
            'code' => '-',
         ]);


        // 4. berhasil melakukan pemanggilan data species ikan
        $response->assertSuccessful();
    }
    
}
