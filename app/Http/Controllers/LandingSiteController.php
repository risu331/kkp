<?php

namespace App\Http\Controllers;

use App\Http\Requests\LandingSiteRequest;
use Illuminate\Http\Request;
use App\Models\LandingSite;
use App\Models\Branch;
use Auth;

class LandingSiteController extends Controller
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
        $landing_site = LandingSite::orderBy('name');

        if(Auth::user()->role != 'superadmin')
        {
            $landing_site->where('branch_id', Auth::user()->branch_id);
        } else {
            $landing_site->whereIn('branch_id', $request->branch_ids ?? [0]);
        }

        $data['landing_sites'] = $landing_site->get();
        $data['branches'] = Branch::where('deleted_at', null)->orderBy('name')->get();

        // menuju ke halaman alat tangkap ikan dengan membawa variabel data
        return view('dashboard.landing-site.index', $data);
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

        // menuju ke halaman tambah lokasi pendaratan
        return view('dashboard.landing-site.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LandingSiteRequest $request)
    {
        // proses pembuatan data lokasi pendaratan
        $data = LandingSite::create([
            'branch_id' => $request->branch_id,
            'name' => $request->name,
            'code' => $request->code,
            'lat' => $request->lat,
            'lng' => $request->lng,
         ]);
         
         // proses pembuatan token data
         $data->update([
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
        ]);
 
        //  setelah berhasil menambahkan data lokasi pendaratan maka user akan kembali ke halaman lihat lokasi pendaratan
         return redirect()->route('dashboard.landing-site.index')->with('OK', 'Berhasil melakukan tambah lokasi pendaratan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LandingSite  $landingSite
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LandingSite  $landingSite
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

        // mendapatkan data lokasi pendaratan sesuai dengan data token yang dikirim
        $data['landing_site'] = LandingSite::where('dtkn', $id)->firstOrFail();
        
        // menuju ke halaman ubah lokasi pendaratan dengan membawa variabel data
        return view('dashboard.landing-site.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LandingSite  $landingSite
     * @return \Illuminate\Http\Response
     */
    public function update(LandingSiteRequest $request, $id)
    {
        // mendapatkan data lokasi pendaratan sesuai dengan data token yang dikirim
        $data = LandingSite::where('dtkn', $id)->firstOrFail();

        // proses pengubahan data
        $data->update([
            'name' => $request->name,
            'code' => $request->code,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
        ]);

        // setelah berhasil melakukan pengubahan data, maka pengguna akan menuju halaman lihat data lokasi pendaratan
        return redirect()->route('dashboard.landing-site.index')->with('OK', 'Berhasil melakukan ubah lokasi pendaratan.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LandingSite  $landingSite
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // mendapatkan data lokasi pendaratan sesuai dengan data token yang dikirim
        $data = LandingSite::where('dtkn', $id)->firstOrFail();

        // proses penghapusan data kapal
        $data->delete();
        
        // setelah berhasil menghapus data maka pengguna akan kembali ke halaman lihat lokasi pendaratan
        return redirect()->route('dashboard.landing-site.index')->with('OK', 'Berhasil melakukan hapus lokasi pendaratan.');
    }
}
