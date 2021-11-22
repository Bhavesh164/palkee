@extends('admin.layout.master',['title' => 'Update Documents'])
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
                                            <h4>Update Documents</h4>
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
                                            <a class="nav-link " id="icon-car-info" href="{{url('admin/driver/edit_vehicle_info/'.$driver->id)}}" role="tab" aria-controls="icon-contact" aria-selected="false">
                                                <i class="fas fa-car"></i> Vehicle Info</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" id="icon-documents" href="{{url('admin/driver/edit_documents/'.$driver->id)}}" role="tab"  aria-controls="icon-contact" aria-selected="true">
                                               <i class="fas fa-file"></i> Documents</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="iconTabContent-1">
                                        <div class="tab-pane fade" id="driver_info" role="tabpanel" aria-labelledby="driver-info-tab">
                                      
                                        </div>
                                        <div class="tab-pane fade " id="car-info" role="tabpanel" aria-labelledby="icon-car-info">
                                         
                                            
                                        </div>
                                        <div class="tab-pane fade show active" id="documents" role="tabpanel" aria-labelledby="icon-documents">
                                           <form action="{{action('Admin\driverController@update_documents',$driver->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                         {{csrf_field()}}
                                         <input type="hidden" name="vehicle_id" value="<?=isset($vehicle_info->id)?$vehicle_info->id:''?>">
                                         
                                        <div class="form-group row  mb-4">
                                            <label for="dl_number" class="col-sm-3 col-form-label col-form-label-sm">Driver Licence Number</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-sm" id="dl_number" name="dl_number" placeholder="Driver Licence Number" required="" value="{{ $driver->dl_number}}">
                                            </div>
                                        </div> 
                                        <div class="form-group row  mb-4">
                                            <label for="dl_expiry_date" class="col-sm-3 col-form-label col-form-label-sm">Driving Licence Expiry Date</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-sm" id="dl_expiry_date" name="dl_expiry_date" placeholder="Driving Licence Expiry Date" required="" value="{{$driver->dl_expiry_date}}">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row mb-4">
                                            
                                            <label for="colFormLabel" class="col-sm-3 col-form-label">Driving Licence Image</label>
                                            <div class="col-sm-6">
                                                <div class="custom-file-container" data-upload-id="dl_image">
                                                    
                                                    <label class="custom-file-container__custom-file" >
                                                        <input type="file" name="dl_image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                                                        <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                                                        <span class="custom-file-container__custom-file__custom-file-control"></span>
                                                    </label>
                                                   
                                                    <div class="custom-file-container__image-preview dl_image">
                                                      <div class="image-clear">
                                                        <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">&times;</a> 
                                                      </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                         
                                        <div class="form-group row mb-4">
                                            
                                            <label for="colFormLabel" class="col-sm-3 col-form-label">Vehicle Registration</label>
                                            <div class="col-sm-6">
                                                <div class="custom-file-container" data-upload-id="reg_image">
                                                    
                                                    <label class="custom-file-container__custom-file" >
                                                        <input type="file" name="reg_image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                                                        <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                                                        <span class="custom-file-container__custom-file__custom-file-control"></span>
                                                    </label>
                                                   
                                                    <div class="custom-file-container__image-preview reg_image">
                                                      <div class="image-clear">
                                                        <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">&times;</a> 
                                                      </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                         
                                        <div class="form-group row mb-4">
                                            
                                            <label for="colFormLabel" class="col-sm-3 col-form-label">Vehicle Insurance</label>
                                            <div class="col-sm-6">
                                                <div class="custom-file-container" data-upload-id="ins_image">
                                                    
                                                    <label class="custom-file-container__custom-file" >
                                                        <input type="file" name="ins_image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                                                        <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                                                        <span class="custom-file-container__custom-file__custom-file-control"></span>
                                                    </label>
                                                   
                                                    <div class="custom-file-container__image-preview ins_image">
                                                      <div class="image-clear">
                                                        <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">&times;</a> 
                                                      </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="form-group row  mb-4">
                                            <label for="is_activated" class="col-sm-3 col-form-label col-form-label-sm">Approved</label>
                                            <div class="col-sm-6">
                                               <label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                                <input type="checkbox" id="is_approved" name="is_approved" <?=($driver->is_approved == 1)?'checked':''?> >
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

                </div>

</div>
     
@endsection
@section('page_script')

<script>
        //First upload
        var dl_image = new FileUploadWithPreview('dl_image');
        var reg_image = new FileUploadWithPreview('reg_image');
        var ins_image = new FileUploadWithPreview('ins_image');
//        var ss = $(".select2").select2({
//        });
//        $(".tagging").select2({
//            tags: true
//        });
        var f1 = flatpickr(document.getElementById('dl_expiry_date'));
        <?php if(isset($driver->dl_image) && $driver->dl_image!=''){?>
        $('.custom-file-container__image-preview.dl_image').css('background-image','url("<?= url('uploads/driver/docs/'.$driver->id.'/'.$driver->dl_image) ?>")');
        <?php }?>
        <?php if(isset($vehicle_info->reg_image) && $vehicle_info->reg_image!=''){?>
        $('.custom-file-container__image-preview.reg_image').css('background-image','url("<?= url('uploads/driver/docs/'.$driver->id.'/'.$vehicle_info->reg_image) ?>")');
        <?php }?>
        <?php if(isset($vehicle_info->ins_image) && $vehicle_info->ins_image!=''){?>
        $('.custom-file-container__image-preview.ins_image').css('background-image','url("<?= url('uploads/driver/docs/'.$driver->id.'/'.$vehicle_info->ins_image) ?>")');
        <?php }?>
        
        
</script>


@endsection