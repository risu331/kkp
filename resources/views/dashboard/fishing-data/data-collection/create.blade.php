@extends('layouts.dashboard')

@push('style')
<style>
    input[type="file"] {
        display: none;
    }
</style>

@endpush

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb"> 
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.fishing-data.index') }}">Pendataan Ikan</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.fishing-data.data-collection.index') }}?id={{ Request::input('id') }}">Detail Pendataan Ikan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Data Ikan</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">Tambah Pendataan Ikan Form</h6>
                    <form method="get" id="form-species">
                        <input type="hidden" name="id" value="{{ Request::input('id') }}">
                        <div class="row mb-3">
                            <label for="" class="col-sm-3 col-form-label">Nama Ilmiah/Lokal/Umum Ikan <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="species_fish_id" class="js-example-placeholder-single js-states form-control @error('species_fish_id') is-invalid @enderror" id="select-search-fish">
                                    <option></option>
                                    @foreach($type_fishs as $type_fish)
                                    <optgroup label="{{ $type_fish->type }}">
                                        @foreach($type_fish->species_fishs as $species_fish)
                                            <option value="{{ $species_fish->dtkn }}" {{ Request::input('species_fish_id') == $species_fish->dtkn ? 'selected' : '' }}><i>{{ $species_fish->species }}</i>/{{ $species_fish->local }}/{{ $species_fish->general }}</option>
                                        @endforeach
                                    </optgroup>
                                    @endforeach
                                </select>
                                @error('ship_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </form>
                    @php
                        $species = \App\Models\SpeciesFish::where('dtkn', Request::input('species_fish_id'))->first();
                    @endphp
                    @if($species != null)
                        <form action="{{ route('dashboard.fishing-data.data-collection.store') }}" method="POST" class="forms-sample" enctype="multipart/form-data">
                            @csrf
                            @php
                                $fishing_data_id = App\Models\FishingData::where('dtkn', Request::input('id'))->pluck('id')->first();
                                $species_fish_id = App\Models\SpeciesFish::where('dtkn', Request::input('species_fish_id'))->pluck('id')->first();
                            @endphp
                            <input type="hidden" name="species_fish_id" value="{{ $species_fish_id }}">
                            <input type="hidden" name="fishing_data_id" value="{{ $fishing_data_id }}">
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Jumlah Ekor <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" name="amount_fish" class="form-control @error('amount_fish') is-invalid @enderror input-amount-fish" id="" placeholder="Jumlah Ekor" value="{{ old('amount_fish', 1) }}" >
                                    @error('amount_fish')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label for="" class="col-sm-3 col-form-label"><b>Ukuran tubuh hiu/pari yang menyerupai hiu</b></label>
                            </div>
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">SL (cm)<i class="link-icon text-info ms-1" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="right" title="Panjang baku (standard length)"></i></label>
                                <div class="col-sm-9">
                                    <input type="number" name="sl" class="form-control @error('sl') is-invalid @enderror" id="" placeholder="99.9 cm" value="{{ old('sl') }}" >
                                    @error('sl')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">FL (cm)<i class="link-icon text-info ms-1" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="right" title="Panjang cagak (fork length)"></i></label>
                                <div class="col-sm-9">
                                    <input type="number" name="fl" class="form-control @error('fl') is-invalid @enderror" id="" placeholder="99.9 cm" value="{{ old('fl') }}" >
                                    @error('fl')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">TL (cm)<i class="link-icon text-info ms-1" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="right" title="Panjang total (total length)"></i></label>
                                <div class="col-sm-9">
                                    <input type="number" name="tl" class="form-control @error('tl') is-invalid @enderror" id="" placeholder="99.9 cm" value="{{ old('tl') }}" >
                                    @error('tl')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">DW (cm)<i class="link-icon text-info ms-1" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="right" title="Lebar pari (disc width)"></i></label>
                                <div class="col-sm-9">
                                    <input type="number" name="dw" class="form-control @error('dw') is-invalid @enderror" id="" placeholder="99.9 cm" value="{{ old('dw') }}" >
                                    @error('dw')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label for="" class="col-sm-3 col-form-label"><b>Ukuran sirip punggung <i>(dorsal fin)</i> pertama</b></label>
                            </div>
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">M (cm)<i class="link-icon text-info ms-1" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="right" title="Tinggi miring"></i></label>
                                <div class="col-sm-9">
                                    <input type="number" name="m" class="form-control @error('m') is-invalid @enderror" id="" placeholder="99.9 cm" value="{{ old('m') }}" >
                                    @error('m')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">P (cm)<i class="link-icon text-info ms-1" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="right" title="Panjang"></i></label>
                                <div class="col-sm-9">
                                    <input type="number" name="p" class="form-control @error('p') is-invalid @enderror" id="" placeholder="99.9 cm" value="{{ old('p') }}" >
                                    @error('p')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">T (cm)<i class="link-icon text-info ms-1" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="right" title="Tinggi tegak"></i></label>
                                <div class="col-sm-9">
                                    <input type="number" name="t" class="form-control @error('t') is-invalid @enderror" id="" placeholder="99.9 cm" value="{{ old('t') }}" >
                                    @error('t')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">MP (cm)<i class="link-icon text-info ms-1" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="right" title="Panjang sirip dada (pectoral fin "></i></label>
                                <div class="col-sm-9">
                                    <input type="number" name="mp" class="form-control @error('mp') is-invalid @enderror" id="" placeholder="99.9 cm" value="{{ old('mp') }}" >
                                    @error('mp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!--<div class="row mb-3">-->
                            <!--    <label for="" class="col-sm-3 col-form-label">Ukuran PT/TL<span class="text-danger">*</span></label>-->
                            <!--    <div class="col-sm-9">-->
                            <!--        <input type="number" name="pt" class="form-control @error('pt') is-invalid @enderror" id="" placeholder="99.9 cm" value="{{ old('pt') }}" >-->
                            <!--        @error('pt')-->
                            <!--            <div class="invalid-feedback">{{ $message }}</div>-->
                            <!--        @enderror-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="row mb-3">-->
                            <!--    <label for="" class="col-sm-3 col-form-label">Ukuran PS/PCL<span class="text-danger">*</span></label>-->
                            <!--    <div class="col-sm-9">-->
                            <!--        <input type="number" name="ps" class="form-control @error('ps') is-invalid @enderror" id="" placeholder="99.9 cm" value="{{ old('ps') }}" >-->
                            <!--        @error('ps')-->
                            <!--            <div class="invalid-feedback">{{ $message }}</div>-->
                            <!--        @enderror-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="row mb-3">-->
                            <!--    <label for="" class="col-sm-3 col-form-label">Ukuran LT/DW<span class="text-danger">*</span></label>-->
                            <!--    <div class="col-sm-9">-->
                            <!--        <input type="number" name="lt" class="form-control @error('lt') is-invalid @enderror" id="" placeholder="99.9 cm" value="{{ old('lt') }}" >-->
                            <!--        @error('lt')-->
                            <!--            <div class="invalid-feedback">{{ $message }}</div>-->
                            <!--        @enderror-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Berat<span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" name="weight" class="form-control @error('weight') is-invalid @enderror input-weight" id="" placeholder="99.9 Kg" value="{{ old('weight') }}" >
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Jenis Kelamin</label>
                                <div class="col-sm-9">
                                    <select name="gender" class="form-control input-gender">
                                        <option value="" hidden>Pilih</option>
                                        <option value="j">Jantan</option>
                                        <option value="b">Betina</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Panjang Klasper</label>
                                <div class="col-sm-9">
                                    <input type="number" name="clasp_length" class="form-control" placeholder="0 cm" value="{{ old('clasp_length') }}">
                                </div>
                            </div>
                            <div class="row mb-3 input-jantan d-none">
                                <label for="" class="col-sm-3 col-form-label">Tingkat Kematangan Gonad</label>
                                <div class="col-sm-9">
                                    <select name="gonad" class="form-control">
                                        <option value="" hidden>Pilih</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 input-betina d-none">
                                <label for="" class="col-sm-3 col-form-label">Jumlah Anakan</label>
                                <div class="col-sm-9">
                                    <input type="number" name="amount_child" class="form-control" placeholder="0 ekor" value="{{ old('amount_child') }}">
                                </div>
                            </div>
                            <div class="row mb-3 input-betina d-none">
                                <label for="" class="col-sm-3 col-form-label">Berat Anakan</label>
                                <div class="col-sm-9">
                                    <input type="number" name="weight_child" class="form-control" placeholder="99.9 Kg" value="{{ old('weight_child') }}">
                                </div>
                            </div>
                            <div class="row mb-3 input-betina d-none">
                                <label for="" class="col-sm-3 col-form-label">Ukuran Anakan Min</label>
                                <div class="col-sm-9">
                                    <input type="text" name="length_min_child" class="form-control" placeholder="0 cm" value="{{ old('length_min_child') }}">
                                </div>
                            </div>
                            <div class="row mb-3 input-betina d-none">
                                <label for="" class="col-sm-3 col-form-label">Ukuran Anakan Max </label>
                                <div class="col-sm-9">
                                    <input type="text" name="length_max_child" class="form-control" placeholder="0 cm" value="{{ old('length_max_child') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Harga Ekonomi per 1 Kg </label>
                                <div class="col-sm-9">
                                    <input type="number" name="economy_price" class="form-control input-economy-price" placeholder="1.000" value="{{ old('economy_price') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Total Harga Ekonomi</label>
                                <div class="col-sm-9">
                                    <input type="number" name="total_economy_price" class="form-control input-total-economy-price" placeholder="Berat x Harga" value="{{ old('total_economy_price') }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Keterangan </label>
                                <div class="col-sm-9">
                                    <textarea name="description" class="form-control" placeholder="Keterangan">{{ old('description') }}</textarea>
                                </div>
                            </div>
                            @foreach($type_fish_pictures as $key => $picture)
                                <input type="hidden" name="type_fish_picture_id[]" value="{{ $picture->id }}">
                                <div class="row mb-3">
                                    <label for="" class="col-sm-3 col-form-label"><b>{{ $picture->title }}</b> 
                                        @if($picture->is_required == 1)
                                            <span class="text-danger">*</span>
                                        @endif
                                        <i class="link-icon text-info btn-info-{{ $picture->id }}" data-feather="info"></i>
                                    </label>
                                    <div class="col-sm-9 text-center">
                                        <div class="p-3 mb-1" style="border: 1px dashed black;">
                                            <img src="https://static.thenounproject.com/png/2390797-200.png" width="100" id="results-{{ $picture->id }}" data-enlargable>
                                        </div>
                                        <br>
                                        <label class="btn btn-secondary">
                                            <i class="link-icon" data-feather="folder"></i> Buka Berkas
                                            <input type="file" name="image_file[{{ $picture->id }}]" class="image-file" id="image-file-{{ $picture->id }}" onChange="changeImageVal({{$picture->id}}, 'file')" data-id="{{ $picture->id }}" accept="image/png, image/jpeg">
                                        </label> 
                                        <label class="btn btn-secondary">
                                            <i class="link-icon" data-feather="camera"></i> Ambil Gambar
                                            <input type="file" name="image_camera[{{ $picture->id }}]" class="image-camera" id="image-camera-{{ $picture->id }}" onChange="changeImageVal({{$picture->id}}, 'camera')" data-id="{{ $picture->id }}" accept="image/png, image/jpeg" capture="camera">
                                        </label> 
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                            <button type="submit" class="btn btn-primary me-2 submit-form">Submit</button>
                        </form>
                        @foreach($type_fish_pictures as $key => $picture)
                            <div class="modal fade" tabindex="-1" role="dialog" id="info-modal-{{ $picture->id }}">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Contoh Pengambilan Gambar {{ $picture->title }}</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"
                                                data-bs-original-title="" title=""></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-center mb-3">
                                                <img src="{{ $picture->sample_image ?? 'https://static.thenounproject.com/png/2390797-200.png' }}" width="160">
                                            </div>
                                            {!! $picture->sample_description !!}
                                        </div>
                                        <div class="modal-footer">
                                            <form action="" method="post" id="delete-form">
                                                <button type="button" class="btn btn-light font-weight-bolder"
                                                    data-bs-dismiss="modal">Tutup</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                // info events
                                $(document).on("click", ".btn-info-{{ $picture->id }}", function () {
                                    var id = $(this).val();
                                    $("#info-modal-{{ $picture->id }}").modal('show');
                                });
                            </script>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
<script>
    
    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix){
    	var number_string = angka.replace(/[^,\d]/g, '').toString(),
    	split   		= number_string.split(','),
    	sisa     		= split[0].length % 3,
    	rupiah     		= split[0].substr(0, sisa),
    	ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
    
    	// tambahkan titik jika yang di input sudah menjadi angka ribuan
    	if(ribuan){
    		separator = sisa ? '.' : '';
    		rupiah += separator + ribuan.join('.');
    	}
    
    	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    	return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    $('.input-weight').on('keyup', function(){
       var val = $(this).val();
       var price = $('.input-economy-price').val();
       calculateEconomy(val, price);
    });
    
    $('.input-economy-price').on('keyup', function(){
       var val = $(this).val();
       var weight = $('.input-weight').val();
       calculateEconomy(weight, val);
    });
    
    function calculateEconomy(weight, price)
    {
        var val = weight*price;
        $('.input-total-economy-price').val(val);
    }

    function changeImageVal(id, category)
    {
        if(category == 'camera')
        {
            $('#image-file-' + id).val(null);
            console.log($('#image-file-' + id).val());
        } else {
            $('#image-camera-' + id).val(null);
            console.log($('#image-camera-' + id).val());
        }
    }
    
    $("#select-search-fish").select2({
        placeholder: "Pilih Nama Ilmiah/Lokal/Umum Ikan",
        allowClear: true
    });

    $("#select-search-fish").on('change', function(){
        $("#form-species").submit();
    });

    $(".submit-form").on('click', function(){
        $(".forms-sample").submit();
        $(".submit-form").prop('disabled', true);
    });

    $(".input-gender").on('change', function(){
        var val = $(this).val();
        if(val == 'j')
        {
            $('.input-jantan').removeClass('d-none');
            $('.input-betina').addClass('d-none');
        } else {
            $('.input-jantan').addClass('d-none');
            $('.input-betina').removeClass('d-none');
        }
    });

    function readURL(input, id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#results-' + id).attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".image-file").change(function(){
        readURL(this, $(this).data('id'));
    });
    
    $(".image-camera").change(function(){
        readURL(this, $(this).data('id'));
    });

    $('img[data-enlargable]').addClass('img-enlargable').click(function(){
        var src = $(this).attr('src');
        $('<div>').css({
            background: 'RGBA(0,0,0,.5) url('+src+') no-repeat center',
            backgroundSize: 'contain',
            width:'100%', height:'100%',
            position:'fixed',
            zIndex:'10000',
            top:'0', left:'0',
            cursor: 'zoom-out'
        }).click(function(){
            $(this).remove();
        }).appendTo('body');
    });

</script>
@endpush
