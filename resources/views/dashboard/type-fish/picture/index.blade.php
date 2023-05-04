@extends('layouts.dashboard')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.type-fish.index') }}?branch_ids[]={{ $type_fish->branch_id ?? 0 }}">Jenis Ikan ({{ $type_fish->type ?? 'NULL' }})</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pengambilan Gambar Ikan</li>
    </ol>
</nav>

<div class="text-end">
    <a href="{{ route('dashboard.type-fish.picture.create') }}?type_fish_id={{ $type_fish->dtkn }}" class="btn btn-primary mb-2">Tambah</a>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Tabel Pengambilan Gambar Ikan</h6>
                <!-- <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p> -->
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Index</th>
                                <th>Judul</th>
                                <th>Syarat Gambar</th>
                                <th>Contoh Gambar</th>
                                <th style="width: 5%;" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($type_fish->type_fish_pictures as $key => $type_fish_picture)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $type_fish_picture->index ?? 'NULL' }}</td>
                                    <td>{{ $type_fish_picture->title ?? 'NULL' }}</td>
                                    <td>
                                        @if($type_fish_picture->is_required == 1)
                                            Wajib Ada
                                        @else
                                            Tidak Wajib Ada
                                        @endif
                                    </td>
                                    <td><a href="{{ $type_fish_picture->sample_image ?? 'https://via.placeholder.com/30x30' }}" target="_blank"><img src="{{ $type_fish_picture->sample_image ?? 'https://via.placeholder.com/30x30' }}" width="64px"></a></td>
                                    <td class="text-end">
                                        <a href="{{ route('dashboard.type-fish.picture.edit', $type_fish_picture->dtkn) }}" class="btn btn-info text-white">Edit</a>
                                        <button type="button" class="btn btn-danger btn-delete" value="{{ $type_fish_picture->dtkn }}">Hapus</button>
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
                <h5 class="modal-title">Hapus Pengambilan Gambar Ikan</h5>
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
        $("#delete-form").attr("action", "{{ route('dashboard.type-fish.picture.index') }}/" + id);
        $("#delete-modal").modal('show');
    });
</script>
<script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
