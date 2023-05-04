<?php

namespace App\Http\Controllers;

use App\Models\DataCollection;
use Illuminate\Http\Request;
use App\Models\SpeciesFish;
use App\Models\LandingSite;
use App\Models\FishingData;
use App\Models\FishingGear;
use App\Models\TypeFish;
use App\Models\Branch;
use Auth;

class StatisticController extends Controller
{
    public function index1(Request $request)
    {
        // dd($request->all());
        $data['branches'] = Branch::orderBy('name')->get();
        $data['type_fishes'] = TypeFish::when(function($q) use($request){
            if(Auth::user()->role == 'superadmin'){
                $q->whereHas('branch', function($q1) use($request){
                    $q1->where('dtkn', $request->branch_id);
                });
            } else {
                $q->where('branch_id', Auth::user()->branch_id);
            }
        })->orderBy('type')->get();
        $data['landing_sites'] = LandingSite::when(function($q) use($request){
            if(Auth::user()->role == 'superadmin'){
                $q->whereHas('branch', function($q1) use($request){
                    $q1->where('dtkn', $request->branch_id);
                });
            } else {
                $q->where('branch_id', Auth::user()->branch_id);
            }
        })->orderBy('name')->get();

        if(Auth::user()->role == 'superadmin'){
            $branch = Branch::where('dtkn', $request->branch_id)->first();
        } else {
            $branch = Branch::where('id', Auth::user()->branch_id)->first();
        }

        $type_fish = TypeFish::where('dtkn', $request->type_fish_id)->where('branch_id', $branch->id ?? 0)->first();
        
        $explodeDate = explode('/', $request->date);
        
        if(count($explodeDate) == 2)
        {
            $data['start_date'] = $explodeDate[0];
            $data['end_date'] = $explodeDate[1];
        } else {
            $data['start_date'] = date('Y-m-d');
            $data['end_date'] = date('Y-m-d');
        }
        
        $data['statistic'] = $this->data1($branch, $data['start_date'], $data['end_date'], $request->type_fish_id, $request->landing_site_id, $request->category);

        $data['json'] = [];
        if($type_fish != null)
        {
            $data['json'] = $this->jsonData($type_fish, $branch);
        }

        return view('dashboard.statistic.1.index', $data);
    }

    public function index2(Request $request)
    {
        $data['branches'] = Branch::orderBy('name')->get();
        $data['type_fishes'] = TypeFish::when(function($q) use($request){
            if(Auth::user()->role == 'superadmin'){
                $q->whereHas('branch', function($q1) use($request){
                    $q1->where('dtkn', $request->branch_id);
                });
            } else {
                $q->where('branch_id', Auth::user()->branch_id);
            }
        })->orderBy('type')->get();

        if(Auth::user()->role == 'superadmin'){
            $branch = Branch::where('dtkn', $request->branch_id)->first();
        } else {
            $branch = Branch::where('id', Auth::user()->branch_id)->first();
        }

        $type_fish = TypeFish::where('dtkn', $request->type_fish_id)->where('branch_id', $branch->id ?? 0)->first();
        
        $data['month'] = implode(',', $request->month ?? ['01','02','03','04','05','06','07','08','09','10','11','12']);
        
        $data['statistic'] = $this->data2($branch, $request->year, $request->month, $request->type_fish_id);

        $data['json'] = [];
        if($type_fish != null)
        {
            $data['json'] = $this->jsonData($type_fish, $branch);
        }

        return view('dashboard.statistic.2.index', $data);
    }

    public function index3(Request $request)
    {
        $data['branches'] = Branch::orderBy('name')->get();
        $data['type_fishes'] = TypeFish::when(function($q) use($request){
            if(Auth::user()->role == 'superadmin'){
                $q->whereHas('branch', function($q1) use($request){
                    $q1->where('dtkn', $request->branch_id);
                });
            } else {
                $q->where('branch_id', Auth::user()->branch_id);
            }
        })->orderBy('type')->get();

        if(Auth::user()->role == 'superadmin'){
            $branch = Branch::where('dtkn', $request->branch_id)->first();
        } else {
            $branch = Branch::where('id', Auth::user()->branch_id)->first();
        }
        
        $type_fish = TypeFish::where('dtkn', $request->type_fish_id)->where('branch_id', $branch->id ?? 0)->first();
        
        $explodeDate = explode('/', $request->date);
        
        if(count($explodeDate) == 2)
        {
            $data['start_date'] = $explodeDate[0];
            $data['end_date'] = $explodeDate[1];
        } else {
            $data['start_date'] = date('Y-m-d');
            $data['end_date'] = date('Y-m-d');
        }

        $data['statistic'] = $this->data3($branch, $data['start_date'], $data['end_date'], $request->type_fish_id, $request->category);

        $data['json'] = [];
        if($type_fish != null)
        {
            $data['json'] = $this->jsonData($type_fish, $branch);
        }


        return view('dashboard.statistic.3.index', $data);
    }

