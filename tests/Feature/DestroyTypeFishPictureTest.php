<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\TypeFishPicture;
use App\Models\TypeFish;
use Tests\TestCase;
use App\User;

class DestroyTypeFishPictureTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_delete_data_type_fish_picture()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        
        // proses pembuatan data dummy jenis jenis ikan
        $type_fish = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black',
            'dtkn' => 'token'
         ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman lihat jenis ikan
        $response = $this->actingAs($user)->get(route('dashboard.type-fish.picture.index', ['id' => $type_fish->dtkn]));
         
         // proses pembuatan data dummy pengambilan gambar jenis ikan untuk di edit
        $data = TypeFishPicture::create([
            'type_fish_id' => $type_fish->id,
            'index' => 0,
            'title' => 'Sirip',
            'is_required' => 1,
            'sample_image' => 'image',
            'sample_description' => 'foto dari atas',
            'dtkn' => 'token'
         ]);

        // 4. pengguna melakukan hit url untuk menghapus data pengambilan gambar  jenis ikan
        $this->post(route('dashboard.ship.destroy', $data->dtkn));
        
        // 5. proses penghapusan data pengambilan gambar  jenis ikan
        $data->delete();
        
        // 6. berhasil melakukan penghapusan data pengambilan gambar  jenis ikan
        $response->assertSuccessful();
    }
    
}
