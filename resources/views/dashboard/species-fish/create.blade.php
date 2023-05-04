@extends('layouts.dashboard')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.species-fish.index') }}"> Spesies Ikan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Spesies Ikan</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">Tambah Spesies Ikan Form</h6>

                    <form action="{{ route('dashboard.species-fish.store') }}" method="POST" class="forms-sample" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="select1" class="col-sm-3 col-form-label">Jenis Ikan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="type_fish_id" class="form-control @error('type_fish_id') is-invalid @enderror" id="select1" required>
                                    <option value="" hidden>Pilih</option>
                                    @foreach($type_fishs as $type_fish)
                                        <option value="{{ $type_fish->id }}">{{ $type_fish->type }} ({{ $type_fish->branch->name }})</option>
                                    @endforeach
                                </select>
                                @error('type_fish_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input2" class="col-sm-3 col-form-label">Nama Ilmiah Species<span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="species" class="form-control @error('species') is-invalid @enderror" id="input2" placeholder="Nama Ilmiah Species" value="{{ old('species') }}" required>
                                @error('species')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input3" class="col-sm-3 col-form-label">Nama Lokal <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="local" class="form-control @error('local') is-invalid @enderror" id="input3" placeholder="Nama Lokal" value="{{ old('local') }}" required>
                                @error('local')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input4" class="col-sm-3 col-form-label">Nama Umum/Dagang <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="general" class="form-control @error('general') is-invalid @enderror" id="input4" placeholder="Nama Umum/Dagang" value="{{ old('general') }}" required>
                                @error('general')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="select2" class="col-sm-3 col-form-label">Kelompok <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="group" class="form-control @error('group') is-invalid @enderror" id="select2" required>
                                    <option value="" hidden>Pilih</option>
                                    <option value="appendiks">Appendiks</option>
                                    <option value="non-appendiks">Non-appendiks</option>
                                </select>
                                @error('group')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="select2" class="col-sm-3 col-form-label">Size Born (cm)</label>
                            <div class="col-sm-4">
                                <input type="number" name="born_start" class="form-control" id="input5" placeholder="0" value="{{ old('born_start') }}" required>
                            </div>
                            <div class="col-sm-1 text-center">
                                <b>-</b>
                            </div>
                            <div class="col-sm-4">
                                <input type="number" name="born_end" class="form-control" id="input6" placeholder="0" value="{{ old('born_end') }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="select2" class="col-sm-3 col-form-label">Size Mature Male (cm)</label>
                            <div class="col-sm-4">
                                <input type="number" name="mature_male_start" class="form-control" id="input5" placeholder="0" value="{{ old('mature_male_start') }}" required>
                            </div>
                            <div class="col-sm-1 text-center">
                                <b>-</b>
                            </div>
                            <div class="col-sm-4">
                                <input type="number" name="mature_male_end" class="form-control" id="input6" placeholder="0" value="{{ old('mature_male_end') }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="select2" class="col-sm-3 col-form-label">Size Mature Female (cm)</label>
                            <div class="col-sm-4">
                                <input type="number" name="mature_female_start" class="form-control" id="input5" placeholder="0" value="{{ old('mature_female_start') }}" required>
                            </div>
                            <div class="col-sm-1 text-center">
                                <b>-</b>
                            </div>
                            <div class="col-sm-4">
                                <input type="number" name="mature_female_end" class="form-control" id="input6" placeholder="0" value="{{ old('mature_female_end') }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="select2" class="col-sm-3 col-form-label">Size Mega Spawner (cm)</label>
                            <div class="col-sm-9">
                                <input type="number" name="mega_spawner" class="form-control" id="input5" placeholder="0" value="{{ old('mega_spawner') }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
