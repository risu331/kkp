@extends('layouts.dashboard')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<style>
    canvas{
        width:  600px !important;
        height: 600px !important;
        margin: 0 auto;
    }

    @media (min-width:320px)  { 
        canvas{
            width:  300px !important;
            height: 300px !important;
        }
    }
    @media (min-width:768px)  { 
        canvas{
            width:  600px !important;
            height: 600px !important;
        }
    }
    
    @media (min-width:1024px) { 
        canvas{
            width:  600px !important;
            height: 600px !important;
        }
    }
</style>
<style>
        /*!
    * jQuery ComboTree Plugin 
    * Author:  Erhan FIRAT
    * Mail:    erhanfirat@gmail.com
    * Licensed under the MIT license
    * Version: 1.2.1
    */


    :root {
        --ct-bg: #fff;
        --ct-btn-hover: #e8e8e8;
        --ct-btn-active: #ddd;
        --ct-btn-color: #555;
        --ct-border-color: #e1e1e1;
        --ct-border-radius: 5px;
        --ct-tree-hover: #efefef;
        --ct-selection: #418EFF;
        --ct-padding: 8px;
    }


    .comboTreeWrapper{
        position: relative;
        text-align: left !important;
    }

    .comboTreeInputWrapper{
        position: relative;
    }

    .comboTreeArrowBtn {
        position: absolute;
        right: 0px;
        bottom: 0px;
        top: 0px;
        box-sizing: border-box;
        border: 1px solid var(--ct-border-color);
        border-radius: 0 var(--ct-border-radius) var(--ct-border-radius) 0;
        background: var(--ct-border-color);
        cursor: pointer;
        -webkit-user-select: none; /* Safari */
        -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* IE10+/Edge */
        user-select: none; /* Standard */
    }
    .comboTreeArrowBtn:hover {
        background: var(--ct-btn-hover);
    }
    .comboTreeArrowBtn:active {
        background: var(--ct-btn-active);
    }
    .comboTreeInputBox:focus + .comboTreeArrowBtn {
        color: var(--ct-btn-color);
        border-top: 1px solid var(--ct-selection);
        border-right: 1px solid var(--ct-selection);
        border-bottom: 1px solid var(--ct-selection);
    }

    .comboTreeArrowBtnImg{
        font-size: 1.25rem;
    }

    .comboTreeDropDownContainer {
        display: none;
        background: var(--ct-bg);
        border: 1px solid var(--ct-border-color);
        position: absolute;
    width: 100%;
    box-sizing: border-box;
    z-index: 999;
        max-height: 250px;
        overflow-y: auto;
    }

    .comboTreeDropDownContainer ul{
        padding: 0px;
        margin: 0;
    }

    .comboTreeDropDownContainer li{
        list-style-type: none;
        padding-left: 15px;
    }

    .comboTreeDropDownContainer li .selectable{
        cursor: pointer;
    }

    .comboTreeDropDownContainer li .not-selectable{
        cursor: not-allowed;
    }


    .comboTreeDropDownContainer li:hover{
        background-color: var(--ct-tree-hover);}
    .comboTreeDropDownContainer li:hover ul{
        background-color: var(--ct-bg)}
    .comboTreeDropDownContainer li span.comboTreeItemTitle.comboTreeItemHover,
    .comboTreeDropDownContainer label.comboTreeItemHover
    {
        background-color: var(--ct-selection);
        color: var(--ct-bg);
        border-radius: 2px;
    }

    span.comboTreeItemTitle, .comboTreeDropDownContainer .selectAll{
        display: block;
        padding: 3px var(--ct-padding);
    }
    .comboTreeDropDownContainer label{
        cursor: pointer;
        width: 100%;
        display: block;
    }
    .comboTreeDropDownContainer .comboTreeItemTitle input,
    .comboTreeDropDownContainer .selectAll input {
        position: relative;
        top: 2px;
        margin: 0px 4px 0px 0px;
    }
    .comboTreeParentPlus{
        position: relative;
        left: -12px;
        top: 4px;
        width: 4px;
        float: left;
            cursor: pointer;
    }


    .comboTreeInputBox {
        padding: var(--ct-padding);
        border-radius: var(--ct-border-radius);
        border: 1px solid var(--ct-border-color);
        width: 100%;
        box-sizing: border-box;
        padding-right: 24px;
    }
    .comboTreeInputBox:focus {
        border: 1px solid var(--ct-selection);
        outline-width: 0;
    }


    .multiplesFilter{
        width: 100%;
        padding: 5px;
        box-sizing: border-box;
        border-top: none;
        border-left: none;
        border-right: none;
        border-bottom: 1px solid var(--ct-border-color);
    }
