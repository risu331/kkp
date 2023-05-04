<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Statistic1Export;
use App\Exports\Statistic2Export;
use App\Exports\Statistic3Export;
use App\Exports\Statistic4Export;
use App\Exports\Statistic5Export;
use App\Exports\Statistic6Export;
use App\Exports\Statistic7Export;
use Illuminate\Http\Request;
use App\Models\FishingGear;
use App\Models\SpeciesFish;
use App\Models\LandingSite;
use App\Models\TypeFish;
use App\Models\Branch;

class StatisticExportController extends Controller
{
    public function index1(Request $request)
    {
        $landing_site = $request->landing_site_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $category = $request->category;
        // $species = explode(',', $request->species_fish_id);

        $ls = LandingSite::where('dtkn', $landing_site)->first();
        $branch = Branch::where('dtkn', $request->branch_id)->first();
        $speciesFishes = [];
        if($ls != null)
        {
            $speciesFishes = SpeciesFish::
            with(['data_collections' => function($q) use($start_date, $end_date, $ls, $branch){
                $q->whereHas('fishing_data', function($q1) use($start_date, $end_date, $ls, $branch){
                    $q1->where('branch_id', $branch->id);
                    $q1->where('landing_site_id', $ls->id);
                    $q1->whereDate('enumeration_time', '>=', $start_date);
                    $q1->whereDate('enumeration_time', '<=', $end_date);
                    $q1->where('status', 'disetujui');
                });
            }])
            // ->whereIn('id', $species)
            ->get()
            ->map(function($data, $key) use($landing_site, $category)
            {		
                $data['amount_fish'] = $data->data_collections->sum('amount_fish');
                $data['pt'] = $data->data_collections->sum('pt');
                $data['ps'] = $data->data_collections->sum('ps');
                $data['lt'] = $data->data_collections->sum('lt');
                $data['weight'] = $data->data_collections->sum('weight');
                return $data;
            })->sortByDesc('amount_fish');
        }

        $data['speciesFishs'] = $speciesFishes;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['landing_site'] = $ls;
        $data['branch'] = $branch;
        
        // dd($data);

        return Excel::download(new Statistic1Export($data), 'Statistik Komposisi Hasil Tangkap Ikan ('. date('F d, Y', strtotime($start_date)) . ' - '. date('F d, Y', strtotime($end_date)) .').xlsx');
    }

    public function index2(Request $request)
    {
        $year = $request->year;
        // $species = explode(',', $request->species_fish_id);
        $arrayMonth = $request->month ?? ['01','02','03','04','05','06','07','08','09','10','11','12'];
        $months = explode(',', $arrayMonth);

        $branch = Branch::where('dtkn', $request->branch_id)->first();
        $speciesFishes = [];
        if($branch != null)
        {
            $speciesFishes = SpeciesFish::
            with(['data_collections' => function($q) use($year, $branch, $months){
                $q->whereHas('fishing_data', function($q1) use($year, $branch, $months){
                    $q1->where('branch_id', $branch->id);
                    $q1->whereDate('enumeration_time', '>=', $year .'-' . $months[0] . '-01');
                    $q1->whereDate('enumeration_time', '<=', $year .'-' . $months[count($months) - 1] . '-31');
                    $q1->where('status', 'disetujui');
                });
            }])
            // ->whereIn('id', $species)
            ->get()
            ->map(function($data, $key)
            {		
                $data['amount_fish'] = $data->data_collections->sum('amount_fish');
                $data['weight'] = $data->data_collections->sum('weight');
                return $data;
            })->sortByDesc('amount_fish');
        }

        $data['speciesFishs'] = $speciesFishes;
        $data['year'] = $year;
        $data['month'] = null;
        foreach($months as $month)
        {
            $data['month'] = $data['month'] . date('F', strtotime('2023-' . $month)) . ', ';
        }
        $data['branch'] = $branch;

        return Excel::download(new Statistic2Export($data), 'Statistik Data Jenis Ikan.xlsx');
    }

