@extends('admin.layout.master',['title' => 'View Drivers Map'])

@section('page_header_css')

<style>
#legend {
        font-family: Arial, sans-serif;
        background: #fff;
        padding: 10px 10px 10px 10px;
        margin: 25px 20px;
/*        margin: 10px;*/
        border: 1px solid #777;
        position: absolute;
        z-index: 5;
        right: 0px;
        top: 44px;
      }
      #legend .licon {    border-bottom: 1px solid #eee;
       padding: 5px 0 5px 0;color: #777;}   
      #legend h3 {
        margin-top: 0;
        font-size: 15px;
      }
      #legend img {
        vertical-align: middle;
        width: 24px;
            margin: 0 10px 0 0;
      }
 </style>
 <style>
    .tp-btn{
        padding: 6px 10px;
        font-size: 12px;
    }
    .marr-o{
        margin: 0;
    }
    #map {
        height: 600px;
        position: absolute;
        overflow: hidden;
        top: 10px;
        left: 10px;
        right: 10px;

    }
    .div_map {
        height: 600px;
    }
    .formwidth{
        display: inline-block;
        width: 100%;
    }
    .image-upload{
        float: left;
        padding-left: 15px;
        padding-top: 5px;
    } 
    input#search-input {
/*    background-color: #f5f5f5 !important;*/
    border: 1px solid #ccc !important;
    padding: 11px 30px !important;
    border-radius: 50px !important;
    width: 50% !important;
    font-size: 15px !important;
    color: #8492af !important;
    margin-top: 10px !important;
   }
   input#search-input:focus{
       outline: none; 
   }
   .gm-ui-hover-effect:focus
   {
       outline: none;
   }
</style>

@endsection

@section('content')

<!--  BEGIN CONTENT AREA  -->

<div class="layout-px-spacing">

    <div class="row layout-top-spacing" id="cancel-row">

        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">                                
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Map View ( Drivers )</h4>
                        </div>
                    </div>
                </div>     
                <div class="widget-content widget-content-area">
                                 
                          <div class="x_content div_map">
                            <br/>
                            <input type="text" id="search-input" placeholder="Search Location">
                            <div id="map" class="gmaps"></div>
                            <div id="legend">
                                <div class="licon"><img src="{{asset('resources/assets/images/marker-blue.png')}}" />On Job</div>
                                <div class="licon"><img src="{{asset('resources/assets/images/marker-green.png')}}" />Available</div>
<!--                                <div class="licon"><img src="{{asset('resources/assets/images/marker-red.png')}}" />Unavailable</div>-->
                            </div>
                           </div>

                    
                  
                </div>
            </div>
        </div>

    </div>

</div>

<!--  END CONTENT AREA  -->


@endsection
@section('page_script')

@include('admin.driver.load_map')

<script>
$(document).ready(function() {
        
        resizeMap();

$(window).resize(function() {
        resizeMap();
     });
    
     function resizeMap() {
                                        
                var h = window.innerHeight  ;
                var h1 = window.innerHeight - 20 ;
                                                    // old code // var h = window.innerHeight - 250 ;
                h = h+'px' ;
                h1 = h1+'px' ;
                $('.div_map').css('height',h1);
                $('#map').css('height',h);
                
                                             //document.getElementById('map').setAttribute("style","height:"+h);
                                      //$height = $(window).height();
                                     // $('.sidebar-left').height($height);
                                       
      }
});
</script>

@endsection