</style>
@endpush

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Statistik Jenis Kelamin</li>
    </ol>
</nav>

<div class="card p-3 mb-3">
    <form method="get">
        <div class="row">
            @if(Auth::user()->role != 'superadmin')
                <input type="hidden" name="branch_id" value="{{ Auth::user()->branch->dtkn }}">
                <div class="col-6 col-md-5 mb-3">
                    <label>Tanggal</label>
                    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                        <span></span> <b class="caret"></b>
                        <input type="hidden" name="date">
                    </div>
                </div>
                <div class="col-6 col-md-5 mb-3">
                    <label>Jenis Ikan:</label>
                    <select name="type_fish_id" class="js-example-placeholder-single js-states form-control" id="select-search-type-fish" required>
                        <option></option>
                        @foreach($type_fishes as $type_fish)
                            <option value="{{ $type_fish->dtkn }}" {{ $type_fish->dtkn == Request::input('type_fish_id') ? 'selected' : '' }}>{{ $type_fish->type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label>&nbsp;</label>
                    <div class="d-grid">
                        <button class="btn btn-info text-white">Filter</button>
                    </div>
                </div>
            @else
                <div class="col-12 col-md-12 mb-3">
                    <label>Wilayah Kerja:</label>
                    <select name="branch_id" class="js-example-placeholder-single js-states form-control" id="select-search-branch" required>
                        <option></option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->dtkn }}" {{ $branch->dtkn == Request::input('branch_id') ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if(Request::input('branch_id'))
                    <div class="col-6 col-md-5 mb-3">
                        <label>Tanggal</label>
                        <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            <span></span> <b class="caret"></b>
                            <input type="hidden" name="date">
                        </div>
                    </div>
                    <div class="col-6 col-md-5 mb-3">
                        <label>Jenis Ikan:</label>
                        <select name="type_fish_id" class="js-example-placeholder-single js-states form-control" id="select-search-type-fish" required>
                            <option></option>
                            @foreach($type_fishes as $type_fish)
                                <option value="{{ $type_fish->dtkn }}" {{ $type_fish->dtkn == Request::input('type_fish_id') ? 'selected' : '' }}>{{ $type_fish->type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label>&nbsp;</label>
                        <div class="d-grid">
                            <button class="btn btn-info text-white">Filter</button>
                        </div>
                    </div>
                @else
                    <div class="col-12 col-md-12">
                        <div class="d-grid">
                            <button class="btn btn-info text-white">Filter</button>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </form>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            @if(Request::input('type_fish_id') != null)
                <div class="card-header">
                    <!--<button type="button" class="btn btn-success btn-export" style="float: right;">Export</button>-->
                    <a href='{{ route("dashboard.statistic.export.5") }}?branch_id={{ Request::input("branch_id") }}&start_date={{ $start_date }}&end_date={{ $end_date }}' class="btn btn-success" style="float: right;">Export</a>
                </div>
            @endif
            <div class="card-body">
                <h6 class="card-title">Statistik Data Ikan (Jenis Kelamin)</h6>
                <p class="text-muted mb-3">Menampilkan komposisi jenis ikan berdasarkan jenis kelamin (jantan/betina) per spesiesnya</p>
                <div style="align: center;">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Statistik Data Ikan (TKG)</h6>
                <p class="text-muted mb-3">Menampilkan komposisi jenis ikan berdasarkan TKG per spesiesnya</p>
                <div style="align: center;">
                    <canvas id="myChart2"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="export-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Statistik Data Ikan</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <div class="modal-body">
                @php
                    $branch = \App\Models\Branch::where('dtkn', Request::input('branch_id'))->first();
                @endphp
                @if(Auth::user()->role == 'superadmin')
                    <div class="mb-3">
                        <label>Wilayah Kerja:</label>
                        <div class="form-control" readonly>{{ $branch->name ?? '' }}</div>
                    </div>
                @endif
                <div class="mb-3">
                    <label>Bulan:</label>
                    <div class="form-control" readonly>{{ date('F Y', strtotime(Request::input('month'))) ?? '' }}</div>
                </div>
                <div class="" style="margin-bottom: 30vh;">
                    <label>Spesies Ikan:</label>
                    <input type="text" name="species" id="justAnInputBox1" placeholder="Select" autocomplete="off">
                </div>
            </div>
            <div class="modal-footer">
                <form action="" method="get" id="export-form">
                    @csrf
                </form>
                <button type="button" class="btn btn-light font-weight-bolder" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary font-weight-bolder" id="btn-submit-export">Export Data</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
@endpush

@push('custom-scripts')
<script type="text/javascript">
$(function() {

    var start = moment(@json($start_date));
    var end = moment(@json($end_date));

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $('#reportrange input').val(start.format('YYYY-MM-D') + '/' + end.format('YYYY-MM-D'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);
    
});
</script>
<script>
// delete events
    $(document).on("click", ".btn-export", function()
    {
        var id = $(this).val();
        $("#export-modal").modal('show');
        $("#btn-submit-export").on('click', function(){
            event.preventDefault();
            var val = $("input:checkbox:checked").map(function(){
                if($(this).val() % 1 === 0){
                    return $(this).val();
                }
            }).get();
            $("#btn-submit-export").prop('disabled', true);
            var url = '{{ route("dashboard.statistic.export.5") }}?branch_id={{ Request::input("branch_id") }}&month={{ Request::input("month") }}&species_fish_id=' + val;
            $.get(url, function(data) {
                window.open(url , '_blank');
                $("#btn-submit-export").prop('disabled', false);
            });
        });
    });

    var SampleJSONData2 = @json($json);

    jQuery(document).ready(function($) {
            
            comboTree3 = $('#justAnInputBox1').comboTree({
                source : SampleJSONData2,
                isMultiple: true,
                selectableLastNode:true,
                withSelectAll:true,
                collapse: false,
            });

    });
</script>

<script>
const ctx = document.getElementById('myChart');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Jantan', 'Betina'],
        datasets: [{
            label: 'Jumlah Individu (ekor)',
            data: @json($statistic['gender']),
            backgroundColor: ['blue', 'pink'],
            borderWidth: 1
        }]
        },
        options: {
            scales: {
                y: {
                beginAtZero: true
                }
            }
        }
});

const ctx2 = document.getElementById('myChart2');

new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: ['TKG 1', 'TKG 2', 'TKG 3'],
        datasets: [{
            label: 'Jumlah Individu (ekor)',
            data: @json($statistic['tkg']),
            backgroundColor: ['yellow', 'orange', 'red'],
            borderWidth: 1
        }]
        },
        options: {
            scales: {
                y: {
                beginAtZero: true
                }
            }
        }
});
</script>

<script>
    $("#select-search-branch").select2({
        placeholder: "Pilih Wilayah Kerja",
    });

    $("#select-search-type-fish").select2({
        placeholder: "Pilih Jenis Ikan",
    });
</script>
<script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
