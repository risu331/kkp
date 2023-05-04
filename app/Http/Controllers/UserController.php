<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Imports\DataImport;
use App\Models\Branch;
use App\User;
use Auth;

class UserController extends Controller
{
    public function index()
    {
        // kondisi ketika akun yang login bukan sebagai admin
        if(Auth::user()->role == 'enumerator' || Auth::user()->role == 'user')
        {
            // maka akan diarahkan ke halaman 404
            abort(404);
        }
        // pemanggilan data user dari database
        $data['users'] = User::when(function($q){
            if(Auth::user()->role != 'superadmin')
            {
                $q->where('role', '!=', 'superadmin');
            }
        })->orderBy('id')->get();

        // menuju ke halaman user dengan membawa data user
        return view('dashboard.user.index', $data);
    }
    
    public function create()
    {
        // kondisi ketika akun yang login bukan sebagai admin
        if(Auth::user()->role == 'enumerator' || Auth::user()->role == 'user')
        {
            // maka akan diarahkan ke halaman 404
            abort(404);
        }
        $data['branchs'] = Branch::where('deleted_at', null)->get();

        return view('dashboard.user.create', $data);
    }

    public function store(UserRequest $request)
    {        
        $imagePath = '';
        if ($request->hasFile('image')) {
          $save = $request->file('image')->store('images');
          $filename = $request->file('image')->hashName();
          $imagePath = url('/') . '/storage/images/' . $filename;
        }

        $user = User::create([
           'name' => $request->name,
           'email' => $request->email,
           'phone_number' => $request->phone_number,
           'role' => $request->role,
           'password' => bcrypt($request->password),
           'branch_id' => $request->branch_id ?? Auth::user()->branch_id,
           'image' => $imagePath,
        ]);

        return redirect()->route('dashboard.user.index')->with('OK', 'Berhasil melakukan tambah user.');
    }

    public function edit($id)
    {
        // kondisi ketika akun yang login bukan sebagai admin
        if(Auth::user()->role == 'enumerator' || Auth::user()->role == 'user')
        {
            // maka akan diarahkan ke halaman 404
            abort(404);
        }

        // mendapatkan data user sesuai dengan id yang dikirim
        $data['user'] = $this->getUser($id);
        $data['branchs'] = Branch::where('deleted_at', null)->get();
        
        // menuju ke halaman ubah user dengan membawa data user
        return view('dashboard.user.edit', $data);
    }

    public function update(UserRequest $request, $id)
    {
        // mendapatkan data user sesuai dengan id yang dikirim
        $data = $this->getUser($id);
                
        // proses pengubahan gambar user
        $imagePath = $data->image;
        if ($request->hasFile('image')) {
          $save = $request->file('image')->store('images');
          $filename = $request->file('image')->hashName();
          $unlinkPath = explode('/storage', $imagePath);
          if(count($unlinkPath) == 2)
          {
            if (file_exists(public_path('/storage' . $unlinkPath[1]))) {
                unlink(public_path('/storage' . $unlinkPath[1]));
            }
          }
          $imagePath = url('/') . '/storage/images/' . $filename;
        }

        // proses pengubahan data user
        $data->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
            'branch_id' => $request->branch_id ?? Auth::user()->branch_id,
            'image' => $imagePath,
        ]);

        // kondisi ketika input password tidak kosong
        if($request->password != null)
        {
            // maka proses pengubahan password dijalankan
            $data->update([
                'password' => bcrypt($request->password)
            ]);
        }

        // setelah berhasil melakukan pengubahan data, maka pengguna akan menuju halaman lihat data user
        return redirect()->route('dashboard.user.index')->with('OK', 'Berhasil melakukan edit user.');
    }

    public function destroy($id)
    {
        // mendapatkan data user sesuai dengan id yang dikirim
        $data = User::findOrFail($id);
        $imagePath = $data->image;
        $unlinkPath = explode('/storage', $imagePath);
        if(count($unlinkPath) == 2)
        {
        if (file_exists(public_path('/storage' . $unlinkPath[1]))) {
            unlink(public_path('/storage' . $unlinkPath[1]));
        }
        }

        // proses penghapusan data user
        $data->delete();

        // setelah berhasil menghapus user maka pengguna akan kembali ke halaman sebelumnya
        return redirect()->back()->with('OK', 'Berhasil melakukan hapus user.');
    }

    public function getUser($signature)
    {
        $data = User::where('id', $signature)->first() ?? abort(404);

        return $data;
    }
    
    public function editProfile($id)
    {
        // Excel::import(new DataImport, public_path('test-data-2.xlsx'));
        // kondisi jika url id tidak sama dengan url akun yang sedang login
        if(Auth::user()->id != $id)
        {
            // akan diarahkan ke halaman 404
            abort(404);
        }

        // mengambil data user sesuai dengan id yang sedang login
        $data['user'] = $this->getUser($id);
        
        // menampilkan halaman ubah password
        return view('dashboard.profile.edit', $data);
    }

    public function updateProfile(Request $request, $id)
    {
        // mengambil data user sesuai dengan id yang sedang login
        $data = $this->getUser($id);
        
        $imagePath = $data->image;
        if ($request->hasFile('image')) {
          $save = $request->file('image')->store('images');
          $filename = $request->file('image')->hashName();
          $unlinkPath = explode('/storage', $imagePath);
          if(count($unlinkPath) == 2)
          {
            if (file_exists(public_path('/storage' . $unlinkPath[1]))) {
                unlink(public_path('/storage' . $unlinkPath[1]));
            }
          }
          $imagePath = url('/') . '/storage/images/' . $filename;
        }

        $data->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'image' => $imagePath,
        ]);

        if($request->password != null)
        {
            // proses mengubah password, sesuai dengan input password baru
            $data->update([
                'password' => bcrypt($request->password)
            ]);
        }

        // setelah berhasil melakukan ubah password akan di arahkan kehalaman ubah password kembali
        return redirect()->route('dashboard.user.index')->with('OK', 'Berhasil melakukan edit profile.');
    }
}