    public function index3(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $category = $request->category;
        // $species = explode(',', $request->species_fish_id);

        $branch = Branch::where('dtkn', $request->branch_id)->first();
        $speciesFishes = [];
        if($branch != null)
        {
            $speciesFishes = SpeciesFish::
            with(['data_collections' => function($q) use($start_date, $end_date, $branch){
                $q->whereHas('fishing_data', function($q1) use($start_date, $end_date, $branch){
                    $q1->where('branch_id', $branch->id);
                    $q1->whereDate('enumeration_time', '>=', $start_date);
                    $q1->whereDate('enumeration_time', '<=', $end_date);
                    $q1->where('status', 'disetujui');
                });
            }])
            // ->whereIn('id', $species)
            ->get()
            ->map(function($data, $key)
            {		
                $data['amount_fish'] = $data->data_collections->sum('amount_fish');
                $data['weight'] = $data->data_collections->sum('weight');
                return $data;
            })->sortByDesc('amount_fish');
        }

        $data['speciesFishs'] = $speciesFishes;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['branch'] = $branch;

        return Excel::download(new Statistic3Export($data), 'Statistik Status Appendiks ('. date('F d, Y', strtotime($start_date)) . ' - '. date('F d, Y', strtotime($end_date)) .').xlsx');
    }

    public function index4(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $category = $request->category;
        // $species = explode(',', $request->species_fish_id);

        $branch = Branch::where('dtkn', $request->branch_id)->first();
        $speciesFishes = [];
        if($branch != null)
        {
            $speciesFishes = SpeciesFish::
            with(['data_collections' => function($q) use($start_date, $end_date, $branch){
                $q->whereHas('fishing_data', function($q1) use($start_date, $end_date, $branch){
                    $q1->where('branch_id', $branch->id);
                    $q1->whereDate('enumeration_time', '>=', $start_date);
                    $q1->whereDate('enumeration_time', '<=', $end_date);
                    $q1->where('status', 'disetujui');
                });
            }])
            // ->whereIn('id', $species)
            ->where('group', $request->status)
            ->get()
            ->map(function($data, $key) use($branch)
            {		
                $fishing_gears = FishingGear::where('branch_id', $branch->id)->get();
                $arr = [];
                foreach($fishing_gears as $fishing_gear){
                    $weight = 0;
                    $amount = 0;
                    foreach($data->data_collections as $data_collection)
                    {
                        if($data_collection->fishing_data->fishing_gear_id == $fishing_gear->id)
                        {
                            $amount += $data_collection->amount_fish;
                            $weight += $data_collection->weight;
                        }
                    }
                    array_push($arr, [
                        'gear' => $fishing_gear->name,
                        'amount_fish' => $amount,
                        'weight' => $weight,
                    ]);
                }
                $data['fishing_gears'] = $arr;
                return $data;
            })->sortByDesc('amount_fish');
        }

        $data['speciesFishs'] = $speciesFishes;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['branch'] = $branch;
        $data['status'] = $request->status;
        
        // dd($data);
        return Excel::download(new Statistic4Export($data), 'Statistik Alat Tangkap ('. date('F d, Y', strtotime($start_date)) . ' - '. date('F d, Y', strtotime($end_date)) .').xlsx');
    }

    public function index5(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        // $species = explode(',', $request->species_fish_id);

        $branch = Branch::where('dtkn', $request->branch_id)->first();
        $speciesFishes = [];
        if($branch != null)
        {
            $speciesFishes = SpeciesFish::
            with(['data_collections' => function($q) use($start_date, $end_date, $branch){
                $q->whereHas('fishing_data', function($q1) use($start_date, $end_date, $branch){
                    $q1->where('branch_id', $branch->id);
                    $q1->whereDate('enumeration_time', '>=', $start_date);
                    $q1->whereDate('enumeration_time', '<=', $end_date,);
                    $q1->where('status', 'disetujui');
                });
            }])
            // ->whereIn('id', $species)
            ->get()
            ->map(function($data, $key) use($branch)
            {		
                $data['j'] = 0;
                $data['b'] = 0;
                $data['tkg1'] = 0;
                $data['tkg2'] = 0;
                $data['tkg3'] = 0;
                foreach($data->data_collections as $data_collection)
                {
                    if($data_collection->gender == 'j'){
                        $data['j'] += 1;
                    } else if($data_collection->gender == 'b') {
                        $data['b'] += 1;
                    }
                    
                    if($data_collection->gonad == 1){
                        $data['tkg1'] += 1;
                    } else if($data_collection->gonad == 2) {
                        $data['tkg2'] += 1;
                    } else if($data_collection->gonad == 3) {
                        $data['tkg3'] += 1;
                    }
                }

                return $data;
            })->sortByDesc('amount_fish');
        }

        $data['speciesFishs'] = $speciesFishes;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['branch'] = $branch;
        
        return Excel::download(new Statistic5Export($data), 'Statistik Jenis Kelamin ('. date('F d, Y', strtotime($start_date)) . ' - '. date('F d, Y', strtotime($end_date)) .').xlsx');
    }

