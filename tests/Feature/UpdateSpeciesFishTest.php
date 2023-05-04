<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\SpeciesFish;
use App\Models\TypeFish;
use Tests\TestCase;
use App\User;

class UpdateSpeciesFishTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_edit_species_fish()
    {
         // 1. generate 1 data pengguna dengan role admin   
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // proses pembuatan data dummy jenis ikan
        $typeFish = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black',
            'dtkn' => 'token'
        ]);

        // proses pembuatan data dummy species ikan untuk di edit
        $data = SpeciesFish::create([
            'type_fish_id' => $typeFish->id,
            'species' => 'Hiu Batu',
            'group' => 'Non Appendiks',
            'code' => '-',
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah species ikan
        $response = $this->actingAs($user)->get(route('dashboard.species-fish.edit', $data->dtkn));
        
        // 3. pengguna berhasil mengakses halaman ubah species ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.species-fish.edit');
    }

    public function test_user_cannot_view_edit_species_fish()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        // proses pembuatan data dummy species ikan untuk di edit
        $data = SpeciesFish::create([
            'type' => 'Pengumpul'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah species ikan
        $response = $this->actingAs($user)->get(route('dashboard.species-fish.edit', $data->id));
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman ubah user
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.species-fish.edit');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_update_data_species_fish()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // proses pembuatan data dummy jenis ikan
        $typeFish = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black',
            'dtkn' => 'token'
        ]);

        // proses pembuatan data dummy species ikan untuk di edit
        $data = SpeciesFish::create([
            'type_fish_id' => $typeFish->id,
            'species' => 'Hiu Batu',
            'group' => 'Non Appendiks',
            'code' => '-',
            'dtkn' => 'token'
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah species ikan
        $response = $this->actingAs($user)->get(route('dashboard.species-fish.edit', $data->dtkn));

        // 3. proses mengubah data species ikan
        $data->update([
            'species' => 'New Hiu Batu',
            'group' =>  'Appendiks',
            'code' => '-',
        ]);

        // 4. berhasil melakukan pengubahan data species ikan
        $response->assertSuccessful();
    }
    
}
