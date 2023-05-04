@extends('layouts.dashboard')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.type-ship.index') }}?branch_ids[]={{ $type_ship->branch_id }}">Jenis Kapal ({{ $type_ship->type ?? 'NULL' }})</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.ship.index') }}?id={{ $type_ship->dtkn }}"> Kapal</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Kapal</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">Edit Kapal Form</h6>

                    <form action="{{ route('dashboard.ship.update', $ship->dtkn) }}" method="POST" class="forms-sample" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="select1" class="col-sm-3 col-form-label">Jenis Kapal <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="hidden" name="type_ship_id" value="{{ $type_ship->id }}">
                                <input type="text" class="form-control" value="{{ $type_ship->type }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input1" class="col-sm-3 col-form-label">Nama <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="input1" placeholder="Nama Kapal" value="{{ old('name', $ship->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input2" class="col-sm-3 col-form-label">Pemilik <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="owner" class="form-control @error('owner') is-invalid @enderror" id="input2" placeholder="Pemilik Kapal" value="{{ old('owner', $ship->owner) }}" required>
                                @error('owner')
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
