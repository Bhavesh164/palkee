@extends('admin.layout.master',['title' => 'Edit Vehicle Make'])

@section('content')
 
<div class="layout-px-spacing">
                
                <div class="row layout-top-spacing">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-12  layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">                                
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Edit Vehicle Make</h4>
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
                                    <form action="{{action('Admin\vehicle_makeController@update',$vehicle_make->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                         {{csrf_field()}}
                                         @method('PATCH')
                                        <div class="form-group row  mb-4">
                                            <label for="make_name" class="col-sm-2 col-form-label col-form-label-sm">Make Name</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-sm" id="make_name" name="make_name" value="{{$vehicle_make->make_name}}" placeholder="Make Name">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-4">
                                            
                                            <label for="colFormLabel" class="col-sm-2 col-form-label">Make Image</label>
                                            <div class="col-sm-6">
                                                <div class="custom-file-container" data-upload-id="myFirstImage">
                                                    
                                                    <label class="custom-file-container__custom-file" >
                                                        <input type="file" name="image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                                                        <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                                                        <span class="custom-file-container__custom-file__custom-file-control"></span>
                                                    </label>
                                                    <div class="custom-file-container__image-preview" style="<?php if($vehicle_make->image) {?> background-image: url('{{url('uploads/vehicle_make/'.$vehicle_make->image)}}') !important; <?php }?>">
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
                                                <input type="checkbox" id="is_activated" name="is_activated" <?=($vehicle_make->is_activated == 1)?'checked':''?> >
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
        <?php if($vehicle_make->image){?>
        $('.custom-file-container__image-preview').css('background-image','url("<?= url('uploads/vehicle_make/'.$vehicle_make->image) ?>")');
        <?php }?>
</script>
@endsection
