@extends('layouts.dashboard')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<style>
    .data-collection{
        display: block;
    }

    .scrollcards {
      background-color: transparent;
      overflow: auto;
      white-space: nowrap;
    }

    ::-webkit-scrollbar {
        width: 0px;
        height: 0px;
        background: transparent;
    }
    
    div.scrollcards .card {
        display: inline-block;
        padding: 20px;
        text-decoration: none;
        height: auto; 
        margin-right: 5px;
    }

    .float{
        position:fixed;
        width:60px;
        height:60px;
        bottom:40px;
        right:40px;
        background-color:#0C9;
        color:#FFF;
        border-radius:50px;
        text-align:center;
        box-shadow: 2px 2px 3px #999;
    }

    .btn-float{
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    @media (min-width:320px)  { 
        div.scrollcards .card {
            display: inline-block;
            padding: 20px;
            text-decoration: none;
            height: auto; 
            width: 80vw;
            margin-right: 5px;
        }

        .desktop-design{
            display: none !important;
        }
    }
    @media (min-width:768px)  { 
        div.scrollcards .card {
            display: none;
            padding: 20px;
            text-decoration: none;
            height: auto; 
            width: 40vw;
            margin-right: 5px;
        }

        .data-collection{
            display: none !important;
        }

        .float{
            display: none !important;
        }

        .mobile-design{
            display: none !important;
        }

        .desktop-design{
            display: block !important;
        }
    }
    
    @media (min-width:1024px) { 
        div.scrollcards .card {
            padding: 20px;
            text-decoration: none;
            height: auto; 
            width: 30vw;
            margin-right: 5px;
            display: none;
        }

        .data-collection{
            display: none !important;
        }

        .float{
            display: none !important;
        }

        .mobile-design{
            display: none !important;
        }

        .desktop-design{
            display: block !important;
        }
    }
</style>
@endpush

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.fishing-data.index') }}">Pendataan Ikan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Pendataan Ikan</li>
    </ol>
</nav>

<div class="row mb-3 mobile-design">
    <div class="col-md-12">
        <div class="card bg-transparent border">
            <div class="card-header">
                <h6 class="card-title">#{{ $fishing_data->id }} Detail Pendataan Ikan ( 
                    @if($fishing_data->status == 'menunggu persetujuan')
                        <button class="badge bg-warning" style="border: 0px;"><span>{{ $fishing_data->status }}</span></button>
                    @else
                        <span class="badge bg-success">{{ $fishing_data->status }}</span>
                    @endif
                )</h6>
                <ul class="nav nav-tabs card-header-tabs" data-bs-tabs="tabs">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="true" data-bs-toggle="tab" href="#info-1">Informasi 1</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#info-2">Informasi 2</a>
                    </li>
                </ul>
            </div>
            <div class="card-body tab-content">
                <div class="row mb-3 tab-pane active" id="info-1">
                    <div class="col-sm-6 col-lg-6 mb-3">
                        <label>Waktu Enumerasi:</label>
                        <div class="form-control">{{ date('d-m-Y', strtotime($fishing_data->enumeration_time)) }}</div>
                    </div>
                    @if(Auth::user()->role == 'superadmin')
                        <div class="col-sm-6 col-lg-6 mb-3">
                            <label>Wilayah Kerja:</label>
                            <div class="form-control">{{ $fishing_data->branch->name ?? '-' }}</div>
                        </div>
                    @endif
                    <div class="col-sm-6 col-lg-6 mb-3">
                        <label>Nama Enumerator:</label>
                        <div class="form-control">{{ $fishing_data->user_name ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6 col-lg-6 mb-3">
                        <label>Lokasi Pendaratan/Kode:</label>
                        <div class="form-control">{{ $fishing_data->landing_site->name ?? '-' }}/{{ $fishing_data->landing_site->code ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6 col-lg-6 mb-3">
                        <label>Jenis/Nama Kapal/GT:</label>
                        <div class="form-control">{{ $fishing_data->ship->type_ship->type ?? '-' }}/{{ $fishing_data->ship->name ?? '-' }}/{{ $fishing_data->gt ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6 col-lg-6 mb-3">
                        <label>Alat Tangkap:</label>
                        <div class="form-control">{{ $fishing_data->fishing_gear->name ?? '-' }}</div>
                    </div>
                </div>
                <div class="row mb-3 tab-pane" id="info-2">
                    <div class="col-sm-6 col-lg-6 mb-3">
                        <label>Jml Hari Operasional/Perjalanan/Setting:</label>
                        <div class="form-control">{{ $fishing_data->operational_day ?? '-' }} hari/{{ $fishing_data->travel_day ?? '-' }} hari/{{ $fishing_data->setting ?? '-' }} x</div>
                    </div>
                    <div class="col-sm-6 col-lg-6 mb-3">
                        <label>Hasil Tangkap Ikan:</label>
                        <div class="form-control">
                            @if($fishing_data->is_htu == 1)
                                Hasil Tangkap Ikan Utama (HTU)
                            @else
                                Hasil Tangkap Ikan Sampingan (HTS)
                            @endif
                        </div>
                    </div>
                    @php
                        $type_fishs = App\Models\TypeFish::where('branch_id', $fishing_data->branch_id)->get();
                        $all_weight = 0;
                    @endphp
                    @foreach($type_fishs as $type_fish)
                        @php
                            $amount = 0;
                            $weight = 0;
                            foreach($fishing_data->data_collections as $data)
                            {
                                if($data->species_fish->type_fish_id == $type_fish->id)
                                {
                                    $amount += $data->amount_fish;
                                    $weight += $data->weight;
                                    $all_weight += $data->weight;
                                }
                            } 
                        @endphp
                        <div class="col-sm-6 col-lg-6 mb-3">
                            <label>Total Hasil Tangkapan {{ $type_fish->type }}:</label>
                            <div class="form-control">{{ $weight }} Kg ({{ $amount }} ekor)</div>
                        </div>
                    @endforeach
                    <div class="col-sm-6 col-lg-6 mb-3">
                        <label>Total Hasil Tangkapan Ikan Lainnya:</label>
                        <div class="form-control">{{ $fishing_data->total_other_fish }} Kg</div>
                    </div>
                    <div class="col-sm-6 col-lg-6 mb-3">
                        <label>Total Hasil Tangkapan Ikan:</label>
                        <div class="form-control">{{ $all_weight + $fishing_data->total_other_fish }} Kg</div>
                    </div>
                </div>
            </div>
            @if($fishing_data->status != 'disetujui')
                <div class="card-footer">
                    <a href="{{ route('dashboard.fishing-data.edit', $fishing_data->dtkn) }}" class="btn btn-info text-white" style="float: right;">Edit</a>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row mb-3 desktop-design">
    <div class="col-md-12">
        <div class="card bg-transparent border">
            <div class="card-header">
                <h6 class="card-title">#{{ $fishing_data->id }} Detail Pendataan Ikan ( 
                    @if($fishing_data->status == 'menunggu persetujuan')
                        <button class="badge bg-warning" style="border: 0px;"><span>{{ $fishing_data->status }}</span></button>
                    @else
                        <span class="badge bg-success">{{ $fishing_data->status }}</span>
                    @endif
                )</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-6 col-lg-6">
                        <div class="mb-3">
                            <label>Waktu Enumerasi:</label>
                            <div class="form-control">{{ date('d-m-Y', strtotime($fishing_data->enumeration_time)) }}</div>
                        </div>
                        @if(Auth::user()->role == 'superadmin')
                            <div class="mb-3">
                                <label>Wilayah Kerja:</label>
                                <div class="form-control">{{ $fishing_data->branch->name ?? '-' }}</div>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label>Nama Enumerator:</label>
                            <div class="form-control">{{ $fishing_data->user_name ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label>Jenis/Nama Kapal/GT:</label>
                            <div class="form-control">{{ $fishing_data->ship->type_ship->type ?? '-' }}/{{ $fishing_data->ship->name ?? '-' }}/{{ $fishing_data->gt ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label>Lokasi Pendaratan/Kode:</label>
                            <div class="form-control">{{ $fishing_data->landing_site->name ?? '-' }}/{{ $fishing_data->landing_site->code ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label>Alat Tangkap:</label>
                            <div class="form-control">{{ $fishing_data->fishing_gear->name ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6">
                        <div class="mb-3">
                            <label>Jml Hari Operasional/Perjalanan/Setting:</label>
                            <div class="form-control">{{ $fishing_data->operational_day ?? '-' }} hari/{{ $fishing_data->travel_day ?? '-' }} hari/{{ $fishing_data->setting ?? '-' }} x</div>
                        </div>
                        <div class="mb-3">
                            <label>Hasil Tangkap Ikan:</label>
                            <div class="form-control">
                                @if($fishing_data->is_htu == 1)
                                    Hasil Tangkap Ikan Utama (HTU)
                                @else
                                    Hasil Tangkap Ikan Sampingan (HTS)
                                @endif
                            </div>
                        </div>
                        @php
                            $type_fishs = App\Models\TypeFish::where('branch_id', $fishing_data->branch_id)->get();
                            $all_weight = 0;
                        @endphp
                        @foreach($type_fishs as $type_fish)
                            @php
                                $weight = 0;
                                $amount = 0;
                                foreach($fishing_data->data_collections as $data)
                                {
                                    if($data->species_fish->type_fish_id == $type_fish->id)
                                    {
                                        $amount += $data->amount_fish;
                                        $weight += $data->weight;
                                        $all_weight += $data->weight;
                                    }
                                } 
                            @endphp
                            <div class="mb-3">
                                <label>Total Hasil Tangkapan {{ $type_fish->type }}:</label>
                                <div class="form-control">{{ $weight }} Kg ({{ $amount }} ekor)</div>
                            </div>
                        @endforeach
                        <div class="mb-3">
                            <label>Total Hasil Tangkapan Ikan Lainnya:</label>
                            <div class="form-control">{{ $fishing_data->total_other_fish }} Kg</div>
                        </div>
                        <div class="mb-3">
                            <label>Total Hasil Tangkapan Ikan:</label>
                            <div class="form-control">{{ $all_weight + $fishing_data->total_other_fish }} Kg</div>
                        </div>
                    </div>
                </div>
            </div>
            @if($fishing_data->status != 'disetujui')
                <div class="card-footer">
                    <a href="{{ route('dashboard.fishing-data.edit', $fishing_data->dtkn) }}" class="btn btn-info text-white" style="float: right;">Edit</a>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row mt-3 mb-3 data-collection">
    <h4 class="text-success">Data Ikan ({{ count($fishing_data->data_collections) }} Data)</h4>
</div>

<section>
    <div class="scrollcards">
        @foreach($fishing_data->data_collections as $data_collection)
            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <p class="fs-5 f-w-500 float-start" style="color: #009ce3;">{{ $data_collection->species_fish->type_fish->type }} ({{ $data_collection->amount_fish }} ekor)</p>
                        <!-- <p class="float-end" style="font-size: 24px; margin-top: -15px;">...</p> -->
                        <div class="dropdown float-end">
                            <p class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                               
                            </p>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="{{ route('dashboard.fishing-data.data-collection.show', $data_collection->dtkn) }}?id={{ Request::input('id') }}">Detail</a></li>
                                @if($data_collection->status != 'disetujui')
                                    <li><button class="dropdown-item btn-delete" value="{{ $data_collection->dtkn }}">Hapus</button></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <h6>Nama Ilmiah:</h6>
                        <p><i>{{ $data_collection->species_fish->species }}</i></p>
                        <hr>
                        <h6>Nama Lokal:</h6>
                        <p>{{ $data_collection->species_fish->local }}</p>
                        <hr>
                        <h6>Nama Umum:</h6>
                        <p>{{ $data_collection->species_fish->general }}</p>
                        <hr>
                        <h6>Status:</h6>
                        <p>
                            @if($data_collection->status == 'disetujui')
                                <span class="badge bg-success">{{ $data_collection->status }}</span>
                            @else
                                @if(Auth::user()->role != 'enumerator')
                                    <button class="btn-verification badge bg-warning" value="{{ $data_collection->dtkn }}" style="border: 0px;"><span data-bs-toggle="tooltip" data-bs-placement="top" title="Verifikasi">menunggu persetujuan</span></button>
                                @else
                                    <span class="badge bg-warning">{{ $data_collection->status }}</span>    
                                @endif
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<div class="desktop-design">
    @if($fishing_data->status != 'disetujui')
        <div class="text-end">
            <a href="{{ route('dashboard.fishing-data.data-collection.create') }}?id={{ $fishing_data->dtkn }}" class="btn btn-primary mb-2">Tambah</a>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Tabel List Data Ikan</h6>
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">Jenis Ikan</th>
                                    <th>Ilmiah/Lokal/Umum</th>
                                    <th>Kelompok</th>
                                    <th>Status</th>
                                    <th style="width: 5%;" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fishing_data->data_collections as $key => $data_collection)
                                    <tr>
                                        <td>{{ $data_collection->species_fish->type_fish->type }} ({{ $data_collection->amount_fish }} ekor)</td>
                                        <td><i>{{ $data_collection->species_fish->species ?? 'NULL' }}</i>/{{ $data_collection->species_fish->local ?? 'NULL' }}/{{ $data_collection->species_fish->general ?? 'NULL' }}</td>
                                        <td>{{ $data_collection->species_fish->group ?? 'NULL' }}</td>
                                        <td>
                                            @if($data_collection->status == 'disetujui')
                                                <span class="badge bg-success">{{ $data_collection->status }}</span>
                                            @else
                                                <button class="btn-verification badge bg-warning" value="{{ $data_collection->dtkn }}" style="border: 0px;"><span data-bs-toggle="tooltip" data-bs-placement="top" title="Verifikasi">menunggu persetujuan</span></button>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('dashboard.fishing-data.data-collection.show', $data_collection->dtkn) }}?id={{ Request::input('id') }}" class="btn btn-primary text-white">Detail</a>
                                            @if($data_collection->status != 'disetujui')
                                                <button type="button" class="btn btn-danger btn-delete" value="{{ $data_collection->dtkn }}">Hapus</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="delete-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Pendataan Ikan</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"
                    data-bs-original-title="" title=""></button>
            </div>
            <div class="modal-body">
                <p>Tindakan ini akan menghapus data tersebut dan data yang dihapus tidak dapat di kembalikan, apakah
                    Anda yakin ingin melanjutkan?</p>
            </div>
            <div class="modal-footer">
                <form action="" method="post" id="delete-form">
                    @csrf
                    @method("DELETE")
                    <button type="button" class="btn btn-light font-weight-bolder"
                        data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary font-weight-bolder" id="btn-submit-delete">Ya, Saya
                        Yakin</button>
                </form>
            </div>
        </div>
    </div>
</div>

@if($fishing_data->status != 'disetujui')
<a href="{{ route('dashboard.fishing-data.data-collection.create') }}?id={{ $fishing_data->dtkn }}" class="float">
    <i class="link-icon btn-float" data-feather="plus"></i>
</a>
@endif

<div class="modal fade" tabindex="-1" role="dialog" id="verification-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikasi Pendataan Ikan</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"
                    data-bs-original-title="" title=""></button>
            </div>
            <div class="modal-body">
                <p>Tindakan ini akan memverifikasi data tersebut dan data yang diverifikasi akan menjadi hasil data statistik dan pemetaan peta, apakah
                    Anda yakin ingin melanjutkan?</p>
            </div>
            <div class="modal-footer">
                <form action="" method="post" id="verification-form">
                    @csrf
                    <button type="button" class="btn btn-light font-weight-bolder"
                        data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary font-weight-bolder" id="btn-submit-delete">Ya, Saya
                        Yakin</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
    // delete events
    $(document).on("click", ".btn-delete", function () {
        var id = $(this).val();
        $("#delete-form").attr("action", "{{ route('dashboard.fishing-data.data-collection.index') }}/" + id);
        $("#delete-modal").modal('show');
    });

    $('img[data-enlargable]').addClass('img-enlargable').click(function(){
        var src = $(this).attr('src');
        $('<div>').css({
            background: 'RGBA(0,0,0,.5) url('+src+') no-repeat center',
            backgroundSize: 'contain',
            width:'100%', height:'100%',
            position:'fixed',
            zIndex:'10000',
            top:'0', left:'0',
            cursor: 'zoom-out'
        }).click(function(){
            $(this).remove();
        }).appendTo('body');
    });

    $(document).on("click", ".btn-verification", function () {
        var id = $(this).val();
        console.log(id);
        $("#verification-form").attr("action", "{{ route('dashboard.fishing-data.data-collection.index') }}/" + id + "/verification");
        $("#verification-modal").modal('show');
    });

</script>
<script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
