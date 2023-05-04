<?php

namespace App\Http\Controllers;

use App\Http\Requests\FishingGearRequest;
use Illuminate\Http\Request;
use App\Models\FishingGear;
use App\Models\Branch;
use Auth;

class FishingGearController extends Controller
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

        // pemanggilan data jenis kapal dari database
        $fishing_gear = FishingGear::orderBy('name');

        if(Auth::user()->role != 'superadmin')
        {
            $fishing_gear->where('branch_id', Auth::user()->branch_id);
        } else {
            $fishing_gear->whereIn('branch_id', $request->branch_ids ?? [0]);
        }

        $data['fishing_gears'] = $fishing_gear->get();
        $data['branches'] = Branch::where('deleted_at', null)->orderBy('name')->get();

        // menuju ke halaman alat tangkap ikan dengan membawa variabel data
        return view('dashboard.fishing-gear.index', $data);
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
        return view('dashboard.fishing-gear.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FishingGearRequest $request)
    {
        // proses pembuatan data alat tangkap ikan
        $data = FishingGear::create([
            'branch_id' => $request->branch_id,
            'name' => $request->name,
         ]);

         // proses pembuatan token data
         $data->update([
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
         ]);
 
         //  setelah berhasil menambahkan data alat tangkap ikan maka user akan kembali ke halaman lihat alat tangkap ikan
         return redirect()->route('dashboard.fishing-gear.index')->with('OK', 'Berhasil melakukan tambah alat tangkap ikan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FishingGear  $fishingGear
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FishingGear  $fishingGear
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

        // mendapatkan data alat tangkap ikan sesuai dengan data token yang dikirim
        $data['fishing_gear'] = FishingGear::where('dtkn', $id)->firstOrFail();
        
        // menuju ke halaman ubah alat tangkap ikan dengan membawa variabel data
        return view('dashboard.fishing-gear.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FishingGear  $fishingGear
     * @return \Illuminate\Http\Response
     */
    public function update(FishingGearRequest $request, $id)
    {
        // mendapatkan data alat tangkap ikan sesuai dengan data token yang dikirim
        $data = FishingGear::where('dtkn', $id)->firstOrFail();

        // proses pengubahan data kapal
        $data->update([
            'name' => $request->name,
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
        ]);

        // setelah berhasil melakukan pengubahan data, maka pengguna akan menuju halaman lihat data alat tangkap ikan
        return redirect()->route('dashboard.fishing-gear.index')->with('OK', 'Berhasil melakukan ubah alat tangkap ikan.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FishingGear  $fishingGear
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // mendapatkan data alat tangkap ikan sesuai dengan data token yang dikirim
        $data = FishingGear::where('dtkn', $id)->firstOrFail();

        // proses penghapusan data kapal
        $data->delete();

        // setelah berhasil menghapus data maka pengguna akan kembali ke halaman lihat alat tangkap ikan
        return redirect()->route('dashboard.fishing-gear.index')->with('OK', 'Berhasil melakukan hapus alat tangkap ikan.');
    }
}