    public function index4(Request $request)
    {
        $data['branches'] = Branch::orderBy('name')->get();
        $data['type_fishes'] = TypeFish::when(function($q) use($request){
            if(Auth::user()->role == 'superadmin'){
                $q->whereHas('branch', function($q1) use($request){
                    $q1->where('dtkn', $request->branch_id);
                });
            } else {
                $q->where('branch_id', Auth::user()->branch_id);
            }
        })->orderBy('type')->get();

        if(Auth::user()->role == 'superadmin'){
            $branch = Branch::where('dtkn', $request->branch_id)->first();
        } else {
            $branch = Branch::where('id', Auth::user()->branch_id)->first();
        }

        $type_fish = TypeFish::where('dtkn', $request->type_fish_id)->where('branch_id', $branch->id ?? 0)->first();
        
        $explodeDate = explode('/', $request->date);
        
        if(count($explodeDate) == 2)
        {
            $data['start_date'] = $explodeDate[0];
            $data['end_date'] = $explodeDate[1];
        } else {
            $data['start_date'] = date('Y-m-d');
            $data['end_date'] = date('Y-m-d');
        }
        
        $data['statistic'] = $this->data4($branch, $data['start_date'], $data['end_date'], $request->type_fish_id, $request->category);
        // dd($data['statistic']);

        $data['jsonAppendiks'] = [];
        $data['jsonNonAppendiks'] = [];
        if($type_fish != null)
        {
            $data['jsonAppendiks'] = $this->jsonDataAppendiks($type_fish, $branch);
            $data['jsonNonAppendiks'] = $this->jsonDataNonAppendiks($type_fish, $branch);
        }

        return view('dashboard.statistic.4.index', $data);
    }

    public function index5(Request $request)
    {
        $data['branches'] = Branch::orderBy('name')->get();
        $data['type_fishes'] = TypeFish::when(function($q) use($request){
            if(Auth::user()->role == 'superadmin'){
                $q->whereHas('branch', function($q1) use($request){
                    $q1->where('dtkn', $request->branch_id);
                });
            } else {
                $q->where('branch_id', Auth::user()->branch_id);
            }
        })->orderBy('type')->get();

        if(Auth::user()->role == 'superadmin'){
            $branch = Branch::where('dtkn', $request->branch_id)->first();
        } else {
            $branch = Branch::where('id', Auth::user()->branch_id)->first();
        }
        
        $type_fish = TypeFish::where('dtkn', $request->type_fish_id)->where('branch_id', $branch->id ?? 0)->first();

        $explodeDate = explode('/', $request->date);
        
        if(count($explodeDate) == 2)
        {
            $data['start_date'] = $explodeDate[0];
            $data['end_date'] = $explodeDate[1];
        } else {
            $data['start_date'] = date('Y-m-d');
            $data['end_date'] = date('Y-m-d');
        }
    
        $data['statistic'] = $this->data5($branch, $data['start_date'], $data['end_date'], $request->type_fish_id);
        // dd($data['statistic']);

        $data['json'] = [];
        if($type_fish != null)
        {
            $data['json'] = $this->jsonData($type_fish, $branch);
        }

        return view('dashboard.statistic.5.index', $data);
    }

