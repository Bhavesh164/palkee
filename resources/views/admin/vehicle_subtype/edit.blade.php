@extends('admin.layout.master',['title' => 'Edit Vehicle Sub Type'])

@section('content')
 
<div class="layout-px-spacing">
                
                <div class="row layout-top-spacing">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-12  layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">                                
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <h4>Edit Vehicle Sub Type</h4>
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
                                    <form action="{{action('Admin\vehicle_subtypeController@update',$vehicle_subtype->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                         {{csrf_field()}}
                                         @method('PATCH')
                                         <div class="form-row mb-2">
                                             <div class="form-group col-md-6">
                                                <label for="type">Vehicle Type</label>
                                                <select class="form-control select2" name="type_id" id="type" required="">
                                                    <option value="">Select Vehicle Type</option>
                                                    @foreach ($vehicle_types as $vehicle_type)
                                                    <option value="{{$vehicle_type->id}}" <?=($vehicle_type->id == $vehicle_subtype->type_id)?'selected':''?>>{{$vehicle_type->type_name}}</option>
                                                    @endforeach
                                                    
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="subtype_name">Sub Type Name</label>
                                                <input type="text" class="form-control form-control-sm" id="subtype_name" name="subtype_name" placeholder="Sub Type Name" required="" value="{{ $vehicle_subtype->subtype_name }}">
                                            </div>
                                         </div>
                                         <div class="form-row mb-4">
                                              <div class="form-group col-md-6">
                                                <label for="base_price"> Base Price</label>
                                                <input type="text" class="form-control form-control-sm" id="base_price" name="base_price" placeholder="Base Price" required="" onkeypress="javascript:return float(event,this)" value="{{ $vehicle_subtype->base_price }}">
                                              </div>
                                              <div class="form-group col-md-6">
                                                <label for="min_price"> Minimum Price</label>
                                                <input type="text" class="form-control form-control-sm" id="type_name" name="min_price" placeholder="Minimum Name" required="" onkeypress="javascript:return float(event,this)" value="{{ $vehicle_subtype->min_price }}">
                                              </div>
                                         </div>
                                         <div class="form-row mb-4">
                                              <div class="form-group col-md-4">
                                                <label for="per_min_price">Per Minute price</label>
                                                <input type="text" class="form-control form-control-sm" id="per_min_price" name="per_min_price" placeholder="Per Minute price" required="" onkeypress="javascript:return float(event,this)" value="{{ $vehicle_subtype->per_minute_price }}">
                                              </div>
                                              <div class="form-group col-md-4">
                                                <label for="per_km_price">Per KM price</label>
                                                <input type="text" class="form-control form-control-sm" id="per_km_price" name="per_km_price" placeholder="Per KM price" onkeypress="javascript:return float(event,this)" value="{{ $vehicle_subtype->per_km_price }}">
                                              </div>
                                             <div class="form-group col-md-4">
                                                <label for="per_mile_price">Per Mile price</label>
                                                <input type="text" class="form-control form-control-sm" id="per_mile_price" name="per_mile_price" placeholder="Per Mile price" onkeypress="javascript:return float(event,this)" value="{{ $vehicle_subtype->per_mile_price }}">
                                              </div>
                                         </div>
                                        <div class="form-group row  mb-4">
                                            <label for="is_activated" class="col-sm-1 col-form-label col-form-label-sm">Active</label>
                                            <div class="col-sm-6">
                                               <label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                                <input type="checkbox" id="is_activated" name="is_activated" checked>
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
        
        var ss = $(".select2").select2({
   
});
        
</script>
@endsection