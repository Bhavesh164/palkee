@extends('admin.layout.master',['title' => 'View Ride Detail'])
@section('page_header_css')
<style>
    .table th {
        width: 200px;
    }
</style>
@endsection
@section('content')

<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12  layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>View Ride Detail</h4>
                        </div>
                    </div>
                </div>

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
                    <form action="{{action('Admin\rideController@update',$ride_detail->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                        {{csrf_field()}}
                        @method('PATCH')

                        <table class="table mb-0">

                            <tbody>
                                <tr>
                                    <th scope="row">Ride Number </th>
                                    <td>{{$ride_detail->ride_number}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Ride Type</th>
                                    <td>{{$ride_detail->ride_type_name}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Rider</th>
                                    <td>{{$ride_detail->rider_name}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Driver</th>
                                    <td>{{$ride_detail->driver_name}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Vehicle Number</th>
                                    <td>{{$ride_detail->vehicle_number}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Pickup Location</th>
                                    <td>{{$ride_detail->start_location}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">End Location</th>
                                    <td>{{$ride_detail->end_location}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Distance</th>
                                    <td>{{$ride_detail->distance}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Duration</th>
                                    <td>{{$ride_detail->duration}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Pickup Time</th>
                                    <td>{{date('d M Y H:i:s',strtotime($ride_detail->pickup_time))}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Drop Time</th>
                                    <td>{{date('d M Y H:i:s',strtotime($ride_detail->drop_time))}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Base Fare</th>
                                    <td>{{config('constant.currency_symbol').$ride_detail->base_fare}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Distance Fare</th>
                                    <td>{{config('constant.currency_symbol').$ride_detail->distance_fare}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time Fare</th>
                                    <td>{{config('constant.currency_symbol').$ride_detail->time_fare}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Tax</th>
                                    <td>{{config('constant.currency_symbol').$ride_detail->tax}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Total Bill</th>
                                    <td>{{config('constant.currency_symbol').$ride_detail->total_bill}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Driver Earning</th>
                                    <td>{{config('constant.currency_symbol').$ride_detail->driver_earning}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Admin Earning</th>
                                    <td>{{config('constant.currency_symbol').$ride_detail->admin_earning}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Status</th>
                                    <td>
                                        <div class="form-group col-md-6" style="margin-left: -15px;">
                                            <select class="form-control select2" name="status" id="status">
                                                <option value="">Select Status</option>
                                                @foreach ($all_status as $key => $value)
                                                <option value="{{$value->id}}" <?= ($value->id == $ride_detail->ride_status_id) ? 'selected' : '' ?>>{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                        <div class="row" style="margin-top:15px">
                            <div class="col-sm-3">
                            </div>
                            <div class="col-sm-9">
                                <button class="btn btn-primary px-5" type="submit" name="submit">Update</button>
                            </div>
                        </div>
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
    //var firstUpload = new FileUploadWithPreview('myFirstImage');
    var ss = $(".select2").select2({});
</script>

@endsection