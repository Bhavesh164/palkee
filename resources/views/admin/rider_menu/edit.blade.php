@extends('admin.layout.master',['title' => 'Edit Rider Menu'])
@section('page_header_css')

@endsection
@section('content')
 
<div class="layout-px-spacing">
                
                <div class="row layout-top-spacing">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-12  layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">                                
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Edit Rider Menu</h4>
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
                                    <form action="{{action('Admin\rider_menuController@update',$rider_menu->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                         {{csrf_field()}}
                                         @method('PATCH')
                                         <div class="form-group row  mb-4">
                                            <label for="menu_name" class="col-sm-2 col-form-label col-form-label-sm">Menu Name</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-sm" id="menu_name" name="menu_name" placeholder="Menu Name" required="" value="{{ $rider_menu->menu_name }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-4">
                                            
                                            <label for="colFormLabel" class="col-sm-2 col-form-label">Menu Image</label>
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
                                            <label for="priority" class="col-sm-2 col-form-label col-form-label-sm">Priority</label>
                                            <div class="col-sm-6">
                                                <input type="number" class="form-control form-control-sm" id="priority" name="priority" placeholder="Priority" required="" value="{{ $rider_menu->priority }}" min="1">
                                            </div>
                                        </div>
                                        <div class="form-group row  mb-1">
                                            <label for="ride_type" class="col-sm-2 col-form-label col-form-label-sm">Ride Type</label>
                                            <div class="col-sm-6">
                                                <select class="form-control select2" name="ride_type_id" id="ride_type" required="">
                                                    @foreach ($ride_types as $ride_type)
                                                    <option value="{{$ride_type->id}}" <?= ($ride_type->id == $rider_menu->ride_type_id)?'selected':''?> >{{$ride_type->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row  mb-1">
                                            <label for="vehicle_type" class="col-sm-2 col-form-label col-form-label-sm">Vehicle Types</label>
                                            <div class="col-sm-6">
                                                <select class="form-control select2" multiple="multiple" name="vehicle_type_ids[]" id="vehicle_type" required="">
                                                    @foreach ($vehicle_types as $vehicle_type)
                                                    <option value="{{$vehicle_type->id}}" <?= ($rider_menu->vehicle_type_ids)?((in_array($vehicle_type->id,explode(',',$rider_menu->vehicle_type_ids)))?'selected':''):''?> >{{$vehicle_type->type_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row  mb-4">
                                            <label for="is_activated" class="col-sm-2 col-form-label col-form-label-sm">Active</label>
                                            <div class="col-sm-6">
                                               <label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                                <input type="checkbox" id="is_activated" name="is_activated" <?=($rider_menu->is_activated == 1)?'checked':''?> >
                                                <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                         
                                         <input type="submit" name="submit" value="Update" class="mb-4 btn btn-primary">
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
         <?php if($rider_menu->image){?>
        $('.custom-file-container__image-preview').css('background-image','url("<?= url('uploads/admin/rider_menu/'.$rider_menu->image) ?>")');
        <?php }?>
        
           
</script>
@endsection