    public function index6(Request $request)
    {
        $data['branches'] = Branch::orderBy('name')->get();
        $data['type_fishes'] = TypeFish::when(function($q) use($request){
            if(Auth::user()->role == 'superadmin'){
                $q->whereHas('branch', function($q1) use($request){
                    $q1->where('dtkn', $request->branch_id);
                });
            } else {
                $q->where('branch_id', Auth::user()->branch_id);
            }
        })->orderBy('type')->get();

        if(Auth::user()->role == 'superadmin'){
            $branch = Branch::where('dtkn', $request->branch_id)->first();
        } else {
            $branch = Branch::where('id', Auth::user()->branch_id)->first();
        }

        $type_fish = TypeFish::where('dtkn', $request->type_fish_id)->where('branch_id', $branch->id ?? 0)->first();
        
        $data['month'] = implode(',', $request->month ?? ['01','02','03','04','05','06','07','08','09','10','11','12']);
        
        $data['species_fish_id'] = $request->species_fish_id;
        
        $data['statistic'] = $this->data6($branch, $request->year, $request->month, $request->type_fish_id, $request->species_fish_id);
        // dd($data['statistic']);

        $data['json'] = [];
        if($type_fish != null)
        {
            $data['json'] = $this->jsonData($type_fish, $branch);
        }

        return view('dashboard.statistic.6.index', $data);
    }
    
    public function index7(Request $request)
    {
        $data['branches'] = Branch::orderBy('name')->get();
        
        $data['type_fishes'] = TypeFish::when(function($q) use($request){
            if(Auth::user()->role == 'superadmin'){
                $q->whereHas('branch', function($q1) use($request){
                    $q1->where('dtkn', $request->branch_id);
                });
            } else {
                $q->where('branch_id', Auth::user()->branch_id);
            }
        })->orderBy('type')->get();
        
        if(Auth::user()->role == 'superadmin'){
            $branch = Branch::where('dtkn', $request->branch_id)->first();
        } else {
            $branch = Branch::where('id', Auth::user()->branch_id)->first();
        }
        
        $type_fish = TypeFish::where('dtkn', $request->type_fish_id)->where('branch_id', $branch->id ?? 0)->first();

        $data['month'] = implode(',', $request->month ?? ['01','02','03','04','05','06','07','08','09','10','11','12']);
        
        $data['species_fish_id'] = $request->species_fish_id;
        
        $data['statistic'] = $this->data7($branch, $request->year, $request->month, $request->type_fish_id, $request->species_fish_id);
        // dd($data['statistic']);

        $data['json'] = [];
        if($type_fish != null)
        {
            $data['json'] = $this->jsonData($type_fish, $branch);
        }
        
        return view('dashboard.statistic.7.index', $data);
    }

    public function data1($branch, $start_date, $end_date, $type_fish, $landing_site, $category)
    {
        $tf = TypeFish::where('dtkn', $type_fish)->first();
        $ls = LandingSite::where('dtkn', $landing_site)->first();
        $speciesFishes = [];
        if($tf != null && $ls != null)
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
            ->where('type_fish_id', $tf->id)
            ->get()
            ->map(function($data, $key) use($landing_site, $category)
            {		
                if($category == 'amount_fish')
                {
                    $data['amount_fish'] = $data->data_collections->sum('amount_fish');
                } else {
                    $data['amount_fish'] = $data->data_collections->sum('weight');
                }
                $data['color'] = '#' . substr(md5(rand()), 0, 6);
                return $data;
            })->sortByDesc('amount_fish')->take(10);
        }

        $data = [
            'labels' => [],
            'colors' => [],
            'datas' => []
        ];

        foreach($speciesFishes as $key => $speciesFish)
        {
            if($speciesFish->amount_fish > 0)
            {
                array_push($data['labels'], $speciesFish->species);
                array_push($data['colors'], $speciesFish->color);
                array_push($data['datas'], $speciesFish->amount_fish);
            }
        }

