<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchRequest;
use Illuminate\Http\Request;
use App\Models\Branch;
use Auth;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role != 'superadmin')
        {
            abort(404);
        }
        $data['branches'] = Branch::where('deleted_at', null)->orderBy('name')->get();

        return view('dashboard.branch.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role != 'superadmin')
        {
            abort(404);
        }
        return view('dashboard.branch.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BranchRequest $request)
    {
        $data = Branch::create([
            'name' => $request->name,
            'lat' => $request->lat,
            'lng' => $request->lng,
         ]);

         $data->update([
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
         ]);
 
         return redirect()->route('dashboard.branch.index')->with('OK', 'Berhasil melakukan tambah wilayah kerja.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->role != 'superadmin')
        {
            abort(404);
        }
        $data['branch'] = Branch::where('dtkn', $id)->firstOrFail();
        
        return view('dashboard.branch.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(BranchRequest $request, $id)
    {
        $data = Branch::where('dtkn', $id)->firstOrFail();
        $data->update([
            'name' => $request->name,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'dtkn' => sha1('#' . $data->id . date('Y-m-d H:i'))
        ]);

        return redirect()->route('dashboard.branch.index')->with('OK', 'Berhasil melakukan ubah wilayah kerja.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Branch::where('dtkn', $id)->firstOrFail();
        $data->update([
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->route('dashboard.branch.index')->with('OK', 'Berhasil melakukan hapus wilayah kerja.');
    }
}
