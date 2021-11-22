@extends('admin.layout.master',['title' => 'Edit Vehicle Model'])

@section('content')
 
<div class="layout-px-spacing">
                
                <div class="row layout-top-spacing">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-12  layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">                                
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Edit Vehicle Model</h4>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="widget-content widget-content-area">
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
                                    <form action="{{action('Admin\vehicle_modelController@update',$vehicle_model->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                         {{csrf_field()}}
                                         @method('PATCH')
                                        <div class="form-group row  mb-2">
                                            <label for="vehicle_type" class="col-sm-2 col-form-label col-form-label-sm">Vehicle Type</label>
                                            <div class="col-sm-6">
                                                <select class="form-control select2" name="vehicle_type_id" id="vehicle_type" required="">
                                                    <option value="">Select Vehicle Type</option>
                                                    @foreach ($vehicle_types as $vehicle_type)
                                                    <option value="{{$vehicle_type->id}}" <?=($vehicle_type->id == $vehicle_model->vehicle_type_id)?'selected':''?> >{{$vehicle_type->type_name}}</option>
                                                    @endforeach
                                                    
                                                </select>
                                            </div>
                                         </div>
                                         <div class="form-group row  mb-2">
                                            <label for="vehicle_subtype" class="col-sm-2 col-form-label col-form-label-sm">Vehicle Sub Type</label>
                                            <div class="col-sm-6">
                                                <select class="form-control select2" name="vehicle_subtype_id" id="vehicle_subtype" required="">
                                                    <option value="">Select Vehicle SubType</option>
                                                    @foreach ($vehicle_subtypes as $vehicle_subtype)
                                                    <option value="{{$vehicle_subtype->id}}" <?=($vehicle_subtype->id == $vehicle_model->vehicle_subtype_id)?'selected':''?> >{{$vehicle_subtype->subtype_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row  mb-2">
                                            <label for="vehicle_make" class="col-sm-2 col-form-label col-form-label-sm">Vehicle Make</label>
                                            <div class="col-sm-6">
                                                <select class="form-control select2" name="vehicle_make_id" id="vehicle_make" required="">
                                                    <option value="">Select Vehicle Make</option>
                                                    @foreach ($vehicle_makes as $vehicle_make)
                                                    <option value="{{$vehicle_make->id}}" <?=($vehicle_make->id == $vehicle_model->vehicle_make_id)?'selected':''?>>{{$vehicle_make->make_name}}</option>
                                                    @endforeach
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row  mb-4">
                                            <label for="model_name" class="col-sm-2 col-form-label col-form-label-sm">Model Name</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-sm" id="model_name" name="model_name" value="{{$vehicle_model->model_name}}" placeholder="Model Name">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-4">
                                            
                                            <label for="colFormLabel" class="col-sm-2 col-form-label">Model Image</label>
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
                                        <div class="form-group row  mb-4">
                                            <label for="is_activated" class="col-sm-2 col-form-label col-form-label-sm">Active</label>
                                            <div class="col-sm-6">
                                               <label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                                <input type="checkbox" id="is_activated" name="is_activated" <?=($vehicle_model->is_activated == 1)?'checked':''?> >
                                                <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                         
                                        <input type="submit" name="Update" class="mb-4 btn btn-primary">
                                    </form>

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
        <?php if($vehicle_model->image){?>
        $('.custom-file-container__image-preview').css('background-image','url("<?= url('uploads/vehicle_model/'.$vehicle_model->image) ?>")');
        <?php }?>
            
        function get_vehicle_subtype()
        {
           // alert("country change");

            var vehicle_type_id = $("#vehicle_type option:selected").val();

            if(vehicle_type_id)
            {
                var token = $('meta[name="csrf-token"]').attr('content');
                console.log(token);
                //get the action-url of the form
                $.ajax({
                    type: 'POST',
                    header:{
                      'X-CSRF-TOKEN': token
                    },
                    dataType: 'json',
                    url: BASE_URL+"/admin/common/get_selected_subtype_list",
                    data: {vehicle_type_id: vehicle_type_id,type:'json',_token: token},
                    success: function (response) {

                        if (response.success == 1)
                        {
                            var data = response.data;
                            $('#vehicle_subtype').empty();
                            var option = new Option('Select Vehicle SubType','', true, true);
                            $('#vehicle_subtype').append(option);
                            
                            // $('#region').append('<option value="">Select Region</option>');
                            $.each(data, function (key, val) {
                                var option = new Option(val.subtype_name, val.id,false,false);
                                $('#vehicle_subtype').append(option);
                                //$('#region').append('<option id="' + val.regionId + '" value="' + val.regionId + ' " title="' + val.name + '">' + val.name + '</option>');
                            });
                        }else
                        {
                             $('#vehicle_subtype').empty().append(new Option('Select Vehicle SubType','', true, true));
                        }
                    }
                });
            }else
            {  
                  $('#vehicle_subtype').empty().append(new Option('Select Vehicle SubType','', true, true));
        //        $('#region').empty().append('<option value="">Select Region</option>');
        //        $('#city').empty().append('<option value="">Select City</option>');
            }
        }
        
        $(document).on('change','#vehicle_type',function(){

                 get_vehicle_subtype();
        });    
</script>
@endsection
