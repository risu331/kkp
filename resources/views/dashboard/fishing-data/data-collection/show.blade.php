@extends('layouts.dashboard')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<style>
    .data-collection{
        display: block;
    }

    .scrollcards {
      background-color: transparent;
      overflow: auto;
      white-space: nowrap;
    }

    ::-webkit-scrollbar {
        width: 0px;
        height: 0px;
        background: transparent;
    }
    
    div.scrollcards .card {
        display: inline-block;
        padding: 20px;
        text-decoration: none;
        height: auto; 
        margin-right: 5px;
    }

    .float{
        position:fixed;
        width:60px;
        height:60px;
        bottom:40px;
        right:40px;
        background-color:#0C9;
        color:#FFF;
        border-radius:50px;
        text-align:center;
        box-shadow: 2px 2px 3px #999;
    }

    .btn-float{
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    @media (min-width:320px)  { 
        div.scrollcards .card {
            display: inline-block;
            padding: 20px;
            text-decoration: none;
            height: auto; 
            width: 80vw;
            margin-right: 5px;
        }

        .desktop-design{
            display: none !important;
        }
    }
    @media (min-width:768px)  { 
        div.scrollcards .card {
            display: none;
            padding: 20px;
            text-decoration: none;
            height: auto; 
            width: 40vw;
            margin-right: 5px;
        }

        .data-collection{
            display: none !important;
        }

        .float{
            display: none !important;
        }

        .mobile-design{
            display: none !important;
        }

        .desktop-design{
            display: block !important;
        }
    }
    
    @media (min-width:1024px) { 
        div.scrollcards .card {
            padding: 20px;
            text-decoration: none;
            height: auto; 
            width: 30vw;
            margin-right: 5px;
            display: none;
        }

        .data-collection{
            display: none !important;
        }

        .float{
            display: none !important;
        }

        .mobile-design{
            display: none !important;
        }

        .desktop-design{
            display: block !important;
        }
    }
    
    .table-responsive::-webkit-scrollbar {
        -webkit-appearance: none;
    }
    
    .table-responsive::-webkit-scrollbar:horizontal {
        height: 12px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, .5);
        border-radius: 10px;
        border: 2px solid #ffffff;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        border-radius: 10px;  
        background-color: #ffffff; 
    }
