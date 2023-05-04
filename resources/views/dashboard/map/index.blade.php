@extends('layouts.dashboard')

@push('plugin-styles')
<style>
    .map-style {
        width: 100%;
        height: 100%;
    }

    .page-content {
        padding: 0px !important;
    }

    .float{
        position:fixed;
        width:60px;
        height:60px;
        bottom:40px;
        right:10px;
        background-color:#0d6efd;
        color:#FFF;
        border-radius:8px;
        text-align:center;
        box-shadow: 2px 2px 3px #999;
        z-index: 1;
    }

    .btn-float{
        margin  : 0;
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    #accordionPanelsStayOpenExample {
        position: absolute;
        overflow: auto;
        margin-top: 10px;
        z-index: 1;
        width: 20vw;
        margin-left: 10px;
    }

    .accordion-body {
        padding: 1rem;
    }

    .form-group {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input {
        padding: 0;
        height: initial;
        width: initial;
        margin-bottom: 0;
        display: none;
        cursor: pointer;
    }

    .form-group label {
        position: relative;
        cursor: pointer;
    }

    .form-group label:before {
        content:'';
        -webkit-appearance: none;
        background-color: transparent;
        border: 2px solid #0079bf;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05);
        padding: 7px;
        display: inline-block;
        position: relative;
        vertical-align: middle;
        cursor: pointer;
        margin-right: 5px;
    }

    .form-group input:checked + label:after {
        content: '';
        display: block;
        position: absolute;
        top: 4px;
        left: 6px;
        width: 6px;
        height: 10px;
        border: solid #0079bf;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    @media (min-width:320px)  { 
        #accordionPanelsStayOpenExample {
            width: 50vw;
            height: 40vh;
        }
    }
    @media (min-width:768px)  { 
        #accordionPanelsStayOpenExample {
            width: 30vw;
            height: 50vh;
        }
    }
    
    @media (min-width:1024px) { 
        #accordionPanelsStayOpenExample {
            width: 20vw;
            height: 50vh;
        }
    }

    #footer {
        display: none !important;
    }

    span.select2-container {
        z-index:10050;
    }

    .modal-dialog-scrollable .modal-content {
        max-height: 100%;
        overflow-y: auto !important;
    }

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

    .gm-style-iw {
        position: absolute;
    	width: 300px !important;
    	height: auto;
    	top: 15px !important;
    	left: 0px !important;
    	background-color: #fff;
    	box-shadow: 0 1px 6px rgba(178, 178, 178, 0.6);
    	border: 1px solid rgba(72, 181, 233, 0.6);
    	border-radius: 2px 2px 10px 10px;
    }
    #iw-container {
    	margin-bottom: 10px;
    }
    #iw-container .iw-title {
    	font-family: 'Open Sans Condensed', sans-serif;
    	font-size: 22px;
    	font-weight: 300;
    	padding: 10px;
    	background-color: #48b5e9;
    	color: white;
    	margin: 0;
    	border-radius: 2px 2px 0 0;
    }
    #iw-container .iw-sub-title {
    	font-family: 'Open Sans Condensed', sans-serif;
    	font-size: 14px;
    	font-weight: 200;
    	padding: 10px;
    	background-color: #48b5e9;
    	color: white;
    	margin-top: -10px;
    	border-radius: 2px 2px 0 0;
    }
    #iw-container .iw-content {
    	font-size: 13px;
    	line-height: 18px;
    	font-weight: 400;
    	margin-right: 1px;
    	padding: 15px 0px 0px 0px;
    	max-height: 350px;
    	/*overflow-y: auto;*/
    	/*overflow-x: hidden;*/
    }

    .iw-subTitle {
    	font-size: 16px;
    	font-weight: 700;
    	padding: 5px 0;
    }
    .iw-bottom-gradient {
    	position: absolute;
    	width: 326px;
    	height: 25px;
    	bottom: 10px;
    	right: 18px;
    	background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
    	background: -webkit-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
    	background: -moz-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
    	background: -ms-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
    }

</style>
@endpush

@section('content')
<button href="#" class="float btn-filter" style="border: 0px;">
    <i class="link-icon btn-float" data-feather="filter"></i>
</button>

