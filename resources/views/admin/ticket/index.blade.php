<?php

$title =  'View Tickets';

?>
@extends('admin.layout.master',['title' => $title])

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
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>{{ $title }} ( {{$data->total()}} )</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="dt-custom-header">
                        <div class="d-flex align-items-center justify-content-between my-3 px-4">
                            <div class="position-relative">
                                <div class="dataTables_length">
                                    <label class="d-flex align-items-center my-0">Show <select onchange="setLimit(this.value)" class="table_len form-control mx-2 px-1">
                                            <option value="10" <?= ($limit == '10') ? 'selected' : '' ?>>10</option>
                                            <option value="25" <?= ($limit == '25') ? 'selected' : '' ?>>25</option>
                                            <option value="50" <?= ($limit == '50') ? 'selected' : '' ?>>50</option>
                                            <option value="100" <?= ($limit == '100') ? 'selected' : '' ?>>100</option>
                                        </select> entries
                                    </label>
                                </div>
                            </div>

                            <div class="ms-auto">

                                <form method="get" action="">
                                    <div id="myTable_filter" class="dataTables_filter d-flex align-items-start">

                                        <div class="mr-2">
                                            <select name="searchcolumn" class="form-control w-auto">
                                                <option value="" <?= ($searchcolumn == '') ? 'selected' : '' ?>>All </option>
                                                <option value="driver_name" <?= ($searchcolumn == 'driver_name') ? 'selected' : '' ?>>Driver Name</option>
                                                <option value="rider_name" <?= ($searchcolumn == 'rider_name') ? 'selected' : '' ?>>Rider Name</option>
                                            </select>
                                        </div>
                                        <div class="mr-2" style="width: 140px;">
                                            <input type="text" name="startdate" class="date form-control w-100" placeholder="Start Date" value="{{$startdate}}" />
                                        </div>
                                        <div class="mr-2" style="width: 140px;">
                                            <input type="text" name="enddate" class="date form-control w-100" placeholder="End Date" value="{{$enddate}}" />
                                        </div>
                                        <div class="input-group" style="width: 240px;">
                                            <input type="search" class="form-control" name="searchkeyword" aria-controls="myTable" placeholder="Search" value="<?php echo $searchkeyword; ?>" autocomplete="off">
                                            <!--									<input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2">-->
                                            <button class="btn btn-outline-primary m-0" type="submit" id="button-addon2">Go</button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center px-4">
                            <?php if ($searchkeyword != '') { ?>
                                <div class="search-key-name text-center" id="example_length2">
                                    Search Results for <b>"<?= $searchkeyword ?>"</b>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <table id="zero-config" class="table table-striped dt-responsive nowrap" style="width:100%">
                        <thead>

                            <tr>
                                <th> S.No. </th>
                                <th class="sorting" columnName="subject" onclick="setUrl('subject')">Subject</th>
                                <th class="sorting" columnName="rider_name" onclick="setUrl('rider_name')">Rider</th>
                                <th class="sorting" columnName="driver_name" onclick="setUrl('driver_name')">Driver</th>
                                <th class="sorting" columnName="created_at" onclick="setUrl('created_at')">Created At</th>
                                <th data-priority="2">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = $start; ?>
                            @foreach ($data as $key => $value)
                            <?php $i++; ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= $value->subject ?></td>
                                <td><?= $value->rider_name ?></td>
                                <td><?= $value->driver_name ?></td>
                                <td><?= ($value->created_on) ? date('d M Y H:i:s', strtotime($value->created_on)) : ''; ?></td>

                                <td class="table-action">
                                    <a href="{{action('Admin\ticketController@edit',$value->id)}}" class=""><i class="far fa-eye"></i></a>

                                    <form action="{{action('Admin\ticketController@destroy',$value->id)}}" id="form-<?= $value->id ?>" class="delete-form" method="post">
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
                    <!--                    {{ $data->onEachSide(5)->links() }}-->
                    <!--                    {{ $data->render() }}-->
                    <div class="defualt-pagination pr-3">
                        <?php
                        $pagination_append_fields = [
                            'page' => $page,
                            'limit' => $limit,
                            'startdate' => $startdate,
                            'enddate' => $enddate,
                            'searchcolumn' => $searchcolumn,
                            'searchkeyword' => $searchkeyword,
                            'sort' => $sort,
                            'sortorder' => $sortorder
                        ];
                        ?>
                        {{ $data->appends($pagination_append_fields)->onEachSide(1)->render() }}
                    </div>
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
    var f1 = flatpickr(document.getElementsByClassName('date'));

    $('#zero-config').DataTable({
        "dom": "<'dt--top-section-custom'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
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
        "pageLength": 10,
        "bSort": false,
        "paging": false,
        "info": false,
        "bFilter": false
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

    function activation(e, table_name, primary_column, id, status) {
        var img = $('<img >'); //Equivalent: $(document.createElement('img'))
        img.attr('src', BASE_URL + "/resources/assets/images/loading_1.gif");
        $(e).replaceWith(img);
        var token = $('meta[name="csrf-token"]').attr('content');
        var requesturl = BASE_URL + "/admin/common/updatestatus";
        $.ajax({
            context: img,
            url: requesturl,
            header: {
                'X-CSRF-TOKEN': token
            },
            data: {
                "id": id,
                table_name: table_name,
                primary_column: primary_column,
                status: status,
                _token: token
            },
            type: 'post',
            dataType: 'json',
            success: function(data) {
                if (data.success == 1) {
                    if (status === '0') {
                        $(this).replaceWith('<div class="t-dot bg-danger" data-toggle="tooltip" data-placement="top" title=""  onclick="javascript:activation(this,\'' + table_name + '\',\'' + primary_column + '\',\'' + id + '\',\'1\')"></div>')

                    } else {
                        $(this).replaceWith('<div class="t-dot bg-success" data-toggle="tooltip" data-placement="top" title=""  onclick="javascript:activation(this,\'' + table_name + '\',\'' + primary_column + '\',\'' + id + '\',\'0\')"></div>')

                    }

                } else {
                    $(this).replaceWith('Error updating');
                }

            }
        });
    }


    window.onload = function() {
        <?php
        if (isset($sort)) {
            if (isset($sortorder)) {
                if ($sortorder == "asc") {
                    $sorting_class = "sorting_asc";
                } else {
                    $sorting_class = "sorting_desc";
                } ?>
                var scn_obj = document.querySelectorAll("[columnName='<?php echo $sort; ?>']")[0];
                if (scn_obj) {
                    scn_obj.classList.add('<?php echo $sorting_class; ?>');
                }
        <?php }
        } ?>
    };

    function setUrl(paramName) {
        <?php
        if (isset($sortorder)) { ?>
            paramValue = '<?php echo $sortorder; ?>';
        <?php } else { ?> paramValue = "";
        <?php } ?>

        if (paramValue == 'asc') {
            sortorder = 'desc';
        } else {
            sortorder = 'asc';
        }

        window.location.href = setGetParameter('sort', paramName, 'sortorder', sortorder);
    }
</script>
@endsection