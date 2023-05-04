<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeFishPictureRequest;
use App\Models\TypeFishPicture;
use Illuminate\Http\Request;
use App\Models\TypeFish;

class TypeFishPictureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['type_fish'] = TypeFish::with(['type_fish_pictures' => function($q){
            $q->orderBy('index');
        }])->where('dtkn', $request->id ?? 0)->orderBy('type')->firstOrFail();

        return view('dashboard.type-fish.picture.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['type_fish'] = TypeFish::with('type_fish_pictures')->where('dtkn', $request->type_fish_id ?? 0)->orderBy('type')->firstOrFail();

        return view('dashboard.type-fish.picture.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TypeFishPictureRequest $request)
    {
        $sampleImagePath = '';
        if ($request->hasFile('sample_image')) {
          $save = $request->file('sample_image')->store('sample-image');
          $filename = $request->file('sample_image')->hashName();
          $sampleImagePath = url('/') . '/storage/sample-image/' . $filename;
        }

        $data = TypeFishPicture::create([
            'type_fish_id' => $request->type_fish_id,
            'index' => $request->index ?? 0,
            'title' => $request->title,
            'is_required' => $request->is_required,
            'sample_image' => $sampleImagePath,
            'sample_description' => $request->sample_description,
        ]);

        $data->update([
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
        ]);

        return redirect()->route('dashboard.type-fish.picture.index', ['id' => $data->type_fish->dtkn])->with('OK', 'Berhasil melakukan tambah pengambilan gambar ikan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TypeFishPicture  $typeFishPicture
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TypeFishPicture  $typeFishPicture
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['type_fish_picture'] = TypeFishPicture::where('dtkn', $id)->firstOrFail();
        return view('dashboard.type-fish.picture.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TypeFishPicture  $typeFishPicture
     * @return \Illuminate\Http\Response
     */
    public function update(TypeFishPictureRequest $request, $id)
    {
        $data = TypeFishPicture::where('dtkn', $id)->firstOrFail();
        
        $sampleImagePath = $data->sample_image;
        if ($request->hasFile('sample_image')) {
          $save = $request->file('sample_image')->store('sample-image');
          $filename = $request->file('sample_image')->hashName();
          $sampleImagePath = url('/') . '/storage/sample-image/' . $filename;
        }

        $data->update([
            'type_fish_id' => $request->type_fish_id,
            'index' => $request->index,
            'title' => $request->title,
            'is_required' => $request->is_required,
            'sample_image' => $sampleImagePath,
            'sample_description' => $request->sample_description,
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
        ]);

        return redirect()->route('dashboard.type-fish.picture.index', ['id' => $data->type_fish->dtkn])->with('OK', 'Berhasil melakukan ubah pengambilan gambar ikan.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TypeFishPicture  $typeFishPicture
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $data = TypeFishPicture::where('dtkn', $id)->firstOrFail();
        $type_fish_id = $data->type_fish->dtkn;
        $data->delete();

        return redirect()->route('dashboard.type-fish.picture.index', ['id' => $type_fish_id])->with('OK', 'Berhasil melakukan hapus pengambilan gambar ikan.');
    }
}
