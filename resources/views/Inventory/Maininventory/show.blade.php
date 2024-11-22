@extends('layouts.app', ['page' => __('Main Inventory'), 'pageSlug' => 'mainInventory'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class="title">{{ _('Main Inventory Details') }}</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('maininventory') }}" method="POST" autocomplete="off">
                    @csrf
                    <!-- @method('put') -->

                    <!-- @include('alerts.success') -->
                    <div class="form-row align-items-center">
                        @if(!$result==null)
                        @if($result->isEmpty() && $user->USERWG == 'SLT')
                        <div class="form-group col-sm-12 col-md-4 order-md-3 d-flex justify-content-end">
                            <button type="button" id="new_depot" class="btn btn-primary" data-toggle="modal" data-target="#depot_add_modal">
                                {{ _('Create New Inventory') }}
                            </button>
                        </div>
                        @endif
                        @endif
                        <div class="form-group{{ $errors->has('workGroupSelect') ? ' has-danger' : '' }} col-sm-6 col-md-4 order-md-1">
                            <label for="workGroupSelect">{{ _('Workgroup') }}</label>
                            <select class="form-control{{ $errors->has('workGroupSelect') ? ' is-invalid' : '' }}" id="workGroupSelect" name="workGroupSelect">
                                @if($user->USERWG == 'SLT')
                                <option value=""></option>
                                @if(!$contractors==null)
                                @foreach($contractors as $data)
                                <option value="{{ $data }}">{{ $data }}</option>
                                @endforeach
                                @endif
                                @else
                                <option value=""></option>
                                <option value="{{$user->USERWG}}">{{ $user->USERWG }}</option>
                                @endif
                            </select>
                            @include('alerts.feedback', ['field' => 'workGroupSelect'])
                        </div>
                        <div class="form-group col-sm-6 col-md-4 order-md-2">
                            <button type="submit" class="btn btn-fill btn-primary">{{ _('Get Data') }}</button>
                        </div>
                    </div>
                </form>
                <hr />
                @if(!$result==null)
                    @if(!$result->isEmpty() )
                        <div id="edit_btn_div" class="form-row justify-content-end">
                            <button type="button" class="btn btn-success btn-sm" id="erpbtn">
                                {{ _('Sync with ERP') }}
                            </button>
                        </div>
                    @endif
                @endif
                <div>
                    @if(!$result==null)
                        @if($result->isEmpty())
                            @if(!$wgName==null)
                                @push('js')
                                <script>
                                    $('#workGroupSelect').val('{{ $wgName }}');
                                    console.log("ok")
                                </script>
                                @endpush
                            @endif
                        @else
                        @foreach($result as $data)
                            @push('js')
                            <script>
                                $('#workGroupSelect').val('{{ $data->depot_user_name }}');
                            </script>
                            @endpush
                            <div class="row mt-2">
                                <input type="hidden" id="hidden_depot_id"  value="{{ $data->depot_id }}" >
                                <div class="col-sm-3">
                                    <h6>{{ __('Inventory Id : ') }} </h6>
                                    <h5 id="sw_major_doc_pros_id">{{ $data->depot_id }}</h5>
                                </div>
                                <div class="col-sm-3">
                                    <h6>{{ __('Inventory Name : ') }} </h6>
                                    <h5 id="sw_major_pros_name">{{ $data->depot_user_name }}</h5>
                                </div>
                                <div class="col-sm-3">
                                    <h6>{{ __('Inventory ERP Ref : ') }}</h6>
                                    <h5 id="sw_major_pros_svtype">{{ $data->depot_erp_ref }}</h5>
                                </div>
                                <div class="col-sm-3">
                                    <h6>{{ __('Inventory Status : ') }}</h6>
                                    <h5 id="sw_major_pros_status">{{ $data->depot_status }}</h5>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-sm-3">
                                    <h6>{{ __('Inventory User Address : ') }}</h6>
                                    <h5 id="sw_major_pros_target_enddate">{{ $data->depot_user_address }}</h5>
                                </div>
                                <div class="col-sm-3">
                                    <h6>{{ __('Inventory Type : ') }}</h6>
                                    <h5 id="sw_major_pros_target_enddate">{{ $data->depot_type }}</h5>
                                </div>
                                <div class="col-sm-3">
                                    <h6>{{ __('Last Sync Date : ') }}</h6>
                                    <h5 id="sw_major_pros_target_enddate">{{ $data->depot_last_snyc }}</h5>
                                </div>
                            </div>
                        @endforeach
                        @endif
                    @endif
                    @if(!$result==null)
                        @if(!$result->isEmpty() && $user->USERWG == 'SLT')
                            <div id="edit_btn_div" class="form-row float-right">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#depot_edit_modal">
                                    {{ _('Edit') }}
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
                <br />
                <br />
                <hr />
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1" id="materialbtn">Materials</a></li>
                        <li><a href="#tabs-2" id="commentbtn">Comment</a></li>
                    </ul>
                    <div id="tabs-1">
                        <div class="row">
                            <div class="col-12 form-group">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="depot_table">
                                        <thead>
                                            <th></th>
                                            <th>Item Code</th>
                                            <th>Item Description</th>
                                            <th>Total Balance</th>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tabs-2">
                    <div class="row">
                            <div class="col-8 form-group">
                                <h6 for="text">{{ _('Text') }}</h6>
                                <input type="text" name="text" id="text" class="form-control{{ $errors->has('project') ? ' is-invalid' : '' }}" value="" placeholder="{{ __('Comment text') }}" required>
                                <input value="{{App\Enums\CommentTypes::USERCOM}}" hidden id="commtype" />
                            </div>
                            <div class="col-2 form-group">
                                <br />
                                <button type="button" class="btn btn-primary btn-sm" id="commentaddbtn">Save</button>
                            </div>
                            <div class="col-2 form-group"></div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover" id="comment_table">
                                <thead>
                                    <th>Time Stamp</th>
                                    <th>User</th>
                                    <th>Text</th>
                                </thead>

                            </table>
                        </div>
                    </div>
                    
                </div>
                

                <div class="row">
                    <div id="example-table"></div>
                </div>
            </div>
            <div class="card-footer">
            </div>

        </div>
    </div>