    public function index6(Request $request)
    {
        $year = $request->year;
        $species = $request->species_fish_id;
        $arrayMonth = $request->month ?? ['01','02','03','04','05','06','07','08','09','10','11','12'];
        $months = explode(',', $arrayMonth);

        $branch = Branch::where('dtkn', $request->branch_id)->first();
        $tf = TypeFish::where('dtkn', $request->type_fish_id)->first();
        $speciesFishes = [];
        if($branch != null)
        {
            if($species == null)
            {
                $speciesFishes = SpeciesFish::
                with(['data_collections' => function($q) use($year, $branch, $months){
                    $q->whereHas('fishing_data', function($q1) use($year, $branch, $months){
                        $q1->where('branch_id', $branch->id);
                        $q1->whereDate('enumeration_time', '>=', $year .'-' . $months[0] . '-01');
                        $q1->whereDate('enumeration_time', '<=', $year .'-' . $months[count($months) - 1] . '-31');
                        $q1->where('status', 'disetujui');
                    });
                }])
                ->where('type_fish_id', $tf->id)
                ->get()
                ->map(function($data, $key)
                {		
                    $data['economy'] = $data->data_collections->sum('total_economy_price');
                    return $data;
                })->sortByDesc('economy');
            } else {
                $speciesFishes = SpeciesFish::
                with(['data_collections' => function($q) use($year, $branch, $months){
                    $q->whereHas('fishing_data', function($q1) use($year, $branch, $months){
                        $q1->where('branch_id', $branch->id);
                        $q1->whereDate('enumeration_time', '>=', $year .'-' . $months[0] . '-01');
                        $q1->whereDate('enumeration_time', '<=', $year .'-' . $months[count($months) - 1] . '-31');
                        $q1->where('status', 'disetujui');
                    });
                }])
                ->where('dtkn', $species)
                ->get()
                ->map(function($data, $key)
                {		
                    $data['economy'] = $data->data_collections->sum('total_economy_price');
                    return $data;
                })->sortByDesc('economy');
            }
        }

        $data['speciesFishs'] = $speciesFishes;
        $data['year'] = $year;
        $data['month'] = null;
        foreach($months as $month)
        {
            $data['month'] = $data['month'] . date('F', strtotime('2023-' . $month)) . ', ';
        }
        $data['branch'] = $branch;

        return Excel::download(new Statistic6Export($data), 'Statistik Harga Ekonomi.xlsx');
    }
    
