@extends('layouts.dashboard')

@push('style')
<style>
    #map {
        height: 50vh;
    }
</style>
@endpush

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.branch.index') }}">Wilayah Kerja</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Wilayah Kerja</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">Edit Wilayah Kerja Form</h6>

                    <form action="{{ route('dashboard.branch.update', $branch->dtkn) }}" method="POST" class="forms-sample" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="input2" class="col-sm-3 col-form-label">Nama <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="input2" placeholder="Nama Lokasi" value="{{ old('name', $branch->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="map" class="col-sm-3 col-form-label">Titik Lokasi <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <div id="map"></div>
                                <small class="text-danger font-weight-bold">* Geser pin ke titik lokasi</small>
                            </div>
                        </div>
                        <input type="text" name="lat" class="form-control" placeholder="lat" value="{{ $branch->lat }}" id="latitude" hidden>
                        <input type="text" name="lng" class="form-control" placeholder="lng" value="{{ $branch->lng }}" id="longitude" hidden>
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAovhxjkjJX16jhgBhuFGJTlIdfJZ8uYCY&libraries=geometry"></script>
@endpush

@push('custom-scripts')
<script>
    var map;
    var lat = '{{ $branch->lat }}';
    var lng = '{{ $branch->lng }}';
    
    map = new google.maps.Map(
    document.getElementById("map"), {
        center: new google.maps.LatLng(lat, lng),
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControlOptions: {
          style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
          position: google.maps.ControlPosition.TOP_CENTER,
        },
    });

    var marker = new google.maps.Marker({
        map: map,
        draggable: true,
        position: new google.maps.LatLng(lat, lng),
    });

    google.maps.event.addListener(marker, 'dragend', function (event) {
        document.getElementById("latitude").value = this.getPosition().lat();
        document.getElementById("longitude").value = this.getPosition().lng();
        createMapCircle.setCenter({ lat: this.getPosition().lat(), lng: this.getPosition().lng() })
    });
</script>
@endpush
