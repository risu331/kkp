@extends('layouts.dashboard')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.type-ship.index') }}">Jenis Kapal</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Jenis Kapal</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">Tambah Jenis Kapal Form</h6>

                    <form action="{{ route('dashboard.type-ship.store') }}" method="POST" class="forms-sample" enctype="multipart/form-data">
                        @csrf
                        @if(Auth::user()->role == 'superadmin')
                            <div class="row mb-3">
                                <label for="input1" class="col-sm-3 col-form-label">Wilayah Kerja <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select name="branch_id" class="form-control @error('branch_id') is-invalid @enderror" required>
                                            <option value="" hidden>Pilih</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    <select>
                                    @error('branch_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="branch_id" value="{{ Auth::user()->branch_id }}">
                        @endif
                        <div class="row mb-3">
                            <label for="input1" class="col-sm-3 col-form-label">Jenis <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" id="input1" placeholder="Jenis" value="{{ old('type') }}" required>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