</div>
@endsection


<!-- Edit Modal -->
<div class="modal fade" id="depot_edit_modal" tabindex="-1" role="dialog" aria-labelledby="depot_edit_modal_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="depot_edit_modal_label">{{ _('Edit Main Inventory') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="tim-icons icon-simple-remove"></i>
                </button>
            </div>
            <form method="post" action="" autocomplete="off">
                <div class="modal-body">
                    @csrf
                    <!-- @method('put') -->
                    <!-- @include('alerts.success', ['key' => 'password_status']) -->
                    @if(!$result==null)
                    @foreach($result as $data)
                    @include('Inventory.Maininventory.form', ['form_type' => __('EDIT'), 'form_data' => $data])
                    @endforeach
                    @else
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="update_depot_btn" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Depot Modal -->
<div class="modal fade" id="depot_add_modal" tabindex="-1" role="dialog" aria-labelledby="depot_add_modal_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="depot_add_modal_label">{{ _('Create New Main Inventory') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="tim-icons icon-simple-remove"></i>
                </button>
            </div>
            <form method="post" action="" autocomplete="off">
                <div class="modal-body">
                    @csrf
                    <!-- @method('put') -->
                    <!-- @include('alerts.success', ['key' => 'password_status']) -->
                    @include('Inventory.Maininventory.form', ['form_type' => __('ADD'), 'form_data' => null])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="create_depot_btn" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('css')
