@extends('admin.layout.master',['title' => 'Add Vehicle Type'])

@section('content')
 
<div class="layout-px-spacing">
                
                <div class="row layout-top-spacing">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-12  layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">                                
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Add Vehicle Type</h4>
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
                                    <form action="{!! url('admin/vehicletype') !!}" method="post" enctype="multipart/form-data" autocomplete="off">
                                         @csrf
                                        <div class="form-group row  mb-4">
                                            <label for="type_name" class="col-sm-2 col-form-label col-form-label-sm">Type Name</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-sm" id="type_name" name="type_name" placeholder="Type Name">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-4">
                                            
                                            <label for="colFormLabel" class="col-sm-2 col-form-label">Type Image</label>
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
                                            <label for="type_name" class="col-sm-2 col-form-label col-form-label-sm">Active</label>
                                            <div class="col-sm-6">
                                               <label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                                <input type="checkbox" name="is_activated" checked>
                                                <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                         
                                        <input type="submit" name="submit" class="mb-4 btn btn-primary">
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
        var firstUpload = new FileUploadWithPreview('myFirstImage')
</script>
@endsection