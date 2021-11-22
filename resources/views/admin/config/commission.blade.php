@extends('admin.layout.master',['title' => 'Commission & Rides'])

@section('content')

<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12  layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Commission & Rides</h4>
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
                    <form action="{{action('Admin\configController@update_commission')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf

                        <div class="form-row mb-2">
                            <div class="form-group col-md-6">
                                <label for="commission_type">Commission Type</label>
                                <select class="form-control select2" name="commission_type" id="commission_type" required="">
                                    <option value="per" <?= ($config_values->commission_type == 'per') ? 'selected' : '' ?>>Percentage</option>
                                    <option value="flat" <?= ($config_values->commission_type == 'flat') ? 'selected' : '' ?>>Flat</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="commission_value">Commission Given Value</label>
                                <input type="text" class="form-control form-control-sm" id="commission_value" name="commission_value" placeholder="Commission Value" required="" value="{{$config_values->commission_value}}" onkeypress="javascript:return float(event,this)">
                            </div>
                        </div>
                        <div class="form-row mb-4">
                            <div class="form-group col-md-6">
                                <label for="base_price"> Ride Cancelation Charges</label>
                                <input type="text" class="form-control form-control-sm" id="base_price" name="ride_cancel_charges" placeholder="Ride Cancelation Charges" required="" value="{{$config_values->ride_cancel_charges}}" onkeypress="javascript:return float(event,this)">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="recahed_pickup_location_per">Amount Given to Cost Reached Pickup Location ( % )</label>
                                <input type="text" class="form-control form-control-sm" id="recahed_pickup_location_per" name="recahed_pickup_location_per" placeholder="Amount Given to Cost Reached Pickup Location ( % )" required="" value="{{$config_values->recahed_pickup_location_per}}" onkeypress="javascript:return float(event,this)">
                            </div>
                        </div>
                        <div class="form-row mb-4">
                            <div class="form-group col-md-6">
                                <label for="fixed_commission_offline_drive"> Fixed Commission Taken for Offline Drive </label>
                                <input type="text" class="form-control form-control-sm" id="fixed_commission_offline_drive" name="fixed_commission_offline_drive" placeholder="Fixed Commission Taken for Offline Drive" required="" value="{{isset($config_values->fixed_commission_offline_drive)?$config_values->fixed_commission_offline_drive:''}}" onkeypress="javascript:return float(event,this)">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fixed_commission_offline_drive">Tax (in Percentage)</label>
                                <input type="text" class="form-control form-control-sm" id="tax" name="tax" placeholder="Tax" required="" value="{{isset($config_values->tax)?$config_values->tax:''}}" onkeypress="javascript:return float(event,this)">
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
</script>
@endsection