<!-- <link href="https://unpkg.com/tabulator-tables@4.1.4/dist/css/tabulator.min.css" rel="stylesheet"> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
<style>
    td.details-control {
        background: url('https://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.shown td.details-control {
        background: url('https://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
    }


    td.details-control1 {
        background: url('https://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.shown td.details-control1 {
        background: url('https://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
    }
</style>

@endpush

@push('js')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>


<!-- <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.1.4/dist/js/tabulator.min.js"></script> -->

<script>
    $('#erpbtn').unbind('click').bind('click', function(e) {
        $('#erpbtn').prop("disabled", true);
        $("#erpbtn").html("Synchronizing");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: "{{ route('inventory/erpdata') }}",
            data: {
                'contractor': $('#depot_erp_ref').val(),
                'id': $('#depot_id').val(),
            },
            success: function(data, textStatus, jqXHR) {
                $('#erpbtn').prop("disabled", false);
                $("#erpbtn").html("Sync with ERP");
                if (data['responce']) {
                    demo.showCustomNotification('top', 'center', "ERP Sync Success", AlertTypes.Success);
                    location.reload();
                    getdatatable()
                } else {
                    demo.showCustomNotification('top', 'center', data['message'], AlertTypes.Danger);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#erpbtn').prop("disabled", false);
                $("#erpbtn").html("Sync with ERP");
                demo.showCustomNotification('top', 'center', 'Error occurred!' + errorThrown, AlertTypes.Danger);
            }

        });
    });
    </script>

@if($user->USERWG == 'SLT')
<script>
    // $('#erpbtn').unbind('click').bind('click', function(e) {
    //     $('#erpbtn').prop("disabled", true);
    //     $("#erpbtn").html("Synchronizing");
    //     $.ajax({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         type: "GET",
    //         url: "{{ route('inventory/erpdata') }}",
    //         data: {
    //             'contractor': $('#depot_erp_ref').val(),
    //             'id': $('#depot_id').val(),
    //         },
    //         success: function(data, textStatus, jqXHR) {
    //             $('#erpbtn').prop("disabled", false);
    //             $("#erpbtn").html("Sync with ERP");
    //             if (data['responce']) {
    //                 demo.showCustomNotification('top', 'center', "ERP Sync Success", AlertTypes.Success);
    //                 location.reload();
    //                 getdatatable()
    //             } else {
    //                 demo.showCustomNotification('top', 'center', data['message'], AlertTypes.Danger);
    //             }
    //         },
    //         error: function(jqXHR, textStatus, errorThrown) {
    //             $('#erpbtn').prop("disabled", false);
    //             $("#erpbtn").html("Sync with ERP");
    //             demo.showCustomNotification('top', 'center', 'Error occurred!' + errorThrown, AlertTypes.Danger);
    //         }

    //     });
    // });

    $('#depot_add_modal #depot_user_name').val($('#workGroupSelect').val())

    $('#new_depot').unbind('click').bind('click', function(e) {
        $('#depot_add_modal #depot_user_name').val($('#workGroupSelect').val())
    })

    $("#depot_add_modal").on("hidden.bs.modal", function(e) {
        console.log("Modal hidden");
        $('#depot_add_modal #depot_user_name').val(" "),
            $('#depot_add_modal #depot_erp_ref').val(" "),
            $('#depot_add_modal #depot_status').val(" "),
            $('#depot_add_modal #depot_user_address').val(" "),
            $('#depot_add_modal #depot_type').val(" ")
    });

    //Create
    $('#depot_add_modal').find('#create_depot_btn').unbind('click').bind('click', function(e) {
        if (!$('#depot_add_modal #depot_user_name').val()) {
            demo.showCustomNotification('top', 'center', 'Please Enter a User Name', AlertTypes.Info);
            return;
        }
        if (!$('#depot_add_modal #depot_erp_ref').val()) {
            demo.showCustomNotification('top', 'center', 'Please Enter an ERP Reference', AlertTypes.Info);
            return;
        }
        if (!$('#depot_add_modal #depot_status').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Inventoty Status', AlertTypes.Info);
            return;
        }
        if (!$('#depot_add_modal #depot_type').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Inventoty Type', AlertTypes.Info);
            return;
        }


        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('createmaininventory') }}",
            data: {
                'DEPOT_USER_NAME': $('#depot_add_modal #depot_user_name').val(),
                'DEPOT_ERP_REF': $('#depot_add_modal #depot_erp_ref').val(),
                'DEPOT_STATUS': $('#depot_add_modal #depot_status').val(),
                'DEPOT_USER_ADDRESS': $('#depot_add_modal #depot_user_address').val(),
                'DEPOT_TYPE': $('#depot_add_modal #depot_type').val()
            },
            success: function(data, textStatus, jqXHR) {
                if (data['alert-type'] == 'success') {
                    $('#depot_add_modal').modal('hide');
                    alert('Success!');
                    location.reload();
                } else {
                    alert('Error occurred!');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error occurred!' + errorThrown);
            }

        });
    });

    var updData = {}

    updData["DEPOT_ID"] = $('#depot_edit_modal #depot_id').val()
    updData["DEPOT_USER_NAME"] = $('#depot_edit_modal #depot_user_name').val()

    $('#depot_edit_modal input, #depot_edit_modal select, #depot_edit_modal textarea').on('change', function() {
        var fieldId = $(this)[0].id
        updData[fieldId.toUpperCase()] = $(this).val()
    });
  

    //Update
    $('#depot_edit_modal').find('#update_depot_btn').unbind('click').bind('click', function(e) {
        if (!$('#depot_edit_modal #depot_user_name').val()) {
            demo.showCustomNotification('top', 'center', 'Please Enter a User Name', AlertTypes.Info);
            return;
        }
        if (!$('#depot_edit_modal #depot_erp_ref').val()) {
            demo.showCustomNotification('top', 'center', 'Please Enter an ERP Reference', AlertTypes.Info);
            return;
        }
        if (!$('#depot_edit_modal #depot_status').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Inventoty Status', AlertTypes.Info);
            return;
        }
        if (!$('#depot_edit_modal #depot_type').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Inventoty Type', AlertTypes.Info);
            return;
        }


        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "PUT",
            url: "{{ route('updatemaininventory') }}",
            data: updData,
            // {
            //     'DEPOT_ID': $('#depot_edit_modal #depot_id').val(),
            //     'DEPOT_USER_NAME': $('#depot_edit_modal #depot_user_name').val(),
            //     'DEPOT_ERP_REF': $('#depot_edit_modal #depot_erp_ref').val(),
            //     'DEPOT_STATUS': $('#depot_edit_modal #depot_status').val(),
            //     'DEPOT_USER_ADDRESS': $('#depot_edit_modal #depot_user_address').val(),
            //     'DEPOT_TYPE': $('#depot_edit_modal #depot_type').val()
            // },
            success: function(data, textStatus, jqXHR) {
                if (data['alert-type'] == 'success') {
                    $('#depot_edit_modal').modal('hide');
                    alert('Success!');
                    location.reload();
                } else {
                    alert('Error occurred!');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error occurred!' + errorThrown);
            }

        });
    });
