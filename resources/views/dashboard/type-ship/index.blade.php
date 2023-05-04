@extends('layouts.dashboard')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Jenis Kapal</li>
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
    <a href="{{ route('dashboard.type-ship.create') }}" class="btn btn-primary mb-2">Tambah</a>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Tabel Jenis Kapal</h6>
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                @if(Auth::user()->role == 'superadmin')
                                    <th>Wilayah Kerja</th>
                                @endif
                                <th>Jenis</th>
                                <th>Jumlah Kapal</th>
                                <th style="width: 5%;" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($type_ships as $key => $type_ship)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    @if(Auth::user()->role == 'superadmin')
                                        <td>{{ $type_ship->branch->name ?? 'NULL' }}</td>
                                    @endif
                                    <td>{{ $type_ship->type ?? 'NULL' }}</td>
                                    <td><a href="{{ route('dashboard.ship.index') }}?id={{ $type_ship->dtkn }}"><span class="badge bg-primary p-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail Kapal">{{ count($type_ship->ships) }} Kapal</span></a></td>
                                    <td class="text-end">
                                        <a href="{{ route('dashboard.type-ship.edit', $type_ship->dtkn) }}" class="btn btn-info text-white">Edit</a>
                                        <button type="button" class="btn btn-danger btn-delete" value="{{ $type_ship->dtkn }}">Hapus</button>
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
                <h5 class="modal-title">Hapus Jenis Kapal</h5>
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
        $("#delete-form").attr("action", "{{ route('dashboard.type-ship.index') }}/" + id);
        $("#delete-modal").modal('show');
    });
</script>
<script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
