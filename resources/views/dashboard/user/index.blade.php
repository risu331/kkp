@extends('layouts.dashboard')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Manajemen User</li>        
    </ol>
    <div class="text-end">
        <a href="{{ route('dashboard.user.create') }}" class="btn btn-primary mb-2">Tambah</a>
    </div>
</nav>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Tabel User</h6>
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Avatar</th>
                                <th>Hak Akses</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($users as $key => $user)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td><a href="{{ $user->image ?? 'https://via.placeholder.com/30x30' }}" target="_blank"><img src="{{ $user->image ?? 'https://via.placeholder.com/30x30' }}" width="64px"></a></td>
                                <td>{{ $user->role }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="text-end">
                                    <a href="{{ route('dashboard.user.edit', $user->id) }}" class="btn btn-info text-white">Edit</a>
                                    @if($user->role != 'superadmin')
                                        @if(Auth::user()->role != $user->role)
                                            <button type="button" class="btn btn-danger btn-delete" value="{{ $user->id }}">Hapus</button>
                                        @endif
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

<div class="modal fade" tabindex="-1" role="dialog" id="delete-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus User</h5>
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
        $("#delete-form").attr("action", "{{ route('dashboard.user.index') }}/" + id);
        $("#delete-modal").modal('show');
    });
</script>
<script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
