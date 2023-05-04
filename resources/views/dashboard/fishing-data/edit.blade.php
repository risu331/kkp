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
            <li class="breadcrumb-item"><a href="{{ route('dashboard.fishing-data.index') }}">Pendataan Ikan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Pendataan Ikan</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">Tambah Pendataan Ikan Form</h6>

                    @if(Auth::user()->role == 'superadmin')
                        <form method="get" id="form-branch">
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Wilayah Kerja <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <div class="form-control">
                                        {{ $fishing_data->branch->name }}
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif

                    <form action="{{ route('dashboard.fishing-data.update', $fishing_data->dtkn) }}" method="POST" class="forms-sample" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="" class="col-sm-3 col-form-label">Waktu Enumerasi <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="date" name="enumeration_time" class="form-control @error('enumeration_time') is-invalid @enderror" id="" placeholder="Daerah Penangkapan Ikan" value="{{ old('enumeration_time', $fishing_data->enumeration_time) }}" required>
                                @error('enumeration_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-sm-3 col-form-label">Nama Kapal/Pemilik <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="ship_id" class="js-example-placeholder-single js-states form-control @error('ship_id') is-invalid @enderror" id="select-search-ship" required>
                                    <option></option>
                                    @foreach($type_ships as $type_ship)
                                    <optgroup label="{{ $type_ship->type }}">
                                        @foreach($type_ship->ships as $ship)
                                            <option value="{{ $ship->id }}" {{ $ship->id == $fishing_data->ship_id ? 'selected' : '' }}>{{ $ship->name }}/{{ $ship->owner }}</option>
                                        @endforeach
                                    </optgroup>
                                    @endforeach
                                </select>
                                <a href="#" class="badge bg-info btn-create-ship" style="float: right; margin-top: 5px !important; padding: 8px;">Tambah Kapal</a>
                                @error('ship_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-sm-3 col-form-label">Jenis Alat Tangkap <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="fishing_gear_id" class="js-example-placeholder-single js-states form-control @error('fishing_gear_id') is-invalid @enderror" id="select-search-fishing-gear" required>
                                    <option></option>
                                    @foreach($fishing_gears as $fishing_gear)
                                        <option value="{{ $fishing_gear->id }}" {{ $fishing_gear->id == $fishing_data->fishing_gear_id ? 'selected' : '' }}>{{ $fishing_gear->name }}</option>
                                    @endforeach
                                </select>
                                <a href="#" class="badge bg-info btn-create-fish-gear" style="float: right; margin-top: 5px !important; padding: 8px;">Tambah Alat Tangkap</a>
                                @error('fishing_gear_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-sm-3 col-form-label">GT <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" name="gt" class="form-control @error('gt') is-invalid @enderror" id="" placeholder="GT" value="{{ old('gt', $fishing_data->gt) }}" required>
                                @error('gt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-sm-3 col-form-label">Jumlah Hari Operasional <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" name="operational_day" class="form-control @error('operational_day') is-invalid @enderror" id="" placeholder="Jumlah Hari Operasional" value="{{ old('operational_day', $fishing_data->operational_day) }}" required>
                                @error('operational_day')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-sm-3 col-form-label">Jumlah Hari Perjalanan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" name="travel_day" class="form-control @error('travel_day') is-invalid @enderror" id="" placeholder="Jumlah Hari Perjalanan" value="{{ old('travel_day', $fishing_data->travel_day) }}" required>
                                @error('travel_day')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-sm-3 col-form-label">Jumlah Setting <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" name="setting" class="form-control @error('setting') is-invalid @enderror" id="" placeholder="Jumlah Setting" value="{{ old('setting', $fishing_data->setting) }}" required>
                                @error('setting')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-sm-3 col-form-label">Lokasi Pendaratan/Kode <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="landing_site_id" class="js-example-placeholder-single js-states form-control @error('landing_site_id') is-invalid @enderror" id="select-search-landing-site" required>
                                    <option></option>
                                    @foreach($landing_sites as $landing_site)
                                        <option value="{{ $landing_site->id }}" data-lat="{{ $landing_site->lat }}" data-lng="{{ $landing_site->lng }}" {{ $landing_site->id == $fishing_data->landing_site_id ? 'selected' : '' }}>{{ $landing_site->name }}/{{ $landing_site->code }}</option>
                                    @endforeach
                                </select>
                                @error('landing_site_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-sm-3 col-form-label">Daerah Penangkapan Ikan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="area" class="form-control @error('area') is-invalid @enderror" id="" placeholder="Daerah Penangkapan Ikan" value="{{ old('area', $fishing_data->area) }}" required>
                                @error('area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Hasil Tangkap Ikan <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select name="is_htu" class="js-example-placeholder-single js-states form-control @error('is_htu') is-invalid @enderror" required>
                                        <option value="1" {{ $fishing_data->is_htu == 1 ? 'selected' : '' }}>Hasil Tangkap Utama (HTU)</option>
                                        <option value="0" {{ $fishing_data->is_htu == 0 ? 'selected' : '' }}>Hasil Tangkap Sampingan (HTS)</option>
                                    </select>
                                    @error('is_htu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Total Tangkapan Ikan Lainnya <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" name="total_other_fish" class="form-control @error('total_other_fish') is-invalid @enderror" id="" placeholder="99.9 Kg" value="{{ old('total_other_fish', $fishing_data->total_other_fish) }}" required>
                                    @error('total_other_fish')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        <div class="row mb-3">
                            <label for="map" class="col-sm-3 col-form-label">Titik Lokasi</label>
                            <div class="col-sm-9">
                                <div class="map">
                                    <div id="map"></div>
                                    <small class="text-danger font-weight-bold">* Geser pin A ke titik lokasi dan tekan peta untuk mengganti posisi pin B</small>
                                </div>
                                <div class="map-load text-center d-none">
                                    <img src="/assets/images/map2.jpg" style="width: 30vh">
                                    <br>
                                    <small class="text-danger font-weight-bold">* Silahkan pilih lokasi pendaratan untuk menampilkan peta</small>
                                </div>
                            </div>
                            <input type="text" name="lat" class="form-control" placeholder="lat" value="{{ $fishing_data->lat }}" id="latitude" hidden>
                            <input type="text" name="lng" class="form-control" placeholder="lng" value="{{ $fishing_data->lng }}" id="longitude" hidden>
                            <input type="hidden" name="flat" class="form-control" placeholder="lat" value="{{ $fishing_data->flat }}" id="from-latitude" hidden>
                            <input type="hidden" name="flng" class="form-control" placeholder="lng" value="{{ $fishing_data->flng }}" id="from-longitude" hidden>
                            <input type="hidden" name="miles" class="form-control" placeholder="miles" value="{{ $fishing_data->miles }}" id="miles" hidden>
                        </div>
                        <button type="submit" class="btn btn-primary me-2 submit-form">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" tabindex="-1" role="dialog" id="create-ship-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kapal</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"
                        data-bs-original-title="" title=""></button>
                </div>
                <form action="" method="post" id="create-ship-form">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="branch_id" value="{{ Request::input('branch_id') }}">
                        <div class="row mb-3">
                            <label for="select1" class="col-sm-3 col-form-label">Jenis Kapal <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="type_ship_id" class="form-control" required>
                                    <option value="" hidden>Piilh</option>
                                    @foreach($type_ships as $type_ship)
                                        <option value="{{ $type_ship->id }}">{{ $type_ship->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input1" class="col-sm-3 col-form-label">Nama <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="input1" placeholder="Nama Kapal" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <label for="input2" class="col-sm-3 col-form-label">Pemilik <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="owner" class="form-control @error('owner') is-invalid @enderror" id="input2" placeholder="Pemilik Kapal" value="{{ old('owner') }}" required>
                                @error('owner')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-light font-weight-bolder"
                                data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary font-weight-bolder" id="btn-submit-delete">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal fade" tabindex="-1" role="dialog" id="create-fish-gear-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jenis Alat Tangkap</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"
                        data-bs-original-title="" title=""></button>
                </div>
                <form action="" method="post" id="create-fish-gear-form">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="branch_id" value="{{ $fishing_data->branch->dtkn }}">
                        <div class="row">
                            <label for="input1" class="col-sm-3 col-form-label">Nama <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="input1" placeholder="Nama Alat" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-light font-weight-bolder"
                                data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary font-weight-bolder" id="btn-submit-delete">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
<script>
    $(document).on("click", ".btn-create-ship", function () {
        var id = $(this).val();
        $("#create-ship-form").attr("action", "{{ route('dashboard.fishing-data.ship.store') }}");
        $("#create-ship-modal").modal('show');
    });
    
    $(document).on("click", ".btn-create-fish-gear", function () {
        var id = $(this).val();
        $("#create-fish-gear-form").attr("action", "{{ route('dashboard.fishing-data.fishing-gear.store') }}");
        $("#create-fish-gear-modal").modal('show');
    });
    
    $(".submit-form").on('click', function(){
        $(".forms-sample").submit();
        $(".submit-form").prop('disabled', true);
    });

    $("#select-search-landing-site").select2({
        placeholder: "Pilih Lokasi Pendaratan",
    });

    $("#select-search-ship").select2({
        placeholder: "Pilih Kapal",
    });

    $("#select-search-fishing-gear").select2({
        placeholder: "Pilih Jenis Alat Tangkap",
    });

    $("#select-search-branch").select2({
        placeholder: "Pilih Wilayah Kerja",
    });

    $("#select-search-branch").on('change', function(){
        $("#form-branch").submit();
    });

    var map;
    var lat = @json($fishing_data->flat);
    var lng = @json($fishing_data->flng);
    
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

    const infoWindow = new google.maps.InfoWindow();

    var marker = null;
    var marker_fishing = null;
    var flightPath = null;
    var distanceLabel = null;
    function addMarker(lat, lng)
    {
        lat = @json($fishing_data->flat);
        lng = @json($fishing_data->flng);
        map.setCenter({ lat: lat, lng: lng });
        if(marker != null)
        {
            marker.setMap(null);
            marker_fishing.setMap(null);
            if(flightPath != null)
            {
                flightPath.setMap(null);
            }
    
            if(distanceLabel != null)
            {
                distanceLabel.setMap(null);
            }
        }
        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            title: 'Lokasi Pendaratan Ikan',
            label: 'A',
            position: new google.maps.LatLng(lat, lng),
        });
    
        marker.addListener("click", () => {
          infoWindow.close();
          infoWindow.setContent(marker.getTitle());
          infoWindow.open(marker.getMap(), marker);
        });
    
        marker_fishing = new google.maps.Marker({
            map: map,
            draggable: false,
            title: 'Lokasi Penangkapan Ikan',
            label: 'B',
            position: new google.maps.LatLng(@json($fishing_data->lat), @json($fishing_data->lng)),
        });

        var flightPlanCoordinates = [
            { lat: lat, lng: lng },
            { lat: @json($fishing_data->lat), lng: @json($fishing_data->lng) },
        ];
            
        flightPath = new google.maps.Polyline({
            path: flightPlanCoordinates,
            strokeColor: "#FF0000",
            strokeOpacity: 0.5,
            strokeWeight: 4,
            geodesic: true,
        });

        var latlng = new google.maps.LatLng(lat, lng);
        var fishingLatlng = new google.maps.LatLng(@json($fishing_data->lat), @json($fishing_data->lng));

        stuDistances = calculateDistances(latlng, fishingLatlng);
        
        $('#miles').val(stuDistances.miles);

        // get the point half-way between this marker and the home marker
        inBetween = google.maps.geometry.spherical.interpolate(latlng, fishingLatlng, 0.5);  
            
        // create an invisible marker
        labelMarker = new google.maps.Marker({  
            position: inBetween,  
            map: map,
            visible: false
        });

        
        distanceLabel = new Label();
        
        distanceLabel.bindTo('position', labelMarker, 'position');
        distanceLabel.set('text', stuDistances.miles + ' miles');

        distanceLabel.setMap(map);
        
        flightPath.setMap(map);
    
        marker_fishing.addListener("click", () => {
          infoWindow.close();
          infoWindow.setContent(marker_fishing.getTitle());
          infoWindow.open(marker_fishing.getMap(), marker_fishing);
        });

        // click on map and set you marker to that position
        google.maps.event.addListener(map, 'click', function(event) {
            marker_fishing.setPosition(event.latLng);
            document.getElementById("latitude").value = event.latLng.lat();
            document.getElementById("longitude").value = event.latLng.lng();
            
            lat = $('#from-latitude').val();
            lng = $('#from-longitude').val();
            
            if(flightPath != null)
            {
                flightPath.setMap(null);
            }
    
            if(distanceLabel != null)
            {
                distanceLabel.setMap(null);
            }
    
            var flightPlanCoordinates = [
                { lat: parseFloat(lat), lng: parseFloat(lng) },
                { lat: event.latLng.lat(), lng: event.latLng.lng() },
            ];
            
            flightPath = new google.maps.Polyline({
                path: flightPlanCoordinates,
                strokeColor: "#FF0000",
                strokeOpacity: 0.5,
                strokeWeight: 4,
                geodesic: true,
            });
    
            var latlng = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
            var fishingLatlng = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
    
            stuDistances = calculateDistances(latlng, fishingLatlng);
            
            $('#miles').val(stuDistances.miles);
    
            // get the point half-way between this marker and the home marker
            inBetween = google.maps.geometry.spherical.interpolate(latlng, fishingLatlng, 0.5);  
                
            // create an invisible marker
            labelMarker = new google.maps.Marker({  
                position: inBetween,  
                map: map,
                visible: false
            });
    
            
            distanceLabel = new Label();
            
            distanceLabel.bindTo('position', labelMarker, 'position');
            distanceLabel.set('text', stuDistances.miles + ' miles');
    
            distanceLabel.setMap(map);
            
            flightPath.setMap(map);
        });
        
        google.maps.event.addListener(marker, 'dragend', function (event) {
            document.getElementById("from-latitude").value = this.getPosition().lat();
            document.getElementById("from-longitude").value = this.getPosition().lng();
            
            lat = $('#latitude').val();
            lng = $('#longitude').val();
            
            if(flightPath != null)
            {
                flightPath.setMap(null);
            }
    
            if(distanceLabel != null)
            {
                distanceLabel.setMap(null);
            }
    
            var flightPlanCoordinates = [
                { lat: this.getPosition().lat(), lng: this.getPosition().lng() },
                { lat: parseFloat(lat), lng: parseFloat(lng) },
            ];
            
            flightPath = new google.maps.Polyline({
                path: flightPlanCoordinates,
                strokeColor: "#FF0000",
                strokeOpacity: 0.5,
                strokeWeight: 4,
                geodesic: true,
            });
    
            var latlng = new google.maps.LatLng(this.getPosition().lat(), this.getPosition().lng());
            var fishingLatlng = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
    
            stuDistances = calculateDistances(latlng, fishingLatlng);
            
            $('#miles').val(stuDistances.miles);
    
            // get the point half-way between this marker and the home marker
            inBetween = google.maps.geometry.spherical.interpolate(latlng, fishingLatlng, 0.5);  
                
            // create an invisible marker
            labelMarker = new google.maps.Marker({  
                position: inBetween,  
                map: map,
                visible: false
            });
    
            
            distanceLabel = new Label();
            
            distanceLabel.bindTo('position', labelMarker, 'position');
            distanceLabel.set('text', stuDistances.miles + ' miles');
    
            distanceLabel.setMap(map);
            
            flightPath.setMap(map);
        });
    }

    $(document).ready(function(){
        addMarker(@json($fishing_data->landing_site->lat), @json($fishing_data->landing_site->lng));
    });



    function Label(opt_options) {
        // Initialization
        this.setValues(opt_options);
        
        // Label specific
        var span = this.span_ = document.createElement('span');
        span.style.cssText = 'position: relative; left: -50%; top: -8px; ' +
                            'white-space: nowrap; border: 1px solid blue; ' +
                            'padding: 2px; background-color: white';
        
        var div = this.div_ = document.createElement('div');
        div.appendChild(span);
        div.style.cssText = 'position: absolute; display: none';
    }
    Label.prototype = new google.maps.OverlayView();

    Label.prototype.onAdd = function() {
        var pane = this.getPanes().floatPane;
        pane.appendChild(this.div_);
        
        // Ensures the label is redrawn if the text or position is changed.
        var me = this;
        this.listeners_ = [
            google.maps.event.addListener(this, 'position_changed',
                function() { me.draw(); }),
            google.maps.event.addListener(this, 'text_changed',
                function() { me.draw(); })
        ];
    };

    // Implement onRemove
    Label.prototype.onRemove = function() {
        var i, I;
        this.div_.parentNode.removeChild(this.div_);
        
        // Label is removed from the map, stop updating its position/text.
        for (i = 0, I = this.listeners_.length; i < I; ++i) {
            google.maps.event.removeListener(this.listeners_[i]);
        }
    };

    // Implement draw
    Label.prototype.draw = function() {
        var projection = this.getProjection();
        var position = projection.fromLatLngToDivPixel(this.get('position'));
        
        var div = this.div_;
        div.style.left = position.x + 'px';
        div.style.top = position.y + 'px';
        div.style.display = 'block';
        
        this.span_.innerHTML = this.get('text').toString();
    };

    function calculateDistances(start,end) {
        var stuDistances = {};
        
        stuDistances.metres = google.maps.geometry.spherical.computeDistanceBetween(start, end);	// distance in metres rounded to 1dp
        stuDistances.km = Math.round(stuDistances.metres / 1000 *10)/10;							// distance in km rounded to 1dp
        stuDistances.miles = Math.round(stuDistances.metres / 1000 * 0.6214 *10)/10;				// distance in miles rounded to 1dp

        return stuDistances;
    }
</script>
@endpush