        return $data;
    }

    public function data2($branch, $year, $arrayMonth, $type_fish)
    {
        $tf = TypeFish::where('dtkn', $type_fish)->first();
        $typeFishData = [];
        $data = [
            'bar' => [],
            'line' => [],
            'month' => []
        ];
        if($tf != null)
        {
            $months = $arrayMonth ?? ['01','02','03','04','05','06','07','08','09','10','11','12'];
            foreach($months as $month)
            {
                $typeFishData = DataCollection::
                whereHas('species_fish', function($q) use($tf){
                    $q->where('type_fish_id', $tf->id);
                })
                ->whereHas('fishing_data', function($q) use($branch, $year, $month){
                    $q->where('branch_id', $branch->id);
                    $q->whereDate('enumeration_time', '>=', $year .'-' . $month . '-01');
                    $q->whereDate('enumeration_time', '<=', $year .'-' . $month . '-31');
                    $q->where('status', 'disetujui');
                })
                ->get();  

                $weight = 0;
                $amountFish = 0;
                foreach($typeFishData as $typeFish)
                {
                    $weight += $typeFish->weight;
                    $amountFish += $typeFish->amount_fish;
                }
                array_push($data['bar'], $weight);
                array_push($data['line'], $amountFish);
                array_push($data['month'], date('F', strtotime('2023-' . $month)));
            }

        }
    
        return $data;
    }

    public function data3($branch, $start_date, $end_date, $type_fish, $category)
    {
        $tf = TypeFish::where('dtkn', $type_fish)->first();
        $typeFishData = [];
        $data = [];
        if($tf != null)
        {
                $appendiks = DataCollection::
                whereHas('species_fish', function($q) use($tf){
                    $q->where('type_fish_id', $tf->id);
                    $q->where('group', 'appendiks');
                })
                ->whereHas('fishing_data', function($q) use($branch, $start_date, $end_date){
                    $q->where('branch_id', $branch->id);
                    $q->whereDate('enumeration_time', '>=',  $start_date);
                    $q->whereDate('enumeration_time', '<=', $end_date);
                    $q->where('status', 'disetujui');
                })
                ->sum($category);

                $nonAppendiks = DataCollection::
                whereHas('species_fish', function($q) use($tf){
                    $q->where('type_fish_id', $tf->id);
                    $q->where('group', 'non-appendiks');
                })
                ->whereHas('fishing_data', function($q) use($branch, $start_date, $end_date){
                    $q->where('branch_id', $branch->id);
                    $q->whereDate('enumeration_time', '>=',  $start_date);
                    $q->whereDate('enumeration_time', '<=', $end_date);
                    $q->where('status', 'disetujui');
                })
                ->sum($category);

                array_push($data, $appendiks);
                array_push($data, $nonAppendiks);

        }

        return $data;
    }

    public function data4($branch, $start_date, $end_date, $type_fish, $category)
    {
        $tf = TypeFish::where('dtkn', $type_fish)->first();
        $typeFishData = [];
        $data = [
            'labels' => [],
            'colors' => [],
            'non-labels' => [],
            'non-colors' => [],
            'appendiks' => [],
            'non-appendiks' => [],
        ];
        if($tf != null)
        {   
            $fishinGears = FishingGear::where('branch_id', $branch->id)->orderBy('name')->get();

            foreach($fishinGears as $fishinGear)
            {
                $appendiks = DataCollection::
                whereHas('species_fish', function($q) use($tf){
                    $q->where('type_fish_id', $tf->id);
                    $q->where('group', 'appendiks');
                })
                ->whereHas('fishing_data', function($q) use($branch, $start_date, $end_date, $fishinGear){
                    $q->where('branch_id', $branch->id);
                    $q->where('fishing_gear_id', $fishinGear->id);
                    $q->whereDate('enumeration_time', '>=',  $start_date);
                    $q->whereDate('enumeration_time', '<=', $end_date);
                    $q->where('status', 'disetujui');
                })->sum($category);

                $nonAppendiks = DataCollection::
                whereHas('species_fish', function($q) use($tf){
                    $q->where('type_fish_id', $tf->id);
                    $q->where('group', 'non-appendiks');
                })
                ->whereHas('fishing_data', function($q) use($branch, $start_date, $end_date, $fishinGear){
                    $q->where('branch_id', $branch->id);
                    $q->where('fishing_gear_id', $fishinGear->id);
                    $q->whereDate('enumeration_time', '>=',  $start_date);
                    $q->whereDate('enumeration_time', '<=', $end_date);
                    $q->where('status', 'disetujui');
                })->sum($category);
                
                if($appendiks > 0)
                {
                    array_push($data['appendiks'], $appendiks);
                    array_push($data['labels'], $fishinGear->name);
                    array_push($data['colors'], '#' . substr(md5(rand()), 0, 6));
                }
                
                if($nonAppendiks > 0)
                {
                    array_push($data['non-appendiks'], $nonAppendiks);
                    array_push($data['non-labels'], $fishinGear->name);
                    array_push($data['non-colors'], '#' . substr(md5(rand()), 0, 6));
                }
            }

            
        }

        return $data;
    }

    public function data5($branch, $start_date, $end_date, $type_fish)
    {
        $tf = TypeFish::where('dtkn', $type_fish)->first();
        $data = [
            'gender' => [],
            'tkg' => [],
        ];
        if($tf != null)
        {   

            $male = DataCollection::
            whereHas('species_fish', function($q) use($tf){
                $q->where('type_fish_id', $tf->id);
            })
            ->where('gender', 'j')
            ->whereHas('fishing_data', function($q) use($branch, $start_date, $end_date){
                $q->where('branch_id', $branch->id);
                $q->whereDate('enumeration_time', '>=',  $start_date);
                $q->whereDate('enumeration_time', '<=', $end_date);
                $q->where('status', 'disetujui');
            })->sum('amount_fish');

            $female = DataCollection::
            whereHas('species_fish', function($q) use($tf){
                $q->where('type_fish_id', $tf->id);
            })
            ->where('gender', 'b')
            ->whereHas('fishing_data', function($q) use($branch, $start_date, $end_date){
                $q->where('branch_id', $branch->id);
                $q->whereDate('enumeration_time', '>=',  $start_date);
                $q->whereDate('enumeration_time', '<=', $end_date);
                $q->where('status', 'disetujui');
            })->sum('amount_fish');

            $tkg1 = DataCollection::
            whereHas('species_fish', function($q) use($tf){
                $q->where('type_fish_id', $tf->id);
            })
            ->where('gender', 'j')
            ->where('gonad', '1')
            ->whereHas('fishing_data', function($q) use($branch, $start_date, $end_date){
                $q->where('branch_id', $branch->id);
                $q->whereDate('enumeration_time', '>=',  $start_date);
                $q->whereDate('enumeration_time', '<=', $end_date);
                $q->where('status', 'disetujui');
            })->sum('amount_fish');

            $tkg2 = DataCollection::
            whereHas('species_fish', function($q) use($tf){
                $q->where('type_fish_id', $tf->id);
            })
            ->where('gender', 'j')
            ->where('gonad', '2')
            ->whereHas('fishing_data', function($q) use($branch, $start_date, $end_date){
                $q->where('branch_id', $branch->id);
                $q->whereDate('enumeration_time', '>=',  $start_date);
                $q->whereDate('enumeration_time', '<=', $end_date);
                $q->where('status', 'disetujui');
            })->sum('amount_fish');

            $tkg3 = DataCollection::
            whereHas('species_fish', function($q) use($tf){
                $q->where('type_fish_id', $tf->id);
            })
            ->where('gender', 'j')
            ->where('gonad', '3')
            ->whereHas('fishing_data', function($q) use($branch, $start_date, $end_date){
                $q->where('branch_id', $branch->id);
                $q->whereDate('enumeration_time', '>=',  $start_date);
                $q->whereDate('enumeration_time', '<=', $end_date);
                $q->where('status', 'disetujui');
            })->sum('amount_fish');

            array_push($data['gender'], $male, $female);
            array_push($data['tkg'], $tkg1, $tkg2, $tkg3);
        }

        return $data;
    }

    public function data6($branch, $year, $arrayMonth, $type_fish, $species_fish)
    {
        $tf = TypeFish::where('dtkn', $type_fish)->first();
        $sf = SpeciesFish::where('dtkn', $species_fish)->first();
        $data = [
            'line' => [],
            'month' => []
        ];
        if($tf != null)
        {
            $months = $arrayMonth ?? ['01','02','03','04','05','06','07','08','09','10','11','12'];
            foreach($months as $month)
            {
                if($sf != null)
                {
                    $typeFishData = DataCollection::whereHas('species_fish', function($q1) use($sf){
                        $q1->where('id', $sf->id);
                    })
                    ->whereHas('fishing_data', function($q) use($branch, $year, $month){
                        $q->where('branch_id', $branch->id);
                        $q->whereDate('enumeration_time', '>=', $year . '-' . $month . '-01');
                        $q->whereDate('enumeration_time', '<=', $year . '-' . $month . '-31');
                        $q->where('status', 'disetujui');
                    })
                    ->get();
                } else {
                    $typeFishData = DataCollection::whereHas('species_fish', function($q1) use($tf){
                        $q1->where('type_fish_id', $tf->id);
                    })
                    ->whereHas('fishing_data', function($q) use($branch, $year, $month){
                        $q->where('branch_id', $branch->id);
                        $q->whereDate('enumeration_time', '>=', $year . '-' . $month . '-01');
                        $q->whereDate('enumeration_time', '<=', $year . '-' . $month . '-31');
                        $q->where('status', 'disetujui');
                    })
                    ->get();
                }

                $price = 0;
                foreach($typeFishData as $typeFish)
                {
                    $price += $typeFish->total_economy_price;
                }
                
                array_push($data['line'], $price);
                array_push($data['month'], date('F', strtotime('2023-' . $month)));
            }
        }
        
        // dd($data);

        return $data;
    }
    
    public function data7($branch, $year, $arrayMonth, $type_fish, $species_fish)
    {
        $tf = TypeFish::where('dtkn', $type_fish)->first();
        $sf = SpeciesFish::where('dtkn', $species_fish)->first();
        $born_start = $sf->born_start ?? 0;
        $born_end = $sf->born_end ?? 0;
        $mature_male_start = $sf->mature_male_start ?? 0;
        $mature_female_start = $sf->mature_female_start ?? 0;
        $mega_spawner = $sf->mega_spawner ?? 0;
        $data = [
            'labels' => [
                'Born ' . $born_start . ' cm - ' . $born_end . ' cm (ekor)', 
                'Mature Male ' . $mature_male_start . ' cm - ' . $mega_spawner . ' cm (ekor)',
                'Mature Female ' . $mature_female_start . ' cm - ' . $mega_spawner . ' cm (ekor)',
                'Mega Spawner ' . $mega_spawner . ' cm (ekor)',
                'Tidak Terkategori (ekor)'
            ],
            'born' => [],
            'mature_male' => [],
            'mature_female' => [],
            'mega_spawner' => [],
            'uncategory' => [],
            'month' => []
        ];

        if($tf != null)
        {
            $months = $arrayMonth ?? ['01','02','03','04','05','06','07','08','09','10','11','12'];
            foreach($months as $month)
            {
                $born = DataCollection::whereHas('species_fish', function($q1) use($sf){
                    $q1->where('id', $sf->id);
                })
                ->where('tl', '>=', $sf->born_start ?? 0)
                ->where('tl', '<=', $sf->born_end ?? 0)
                ->whereHas('fishing_data', function($q) use($branch, $year, $month){
                    $q->where('branch_id', $branch->id);
                    $q->whereDate('enumeration_time', '>=', $year . '-' . $month . '-01');
                    $q->whereDate('enumeration_time', '<=', $year . '-' . $month . '-31');
                    $q->where('status', 'disetujui');
                })
                ->sum('amount_fish');
                
                $mature_male = DataCollection::whereHas('species_fish', function($q1) use($sf){
                    $q1->where('id', $sf->id);
                })
                ->where('tl', '>=', $sf->mature_male_start ?? 0)
                ->where('tl', '<', $sf->mega_spawner ?? 0)
                ->where('gender', 'j')
                ->whereHas('fishing_data', function($q) use($branch, $year, $month){
                    $q->where('branch_id', $branch->id);
                    $q->whereDate('enumeration_time', '>=', $year . '-' . $month . '-01');
                    $q->whereDate('enumeration_time', '<=', $year . '-' . $month . '-31');
                    $q->where('status', 'disetujui');
                })
                ->sum('amount_fish');
                
                $mature_female = DataCollection::whereHas('species_fish', function($q1) use($sf){
                    $q1->where('id', $sf->id);
                })
                ->where('tl', '>=', $sf->mature_female_start ?? 0)
                ->where('tl', '<', $sf->mega_spawner ?? 0)
                ->where('gender', 'b')
                ->whereHas('fishing_data', function($q) use($branch, $year, $month){
                    $q->where('branch_id', $branch->id);
                    $q->whereDate('enumeration_time', '>=', $year . '-' . $month . '-01');
                    $q->whereDate('enumeration_time', '<=', $year . '-' . $month . '-31');
                    $q->where('status', 'disetujui');
                })
                ->sum('amount_fish');
                
                $mega_spawner = DataCollection::whereHas('species_fish', function($q1) use($sf){
                    $q1->where('id', $sf->id);
                })
                ->where('tl', '>=', $sf->mega_spawner ?? 0)
                ->whereHas('fishing_data', function($q) use($branch, $year, $month){
                    $q->where('branch_id', $branch->id);
                    $q->whereDate('enumeration_time', '>=', $year . '-' . $month . '-01');
                    $q->whereDate('enumeration_time', '<=', $year . '-' . $month . '-31');
                    $q->where('status', 'disetujui');
                })
                ->sum('amount_fish');
                
                $mature_female = DataCollection::whereHas('species_fish', function($q1) use($sf){
                    $q1->where('id', $sf->id);
                })
                ->where('tl', '>=', $sf->mature_female_start ?? 0)
                ->where('tl', '<', $sf->mega_spawner ?? 0)
                ->where('gender', 'b')
                ->whereHas('fishing_data', function($q) use($branch, $year, $month){
                    $q->where('branch_id', $branch->id);
                    $q->whereDate('enumeration_time', '>=', $year . '-' . $month . '-01');
                    $q->whereDate('enumeration_time', '<=', $year . '-' . $month . '-31');
                    $q->where('status', 'disetujui');
                })
                ->sum('amount_fish');
                
                $uncategory = DataCollection::whereHas('species_fish', function($q1) use($sf){
                    $q1->where('id', $sf->id);
                })
                ->whereHas('fishing_data', function($q) use($branch, $year, $month){
                    $q->where('branch_id', $branch->id);
                    $q->whereDate('enumeration_time', '>=', $year . '-' . $month . '-01');
                    $q->whereDate('enumeration_time', '<=', $year . '-' . $month . '-31');
                    $q->where('status', 'disetujui');
                })
                ->sum('amount_fish');
                
                if($uncategory - $born - $mature_male - $mature_female - $mega_spawner > 0)
                {
                    array_push($data['uncategory'], $uncategory);    
                } else {
                    array_push($data['uncategory'], 0);
                }
                
                array_push($data['born'], $born);
                array_push($data['mature_male'], $mature_male);
                array_push($data['mature_female'], $mature_female);
                array_push($data['mega_spawner'], $mega_spawner);
                array_push($data['month'], date('F', strtotime('2023-' . $month)));
            }
        }
        
        // dd($data);

        return $data;
    }

    public function jsonData($type_fish, $branch)
    {
        $type_fishes = TypeFish::with('species_fishs')
        ->where('id', $type_fish->id)
        ->where('branch_id', $branch->id)
        ->get();
            $merged = collect($type_fishes)->transform(function ($type_fish) {
                $mergedSpecies = collect($type_fish->species_fishs)->transform(function ($species_fish) {

                    return [
                        'id' => $species_fish->id,
                        'title' => $species_fish->species,
                    ];                    

                });
    
                if(count($mergedSpecies) > 0)
                {
                    return [
                        'id' => $type_fish->id,
                        'title' => $type_fish->type,
                        'subs' => $mergedSpecies
                    ];                 
                } else {
                    return [
                        'id' => $type_fish->id,
                        'title' => $type_fish->type,
                    ];                  
                }
            });
    
            $data = json_decode($merged);
        
        return $data;
    }

    public function jsonDataNonAppendiks($type_fish, $branch)
    {
        $type_fishes = TypeFish::with('species_fishs')
        ->where('id', $type_fish->id)
        ->where('branch_id', $branch->id)
        ->get();
            $merged = collect($type_fishes)->transform(function ($type_fish) {
                $mergedSpecies = collect($type_fish->nonappendiks_species_fishs)->transform(function ($species_fish) {

                    return [
                        'id' => $species_fish->id,
                        'title' => $species_fish->species,
                    ];                    

                });
    
                if(count($mergedSpecies) > 0)
                {
                    return [
                        'id' => $type_fish->id,
                        'title' => $type_fish->type,
                        'subs' => $mergedSpecies
                    ];                 
                } else {
                    return [
                        'id' => $type_fish->id,
                        'title' => $type_fish->type,
                    ];                  
                }
            });
    
            $data = json_decode($merged);
        
        return $data;
    }

    public function jsonDataAppendiks($type_fish, $branch)
    {
        $type_fishes = TypeFish::with('species_fishs')
        ->where('id', $type_fish->id)
        ->where('branch_id', $branch->id)
        ->get();
            $merged = collect($type_fishes)->transform(function ($type_fish) {
                $mergedSpecies = collect($type_fish->appendiks_species_fishs)->transform(function ($species_fish) {

                    return [
                        'id' => $species_fish->id,
                        'title' => $species_fish->species,
                    ];                    

                });
    
                if(count($mergedSpecies) > 0)
                {
                    return [
                        'id' => $type_fish->id,
                        'title' => $type_fish->type,
                        'subs' => $mergedSpecies
                    ];                 
                } else {
                    return [
                        'id' => $type_fish->id,
                        'title' => $type_fish->type,
                    ];                  
                }
            });
    
            $data = json_decode($merged);
        
        return $data;
    }
}
