<?php

namespace App\Http\Controllers;

use App\Http\Requests\FishingDataRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FishingDataExport;
use App\Models\DataCollection;
use Illuminate\Http\Request;
use App\Models\LandingSite;
use App\Models\FishingData;
use App\Models\FishingGear;
use App\Models\TypeShip;
use App\Models\Branch;
use App\Models\Ship;
use Auth;

class FishingDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fishing_data = FishingData::orderBy('enumeration_time');

        if(Auth::user()->role != 'superadmin')
        {
            $fishing_data->where('branch_id', Auth::user()->branch_id);
        } else {
            $fishing_data->whereIn('branch_id', $request->branch_ids ?? [0]);
        }

        $start_date = $request->start_date ?? date('Y-m-d');
        $end_date = $request->end_date ?? date('Y-m-d');

        $data['fishing_datas'] = $fishing_data->whereDate('enumeration_time', '>=', $start_date)->whereDate('enumeration_time', '<=', $end_date)->get();
        $data['verified_fishing_datas'] = FishingData::orderBy('enumeration_time')->whereDate('enumeration_time', '>=', $start_date)->whereDate('enumeration_time', '<=', $end_date)->where('status', 'disetujui')->get();
        $data['unverified_fishing_datas'] = FishingData::orderBy('enumeration_time')->whereDate('enumeration_time', '>=', $start_date)->whereDate('enumeration_time', '<=', $end_date)->where('status', 'menunggu persetujuan')->get();
        $data['branches'] = Branch::where('deleted_at', null)->orderBy('name')->get();
        
        return view('dashboard.fishing-data.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['branches'] = Branch::orderBy('name')->get();
        $branch = Branch::where('dtkn', $request->branch_id)->first();
        $data['landing_sites'] = LandingSite::when(function($q) use($branch){
            if($branch != null)
            {
                $q->where('branch_id', $branch->id);
            } else {
                $q->where('branch_id', Auth::user()->branch_id);
            }
        })->orderBy('name')->get();
        $data['type_ships'] = TypeShip::when(function($q) use($branch){
            if($branch != null)
            {
                $q->where('branch_id', $branch->id);
            } else {
                $q->where('branch_id', Auth::user()->branch_id);
            }
        })->orderBy('type')->get();
        $data['fishing_gears'] = FishingGear::when(function($q) use($branch){
            if($branch != null)
            {
                $q->where('branch_id', $branch->id);
            } else {
                $q->where('branch_id', Auth::user()->branch_id);
            }
        })->orderBy('name')->get();

        return view('dashboard.fishing-data.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FishingDataRequest $request)
    {
        $branch = Branch::where('dtkn', $request->branch_id)->first();
        $data = FishingData::create([
            'branch_id' => Auth::user()->branch_id ?? $branch->id,
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'ship_id' => $request->ship_id,
            'landing_site_id' => $request->landing_site_id,
            'fishing_gear_id' => $request->fishing_gear_id,
            'operational_day' => $request->operational_day ?? 0,
            'travel_day' => $request->travel_day ?? 0,
            'setting' => $request->setting ?? 0,
            'area' => $request->area,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'flat' => $request->flat,
            'flng' => $request->flng,
            'miles' => $request->miles,
            'enumeration_time' => $request->enumeration_time,
            'gt' => $request->gt,
            'total_other_fish' => $request->total_other_fish,
            'is_htu' => $request->is_htu,
            'status' => 'menunggu persetujuan',
        ]);

        $data->update([
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
        ]);

        return redirect()->route('dashboard.fishing-data.data-collection.index', ['id' => $data->dtkn])->with('OK', 'Berhasil melakukan tambah pendataan ikan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FishingData  $fishingData
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FishingData  $fishingData
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['fishing_data'] = FishingData::where('dtkn', $id)->firstOrFail();
        $data['branches'] = Branch::orderBy('name')->get();
        $data['landing_sites'] = LandingSite::where('branch_id', $data['fishing_data']->branch_id)->orderBy('name')->get();
        $data['type_ships'] = TypeShip::where('branch_id', $data['fishing_data']->branch_id)->orderBy('type')->get();
        $data['fishing_gears'] = FishingGear::where('branch_id', $data['fishing_data']->branch_id)->orderBy('name')->get();

        return view('dashboard.fishing-data.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FishingData  $fishingData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = FishingData::where('dtkn', $id)->firstOrFail();
        $data->update([
            'ship_id' => $request->ship_id,
            'landing_site_id' => $request->landing_site_id,
            'fishing_gear_id' => $request->fishing_gear_id,
            'operational_day' => $request->operational_day,
            'travel_day' => $request->travel_day,
            'setting' => $request->setting,
            'area' => $request->area,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'flat' => $request->flat,
            'flng' => $request->flng,
            'miles' => $request->miles,
            'enumeration_time' => $request->enumeration_time,
            'gt' => $request->gt,
            'total_other_fish' => $request->total_other_fish,
            'is_htu' => $request->is_htu,
            'status' => 'menunggu persetujuan',
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
        ]);

        if(count($data->data_collections) > 0)
        {
            DataCollection::where('fishing_data_id', $data->id)->update([
                'lat' => $request->lat,
                'lng' => $request->lng,
            ]);
        }

        return redirect()->route('dashboard.fishing-data.data-collection.index', ['id' => $data->dtkn])->with('OK', 'Berhasil melakukan ubah pendataan ikan.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FishingData  $fishingData
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = FishingData::where('dtkn', $id)->firstOrFail();
        $data->delete();
        
        return redirect()->route('dashboard.fishing-data.index')->with('OK', 'Berhasil melakukan hapus pendataan ikan.');
    }

    public function verification($id)
    {
        $data = FishingData::where('dtkn', $id)->firstOrFail();
        $data->update([
            'status' => 'disetujui',
            'verification_by' => Auth::user()->name
        ]);
        
        return redirect()->route('dashboard.fishing-data.index')->with('OK', 'Berhasil melakukan verifikasi pendataan ikan.');
    }

    public function export(Request $request)
    {
        $data['fishing_data'] = FishingData::with('data_collections.species_fish.type_fish', 'fishing_gear', 'landing_site', 'ship.type_ship', 'branch')->where('dtkn', $request->id)->first();

        return Excel::download(new FishingDataExport($data), 'Pendataan Ikan.xlsx');
    }
    
    public function shipStore(Request $request)
    {
        $data = Ship::create([
            'type_ship_id' => $request->type_ship_id,
            'name' => $request->name,
            'owner' => $request->owner,
        ]);

        $data->update([
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
        ]);
 
         return redirect()->back()->with('OK', 'Berhasil melakukan tambah kapal.');
    }
    
    public function fishingGearStore(Request $request)
    {
        $branch = Branch::where('dtkn', $request->branch_id)->first();
        $data = FishingGear::create([
            'branch_id' => $branch->id,
            'name' => $request->name,
         ]);

        $data->update([
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
        ]);
 
         return redirect()->back()->with('OK', 'Berhasil melakukan tambah jenis alat tangkap.');
    }
}
