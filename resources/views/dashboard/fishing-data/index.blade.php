@extends('layouts.dashboard')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<style>
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

        .btn-create{
            display: none !important;
        }

        .desktop-design{
            display: none !important;
        }
    }
    @media (min-width:768px)  { 
        div.scrollcards .card {
            display: inline-block;
            padding: 20px;
            text-decoration: none;
            height: auto; 
            width: 40vw;
            margin-right: 5px;
        }

        .float{
            display: none !important;
        }

        .btn-create{
            display: block !important;
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
            display: inline-block;
            padding: 20px;
            text-decoration: none;
            height: auto; 
            width: 30vw;
            margin-right: 5px;
        }

        .float{
            display: none !important;
        }

        .btn-create{
            display: block !important;
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
        <li class="breadcrumb-item active" aria-current="page">Pendataan Ikan</li>
    </ol>
</nav>

<div class="card p-3">
    <form method="get">
        <div class="row">
            @if(Auth::user()->role == 'superadmin')
                <div class="col-sm-12 col-lg-12 mb-3">
                    <label>Wilayah Kerja:</label>
                    <select class="select-filter-page form-control" name="branch_ids[]" multiple>
                        @php
                            $branch_ids = Request::input('branch_ids') ?? [0];
                        @endphp
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ in_array($branch->id, $branch_ids) ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="col-6 col-md-5">
                <label>Dari:</label>
                <input type="date" name="start_date" class="form-control" value="{{ Request::input('start_date') ?? date('Y-m-d') }}">
            </div>
            <div class="col-6 col-md-5">
                <label>Sampai:</label>
                <input type="date" name="end_date" class="form-control" value="{{ Request::input('end_date') ?? date('Y-m-d') }}">
            </div>
            <div class="col-12 col-md-2">
                <label>&nbsp;</label>
                <div class="d-grid">
                    <button class="btn btn-info text-white">Filter</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="text-end btn-create mt-3">
    <a href="{{ route('dashboard.fishing-data.create') }}" class="btn btn-primary">Tambah</a>
</div>

<div class="mobile-design">
    <div class="row mt-3 mb-3">
        <h4 class="text-danger">Data Belum Terverifikasi ({{ count($unverified_fishing_datas) }} Data)</h4>
    </div>

    <section>
        <div class="scrollcards">
            @foreach($unverified_fishing_datas as $fishing_data)
                <div class="card">
                    <div class="row">
                        <div class="col-12">
                            <p class="fs-5 f-w-500 float-start" style="color: #009ce3;">#{{ $fishing_data->id }} | {{ date('d-m-Y', strtotime($fishing_data->enumeration_time)) }}</p>
                            <!-- <p class="float-end" style="font-size: 24px; margin-top: -15px;">...</p> -->
                            <div class="dropdown float-end">
                                <p class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                
                                </p>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="{{ route('dashboard.fishing-data.data-collection.index') }}?id={{ $fishing_data->dtkn }}">Detail</a></li>
                                    <li><a class="dropdown-item" href="{{ route('dashboard.fishing-data.edit', $fishing_data->dtkn) }}">Edit</a></li>
                                    <li><button class="dropdown-item btn-delete" value="{{ $fishing_data->dtkn }}">Hapus</button></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            @if(Auth::user()->role == 'superadmin')
                                <h6 class="mt-1">Wilayah Kerja:</h6>
                                <p>{{ date('d-m-Y', strtotime($fishing_data->branch->name )) }}</p>
                                <hr>
                            @endif
                            <h6 class="mt-1">Nama Enumerator:</h6>
                            <p>{{ $fishing_data->user_name }}</p>
                            <hr>
                            <h6 class="mt-1">Lokasi Pendaratan/Kode:</h6>
                            <p>{{ $fishing_data->landing_site->name }}/{{ $fishing_data->landing_site->code }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="row mt-3 mb-3">
        <h4 class="text-success">Data Sudah Terverifikasi ({{ count($verified_fishing_datas) }} Data)</h4>
    </div>

    <section>
        <div class="scrollcards">
            @foreach($verified_fishing_datas as $fishing_data)
                <div class="card">
                    <div class="row">
                        <div class="col-12">
                            <p class="fs-5 f-w-500 float-start" style="color: #009ce3;">#{{ $fishing_data->id }} | {{ date('d-m-Y', strtotime($fishing_data->enumeration_time)) }}</p>
                            <!-- <p class="float-end" style="font-size: 24px; margin-top: -15px;">...</p> -->
                            <div class="dropdown float-end">
                                <p class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                
                                </p>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="{{ route('dashboard.fishing-data.export') }}?id={{ $fishing_data->dtkn }}">Export</a></li>
                                    <li><a class="dropdown-item" href="{{ route('dashboard.fishing-data.data-collection.index') }}?id={{ $fishing_data->dtkn }}">Detail</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            @if(Auth::user()->role == 'superadmin')
                                <h6 class="mt-1">Wilayah Kerja:</h6>
                                <p>{{ $fishing_data->branch->name }}</p>
                                <hr>
                            @endif
                            <h6 class="mt-1">Nama Enumerator:</h6>
                            <p>{{ $fishing_data->user_name }}</p>
                            <hr>
                            <h6 class="mt-1">Lokasi Pendaratan/Kode:</h6>
                            <p>{{ $fishing_data->landing_site->name }}/{{ $fishing_data->landing_site->code }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>

<div class="desktop-design mt-3">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Tabel Pendataan Ikan</h6>
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">ID</th>
                                    <th style="width: 5%;">Waktu Enumerasi</th>
                                    <th>Lokasi Pendaratan/Kode</th>
                                    <th>Status</th>
                                    <th style="width: 5%;" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fishing_datas as $key => $fishing_data)
                                    <tr>
                                        <td>#{{ $fishing_data->id }}</td>
                                        <td>{{ date('d-m-Y', strtotime($fishing_data->enumeration_time)) }}</td>
                                        <td>{{ $fishing_data->landing_site->name ?? 'NULL' }}/{{ $fishing_data->landing_site->code ?? 'NULL' }}</td>
                                        <td class="text-capitalize">
                                            @if($fishing_data->status == 'menunggu persetujuan')
                                                @if(Auth::user()->role != 'enumerator')
                                                    <button class=" badge bg-warning" style="border: 0px;"><span >{{ $fishing_data->status }}</span></button>
                                                @else
                                                    <span class="badge bg-warning">{{ $fishing_data->status }}</span>
                                                @endif
                                            @else
                                                <span class="badge bg-success">{{ $fishing_data->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('dashboard.fishing-data.data-collection.index') }}?id={{ $fishing_data->dtkn }}" class="btn btn-primary text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail">
                                                <i class="link-icon" data-feather="info"></i>
                                            </a>
                                            @if($fishing_data->status != 'menunggu persetujuan')
                                                <a href="{{ route('dashboard.fishing-data.export') }}?id={{ $fishing_data->dtkn }}" class="btn btn-success text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Export">
                                                    <i class="link-icon" data-feather="file"></i>
                                                </a>
                                            @endif
                                            @if($fishing_data->status == 'menunggu persetujuan')
                                                <button type="button" class="btn btn-danger btn-delete" value="{{ $fishing_data->dtkn }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                                    <i class="link-icon" data-feather="trash"></i>
                                                </button>
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

<a href="{{ route('dashboard.fishing-data.create') }}" class="float">
    <i class="link-icon btn-float" data-feather="plus"></i>
</a>

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
        console.log(id);
        $("#delete-form").attr("action", "{{ route('dashboard.fishing-data.index') }}/" + id);
        $("#delete-modal").modal('show');
    });

    $(document).on("click", ".btn-verification", function () {
        var id = $(this).val();
        console.log(id);
        $("#verification-form").attr("action", "{{ route('dashboard.fishing-data.index') }}/" + id + "/verification");
        $("#verification-modal").modal('show');
    });

</script>
<script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
