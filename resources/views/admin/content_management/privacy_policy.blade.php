<?php
// echo "<pre>";
// print_r($content_management);
// exit;

?>


@extends('admin.layout.master',['title' => 'Edit Vehicle Make'])

@section('content')

<style>
    .widget-content-area {
        padding:0px 10px;
    }
</style>

<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12  layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">                                
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Edit Privacy Policy</h4>
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
                    <?php //action="{{action('Admin\content_managementController@update',$content_management->content_id)}}" 
                    ?>
                    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                        {{csrf_field()}}
                        @method('PATCH')

                        <?php /*
                        <div class="form-group row  mb-4">
                            <label for="page_content" class="col-sm-2 col-form-label col-form-label-sm">Privacy Policy</label>
                            <div class="col-sm-6">

                               <!--  <input type="text" class="form-control form-control-sm" id="page_content" name="page_content" value="{{$content_management->page_content}}" placeholder="Privacy Policy"> -->

                                <!-- <textarea id="textarea_content" placeholder="Description" name="description" class="form-control tiny">{{$content_management->page_content}}</textarea> -->

                            </div>
                        </div>

                        */ ?>




                         <div class="form-group">
                             <label for="page_content" class="col-sm-2 col-form-label col-form-label-sm">Privacy Policy</label>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <textarea id="textarea_content" placeholder="Description" name="description" class="form-control tiny">{{$content_management->page_content}}</textarea>
                                <ul class="parsley-errors-list" id="parsley-id-2309"></ul>
                                
                                <input type="hidden" value="{{$content_management->content_id}}" name="content_id">

                                <input type="submit" value="Update" name="update" class="mb-4 btn btn-primary">

                                

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

@endsection
