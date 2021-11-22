@extends('admin.layout.master',['title' => 'View Promo Code'])

@section('page_header_css')

<link rel="stylesheet" type="text/css" href="{{asset('resources/admin_template/plugins/table/datatable/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('resources/admin_template/plugins/table/datatable/dt-global_style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('resources/admin_template/plugins/table/datatable/responsive.dataTables.min.css')}}">

@endsection

@section('content')

<!--  BEGIN CONTENT AREA  -->

<div class="layout-px-spacing">

    <div class="row layout-top-spacing" id="cancel-row">

        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-10 col-md-10 col-sm-12 col-10">
                            <h4>View Promo Codes</h4>
                        </div>
                        <div class="col-xl-2 col-md-2 col-sm-12 col-2 pull-right">
                            <a href="{{ url('admin/promo_code/create') }}"><span class="btn btn-primary mt-2"><i class="fa fa-plus"></i> Add</span></a>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">

                    <table id="zero-config" class="table table-striped dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Promo Name</th>
                                <th>Promo Code</th>
                                <th>Promo Type</th>
                                <th>Promo Rate</th>
                                <th>Expiry Date</th>
                                <?php
                                /*
                                <th>Promo Image</th>
                                */ ?>
                                <th style="width:70px">Status</th>
                                <th>Created At</th>
                                <th class="no-content" data-priority="2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $i = 0; ?>
                            @foreach ($data as $key => $value)
                            <?php $i++; ?>
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $value->promo_name }}</td>
                                <td>{{ $value->promo_code }}</td>
                                <td>{{ $value->promo_type }}</td>
                                <td>{{ $value->promo_rate }}</td>
                                <td>{{date('Y-m-d',strtotime($value->expiry_date))}}</td>
                                <?php /*
                                <td><?php
                                    if ($value->image) {
                                        ?>
                                        <img src="{{url('uploads/promo_code/'.$value->image)}}" height="50" width="50">
                                    <?php } else { ?>
                                        <img src="{{url('uploads/default/no-image.png')}}" height="50" width="50"> 
                                    <?php } ?>
                                </td>
                                */ ?>
                                <td class=" ">

                                    <?php if ($value->is_activated == '0') { ?>
                                        <span class="shadow-none badge badge-danger">Inactive</span>
                                    <?php } else { ?>
                                        <span class="shadow-none badge badge-success">Active</span>

                                    <?php } ?>
                                </td>
                                <td><?= ($value->created_at) ? date('d M Y H:i:s', strtotime($value->created_at)) : ''; ?></td>

                                <td class="table-action">
                                    <a href="{{action('Admin\promo_codeController@edit',$value->id)}}" class=""><i class="far fa-edit"></i></a>

                                    <form action="{{action('Admin\promo_codeController@destroy',$value->id)}}" id="form-<?= $value->id ?>" class="delete-form" method="post">
                                        {{csrf_field()}}
                                        @method('DELETE')
                                        <button type="submit" class="" data-id="<?= $value->id ?>" name="submit"><i class="far fa-trash-alt"></i></button>
                                        <!--                                           <a href="{{url('vehicletype/delete/'.$value->id)}}" class="ms-3"><i class="far fa-trash-alt"></i></a>-->
                                    </form>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

<!--  END CONTENT AREA  -->



@endsection
@section('page_script')
<script src="{{asset('resources/admin_template/plugins/table/datatable/datatables.js')}}"></script>
<script src="{{asset('resources/admin_template/plugins/table/datatable/dataTables.responsive.min.js')}}"></script>
<script>
    $('#zero-config').DataTable({
        "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
            "<'table-responsive'tr>" +
            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
        "oLanguage": {
            "oPaginate": {
                "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
            },
            "sInfo": "Showing page _PAGE_ of _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Search...",
            "sLengthMenu": "Results :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [10, 20, 50],
        "pageLength": 10
    });


    $('.delete-form').on('submit', function(e) {

        e.preventDefault();
        $form_id = $(this).attr('id');
        const swalWithBootstrapButtons = swal.mixin({
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger mr-3',
            buttonsStyling: false,
        })
        swalWithBootstrapButtons({
            title: 'Are you sure to Delete?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true,
            padding: '2em'
        }).then(function(result) {
            if (result.value) {

                var token = $('meta[name="csrf-token"]').attr('content');
                //get the action-url of the form
                var actionurl = e.currentTarget.action;
                //do your own request an handle the results
                $.ajax({
                    url: actionurl,
                    header: {
                        'X-CSRF-TOKEN': token
                    },
                    type: 'post',
                    dataType: 'json',
                    data: $('#' + $form_id).serialize(),
                    success: function(data) {

                        if (data.success == 1) {
                            swalWithBootstrapButtons(
                                'Deleted!',
                                data.msg,
                                'success'
                            ).then(function() {
                                location.reload();
                            });
                        }
                    }
                });

            } else if (
                // Read more about handling dismissals
                result.dismiss === swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons(
                    'Cancelled',
                    'Your Record is safe.',
                    'error'
                )
            }
        })
    });
</script>
@endsection