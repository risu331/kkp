@extends('layouts.dashboard')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.type-fish.index') }}?branch_ids[]={{ $type_fish_picture->type_fish->branch_id ?? 0 }}">Jenis Ikan ({{ $type_fish->type ?? 'NULL' }})</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.type-fish.picture.index') }}?id={{ $type_fish_picture->type_fish->dtkn }}"> Pengambilan Gambar Ikan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Pengambilan Gambar Ikan</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">Edit Pengambilan Gambar Ikan Form</h6>

                    <form action="{{ route('dashboard.type-fish.picture.update', $type_fish_picture->dtkn) }}" method="POST" class="forms-sample" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="select1" class="col-sm-3 col-form-label">Jenis Ikan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="hidden" name="type_fish_id" value="{{ $type_fish_picture->type_fish->id }}">
                                <input type="text" class="form-control" value="{{ $type_fish_picture->type_fish->type }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input1" class="col-sm-3 col-form-label">Index <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="index" class="form-control @error('index') is-invalid @enderror" id="input1" placeholder="Urutan Data" value="{{ old('index', $type_fish_picture->index) }}" required>
                                @error('index')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input1" class="col-sm-3 col-form-label">Judul <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="input1" placeholder="Judul Gambar" value="{{ old('title', $type_fish_picture->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input1" class="col-sm-3 col-form-label">Syarat Gambar <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="is_required" class="form-control @error('is_required') is-invalid @enderror" required>
                                    <option value="" hidden>Pilih</option>
                                    <option value="1" {{ $type_fish_picture->is_required == 1 ? 'selected' : '' }}>Wajib Ada</option>
                                    <option value="0" {{ $type_fish_picture->is_required == 0 ? 'selected' : '' }}>Tidak Wajib Ada</option>
                                </select>
                                @error('sample_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input1" class="col-sm-3 col-form-label">Contoh Gambar (<a href="{{ $type_fish_picture->sample_image }}" target="_blank">Lihat</a>)</label>
                            <div class="col-sm-9">
                                <input type="file" name="sample_image" class="form-control" accept="image/png, image/jpeg">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input1" class="col-sm-3 col-form-label">Deskripsi Gambar</label>
                            <div class="col-sm-9">
                                <textarea name="sample_description" class="form-control" id="ckeditor" placeholder="Masukkan Deskripsi Gambar" >{!! old('sample_description', $type_fish_picture->sample_description) !!}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script>
    CKEDITOR.replace('sample_description');
</script>
@endpush
