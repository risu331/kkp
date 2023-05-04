<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeFishRequest;
use Illuminate\Http\Request;
use App\Models\TypeFish;
use App\Models\Branch;
use Auth;

class TypeFishController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // kondisi ketika akun yang login bukan sebagai admin
        if(Auth::user()->role == 'enumerator' || Auth::user()->role == 'user')
        {
            // maka akan diarahkan ke halaman 404
            abort(404);
        }

        // pemanggilan data jenis ikan dari database
        $type_fish = TypeFish::orderBy('type');

        if(Auth::user()->role != 'superadmin')
        {
            $type_fish->where('branch_id', Auth::user()->branch_id);
        } else {
            $type_fish->whereIn('branch_id', $request->branch_ids ?? [0]);
        }

        $data['type_fishs'] = $type_fish->get();
        $data['branches'] = Branch::where('deleted_at', null)->orderBy('name')->get();

        // menuju ke halaman jenis ikan dengan membawa data
        return view('dashboard.type-fish.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // kondisi ketika akun yang login bukan sebagai admin
        if(Auth::user()->role == 'enumerator' || Auth::user()->role == 'user')
        {
            // maka akan diarahkan ke halaman 404
            abort(404);
        }
        $data['branches'] = Branch::where('deleted_at', null)->orderBy('name')->get();

        // menuju ke halaman tambah jenis kapal
        return view('dashboard.type-fish.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TypeFishRequest $request)
    {
        // proses pembuatan data
        $data = TypeFish::create([
            'branch_id' => $request->branch_id,
            'type' => $request->type,
            'icon' => $request->icon
         ]);

         // proses pembuatan token data
         $data->update([
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
         ]);
 
         //  setelah berhasil menambahkan data jenis ikan maka user akan kembali ke halaman lihat jenis ikan
         return redirect()->route('dashboard.type-fish.index')->with('OK', 'Berhasil melakukan tambah jenis ikan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TypeFish  $typeFish
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TypeFish  $typeFish
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // kondisi ketika akun yang login bukan sebagai admin
        if(Auth::user()->role == 'enumerator' || Auth::user()->role == 'user')
        {
            // maka akan diarahkan ke halaman 404
            abort(404);
        }

        // mendapatkan data user sesuai dengan data token yang dikirim
        $data['type_fish'] = TypeFish::where('dtkn', $id)->firstOrFail();
        
        // menuju ke halaman ubah user dengan membawa data jenis ikan
        return view('dashboard.type-fish.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TypeFish  $typeFish
     * @return \Illuminate\Http\Response
     */
    public function update(TypeFishRequest $request, $id)
    {
        // mendapatkan data jenis ikan sesuai dengan data token yang dikirim
        $data = TypeFish::where('dtkn', $id)->firstOrFail();

        // proses pengubahan data jenis ikan
        $data->update([
            'type' => $request->type,
            'icon' => $request->icon,
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
        ]);

        // setelah berhasil melakukan pengubahan data, maka pengguna akan menuju halaman lihat data jenis ikan
        return redirect()->route('dashboard.type-fish.index')->with('OK', 'Berhasil melakukan ubah jenis ikan.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TypeFish  $typeFish
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // mendapatkan data jenis ikan sesuai dengan data token yang dikirim
        $data = TypeFish::where('dtkn', $id)->firstOrFail();

        // proses penghapusan data jenis ikan
        $data->delete();
        
        // setelah berhasil menghapus jenis ikan maka pengguna akan kembali ke halaman lihat jenis ikan
        return redirect()->route('dashboard.type-fish.index')->with('OK', 'Berhasil melakukan hapus jenis ikan.');
    }
}
