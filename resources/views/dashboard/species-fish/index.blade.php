@extends('layouts.dashboard')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Spesies Ikan</li>
    </ol>
</nav>

@if(Auth::user()->role == 'superadmin')
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card bg-transparent border">
                <div class="card-body">
                    <h6 class="card-title">Filter Form</h6>
                    <form method="get">
                        <div class="row mb-3">
                            <div class="col-sm-12 col-lg-10 mb-3">
                                <select class="select-filter-page form-control" name="branch_ids[]" multiple>
                                    @php
                                        $branch_ids = Request::input('branch_ids') ?? [0];
                                    @endphp
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ in_array($branch->id, $branch_ids) ? 'selected' : '' }}>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-lg-2">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="text-end">
    <a href="{{ route('dashboard.species-fish.create') }}" class="btn btn-primary mb-2">Tambah</a>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Tabel Spesies Ikan</h6>
                <!-- <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p> -->
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                @if(Auth::user()->role == 'superadmin')
                                    <th>Wilayah Kerja</th>
                                @endif
                                <th>Jenis</th>
                                <th>Kelompok</th>
                                <th>Ilmiah/Lokal/Umum</th>
                                <th>Size (cm)</th>
                                <th style="width: 5%;" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($species_fishs as $key => $species_fish)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    @if(Auth::user()->role == 'superadmin')
                                        <td>{{ $species_fish->type_fish->branch->name ?? 'NULL' }}</td>
                                    @endif
                                    <td>{{ $species_fish->type_fish->type ?? 'NULL' }}</td>
                                    <td>{{ $species_fish->group ?? 'NULL' }}</td>
                                    <td><i>{{ $species_fish->species ?? 'NULL' }}</i>/{{ $species_fish->local ?? 'NULL' }}/{{ $species_fish->general ?? 'NULL' }}</td>
                                    <td>
                                        <p>Born: {{ $species_fish->born_start ?? 0 }} - {{ $species_fish->born_end ?? 0 }}</p>
                                        <p>Mature Male: {{ $species_fish->mature_male_start ?? 0 }} - {{ $species_fish->mature_male_end ?? 0 }}</p>
                                        <p>Mature Female: {{ $species_fish->mature_female_start ?? 0 }} - {{ $species_fish->mature_female_end ?? 0 }}</p>
                                        <p>Mega Spawner: {{ $species_fish->mega_spawner ?? 0 }}</p>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('dashboard.species-fish.edit', $species_fish->dtkn) }}" class="btn btn-info text-white">Edit</a>
                                        <button type="button" class="btn btn-danger btn-delete" value="{{ $species_fish->dtkn }}">Hapus</button>
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

<div class="modal fade" tabindex="-1" role="dialog" id="delete-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Spesies Ikan</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <div class="modal-body">
                <p>Tindakan ini akan menghapus data tersebut dan data yang dihapus tidak dapat di kembalikan, apakah Anda yakin ingin melanjutkan?</p>
            </div>
            <div class="modal-footer">
                <form action="" method="post" id="delete-form">
                    @csrf
                    @method("DELETE")
                    <button type="button" class="btn btn-light font-weight-bolder" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary font-weight-bolder" id="btn-submit-delete">Ya, Saya Yakin</button>
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
    $(document).on("click", ".btn-delete", function()
    {
        var id = $(this).val();
        $("#delete-form").attr("action", "{{ route('dashboard.species-fish.index') }}/" + id);
        $("#delete-modal").modal('show');
    });
</script>
<script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
