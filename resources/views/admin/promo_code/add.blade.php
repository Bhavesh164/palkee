@extends('admin.layout.master',['title' => 'Add Promo Code'])

@section('content')

<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12  layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Add Promo Code</h4>
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
                    <form action="{!! url('admin/promo_code') !!}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="form-group row  mb-4">
                            <label for="promo_name" class="col-sm-2 col-form-label col-form-label-sm">Promo Name</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="promo_name" name="promo_name" placeholder="Promo Name">
                            </div>
                        </div>

                        <div class="form-group row  mb-4">
                            <label for="promo_code" class="col-sm-2 col-form-label col-form-label-sm">Promo Code</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="promo_code" name="promo_code" placeholder="Promo Code">
                            </div>
                        </div>

                        <div class="form-group row  mb-4">
                            <label for="promo_type" class="col-sm-2 col-form-label col-form-label-sm">Promo Type</label>
                            <div class="col-sm-6">
                                <select class="form-control form-control-sm" id="promo_type" name="promo_type">
                                    <option value="flat">Flat Rate</option>
                                    <option value="percent">Percentage</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row  mb-4">
                            <label for="promo_rate" class="col-sm-2 col-form-label col-form-label-sm">Promo Rate</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="rate" name="promo_rate" placeholder="Promo Rate">
                            </div>
                        </div>

                        <div class="form-group row  mb-4">
                            <label for="promo_rate" class="col-sm-2 col-form-label col-form-label-sm">Minimum Amount</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control form-control-sm" name="minimum_amount" placeholder="Minimum Amount">
                            </div>
                        </div>

                        <div class="form-group row  mb-4">
                            <label for="promo_rate" class="col-sm-2 col-form-label col-form-label-sm">Maximum Discount</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="rate" name="maximum_discount" placeholder="Maximum Amount">
                            </div>
                        </div>

                        <div class="form-group row  mb-4">
                            <label for="redeem_per_user" class="col-sm-2 col-form-label col-form-label-sm">Redeem Per User</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" id="redeem_per_user" name="redeem_per_user" placeholder="Redeem Per User">
                            </div>
                        </div>



                        <div class="form-group row  mb-4">
                            <label for="expiry_date" class="col-sm-2 col-form-label col-form-label-sm">Expiry Date</label>
                            <div class="col-sm-6">
                                <input type="date" class="form-control form-control-sm" id="expiry_date" name="expiry_date" placeholder="Expiry Date">
                            </div>
                        </div>

                        <?php /*

                        <div class="form-group row mb-4">

                            <label for="colFormLabel" class="col-sm-2 col-form-label">Promo Image</label>
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

                        */ ?>


                        <div class="form-group row  mb-4">
                            <label for="type_name" class="col-sm-2 col-form-label col-form-label-sm">Active</label>
                            <div class="col-sm-6">
                                <label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                    <input type="checkbox" name="is_activated" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <input type="submit" name="submit" value="Add" class="mb-4 btn btn-primary">
                    </form>

                </div>
            </div>
        </div>

    </div>

</div>

@endsection
@section('page_script')
<script>
    //var firstUpload = new FileUploadWithPreview('myFirstImage')
    var f1 = flatpickr(document.getElementById('expiry_date'));
</script>
@endsection