@extends('admin.layout.master',['title' => 'About Page'])

@section('content')

<div class="layout-px-spacing">

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12  layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Privacy Policy Page</h4>
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
                    <form action="{{action('Admin\configController@update_privacy_policy_page')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf

                        <div class="form-row mb-2">
                            <div class="form-group col-md-12">
                                <textarea class="form-control summernote" name="privacy_policy_page" id="privacy_policy_page" required="">{{$config_values}}</textarea>
                            </div>
                        </div>
                        <input type="submit" name="update" value="Update" class="mb-4 btn btn-primary">
                    </form>
                </div>
            </div>
        </div>

    </div>
    <link href="//cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
</div>
@endsection
@section('page_script')
<script>
    //First upload
    // var ss = $(".select2").select2({});
    $(document).ready(function() {
        $('.summernote').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['white']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endsection