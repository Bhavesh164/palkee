@extends('admin.layout.master',['title' => 'Update Vehicle Info'])
@section('page_header_css')

<link rel="stylesheet" href="{{asset('resources/assets/plugins/iti/intlTelInput.css')}}">

@endsection

@section('content')

<div class="layout-px-spacing">
                
                <div class="row layout-top-spacing">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-12  layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">                                
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Update Vehicle Info</h4>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="widget-content widget-content-area icon-tab">
                                    @if (count($errors) > 0)
                                        <div class = "alert alert-danger">
                                           <ul>
                                              @foreach ($errors->all() as $error)
                                                 <li>{{ $error }}</li>
                                              @endforeach
                                           </ul>
                                        </div>
                                    @endif
                                    @if(session()->has('success'))
                                        <div class="alert alert-success">
                                            {{ session()->get('success') }}
                                        </div>
                                    @endif
                                    
                                    <ul class="nav nav-tabs  mb-3 mt-3" id="iconTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link" id="driver-info-tab"  href="{{url('admin/driver/'.$driver->id.'/edit')}}" role="tab" aria-controls="icon-home" aria-selected="false">
                                               <i class="fas fa-user"></i> Driver Info 
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" id="icon-car-info" data-toggle="tab" href="{{url('admin/driver/edit_vehicle_info/'.$driver->id)}}" role="tab" aria-controls="icon-contact" aria-selected="true">
                                                <i class="fas fa-car"></i> Vehicle Info</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="icon-documents" href="{{url('admin/driver/edit_documents/'.$driver->id)}}" role="tab"  tabindex="-1" aria-controls="icon-contact" aria-selected="false">
                                               <i class="fas fa-file"></i> Documents</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="iconTabContent-1">
                                        <div class="tab-pane fade" id="driver_info" role="tabpanel" aria-labelledby="driver-info-tab">
                                      
                                        </div>
                                        <div class="tab-pane fade show active" id="car-info" role="tabpanel" aria-labelledby="icon-car-info">
                                         <form action="{{action('Admin\driverController@update_vehicle_info',$driver->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                         {{csrf_field()}}
                                         <input type="hidden" name="vehicle_id" value="<?=isset($vehicle_info->id)?$vehicle_info->id:''?>">
                                        <div class="form-group row  mb-1">
                                            <label for="vehicle_type" class="col-sm-2 col-form-label col-form-label-sm">Vehicle Type</label>
                                            <div class="col-sm-6">
                                                <select class="form-control select2" name="vehicle_type_id" id="vehicle_type" required="">
                                                    <option value="">Select Vehicle Type</option>
                                                    @foreach ($vehicle_types as $vehicle_type)
                                                    <option value="{{$vehicle_type->id}}" <?=(isset($vehicle_info->vehicle_type_id))?(($vehicle_type->id == $vehicle_info->vehicle_type_id)?'selected':''):''?>>{{$vehicle_type->type_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                         
                                        <div class="form-group row  mb-1">
                                            <label for="vehicle_subtype" class="col-sm-2 col-form-label col-form-label-sm">Vehicle SubType</label>
                                            <div class="col-sm-6">
                                                <select class="form-control select2" name="vehicle_subtype_id" id="vehicle_subtype" required="">
                                                    <option value="">Select Vehicle Subtype</option>
                                                    @foreach ($selected_vehicle_subtypes as $vehicle_subtype)
                                                    <option value="{{$vehicle_subtype->id}}" <?=(isset($vehicle_info->vehicle_subtype_id))?(($vehicle_subtype->id == $vehicle_info->vehicle_subtype_id)?'selected':''):''?>>{{$vehicle_subtype->subtype_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                         
                                        <div class="form-group row  mb-1">
                                            <label for="vehicle_make" class="col-sm-2 col-form-label col-form-label-sm">Vehicle Make</label>
                                            <div class="col-sm-6">
                                                <select class="form-control select2" name="vehicle_make_id" id="vehicle_make" required="">
                                                    <option value="">Select Vehicle Make</option>
                                                    @foreach ($selected_vehicle_makes as $vehicle_make)
                                                    <option value="{{$vehicle_make->id}}" <?=(isset($vehicle_info->vehicle_make_id))?(($vehicle_make->id == $vehicle_info->vehicle_make_id)?'selected':''):''?>>{{$vehicle_make->make_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                         
                                        <div class="form-group row  mb-1">
                                            <label for="vehicle_model" class="col-sm-2 col-form-label col-form-label-sm">Vehicle Model</label>
                                            <div class="col-sm-6">
                                                <select class="form-control select2" name="vehicle_model_id" id="vehicle_model" required="">
                                                    <option value="">Select Vehicle Model</option>
                                                    @foreach ($selected_vehicle_models as $vehicle_model)
                                                    <option value="{{$vehicle_model->id}}" <?=(isset($vehicle_info->vehicle_model_id))?(($vehicle_model->id == $vehicle_info->vehicle_model_id)?'selected':''):''?>>{{$vehicle_model->model_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row  mb-4">
                                            <label for="mfg_year" class="col-sm-2 col-form-label col-form-label-sm">Manufacture Year</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-sm" id="mfg_year" name="mfg_year" placeholder="Vehicle Manufacturing Year" required="" value="{{ isset($vehicle_info->mfg_year)?$vehicle_info->mfg_year:''}}">
                                            </div>
                                        </div> 
                                        <div class="form-group row  mb-1">
                                            <label for="color" class="col-sm-2 col-form-label col-form-label-sm">Vehicle Color</label>
                                            <div class="col-sm-6">
                                                <select class="form-control select2" name="vehicle_color" id="color" required="">
                                                    <option value="">Select Vehicle color</option>
                                                    @foreach ($vehicle_colors as $vehicle_color)
                                                    <option value="{{$vehicle_color->color_name}}" <?=(isset($vehicle_info->vehicle_color))?(($vehicle_color->color_name == $vehicle_info->vehicle_color)?'selected':''):''?>>{{$vehicle_color->color_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row  mb-4">
                                            <label for="vehicle_number" class="col-sm-2 col-form-label col-form-label-sm">Vehicle Number</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-sm" id="vehicle_number" name="vehicle_number" placeholder="Vehicle Number" value="{{ isset($vehicle_info->vehicle_number)?$vehicle_info->vehicle_number:''}}">
                                            </div>
                                        </div>
                                        

                                        <div class="form-group row mb-4">
                                            
                                            <label for="colFormLabel" class="col-sm-2 col-form-label">Vehicle Image</label>
                                            <div class="col-sm-6">
                                                <div class="custom-file-container" data-upload-id="myFirstImage">
                                                    
                                                    <label class="custom-file-container__custom-file" >
                                                        <input type="file" name="image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                                                        <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                                                        <span class="custom-file-container__custom-file__custom-file-control"></span>
                                                    </label>
                                                   
                                                    <div class="custom-file-container__image-preview">
                                                      <div class="image-clear">
                                                        <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">&times;</a> 
                                                      </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                         
                                         <input type="submit" name="submit" value="Update" class="mb-4 btn btn-primary">
                                        </form>    
                                            
                                        </div>
                                        <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="icon-documents">
                                            
                                        </div>
                                       
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                </div>

</div>
     
@endsection
@section('page_script')

<script>
        //First upload
        var firstUpload = new FileUploadWithPreview('myFirstImage');
        var ss = $(".select2").select2({
        });
        $(".tagging").select2({
            tags: true
        });
        <?php if(isset($vehicle_info->vehicle_image) && $vehicle_info->vehicle_image!=''){?>
        $('.custom-file-container__image-preview').css('background-image','url("<?= url('uploads/driver/docs/'.$vehicle_info->driver_id.'/'.$vehicle_info->vehicle_image) ?>")');
        <?php }?>
        
        
</script>
<script>
function get_vehicle_subtype()
{
   // alert("country change");
   
    var vehicle_type_id = $("#vehicle_type option:selected").val();
    
    
    if(vehicle_type_id)
    {
        var token = $('meta[name="csrf-token"]').attr('content');
        
        $.post(BASE_URL+"/admin/common/get_selected_vehicle_subtypes", {vehicle_type_id: vehicle_type_id,type:'json',_token: token}, function (response) {
            if (response.success == 1) {
                var data = response.data;
                
                $('#vehicle_subtype').empty();
                var option = new Option('Select Vehicle Subtype','', true, true);
                $('#vehicle_subtype').append(option);
                
                $('#vehicle_make').empty();
                var option = new Option('Select Vehicle Make','', true, true);
                $('#vehicle_make').append(option);
                // $('#region').append('<option value="">Select Region</option>');
                $('#vehicle_model').empty();
                var option = new Option('Select Vehicle Model','', true, true);
                $('#vehicle_model').append(option);
                //$('#city').append('<option value="">Select City</option>');
                $.each(data, function (key, val) {
                    var option = new Option(val.subtype_name, val.id,false,false);
                    $('#vehicle_subtype').append(option);
                    //$('#region').append('<option id="' + val.regionId + '" value="' + val.regionId + ' " title="' + val.name + '">' + val.name + '</option>');
                });
            } else {
                $('#vehicle_subtype').empty().append(new Option('Select Vehicle Subtype','', true, true));
                $('#vehicle_make').empty().append(new Option('Select Vehicle Make','', true, true));
                $('#vehicle_model').empty().append(new Option('Select Vehicle Model','', true, true));
                
//                $('#region').empty().append('<option value="">Select Region</option>');
//                $('#city').empty().append('<option value="">Select City</option>');
            }
        }, "json");
    }else
    {  
        $('#vehicle_subtype').empty().append(new Option('Select Vehicle Subtype','', true, true));
        $('#vehicle_make').empty().append(new Option('Select Vehicle Make','', true, true));
        $('#vehicle_model').empty().append(new Option('Select Vehicle Model','', true, true));
//        $('#region').empty().append('<option value="">Select Region</option>');
//        $('#city').empty().append('<option value="">Select City</option>');
    }
}
function get_vehicle_makes()
{
   // alert("country change");
    var vehicle_type_id = $("#vehicle_type option:selected").val();

    var vehicle_subtype_id = $("#vehicle_subtype option:selected").val();
    
    
    if(vehicle_type_id && vehicle_subtype_id)
    {
        var token = $('meta[name="csrf-token"]').attr('content');
        
        $.post(BASE_URL+"/admin/common/get_selected_vehicle_makes", {vehicle_type_id: vehicle_type_id,vehicle_subtype_id: vehicle_subtype_id,type:'json',_token:token}, function (response) {
            if (response.success == 1) {
                var data = response.data;
                $('#vehicle_make').empty();
                var option = new Option('Select Vehicle Make','', true, true);
                $('#vehicle_make').append(option);
                // $('#region').append('<option value="">Select Region</option>');
                $('#vehicle_model').empty();
                var option = new Option('Select Vehicle Model','', true, true);
                $('#vehicle_model').append(option);
                //$('#city').append('<option value="">Select City</option>');
                $.each(data, function (key, val) {
                    var option = new Option(val.make_name, val.id,false,false);
                    $('#vehicle_make').append(option);
                    //$('#region').append('<option id="' + val.regionId + '" value="' + val.regionId + ' " title="' + val.name + '">' + val.name + '</option>');
                });
            } else {
                $('#vehicle_make').empty().append(new Option('Select Vehicle Make','', true, true));
                $('#vehicle_model').empty().append(new Option('Select Vehicle Model','', true, true));
                
//                $('#region').empty().append('<option value="">Select Region</option>');
//                $('#city').empty().append('<option value="">Select City</option>');
            }
        }, "json");
    }else
    {  
        $('#vehicle_make').empty().append(new Option('Select Vehicle Make','', true, true));
        $('#vehicle_model').empty().append(new Option('Select Vehicle Model','', true, true));
//        $('#region').empty().append('<option value="">Select Region</option>');
//        $('#city').empty().append('<option value="">Select City</option>');
    }
}
function get_vehicle_models()
{
   // alert("country change");
   
    var vehicle_type_id = $("#vehicle_type option:selected").val();
    var vehicle_subtype_id = $("#vehicle_subtype option:selected").val();
    var vehicle_make_id = $("#vehicle_make option:selected").val();
    
    if(vehicle_type_id && vehicle_subtype_id && vehicle_make_id)
    {
        var token = $('meta[name="csrf-token"]').attr('content');

        $.post(BASE_URL+"/admin/common/get_selected_vehicle_models", {vehicle_type_id: vehicle_type_id,vehicle_subtype_id:vehicle_subtype_id,vehicle_make_id:vehicle_make_id,type:'json',_token:token}, function (response) {
            if (response.success == 1) {
                var data = response.data;
                
                $('#vehicle_model').empty();
                var option = new Option('Select Vehicle Model','', true, true);
                $('#vehicle_model').append(option);
                //$('#city').append('<option value="">Select City</option>');
                $.each(data, function (key, val) {
                    var option = new Option(val.model_name, val.id,false,false);
                    $('#vehicle_model').append(option);
                    //$('#region').append('<option id="' + val.regionId + '" value="' + val.regionId + ' " title="' + val.name + '">' + val.name + '</option>');
                });
            } else {
                
                $('#vehicle_model').empty().append(new Option('Select Vehicle Model','', true, true));
                
//                $('#region').empty().append('<option value="">Select Region</option>');
//                $('#city').empty().append('<option value="">Select City</option>');
            }
        }, "json");
    }else
    {  
        $('#vehicle_model').empty().append(new Option('Select Vehicle Model','', true, true));
//        $('#region').empty().append('<option value="">Select Region</option>');
//        $('#city').empty().append('<option value="">Select City</option>');
    }
}
$(document).on('change','#vehicle_type',function(){


         get_vehicle_subtype();
});
$(document).on('change','#vehicle_subtype',function(){


         get_vehicle_makes();
});
$(document).on('change','#vehicle_make',function(){

         get_vehicle_models();
});
</script>

@endsection