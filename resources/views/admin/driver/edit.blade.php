@extends('admin.layout.master',['title' => 'Update Driver Info'])
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
                            <h4>Update Driver Info</h4>
                        </div>
                    </div>
                </div>

                <div class="widget-content widget-content-area icon-tab">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
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
                            <a class="nav-link active" id="driver-info-tab" href="{{url('admin/driver/'.$driver->id.'/edit')}}" role="tab" aria-controls="icon-home" aria-selected="true">
                                <i class="fas fa-user"></i> Driver Info
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " id="icon-car-info" href="{{url('admin/driver/edit_vehicle_info/'.$driver->id)}}" role="tab" aria-controls="icon-contact" aria-selected="false">
                                <i class="fas fa-car"></i> Vehicle Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="icon-documents" href="{{url('admin/driver/edit_documents/'.$driver->id)}}" role="tab" aria-controls="icon-contact" aria-selected="false">
                                <i class="fas fa-file"></i> Documents</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="iconTabContent-1">
                        <div class="tab-pane fade show active" id="driver_info" role="tabpanel" aria-labelledby="driver-info-tab">
                            <form action="{{action('Admin\driverController@update',$driver->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                {{csrf_field()}}
                                @method('PATCH')
                                <div class="form-group row  mb-4">
                                    <label for="full_name" class="col-sm-2 col-form-label col-form-label-sm">Full Name</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-sm" id="rider_name" name="full_name" placeholder="Full Name" required="" value="{{$driver->full_name}}">
                                    </div>
                                </div>
                                <div class="form-group row  mb-4">
                                    <label for="email" class="col-sm-2 col-form-label col-form-label-sm">Email</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-sm" id="email" name="email" placeholder="Email" required="" value="{{$driver->email}}">
                                    </div>
                                </div>
                                <div class="form-group row  mb-4">
                                    <label for="dob" class="col-sm-2 col-form-label col-form-label-sm">D.O.B</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-sm" id="dob" name="dob" placeholder="D.O.B" required="" value="{{$driver->dob}}">
                                    </div>
                                </div>
                                <div class="form-group row  mb-4">
                                    <label for="phone" class="col-sm-2 col-form-label col-form-label-sm">Phone</label>
                                    <div class="col-sm-6">
                                        <input class="form-control form-control-sm" type="text" name="phone" id="phone" placeholder="Phone" required="" pattern="[0-9]*" value="{{$driver->phone}}" onkeypress="javascript:return numaric(event,this)">
                                        <input type="hidden" id="dial_code" name="dial_code" value="{{$driver->dial_code}}">
                                    </div>
                                </div>
                                <div class="form-group row  mb-1">
                                    <label for="country" class="col-sm-2 col-form-label col-form-label-sm">Country</label>
                                    <div class="col-sm-6">
                                        <select class="form-control form-control-sm select2" id="country" name="country_id" required="" onchange="getregions(this)">
                                            <option value="">Select Country</option>
                                            <?php
                                            // $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                                            foreach ($country_list as $key => $value) {
                                            ?>
                                                <option value="<?= $key ?>" <?= ($driver->country_id == $key) ? 'selected' : '' ?>><?= $value ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group row  mb-1">
                                    <label for="region" class="col-sm-2 col-form-label col-form-label-sm">Region</label>
                                    <div class="col-sm-6">
                                        <select class="form-control form-control-sm select2" name="region_id" id="region" required="" onchange="getcities(this)">
                                            <option value="">Select Region</option>
                                            <?php
                                            if ($driver->country_id != 0) {
                                                foreach ($region_list as $key => $value) {
                                            ?>
                                                    <option value="<?= $key ?>" <?= ($driver->region_id == $key) ? 'selected' : '' ?>><?= $value ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group row  mb-1">
                                    <label for="city" class="col-sm-2 col-form-label col-form-label-sm">City</label>
                                    <div class="col-sm-6">
                                        <select class="form-control form-control-sm select2" name="city_id" id="city" required="">
                                            <option value="">Select City</option>
                                            <?php
                                            if ($driver->region_id != 0) {
                                                foreach ($city_list as $key => $value) {
                                            ?>
                                                    <option value="<?= $key ?>" <?= ($driver->city_id == $key) ? 'selected' : '' ?>><?= $value ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group row  mb-4">
                                    <label for="postal_code" class="col-sm-2 col-form-label col-form-label-sm">Postal Code</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-sm" id="postal_code" name="postal_code" placeholder="Postal Code" value="{{$driver->postal_code}}">
                                    </div>
                                </div>
                                <div class="form-group row mb-4">

                                    <label for="colFormLabel" class="col-sm-2 col-form-label">Driver Image</label>
                                    <div class="col-sm-6">
                                        <div class="custom-file-container" data-upload-id="myFirstImage">

                                            <label class="custom-file-container__custom-file">
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
                                        <textarea class="form-control form-control-sm" id="address" name="address" placeholder="Address">{{$driver->address}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row  mb-1">
                                    <label for="languages" class="col-sm-2 col-form-label col-form-label-sm">Languages Known</label>
                                    <div class="col-sm-6">
                                        <select class="form-control form-control-sm tagging" multiple="multiple" name="languages[]" id="languages" required="" placeholder="Select Languages">
                                            <option value="English" <?= (in_array('English', $languages)) ? 'selected' : '' ?>>English</option>
                                            <option value="German" <?= (in_array('German', $languages)) ? 'selected' : '' ?>>German</option>
                                            <option value="French" <?= (in_array('French', $languages)) ? 'selected' : '' ?>>French</option>
                                            <option value="French" <?= (in_array('Bangla', $languages)) ? 'selected' : '' ?>>Bangla</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group row  mb-4">
                                    <label for="password" class="col-sm-2 col-form-label col-form-label-sm">Password</label>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="Password" autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-group row  mb-1">
                                    <label for="offline_drive" class="col-sm-2 col-form-label col-form-label-sm">Offline Drive</label>
                                    <div class="col-sm-6">
                                        <label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                            <input type="checkbox" id="offline_drive" name="offline_drive" <?= ($driver->offline_drive == 1) ? 'checked' : '' ?>>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row  mb-4">
                                    <label for="driver_code" class="col-sm-2 col-form-label col-form-label-sm">Driver Code</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-sm" id="driver_code" name="driver_code" placeholder="Driver Code" value="{{$driver->driver_code}}">
                                    </div>
                                </div>
                                <div class="form-group row  mb-4">
                                    <label for="is_online" class="col-sm-2 col-form-label col-form-label-sm">Online</label>
                                    <div class="col-sm-6">
                                        <label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                            <input type="checkbox" id="is_online" name="is_online" <?= ($driver->is_online == 1) ? 'checked' : '' ?>>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row  mb-4">
                                    <label for="is_activated" class="col-sm-2 col-form-label col-form-label-sm">Active</label>
                                    <div class="col-sm-6">
                                        <label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                            <input type="checkbox" id="is_activated" name="is_activated" <?= ($driver->is_activated == 1) ? 'checked' : '' ?>>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>

                                <input type="submit" name="Update" value="Update" class="mb-4 btn btn-primary">
                            </form>
                        </div>
                        <div class="tab-pane fade" id="car-info" role="tabpanel" aria-labelledby="icon-car-info">

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
    var ss = $(".select2").select2({});
    $(".tagging").select2({
        tags: true
    });
    var f1 = flatpickr(document.getElementById('dob'));
    <?php if ($driver->image) { ?>
        $('.custom-file-container__image-preview').css('background-image', 'url("<?= url('uploads/driver/' . $driver->image) ?>")');
    <?php } ?>
</script>
<script>
    window.addEventListener('load', (event) => {
        var input = document.getElementById("phone");
        var dialing_code = document.getElementById("dial_code");
        var iti = window.intlTelInput(input, {
            // hiddenInput: "full_phone",
            utilsScript: "{{asset('resources/assets/plugins/iti/utils.js')}}"
        });
        // input.style.display = "block";

        iti.setNumber('<?php echo $driver->dial_code; ?>');
        iti.setNumber('<?php echo $driver->phone; ?>');

        //$('#country').intlTelInput("setCountry", iso2);
        // $("#inputContact").intlTelInput("setCountry", "+91");

        input.addEventListener("countrychange", function() {
            var number = iti.selectedCountryData.dialCode;
            dialing_code.value = '+' + number;

        });
    });
</script>
<script src="{{asset('resources/assets/plugins/iti/intlTelInput.js')}}"></script>
@endsection