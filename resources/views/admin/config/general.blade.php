@extends('admin.layout.master',['title' => 'General Configuration'])

@section('content')

<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12  layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>General Configuration</h4>
                        </div>
                    </div>
                </div>
                <?php


                //                                if(isset($request))
                //                                {
                //                                    print_r($request);
                //                                }
                ?>
                <div class="widget-content widget-content-area">
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
                    <form action="{{action('Admin\configController@update_general')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf

                        <div class="form-row mb-2">
                            <div class="form-group col-md-6">
                                <label for="distance_unit">Distance Unit</label>
                                <select class="form-control select2" name="distance_unit" id="distance_unit" required="">
                                    <option value="km" <?= ($config_values->distance_unit == 'km') ? 'selected' : '' ?>>Km</option>
                                    <option value="miles" <?= ($config_values->distance_unit == 'miles') ? 'selected' : '' ?>>Miles</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 km_area" style="<?= ($config_values->distance_unit == 'km') ? '' : 'display:none' ?>">
                                <label for="driver_assignment_area_in_km">Driver Assignment Area in ( Km )</label>
                                <input type="text" class="form-control form-control-sm" id="driver_assignment_area_in_km" name="driver_assignment_area_in_km" placeholder="Driver Assignment Area in ( Km )" <?= ($config_values->distance_unit == 'km') ? 'required=""' : '' ?> value="{{$config_values->driver_assignment_area_in_km}}" onkeypress="javascript:return float(event,this)">
                            </div>
                            <div class="form-group col-md-6 miles_area" style="<?= ($config_values->distance_unit == 'miles') ? '' : 'display:none' ?>">
                                <label for="driver_assignment_area_in_miles">Driver Assignment Area in ( Miles )</label>
                                <input type="text" class="form-control form-control-sm" id="driver_assignment_area_in_miles" name="driver_assignment_area_in_miles" placeholder="Driver Assignment Area in ( Miles )" <?= ($config_values->distance_unit == 'miles') ? 'required=""' : '' ?> value="{{$config_values->driver_assignment_area_in_miles}}" onkeypress="javascript:return float(event,this)">
                            </div>
                            <div class="form-group col-md-6 miles_area">
                                <label for="driver_assignment_area_in_miles">SOS Number</label>
                                <input type="text" class="form-control form-control-sm" id="sos_number" name="sos_number" placeholder="SOS Number" value="{{$config_values->sos_number}}">
                            </div>
                        </div>

                        <input type="submit" name="update" value="Update" class="mb-4 btn btn-primary">
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

    var ss = $(".select2").select2({});
    $(document).on('change', '#distance_unit', function() {

        var distance_unit = $(this).val();
        if (distance_unit == 'km') {
            $('.km_area').css('display', 'block');
            $('.miles_area').css('display', 'none');
            $('#driver_assignment_area_in_km').attr('required', true);
            $('#driver_assignment_area_in_miles').removeAttr('required');

        } else {
            $('.km_area').css('display', 'none');
            $('.miles_area').css('display', 'block');
            $('#driver_assignment_area_in_miles').attr('required', true);
            $('#driver_assignment_area_in_km').removeAttr('required');

        }

    });
</script>
@endsection