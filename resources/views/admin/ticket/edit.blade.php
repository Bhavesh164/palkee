@extends('admin.layout.master',['title' => 'View Complaint Detail'])
@section('page_header_css')
<style>
    .table th {
        width: 200px;
    }

    .comp-header {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }

    .comp-heading {
        padding: 0 10px;
        flex: calc(100% / 3) 0 0;
        width: calc(100% / 3);
    }

    .layout-px-spacing.rm-space {
        min-height: auto !important;
    }

    .layout-spacing.rm-space {
        padding-bottom: 0 !important;
    }

    .comp-wrap {
        padding: 20px;
    }
</style>
@endsection
@section('content')
<div class="layout-px-spacing rm-space">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12  layout-spacing rm-space">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <div class="comp-wrap">
                                <div class="comp-header">
                                    <div class="comp-heading">
                                        <p><b>Complain By:</b></p>
                                        <p>{{ $data['complaint_by'] }}</p>
                                    </div>
                                    <div class="comp-heading">
                                        <p><b>Person Name:</b></p>
                                        <p>{{$data['detail']->full_name}}</p>
                                    </div>
                                    <div class="comp-heading">
                                        <p><b>Complain No:</b></p>
                                        <p>{{$data['detail']->id}}</p>
                                    </div>
                                </div>
                                <div class="comp-body">
                                    <div class="comp-title"><b>{{ $data['complaint']->subject }}</b></div>
                                    <div class="comp-desc">{{ $data['complaint']->message }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="layout-px-spacing rm-space">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12  layout-spacing rm-space">
            @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
            @endif
            <form action="{{action('Admin\ticketController@update',$data['complaint']->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                {{csrf_field()}}
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group w-100">
                            <textarea class="form-control" placeholder="Add comment" name="message" required></textarea>
                        </div>
                        <div class="form-group float-right">
                            <button type="submit" class="btn btn-primary">Add Comment</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @foreach ($data['comments'] as $comment)
        @if ($comment->reply_by==0)
        @php
        $name='Admin'
        @endphp
        @php
        $img=asset('resources/assets/images/dummy-user.png')
        @endphp
        @else
        @php
        $name= $data['detail']->full_name
        @endphp
        @if ($data['complaint_by']=='Rider')
        @php
        $img=config('global.RIDER_IMAGE_PATH').$data['detail']->image
        @endphp
        @else
        @php
        $img=config('global.DRIVER_IMAGE_PATH').$data['detail']->image
        @endphp
        @endif
        @endif
        <div class="col-xl-12 col-lg-12 col-md-12 col-12  layout-spacing rm-space mb-3">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="user d-flex flex-row align-items-center"> <img src="<?php echo $img ?>" width="30" class="user-img rounded-circle mr-2" onerror="this.onerror=null;this.src='{{asset("resources/assets/images/dummy-user.png")}}';"> <span><small class="font-weight-bold text-primary">{{ $name }}</small> <small class="font-weight-bold">{{$comment->message}}</small></span> </div> <small>{{ time_elapsed_string($comment->created_on) }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- <div class="layout-px-spcing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12  layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>View Complaint Detail</h4>
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
                    <form action="{{action('Admin\ticketController@update',1)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                        {{csrf_field()}}
                        @method('PATCH')
                        <table class="table mb-0">
                            <tbody></tbody>
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
</div> -->
@endsection
@section('page_script')
<script>
    //First upload
    //var firstUpload = new FileUploadWithPreview('myFirstImage');
    var ss = $(".select2").select2({});
</script>
@endsection