@if(Request::input('start_date') != null && Request::input('end_date'))
    <div class="accordion" id="accordionPanelsStayOpenExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                    aria-controls="panelsStayOpen-collapseOne">
                    <b>Filter Data</b>
                </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
                aria-labelledby="panelsStayOpen-headingOne" style="overflow-y: auto !important;">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-6 mb-1">
                            <div class="form-group">
                                <p><b>Dari:</b></p>
                                <p>{{ date('d-m-Y', strtotime(Request::input('start_date'))) }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <p><b>Sampai:</b></p>
                                <p>{{ date('d-m-Y', strtotime(Request::input('end_date'))) }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <p><b>Spesies:</b></p>
                                @foreach($speciesArray as $species)
                                    @php
                                        $checkSpecies = App\Models\SpeciesFish::with('type_fish.branch')->where('id', $species)->first();
                                    @endphp
                                    <p style="color: {{ $checkSpecies->type_fish->icon }};">- <i>{{ $checkSpecies->species }}</i> ({{ $checkSpecies->type_fish->branch->name }})</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div id="map" class="map-style">
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="filter-modal">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Data</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"
                    data-bs-original-title="" title=""></button>
            </div>
            <form action="" method="get" id="filter-form">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <label>Dari:</label>
                            <input type="date" name="start_date" class="form-control" value="{{ Request::input('start_date') ?? date('Y-m-d') }}">
                        </div>
                        <div class="col-6 col-md-6">
                            <label>Sampai:</label>
                            <input type="date" name="end_date" class="form-control" value="{{ Request::input('end_date') ?? date('Y-m-d') }}">
                        </div>
                        <div class="col-12 mt-3" style="margin-bottom: 30vh;">
                            <input type="text" name="species" id="justAnInputBox1" placeholder="Select" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @csrf
                    <button type="button" class="btn btn-light font-weight-bolder"
                        data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary font-weight-bolder" id="btn-submit-delete">Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')

@endpush

@push('custom-scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAovhxjkjJX16jhgBhuFGJTlIdfJZ8uYCY&libraries=geometry"></script>
<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
<script>
    $(document).on("click", ".btn-filter", function () {
        $("#filter-modal").modal('show');
    });

    var map = new google.maps.Map(
    document.getElementById("map"), {
        center: new google.maps.LatLng(0.642387, 114.332703),
        zoom: 6,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false,
        mapTypeControl: false,
        fullscreenControl: false,
        zoomControlOptions: {
        position: google.maps.ControlPosition.TOP_RIGHT
            /*,
                    index: -1 */
        },
    });
    
    var dataCollection = @json($dataCollection);
    var markers = [];
    dataCollection.forEach(function(item) {
        console.log(item);
        var circle = new google.maps.Circle({
            strokeColor: item[0].location.color,
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: item[0].location.color,
            fillOpacity: 0.35,
            map: map,
            center: new google.maps.LatLng(item[0].location.lat, item[0].location.lng),
            radius: item[0].location.radius // in meters
        });
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(item[0].location.lat, item[0].location.lng),
            map: map
        });
        circle.bindTo('center', marker, 'position');

        var contentSpecies = '';
        var total = 0;
        item[0].location.ship.species.forEach(function(item2) {
            total += parseInt(item2.amount);
            contentSpecies += 
                `
                    <div class="iw-subTitle">Species (` + item2.type_name + `)</div>
                    <p><i>`+ item2.name +` </i>(` + item2.amount + ` ekor)</p>
                    <div class="iw-subTitle">Total Berat</div>
                    <p>`+ item2.weight +`Kg</p>
                    <hr>
                `
            ;
        });

        const contentString =
        `   
            <div id="iw-container">
            <div class="iw-title"><b>Total Individu = `+ total +` (ekor)</b></div>
            <div class="iw-sub-title">Radius: ` + item[0].location.radius + `m</div>
            <div class="iw-content">
                <div class="iw-subTitle" style="text-align: center;">` + item[0].location.ship.name + ` (` + item[0].location.ship.owner + `) | ` + item[0].location.ship.fishing_gear + `</div>
                `+ contentSpecies +`
            </div>
            </div>
        `  
        ;

        const infowindow = new google.maps.InfoWindow({
            content: contentString,
            ariaLabel: item[0].type_id,
        });

        marker.addListener("click", () => {
                infowindow.open({
                anchor: marker,
                map,
            });
        });

        markers.push(marker);
    });

    // use default algorithm and renderer
    new markerClusterer.MarkerClusterer({ markers, map });

</script>

<script type="text/javascript">

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
@endpush