</script>
@endif
@endpush


@push('js')
<script>
    $(document).ready(function() {
        $("#tabs").tabs();
        getdatatable()
    });

    function getdatatable() {
        $('#depot_table').dataTable().fnClearTable();
        $('#depot_table').dataTable().fnDestroy();
        $('#depot_table').DataTable({
            // processing: true,
            serverSide: false,
            // retrieve: true,
            pageLength: 5,
            paging: true,
            autoWidth: false,
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            ajax: {
                url: "{{ route('inventory/erpdatatable') }}",
                type: 'GET',
                data: {
                    'workGroupSelect': $('#workGroupSelect').val(),
                },
            },
            columns: [{
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": ''
                }, {
                    data: "di_item_code"
                },
                {
                    data: 'item_discription',
                },
                {
                    data: 'di_tot_qty',
                },
            ]
        });
    }



    $('#depot_table').on('click', 'td.details-control', function() {
        var tr = $(this).closest('tr');
        var row = $('#depot_table').DataTable().row(tr);
        var rowData = row.data();

        //get index to use for child table ID
        var index = row.index();
        console.log(index);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(
                '<table class="child_table" id = "child_details' + index + '" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
                '<thead><tr><th>Date Transfered</th><th>Lot No</th><th>Drum NO</th><th>Balance</th></tr></thead><tbody>' +
                '</tbody></table>').show();

            $('#child_details' + index).DataTable({
                // processing: true,
                serverSide: false,
                // retrieve: true,
                pageLength: 5,
                paging: true,
                autoWidth: false,
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: {
                    url: "{{ route('inventory/erpchildtable') }}",
                    type: 'GET',
                    data: {
                        'workGroupSelect': $('#workGroupSelect').val(),
                        'itemCode': rowData['di_item_code'],
                    },
                },
                columns: [{
                        data: 'di_date_transfered',
                    },
                    {
                        data: 'di_lot_no',
                    },
                    {
                        data: 'di_drum_no',
                    },
                    {
                        data: 'di_tot_qty',
                    },
                    // {
                    //     data: 'di_reserved_qty',
                    // },
                    // {
                    //     data: 'available_qty',
                    // }
                ]
            });

            tr.addClass('shown');
        }
    });

    $('#commentbtn').unbind('click').bind('click', function (e) {
        loadComments();
    });


    function loadComments() {
        $('#comment_table').dataTable().fnClearTable();
        $('#comment_table').dataTable().fnDestroy();
        $('#comment_table').DataTable({
            processing: true,
            serverSide: false,
            retrieve: true,
            paging: true,
            autoWidth: false,
            responsive: true,
                ajax: {
                url: "getInventoryComment",
                type: 'GET',
                data: {
                    'depot': $('#hidden_depot_id').val(),
                },
            },
            columns: [{
                data: 'dc_date',
                name: 'Time Stamp'
            },
            {
                data: 'dc_user',
                name: 'User'
            },
            {
                data: 'dc_text',
                name: 'Text'
            },
            {
                data: 'dc_id',
                visible: false,
                searchable: false,
            },
            ],
            order: [
                [3, 'desc']
            ]
        });
    }

    $('#commentaddbtn').unbind('click').bind('click', function (e) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "storeInventoryComment",
            data: {
                'depot': $('#hidden_depot_id').val(),
                'text': $('#text').val(),
            },
            success: function (data, textStatus, jqXHR) {
                if (data['responce']) {
                    alert(data['message']);
                    $('#text').val("");
                    loadComments();
                } else {
                    alert(data['message']);
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error occurred!' + errorThrown);
            }

        });

    });

</script>
@endpush