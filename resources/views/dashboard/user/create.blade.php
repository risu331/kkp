@extends('layouts.dashboard')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.user.index') }}">User</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah User</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">Tambah User Form</h6>

                    <form action="{{ route('dashboard.user.store') }}" method="POST" class="forms-sample" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Avatar </label>
                            <div class="col-sm-9">
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/png, image/jpeg">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @if(Auth::user()->role == 'superadmin')
                            <div class="row mb-3">
                                <label for="select1" class="col-sm-3 col-form-label">Wilayah Kerja <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select name="branch_id" class="form-control @error('branch_id') is-invalid @enderror" id="select1" required>
                                        <option value="" hidden>Pilih</option>
                                        @foreach($branchs as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach()
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="row mb-3">
                            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Username </label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="exampleInputUsername2" placeholder="Username" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Email </label>
                            <div class="col-sm-9">
                                <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail2" placeholder="Email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Nomor Telepon </label>
                            <div class="col-sm-9">
                                <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" placeholder="Nomor Telepon" value="{{ old('phone_number') }}" required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="select1" class="col-sm-3 col-form-label">Hak Akses <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="role" class="form-control @error('role') is-invalid @enderror" id="select1" required>
                                    <option value="" hidden>Pilih</option>
                                    @if(Auth::user()->role == 'superadmin')
                                        <option value="admin">Admin</option>
                                    @endif
                                    <option value="enumerator">Enumerator</option>
                                    <option value="user">User</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Password </label>
                            <div class="col-sm-9">
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="********" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary me-2 btn-submit">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