</style>
@endpush

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.fishing-data.index') }}">Pendataan Ikan</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('dashboard.fishing-data.data-collection.index') }}?id={{ Request::input('id') }}">Detail Pendataan Ikan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Data Ikan</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card" stye="overflow-x: auto;">
            <div class="card-header">
                <h6 class="card-title">Detail Data Ikan</h6>
                <p>Lokasi Pendaratan/Kode: <b>{{ $data_collection->fishing_data->landing_site->name ?? 'NULL' }}/{{ $data_collection->fishing_data->landing_site->code ?? 'NULL' }}</b></p>
                <p>Koordinat: <b>{{ $data_collection->fishing_data->lat ?? 'NULL' }}, {{ $data_collection->fishing_data->lng ?? 'NULL' }}</b></p>
                <p>Tanggal: <b>{{ date('d-m-Y', strtotime($data_collection->fishing_data->enumeration_time)) }}</b></p>
                <p>Nama Enumerator: <b>{{ $data_collection->fishing_data->user_name ?? 'NULL' }}</b></p>
                <p>Status: 
                    @if($data_collection->status == 'disetujui')
                        <span class="badge bg-success">{{ $data_collection->status }}</span>
                    @else
                        @if(Auth::user()->role != 'enumerator')
                            <button class="btn-verification badge bg-warning" value="{{ $data_collection->dtkn }}" style="border: 0px;"><span data-bs-toggle="tooltip" data-bs-placement="top" title="Verifikasi">menunggu persetujuan</span></button>
                        @else
                            <span class="badge bg-warning">{{ $data_collection->status }}</span>    
                        @endif
                    @endif
                </p>
                @if($data_collection->status != 'disetujui')
                    <a href="{{ route('dashboard.fishing-data.data-collection.edit', $data_collection->dtkn) }}" class="btn btn-info text-white" style="float: right;">Edit</a>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="" class="table">
                        <thead>
                            <tr>
                                <th rowspan="2" style="text-align: center;">Nama Lokal</th>
                                <th rowspan="2" style="text-align: center;">Nama Umum/Dagang</th>
                                <th rowspan="2" style="text-align: center;">Nama Ilmiah Species</th>
                                <th colspan="3" style="text-align: center;">Ukuran tubuh hiu/pari yang menyerupai hiu</th>
                                <th rowspan="2" style="text-align: center;">Lebar pari (DW)</th>
                                <th colspan="3" style="text-align: center;">Ukuran sirip punggung</th>
                                <th rowspan="2" style="text-align: center;">Panjang sirip dada (MP)</th>
                                <th rowspan="2" style="text-align: center;">Berat ikan</th>
                                <th rowspan="2" style="text-align: center;">Jenis kelamin</th>
                                <th rowspan="2" style="text-align: center;">Kematangan gonad jantan</th>
                                <th rowspan="2" style="text-align: center;">Nama kapal/Pemilik</th>
                                <th rowspan="2" style="text-align: center;">Alat tangkap</th>
                                <th rowspan="2" style="text-align: center;">GT</th>
                                <th rowspan="2" style="text-align: center;">Daerah penangkapan ikan</th>
                                <th rowspan="2" style="text-align: center;">Trip di laut</th>
                                <!--<th rowspan="2" style="text-align: center;">Total catch</th>-->
                                <th rowspan="2" style="text-align: center;">Ket*</th>
                                <th rowspan="2" style="text-align: center;">Foto</th>
                            </tr>
                            
                            <tr>
                                <th style="text-align: center;">Panjang baku (SL)</th>
                                <th style="text-align: center;">Panjang cagak (FL)</th>
                                <th style="text-align: center;">Panjang total (TL)</th>
                                <th style="text-align: center;">Tinggi miring (M)</th>
                                <th style="text-align: center;">Panjang (P)</th>
                                <th style="text-align: center;">Tinggi tegak (T)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td style="text-align: center;">{{ $data_collection->species_fish->local }}</td>
                            <td style="text-align: center;">{{ $data_collection->species_fish->general }}</td>
                            <td style="text-align: center;"><i>{{ $data_collection->species_fish->species }}</i></td>
                            <td style="text-align: center;">{{ $data_collection->sl ?? 0 }} cm</td>
                            <td style="text-align: center;">{{ $data_collection->fl ?? 0 }} cm</td>
                            <td style="text-align: center;">{{ $data_collection->tl ?? 0 }} cm</td>
                            <td style="text-align: center;">{{ $data_collection->dw ?? 0 }} cm</td>
                            <td style="text-align: center;">{{ $data_collection->m ?? 0 }} cm</td>
                            <td style="text-align: center;">{{ $data_collection->p ?? 0 }} cm</td>
                            <td style="text-align: center;">{{ $data_collection->t ?? 0 }} cm</td>
                            <td style="text-align: center;">{{ $data_collection->mp ?? 0 }} cm</td>
                            <td style="text-align: center;">{{ $data_collection->weight ?? 0 }} kg</td>
                            <td style="text-align: center;">
                                @if($data_collection->gender == 'j')
                                    Jantan
                                @elseif($data_collection->gender == 'b')
                                    Betina
                                @else
                                -
                                @endif
                            </td>
                            <td style="text-align: center;">{{ $data_collection->gonad ?? 0 }}</td>
                            <td style="text-align: center;">{{ $data_collection->fishing_data->ship->name ?? 'NULL' }}/{{ $data_collection->fishing_data->ship->owner ?? 'NULL' }}</td>
                            <td style="text-align: center;">{{ $data_collection->fishing_data->fishing_gear->name ?? 'NULL' }}</td>
                            <td style="text-align: center;">{{ $data_collection->fishing_data->gt ?? 'NULL' }}</td>
                            <td style="text-align: center;">{{ $data_collection->fishing_data->area ?? 'NULL' }}</td>
                            <td style="text-align: center;">{{ ($data_collection->fishing_data->operational_day - $data_collection->fishing_data->travel_day) * $data_collection->fishing_data->setting ?? 0 }} hari</td>
                            <td style="text-align: center;">{{ $data_collection->description ?? '-' }}</td>
                            <td style="text-align: center;">
                                @php
                                    $type_fish_picture = App\Models\TypeFishPicture::where('type_fish_id', $data_collection->species_fish->type_fish_id)->get();
                                @endphp
                                @foreach($type_fish_picture as $key => $picture)
                                    @php
                                        $data_image = App\Models\DataImage::where('data_collection_id', $data_collection->id)->where('title', $picture->title)->first();
                                    @endphp
                                    @if($data_image != null)
                                        <label class="mb-1"><b>{{ $data_image->title }} (<a href="{{ $data_image->image }}" download>Unduh</a>):</b></label>
                                        <br>
                                        <img src="{{ $data_image->image }}" class="mb-3" style="border-radius: 0px !important; width: 64px; height: 64px;" data-enlargable>
                                        <br>
                                    @endif
                                @endforeach
                            </td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="verification-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikasi Pendataan Ikan</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"
                    data-bs-original-title="" title=""></button>
            </div>
            <div class="modal-body">
                <p>Tindakan ini akan memverifikasi data tersebut dan data yang diverifikasi akan menjadi hasil data statistik dan pemetaan peta, apakah
                    Anda yakin ingin melanjutkan?</p>
            </div>
            <div class="modal-footer">
                <form action="" method="post" id="verification-form">
                    @csrf
                    <button type="button" class="btn btn-light font-weight-bolder"
                        data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary font-weight-bolder" id="btn-submit-delete">Ya, Saya
                        Yakin</button>
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

    $(document).on("click", ".btn-verification", function () {
        var id = $(this).val();
        console.log(id);
        $("#verification-form").attr("action", "{{ route('dashboard.fishing-data.data-collection.index') }}/" + id + "/verification");
        $("#verification-modal").modal('show');
    });

</script>
<script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