    public function index7(Request $request)
    {
        $year = $request->year;
        $species = $request->species_fish_id;
        $arrayMonth = $request->month ?? ['01','02','03','04','05','06','07','08','09','10','11','12'];
        $months = explode(',', $arrayMonth);

        $branch = Branch::where('dtkn', $request->branch_id)->first();
        $tf = TypeFish::where('dtkn', $request->type_fish_id)->first();
        $sf = SpeciesFish::where('dtkn', $species)->first();
        $speciesFishes = [];
        if($branch != null)
        {
            $speciesFishes['born'] = SpeciesFish::
            with(['data_collections' => function($q) use($year, $branch, $months, $sf){
                $q->where('tl', '>=', $sf->born_start ?? 0);
                $q->where('tl', '<=', $sf->born_end ?? 0);
                $q->whereHas('fishing_data', function($q1) use($year, $branch, $months){
                    $q1->where('branch_id', $branch->id);
                    $q1->whereDate('enumeration_time', '>=', $year .'-' . $months[0] . '-01');
                    $q1->whereDate('enumeration_time', '<=', $year .'-' . $months[count($months) - 1] . '-31');
                    $q1->where('status', 'disetujui');
                });
            }])
            ->where('dtkn', $species)
            ->get()
            ->map(function($data, $key)
            {		
                $data['amount_fish'] = $data->data_collections->sum('amount_fish');
                return $data;
            })->sortByDesc('amount_fish');
            
            $speciesFishes['mature_male'] = SpeciesFish::
            with(['data_collections' => function($q) use($year, $branch, $months, $sf){
                $q->where('tl', '>=', $sf->mature_male_start ?? 0);
                $q->where('tl', '<', $sf->mega_spawner ?? 0);
                $q->where('gender', 'j');
                $q->whereHas('fishing_data', function($q1) use($year, $branch, $months){
                    $q1->where('branch_id', $branch->id);
                    $q1->whereDate('enumeration_time', '>=', $year .'-' . $months[0] . '-01');
                    $q1->whereDate('enumeration_time', '<=', $year .'-' . $months[count($months) - 1] . '-31');
                    $q1->where('status', 'disetujui');
                });
            }])
            ->where('dtkn', $species)
            ->get()
            ->map(function($data, $key)
            {		
                $data['amount_fish'] = $data->data_collections->sum('amount_fish');
                return $data;
            })->sortByDesc('amount_fish');
            
            $speciesFishes['mature_female'] = SpeciesFish::
            with(['data_collections' => function($q) use($year, $branch, $months, $sf){
                $q->where('tl', '>=', $sf->mature_female_start ?? 0);
                $q->where('tl', '<', $sf->mega_spawner ?? 0);
                $q->where('gender', 'b');
                $q->whereHas('fishing_data', function($q1) use($year, $branch, $months){
                    $q1->where('branch_id', $branch->id);
                    $q1->whereDate('enumeration_time', '>=', $year .'-' . $months[0] . '-01');
                    $q1->whereDate('enumeration_time', '<=', $year .'-' . $months[count($months) - 1] . '-31');
                    $q1->where('status', 'disetujui');
                });
            }])
            ->where('dtkn', $species)
            ->get()
            ->map(function($data, $key)
            {		
                $data['amount_fish'] = $data->data_collections->sum('amount_fish');
                return $data;
            })->sortByDesc('amount_fish');
            
            $speciesFishes['mega_spawner'] = SpeciesFish::
            with(['data_collections' => function($q) use($year, $branch, $months, $sf){
                $q->where('tl', '>=', $sf->mega_spawner ?? 0);
                $q->whereHas('fishing_data', function($q1) use($year, $branch, $months){
                    $q1->where('branch_id', $branch->id);
                    $q1->whereDate('enumeration_time', '>=', $year .'-' . $months[0] . '-01');
                    $q1->whereDate('enumeration_time', '<=', $year .'-' . $months[count($months) - 1] . '-31');
                    $q1->where('status', 'disetujui');
                });
            }])
            ->where('dtkn', $species)
            ->get()
            ->map(function($data, $key)
            {		
                $data['amount_fish'] = $data->data_collections->sum('amount_fish');
                return $data;
            })->sortByDesc('amount_fish');
            
            $speciesFishes['uncategory'] = SpeciesFish::
            with(['data_collections' => function($q) use($year, $branch, $months, $sf){
                // $q->where('tl', '>=', $sf->mega_spawner ?? 0);
                $q->whereHas('fishing_data', function($q1) use($year, $branch, $months){
                    $q1->where('branch_id', $branch->id);
                    $q1->whereDate('enumeration_time', '>=', $year .'-' . $months[0] . '-01');
                    $q1->whereDate('enumeration_time', '<=', $year .'-' . $months[count($months) - 1] . '-31');
                    $q1->where('status', 'disetujui');
                });
            }])
            ->where('dtkn', $species)
            ->get()
            ->map(function($data, $key)
            {		
                $data['amount_fish'] = $data->data_collections->sum('amount_fish');
                return $data;
            })->sortByDesc('amount_fish');
        }
        $data['speciesFishs'] = [$speciesFishes['born'][0]->amount_fish, $speciesFishes['mature_male'][0]->amount_fish, $speciesFishes['mature_female'][0]->amount_fish, $speciesFishes['mega_spawner'][0]->amount_fish, $speciesFishes['uncategory'][0]->amount_fish];
        $data['species'] = $sf;
        $data['year'] = $year;
        $data['month'] = null;
        foreach($months as $month)
        {
            $data['month'] = $data['month'] . date('F', strtotime('2023-' . $month)) . ', ';
        }
        $data['branch'] = $branch;
        

        return Excel::download(new Statistic7Export($data), 'Statistik Frekuensi Panjang.xlsx');
    }
}
