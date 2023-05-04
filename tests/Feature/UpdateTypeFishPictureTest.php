<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\TypeFishPicture;
use App\Models\TypeFish;
use Tests\TestCase;
use App\User;

class UpdateTypeFishPictureTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;

    public function test_user_can_view_edit_type_fish_picture()
    {
         // 1. generate 1 data pengguna dengan role admin   
         $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // proses pembuatan data dummy jenis kapal
        $type_fish = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black',
            'dtkn' => 'token'
         ]);

        // proses pembuatan data dummy kapal untuk di edit
        $data = TypeFishPicture::create([
            'type_fish_id' => $type_fish->id,
            'index' => 0,
            'title' => 'Sirip',
            'is_required' => 1,
            'sample_image' => 'image',
            'sample_description' => 'foto dari atas',
            'dtkn' => 'token'
         ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah pengambilan gambar jenis ikan
        $response = $this->actingAs($user)->get(route('dashboard.type-fish.picture.edit', $data->dtkn));
        
        // 3. pengguna berhasil mengakses halaman ubah pengambilan gambar jenis ikan
        $response->assertSuccessful();
        $response->assertViewIs('dashboard.type-fish.picture.edit');
    }

    public function test_user_cannot_view_edit_type_fish_picture()
    {
         // 1. generate 1 data pengguna dengan role selain admin
         $user = factory(User::class)->create([
            'role' => 'enumerator',
        ]);

        // proses pembuatan data dummy jenis kapal
        $type_fish = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black',
            'dtkn' => 'token'
         ]);

        // proses pembuatan data dummy kapal untuk di edit
        $data = TypeFishPicture::create([
            'type_fish_id' => $type_fish->id,
            'index' => 0,
            'title' => 'Sirip',
            'is_required' => 1,
            'sample_image' => 'image',
            'sample_description' => 'foto dari atas',
            'dtkn' => 'token'
         ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah janis kapal
        $response = $this->actingAs($user)->get(route('dashboard.type-fish.picture.edit', $data->id));
        
        // 3. kondisi dimana dilakukan pengecekkan role apakah pengguna seorang admin atau bukan
        if($user->role == 'admin')
        {
            // 4. jika pengguna adalah seorang admin maka akan diarahkan ke halaman ubah user
            $response->assertSuccessful();
            $response->assertViewIs('dashboard.type-fish.picture.edit');
        } else {
            // 5. jika pengguna bukan seorang admin maka pengguna akan melihat halaman 404
            $response->assertSee('404');
        }
    }

    public function test_user_can_update_data_type_fish_picture()
    {
        // 1. generate 1 data pengguna dengan role admin
        $user = factory(User::class)->create([
            'role' => 'admin',
        ]);

        // proses pembuatan data dummy jenis kapal
        $type_fish = TypeFish::create([
            'type' => 'Hiu',
            'icon' => 'Black',
            'dtkn' => 'token'
         ]);

        // proses pembuatan data dummy kapal untuk di edit
        $data = TypeFishPicture::create([
            'type_fish_id' => $type_fish->id,
            'index' => 0,
            'title' => 'Sirip',
            'is_required' => 1,
            'sample_image' => 'image',
            'sample_description' => 'foto dari atas',
            'dtkn' => 'token'
         ]);

        // 2. sebuah akting bahwa pengguna telah melakukan login sebelumnya dan mengakses halaman ubah janis kapal
        $response = $this->actingAs($user)->get(route('dashboard.type-fish.picture.edit', $data->dtkn));

        // 3. proses mengubah data janis kapal
        $data->update([
            'index' => 1,
            'title' => 'ekor',
            'is_required' => 0,
            'sample_image' => 'image',
            'sample_description' => 'foto dari samping',
        ]);

        // 4. berhasil melakukan pengubahan data janis kapal
        $response->assertSuccessful();
    }
    
}
