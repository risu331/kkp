@extends('layouts.dashboard')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.type-fish.index') }}">Jenis Ikan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Jenis Ikan</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">Edit Jenis Ikan Form</h6>

                    <form action="{{ route('dashboard.type-fish.update', $type_fish->dtkn) }}" method="POST" class="forms-sample" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="input2" class="col-sm-3 col-form-label">Warna <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="color" name="icon" class="form-control" value="{{ $type_fish->icon }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input2" class="col-sm-3 col-form-label">Jenis <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" id="input2" placeholder="Jenis Ikan" value="{{ old('type', $type_fish->type) }}" required>
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
