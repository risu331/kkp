<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpeciesFishRequest;
use Illuminate\Http\Request;
use App\Models\SpeciesFish;
use App\Models\TypeFish;
use App\Models\Branch;
use Auth;

class SpeciesFishController extends Controller
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

        // pemanggilan data species ikan dari database
        $species_fish = SpeciesFish::with('type_fish')->orderBy('species');

        if(Auth::user()->role != 'superadmin')
        {
            $species_fish->whereHas('type_fish', function($q){
                $q->where('branch_id', Auth::user()->branch_id);
            });
        } else {
            $species_fish->whereHas('type_fish', function($q) use($request){
                $q->whereIn('branch_id', $request->branch_ids ?? [0]);
            });
        }

        $data['species_fishs'] = $species_fish->get();
        $data['branches'] = Branch::where('deleted_at', null)->orderBy('name')->get();

        // menuju ke halaman species ikan dengan membawa data
        return view('dashboard.species-fish.index', $data);
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
        $data['type_fishs'] = TypeFish::orderBy('type')->get();

        // menuju ke halaman tambah species ikan
        return view('dashboard.species-fish.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SpeciesFishRequest $request)
    {
        // proses pembuatan data
        $data = SpeciesFish::create([
            'type_fish_id' => $request->type_fish_id,
            'species' => $request->species,
            'local' => $request->local,
            'general' => $request->general,
            'group' => $request->group,
            'code' => '-',
            'born_start' => $request->born_start ?? 0,
            'born_end' => $request->born_end ?? 0,
            'mature_male_start' => $request->mature_male_start ?? 0,
            'mature_male_end' => $request->mature_male_end ?? 0,
            'mature_female_start' => $request->mature_female_start ?? 0,
            'mature_female_end' => $request->mature_female_end ?? 0,
            'mega_spawner' => $request->mega_spawner ?? 0,
         ]);

         // proses pembuatan token data
         $data->update([
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
        ]);
 
        //  setelah berhasil menambahkan data species ikan maka user akan kembali ke halaman lihat species ikan
         return redirect()->route('dashboard.species-fish.index')->with('OK', 'Berhasil melakukan tambah spesies ikan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SpeciesFish  $speciesFish
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SpeciesFish  $speciesFish
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

        // mendapatkan data species sesuai dengan data token yang dikirim
        $data['species_fish'] = SpeciesFish::where('dtkn', $id)->firstOrFail();
        $data['type_fishs'] = TypeFish::orderBy('type')->get();
        
        // menuju ke halaman ubah species ikan dengan membawa data species ikan
        return view('dashboard.species-fish.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SpeciesFish  $speciesFish
     * @return \Illuminate\Http\Response
     */
    public function update(SpeciesFishRequest $request, $id)
    {
        // mendapatkan data species ikan sesuai dengan data token yang dikirim
        $data = SpeciesFish::where('dtkn', $id)->firstOrFail();

        // proses pengubahan data species ikan
        $data->update([
            'type_fish_id' => $request->type_fish_id,
            'species' => $request->species,
            'local' => $request->local,
            'general' => $request->general,
            'group' => $request->group,
            'born_start' => $request->born_start ?? 0,
            'born_end' => $request->born_end ?? 0,
            'mature_male_start' => $request->mature_male_start ?? 0,
            'mature_male_end' => $request->mature_male_end ?? 0,
            'mature_female_start' => $request->mature_female_start ?? 0,
            'mature_female_end' => $request->mature_female_end ?? 0,
            'mega_spawner' => $request->mega_spawner ?? 0,
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
        ]);

        // setelah berhasil melakukan pengubahan data, maka pengguna akan menuju halaman lihat data species ikan
        return redirect()->route('dashboard.species-fish.index')->with('OK', 'Berhasil melakukan ubah spesies ikan.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SpeciesFish  $speciesFish
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // mendapatkan data species ikan sesuai dengan data token yang dikirim
        $data = SpeciesFish::where('dtkn', $id)->firstOrFail();

        // proses penghapusan data species ikan
        $data->delete();

        // setelah berhasil menghapus species ikan maka pengguna akan kembali ke halaman lihat species ikan
        return redirect()->route('dashboard.species-fish.index')->with('OK', 'Berhasil melakukan hapus spesies ikan.');
    }
}
