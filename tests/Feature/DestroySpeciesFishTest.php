<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\SpeciesFish;
use App\Models\TypeFish;
use Tests\TestCase;
use App\User;

class DestroySpeciesFishTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_delete_data_species_fish()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat species ikan
        $response = $this->actingAs($user)->get(route('dashboard.species-fish.index'));

        // proses pembuatan data jenis ikan
        $typeFish = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black'
         ]);

        //  3. proses pembuatan data species ikan untuk dihapus
         $data = SpeciesFish::create([
            'type_fish_id' => $typeFish->id,
            'species' => 'Hiu Batu',
            'group' => 'Non Appendiks',
            'code' => '-',
            'dtkn' => 'token'
         ]);
        // 4. pengguna melakukan hit url untuk menghapus data species ikan
        $this->post(route('dashboard.species-fish.destroy', $data->dtkn));
        
        // 5. proses penghapusan data species ikan
        $data->delete();
        
        // 6. berhasil melakukan penghapusan data species ikan
        $response->assertSuccessful();
    }
    
}
