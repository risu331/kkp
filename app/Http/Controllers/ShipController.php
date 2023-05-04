<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShipRequest;
use Illuminate\Http\Request;
use App\Models\TypeShip;
use App\Models\Ship;
use Auth;

class ShipController extends Controller
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
        $data['type_ship'] = TypeShip::with('ships')->where('dtkn', $request->id ?? 0)->orderBy('type')->firstOrFail();

        // menuju ke halaman kapal dengan membawa data jenis kapal
        return view('dashboard.ship.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // kondisi ketika akun yang login bukan sebagai admin
        if(Auth::user()->role == 'enumerator' || Auth::user()->role == 'user')
        {
            // maka akan diarahkan ke halaman 404
            abort(404);
        }

        // pemanggilan data jenis kapal dari database
        $data['type_ship'] = TypeShip::with('ships')->where('dtkn', $request->type_ship_id ?? 0)->orderBy('type')->firstOrFail();

        // menuju ke halaman tambah kapal dengan membawa data jenis kapal
        return view('dashboard.ship.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShipRequest $request)
    {
        $data = Ship::create([
            'type_ship_id' => $request->type_ship_id,
            'name' => $request->name,
            'owner' => $request->owner,
        ]);

        $data->update([
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
        ]);
 
         return redirect()->route('dashboard.ship.index', ['id' => $data->type_ship->dtkn])->with('OK', 'Berhasil melakukan tambah kapal.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ship  $ship
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ship  $ship
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

        // mendapatkan data kapal sesuai dengan data token yang dikirim
        $data['ship'] = Ship::where('dtkn', $id)->firstOrFail();

        // mendapatkan data jenis kapal sesuai dengan data id pada data kapal diatas
        $data['type_ship'] = TypeShip::where('id', $data['ship']->type_ship_id)->orderBy('type')->first();
        
        // menuju ke halaman ubah kapal dengan membawa data kapal dan jenis kapal
        return view('dashboard.ship.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ship  $ship
     * @return \Illuminate\Http\Response
     */
    public function update(ShipRequest $request, $id)
    {
        // mendapatkan data user sesuai dengan data token yang dikirim
        $data = Ship::where('dtkn', $id)->firstOrFail();

        // proses pengubahan data kapal
        $data->update([
            'type_ship_id' => $request->type_ship_id,
            'name' => $request->name,
            'owner' => $request->owner,
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
        ]);

        // setelah berhasil melakukan pengubahan data, maka pengguna akan menuju halaman lihat data kapal
        return redirect()->route('dashboard.ship.index', ['id' => $data->type_ship->dtkn])->with('OK', 'Berhasil melakukan ubah kapal.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ship  $ship
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // mendapatkan data kapal sesuai dengan data tokken yang dikirim
        $data = Ship::where('dtkn', $id)->firstOrFail();
        $type_ship_id = $data->type_ship->dtkn;

        // proses penghapusan data kapal
        $data->delete();

        // setelah berhasil menghapus data maka pengguna akan kembali ke halaman lihat  kapal
        return redirect()->route('dashboard.ship.index', ['id' => $type_ship_id])->with('OK', 'Berhasil melakukan hapus kapal.');
    }
}
