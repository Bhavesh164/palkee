@extends('admin.layout.master',['title' => 'Edit Rider'])
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
                                            <h4>Edit Rider</h4>
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
                                    <form action="{{action('Admin\riderController@update',$rider->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                         {{csrf_field()}}
                                         @method('PATCH')
                                         <div class="form-group row  mb-4">
                                            <label for="full_name" class="col-sm-2 col-form-label col-form-label-sm">Full Name</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-sm" id="rider_name" name="full_name" placeholder="Full Name" required="" value="{{$rider->full_name}}">
                                            </div>
                                        </div>
                                        <div class="form-group row  mb-4">
                                            <label for="email" class="col-sm-2 col-form-label col-form-label-sm">Email</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-sm" id="email" name="email" placeholder="Email" required="" value="{{$rider->email}}" >
                                            </div>
                                        </div>
                                        <div class="form-group row  mb-4">
                                            <label for="phone" class="col-sm-2 col-form-label col-form-label-sm">Phone</label>
                                            <div class="col-sm-6">
                                                 <input class="form-control form-control-sm" type="text" name="phone" id="phone"  placeholder="Phone" required="" pattern="[0-9]*" value="{{$rider->phone}}" onkeypress="javascript:return numaric(event,this)">
                                                 <input type="hidden" id="dial_code" name="dial_code" value="{{$rider->dial_code}}">
                                            </div>
                                        </div>
                                        <div class="form-group row  mb-4">
                                            <label for="rider_code" class="col-sm-2 col-form-label col-form-label-sm">Rider Code</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-sm" id="rider_code" name="rider_code" placeholder="Rider Code" value="{{$rider->rider_code}}">
                                            </div>
                                        </div> 
                                        <div class="form-group row mb-4">
                                            
                                            <label for="colFormLabel" class="col-sm-2 col-form-label">Rider Image</label>
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
                                            <label for="address" class="col-sm-2 col-form-label col-form-label-sm">Address</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control form-control-sm" id="address" name="address" placeholder="Address">{{$rider->address}}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row  mb-4">
                                            <label for="password" class="col-sm-2 col-form-label col-form-label-sm">Password</label>
                                            <div class="col-sm-6">
                                                <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="Password" autocomplete="new-password">
                                            </div>
                                        </div>
                                        <div class="form-group row  mb-4">
                                            <label for="is_activated" class="col-sm-2 col-form-label col-form-label-sm">Active</label>
                                            <div class="col-sm-6">
                                               <label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                                <input type="checkbox" id="is_activated" name="is_activated" <?=($rider->is_activated == 1)?'checked':''?> >
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
        <?php if($rider->image){?>
        $('.custom-file-container__image-preview').css('background-image','url("<?= url('uploads/rider/'.$rider->image) ?>")');
        <?php }?>
           
</script>
<script>
    
    window.addEventListener('load', (event) => {
        var input = document.getElementById("phone");
        var dialing_code = document.getElementById("dial_code");
        var iti=window.intlTelInput(input, {
            // hiddenInput: "full_phone",
            utilsScript: "{{asset('resources/assets/plugins/iti/utils.js')}}"
        });
        // input.style.display = "block";

        iti.setNumber('<?php echo $rider->dial_code; ?>');
        iti.setNumber('<?php echo $rider->phone; ?>');
       
        //$('#country').intlTelInput("setCountry", iso2);
        // $("#inputContact").intlTelInput("setCountry", "+91");

        input.addEventListener("countrychange", function() {
        var number = iti.selectedCountryData.dialCode;
        dialing_code.value= '+'+number;       

        });
    });

</script>
<script src="{{asset('resources/assets/plugins/iti/intlTelInput.js')}}"></script>
@endsection
