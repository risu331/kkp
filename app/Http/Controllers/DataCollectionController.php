<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\TypeFishPicture;
use App\Models\DataCollection;
use Illuminate\Http\Request;
use App\Models\FishingData;
use App\Models\SpeciesFish;
use App\Models\DataImage;
use App\Models\TypeFish;
use Auth;
use DB;

class DataCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['fishing_data'] = FishingData::where('dtkn', $request->id)->first();

        return view('dashboard.fishing-data.data-collection.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['fishing_data'] = FishingData::where('dtkn', $request->id)->first();
        $data['type_fishs'] = TypeFish::with(['species_fishs' => function($q){
            $q->orderBy('species');
        }])->where('branch_id', $data['fishing_data']->branch_id)->get();
        $species_fish = SpeciesFish::where('dtkn', $request->species_fish_id)->first();
        $data['type_fish_pictures'] = TypeFishPicture::where(function($q) use($species_fish){
            if($species_fish != null)
            {
                $q->where('type_fish_id', $species_fish->type_fish_id);
            }
        })->orderBy('index')->get();

        return view('dashboard.fishing-data.data-collection.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $fishing_data = FishingData::where('id', $request->fishing_data_id)->first();
        DB::beginTransaction();
        try {
            $data_colllection = DataCollection::create([
                'fishing_data_id' => $request->fishing_data_id,
                'species_fish_id' => $request->species_fish_id,
                'amount_fish' => $request->amount_fish,
                'pt' => $request->pt ?? 0,
                'ps' => $request->ps ?? 0,
                'lt' => $request->lt ?? 0,
                'weight' => $request->weight ?? 0,
                'gender' => $request->gender,
                'clasp_length' => $request->clasp_length ?? 0,
                'gonad' => $request->gonad,
                'amount_child' => $request->amount_child ?? 0,
                'length_min_child' => $request->length_min_child ?? 0,
                'length_max_child' => $request->length_max_child ?? 0,
                'weight_child' => $request->weight_child ?? 0,
                'sl' => $request->sl ?? 0,
                'fl' => $request->fl ?? 0,
                'tl' => $request->tl ?? 0,
                'dw' => $request->dw ?? 0,
                'm' => $request->m ?? 0,
                'p' => $request->p ?? 0,
                't' => $request->t ?? 0,
                'mp' => $request->mp ?? 0,
                'economy_price' => $request->economy_price ?? 0,
                'total_economy_price' => $request->total_economy_price ?? 0,
                'type_product' => $request->type_product,
                'description' => $request->description,
                'lat' => $fishing_data->lat,
                'lng' => $fishing_data->lng,
            ]);
    
            $data_colllection->update([
                'dtkn' => sha1('#' . $data_colllection->id . date('Y-m-d H:i'))
            ]);
    
            if($request->type_fish_picture_id != null)
            {
                foreach($request->type_fish_picture_id as $key => $id)
                {
                    $type_fish_picture = TypeFishPicture::where('id', $id)->first();
                    if(isset($request->image_file[$id]))
                    {
                        $save = $request->image_file[$id]->store('data-images');
                        $filename = $request->image_file[$id]->hashName();
                        $iconPath = url('/') . '/storage/data-images/' . $filename;
                        DataImage::create([
                            'data_collection_id' => $data_colllection->id,
                            'title' => $type_fish_picture->title,
                            'image' => $iconPath,
                            'format' => $request->image_file[$id]->getClientOriginalExtension()
                        ]);
                    } else if(isset($request->image_camera[$id])) {
                        $save = $request->image_camera[$id]->store('data-images');
                        $filename = $request->image_camera[$id]->hashName();
                        $iconPath = url('/') . '/storage/data-images/' . $filename;
                        DataImage::create([
                            'data_collection_id' => $data_colllection->id,
                            'title' => $type_fish_picture->title,
                            'image' => $iconPath,
                            'format' => $request->image_camera[$id]->getClientOriginalExtension()
                        ]);
                    }
                }
            }    
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('dashboard.fishing-data.data-collection.index', ['id' => $data_colllection->fishing_data->dtkn])->with('ERR', 'Terjadi kesalahan, silahkan hubungi developer.');
        }
        
        
        return redirect()->route('dashboard.fishing-data.data-collection.index', ['id' => $data_colllection->fishing_data->dtkn])->with('OK', 'Berhasil melakukan tambah data ikan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DataCollection  $dataCollection
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['data_collection'] = DataCollection::where('dtkn', $id)->firstOrFail();
        
        return view('dashboard.fishing-data.data-collection.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DataCollection  $dataCollection
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $data['data_collection'] = DataCollection::where('dtkn', $id)->firstOrFail();
        $data['fishing_data'] = FishingData::where('id', $data['data_collection']->fishing_data_id)->first();
        $data['type_fishs'] = TypeFish::where('branch_id', $data['data_collection']->fishing_data->branch_id)->get();
        $dtkn_species_fish = SpeciesFish::where('dtkn', $request->species_fish_id)->first();
        $species_fish = SpeciesFish::where('id', $dtkn_species_fish->id ?? $data['data_collection']->species_fish_id)->first();
        if($species_fish != null)
        {
            $data['type_fish_pictures'] = TypeFishPicture::where('type_fish_id', $species_fish->type_fish_id)->orderBy('index')->get();
        } else {
            $data['type_fish_pictures'] = TypeFishPicture::where('type_fish_id', $data['data_collection']->species_fish_id)->orderBy('index')->get();
        }

        return view('dashboard.fishing-data.data-collection.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DataCollection  $dataCollection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $data = DataCollection::where('dtkn', $id)->firstOrFail();

        $data->update([
            'species_fish_id' => $request->species_fish_id,
            'amount_fish' => $request->amount_fish,
            'pt' => $request->pt ?? 0,
            'ps' => $request->ps ?? 0,
            'lt' => $request->lt ?? 0,
            'weight' => $request->weight ?? 0,
            'gender' => $request->gender,
            'clasp_length' => $request->clasp_length ?? 0,
            'gonad' => $request->gonad ?? 0,
            'amount_child' => $request->amount_child ?? 0,
            'length_min_child' => $request->length_min_child ?? 0,
            'length_max_child' => $request->length_max_child ?? 0,
            'weight_child' => $request->weight_child ?? 0,
            'sl' => $request->sl ?? 0,
            'fl' => $request->fl ?? 0,
            'tl' => $request->tl ?? 0,
            'dw' => $request->dw ?? 0,
            'm' => $request->m ?? 0,
            'p' => $request->p ?? 0,
            't' => $request->t ?? 0,
            'mp' => $request->mp ?? 0,
            'economy_price' => $request->economy_price ?? 0,
            'total_economy_price' => $request->total_economy_price ?? 0,
            'type_product' => $request->type_product,
            'description' => $request->description,
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
        ]);

        if($request->type_fish_picture_id != null)
        {
            foreach($request->type_fish_picture_id as $key => $id)
            {
                $type_fish_picture = TypeFishPicture::where('id', $id)->first();
                if(isset($request->image_file[$id]))
                {
                    $check_image = DataImage::where('data_collection_id', $data->id)->where('title', $type_fish_picture->title)->first();
                    if($check_image != null) {
                        $imagePath = $check_image->image;
                        $unlinkPath = explode('/storage', $imagePath);
                        if(count($unlinkPath) == 2)
                        {
                            if (file_exists(public_path('/storage' . $unlinkPath[1]))) {
                                unlink(public_path('/storage' . $unlinkPath[1]));
                            }
                        }
                        $check_image->delete();
                    }
                    $save = $request->image_file[$id]->store('data-images');
                    $filename = $request->image_file[$id]->hashName();
                    $iconPath = url('/') . '/storage/data-images/' . $filename;
                    DataImage::create([
                        'data_collection_id' => $data->id,
                        'title' => $type_fish_picture->title,
                        'image' => $iconPath,
                        'format' => $request->image_file[$id]->getClientOriginalExtension()
                    ]);
                } else if(isset($request->image_camera[$id])) {
                    $check_image = DataImage::where('data_collection_id', $data->id)->where('title', $type_fish_picture->title)->first();
                    if($check_image != null) {
                        $imagePath = $check_image->image;
                        $unlinkPath = explode('/storage', $imagePath);
                        if(count($unlinkPath) == 2)
                        {
                            if (file_exists(public_path('/storage' . $unlinkPath[1]))) {
                                unlink(public_path('/storage' . $unlinkPath[1]));
                            }
                        }
                        $check_image->delete();
                    }
                    $save = $request->image_camera[$id]->store('data-images');
                    $filename = $request->image_camera[$id]->hashName();
                    $iconPath = url('/') . '/storage/data-images/' . $filename;
                    DataImage::create([
                        'data_collection_id' => $data->id,
                        'title' => $type_fish_picture->title,
                        'image' => $iconPath,
                        'format' => $request->image_camera[$id]->getClientOriginalExtension()
                    ]);
                }
            }
        }

        return redirect()->route('dashboard.fishing-data.data-collection.index', ['id' => $data->fishing_data->dtkn])->with('OK', 'Berhasil melakukan ubah data ikan.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DataCollection  $dataCollection
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = DataCollection::where('dtkn', $id)->firstOrFail();
        $fishing_id = $data->fishing_data->dtkn;
        foreach($data->data_images as $data_image)
        {
            $imagePath = $data_image->image;
            $unlinkPath = explode('/storage', $imagePath);
            if(count($unlinkPath) == 2)
            {
                if (file_exists(public_path('/storage' . $unlinkPath[1]))) {
                    unlink(public_path('/storage' . $unlinkPath[1]));
                }
            }
        }
        DataImage::where('data_collection_id', $id)->delete();   
        $data->delete();

        return redirect()->route('dashboard.fishing-data.data-collection.index', ['id' => $fishing_id])->with('OK', 'Berhasil melakukan hapus data ikan.');
    }
    
    public function verification($id)
    {
        $data = DataCollection::where('dtkn', $id)->firstOrFail();
        $data->update([
            'status' => 'disetujui',
            'verification_by' => Auth::user()->name
        ]);
        
        $checkStatus = DataCollection::where('fishing_data_id', $data->fishing_data_id)->where('verification_by', null)->get();
        if(count($checkStatus) == 0)
        {
            $data->fishing_data->update([
               'status' => 'disetujui'
            ]);
        }
        
        return redirect()->back()->with('OK', 'Berhasil melakukan verifikasi pendataan ikan.');
    }
}
