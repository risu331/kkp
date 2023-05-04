<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\TypeFishPicture;
use App\Models\TypeFish;
use Tests\TestCase;
use App\User;

class AddTypeFishPictureTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_create_type_fish_picture()
    {
         // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // membuat 1 data dummy pengambilan gambar jenis ikan
        $type_fish = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black',
            'dtkn' => 'token'
         ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah data kapal dengan membawa data token pengambilan gambar jenis ikan
        $response = $this->actingAs($user)->get('/dashboard/type-fish/picture/create?type_fish_id=' . $type_fish->dtkn);
        
        // 3. pengguna berhasil mengakses halaman tambah pengambilan gambar jenis ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.type-fish.picture.create');
    }

    public function test_user_cannot_view_create_type_fish_picture()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        // membuat 1 data dummy pengambilan gambar jenis ikan
        $type_fish = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black',
            'dtkn' => 'token'
         ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah data kapal dengan membawa data token pengambilan gambar jenis ikan
        $response = $this->actingAs($user)->get('/dashboard/type-fish/picture/create?id=' . $type_fish->dtkn);
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman tambah pengambilan gambar jenis ikan
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.type-fish.picture.create');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_store_data_type_fish_picture()
    {
        // 1. generate 1 data pengguna dengan role admin
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // membuat 1 data dummy pengambilan gambar jenis ikan
        $type_fish = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black',
            'dtkn' => 'token'
         ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman tambah data kapal dengan membawa data token pengambilan gambar jenis ikan
        $response = $this->actingAs($user)->get('/dashboard/type-fish/picture/create?type_fish_id=' . $type_fish->dtkn);

        // 3. proses pembuatan data kapal
        $data = TypeFishPicture::create([
            'type_fish_id' => $type_fish->id,
            'index' => 0,
            'title' => 'Sirip',
            'is_required' => 1,
            'sample_image' => 'image',
            'sample_description' => 'foto dari atas',
         ]);

        // 4. berhasil melakukan pemanggilan data kapal
        $response->assertSuccessful();
    }
    
}
