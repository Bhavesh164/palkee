@extends('admin.layout.master',['title' => 'Edit Profile'])
@section('page_header_css')

    <link href="{{asset('resources/admin_template/assets/css/users/account-setting.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{asset('resources/admin_template/plugins/dropify/dropify.min.css')}}" />

@endsection

@section('content')

<!--  BEGIN CONTENT AREA  -->
            <div class="layout-px-spacing">                
                    
                <div class="account-settings-container layout-top-spacing">

                    <div class="account-content">
                        <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
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
                                    <form id="general-info" action="{{action('Admin\authController@update_profile')}}" class="section general-info" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="info">
                                            <h6 class="">Edit Profile</h6>
                                            <div class="row">
                                                <div class="col-lg-11 mx-auto">
                                                    <div class="row">
                                                        <div class="col-xl-2 col-lg-12 col-md-4">
                                                            <div class="upload mt-4 pr-md-4">
                                                                <input type="file" id="input-file-max-fs" name="image" class="dropify" data-default-file="{{url('uploads/admin/profile_image/'.$admin_detail->image)}}" data-max-file-size="2M" accept="image/*" />
<!--                                                                <p class="mt-2"><i class="flaticon-cloud-upload mr-1"></i> Upload Picture</p>-->
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">
                                                            <div class="form">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="fname">First Name</label>
                                                                            <input type="text" class="form-control mb-4" id="fname" name="fname" placeholder="First Name" value="{{$admin_detail->fname}}" required="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="lname">Last Name</label>
                                                                            <input type="text" class="form-control mb-4" id="lname" name="lname" placeholder="Last Name" value="{{$admin_detail->lname}}" required="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="email">Email</label>
                                                                            <input type="text" class="form-control mb-4" id="email" name="email" placeholder="Email" value="{{$admin_detail->email}}" required="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="Contact">Contact</label>
                                                                            <input type="text" class="form-control mb-4" id="contact" name="contact" placeholder="Contact" value="{{$admin_detail->contact}}" required="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="address">Address</label>
                                                                    <textarea class="form-control mb-4" id="address" name="address" placeholder="Address">{{$admin_detail->address}}</textarea>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="password">Password</label>
                                                                            <input type="password" class="form-control mb-4" name="password" id="password" placeholder="Password" autocomplete="new-password">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="confirm_password">Confirm Password</label>
                                                                            <input type="password" class="form-control mb-4" id="confirm_password" name="confirm_password" placeholder="Confirm Password" autocomplete="new-password">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="submit" name="submit" value="Update" class="mb-4 btn btn-primary">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    
                </div>

                </div>
      
        <!--  END CONTENT AREA  -->
     
@endsection
@section('page_script')
<script src="{{asset('resources/admin_template/plugins/dropify/dropify.min.js')}}"></script>
    <script src="{{asset('resources/admin_template/assets/js/users/account-settings.js')}}"></script>
<script>
        //First upload
     //   var firstUpload = new FileUploadWithPreview('myFirstImage');
//        var ss = $(".select2").select2({
//            
//        });
//        
</script>
@endsection
