@extends('admin.layout.master',['title' => 'View Regions'])

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
                            <h4>View Regions</h4>
                        </div>
                    </div>
                </div>     
                <div class="widget-content widget-content-area">

                    <table id="zero-config" class="table table-striped dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Country Name</th>
                                <th>Region Name</th>
                                <th style="width:70px">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            @foreach ($data as $key => $value)
                            <?php $i++; ?>
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $value->country_name }}</td>
                                <td>{{ $value->name }}</td>
                                <td class=" ">

                                    <?php if ($value->is_activated == '0') { ?>
                                    
                                        <div class="t-dot bg-danger" data-toggle="tooltip" data-placement="top" title=""  onclick="javascript:activation(this,'region','regionId','<?= $value->regionId?>','1')"></div>
                                       
                                    <?php } else { ?>
                                        
                                        <div class="t-dot bg-success" data-toggle="tooltip" data-placement="top" title="" onclick="javascript:activation(this,'region','regionId','<?= $value->regionId?>','0')" ></div>

                                    <?php } ?>
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
        "oPaginate": {"sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'},
        "sInfo": "Showing page _PAGE_ of _PAGES_",
        "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
        "sSearchPlaceholder": "Search...",
        "sLengthMenu": "Results :  _MENU_",
    },
    "stripeClasses": [],
    "lengthMenu": [10, 20, 50],
    "pageLength": 10
});


$('.delete-form').on('submit', function (e) {

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
    }).then(function (result) {
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
                success: function (data) {

                    if (data.success == 1)
                    {
                        swalWithBootstrapButtons(
                                'Deleted!',
                                data.msg,
                                'success'
                                ).then(function () {
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

function activation(e,table_name,primary_column,id,status)
   {
       var img = $('<img >'); //Equivalent: $(document.createElement('img'))
       img.attr('src',BASE_URL+"/resources/assets/images/loading_1.gif");                
       $(e).replaceWith(img);
       var token = $('meta[name="csrf-token"]').attr('content');
       var requesturl = BASE_URL+"/admin/common/updatestatus";
       $.ajax({ 
               context: img,    
               url:requesturl,
               header: {
                    'X-CSRF-TOKEN': token
               },
               data:{"id":id,table_name:table_name,primary_column:primary_column,status:status,_token:token},
               type: 'post',
               dataType: 'json',
               success: function (data) {
                   if(data.success == 1)
                   {
                            if(status === '0')
                            {
                                $(this).replaceWith('<div class="t-dot bg-danger" data-toggle="tooltip" data-placement="top" title=""  onclick="javascript:activation(this,\''+table_name+'\',\''+primary_column+'\',\''+id+'\',\'1\')"></div>')

                            }
                            else
                            {
                               $(this).replaceWith('<div class="t-dot bg-success" data-toggle="tooltip" data-placement="top" title=""  onclick="javascript:activation(this,\''+table_name+'\',\''+primary_column+'\',\''+id+'\',\'0\')"></div>')

                            }
                             
                    }
                    else
                    {
                        $(this).replaceWith('Error updating');
                    }
   
               }
           });
   }
</script>
@endsection