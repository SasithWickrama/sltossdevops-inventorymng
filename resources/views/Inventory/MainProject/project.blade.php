@extends('layouts.app', ['page' => __('Inventory Project Data'), 'pageSlug' => 'inventoryMainProject'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">{{ _('Project Inventory Details') }}</h5>
            </div>

            <div class="card-body">

                <form action="{{ route('inventory/project') }}" method="POST" autocomplete="off" id="target">
                    @csrf
                    <div class="row">
                        <div class="col-4 form-group{{ $errors->has('project') ? ' has-danger' : '' }}">
                            <h6 for="project">{{ _('Project ID') }}</h6>
                            <input type="text" name="project" id="project" class="form-control{{ $errors->has('project') ? ' is-invalid' : '' }}" value="" placeholder="{{ __('Project ID') }}" required>
                            @include('alerts.feedback', ['field' => 'project'])
                        </div>
                        <div class="col-2 form-group">
                            <br />
                            <button type="submit" class="btn btn-primary" data-dismiss="modal">Get Data</button>
                        </div>
                        <div class="col-6 form-group"></div>
                    </div>

                </form>
                <hr />

                @if(!$result==null)
                @foreach($result as $data)
                @push('js')
                <script>
                    $('#project').val('{{ $data->major_pros_id }}');
                    $(document).ready(function() {
                        getItemlist();
                    });
                </script>
                @endpush
                <fieldset>

                    <div class="row">
                        <div class="col-4 form-group">
                            <h6 for="projectName">{{ _('Project Name') }}</h6>
                            <h5 id="projectName">{{ $data->major_pros_name }} </h5>
                        </div>
                        <div class="col-4 form-group">
                            <h6 for="projectType">{{ _('Project Document ID') }}</h6>
                            <h5 id="projectType">{{ $data->major_doc_pros_id }}</h5>
                        </div>
                        <div class="col-4 form-group">
                            <h6 for="invstatus">{{ _('Project Status') }}</h6>
                            <h5 id="invstatus">{{ $data->major_pros_status }}</h5>
                        </div>

                    </div>

                </fieldset>


                <hr />
                @if(strcmp($data->major_pros_status, "CONFIRMED") == 0 )
                <div class="row">
                    <div class="col-7 ">
                        <h6 for="usagemodel_dum">{{ _('Item') }}</h6>
                        <select name="usagemodel_dum" id="usagemodel_dum" class="form-control selectpicker" data-live-search="true">
                            <option value=""></option>
                            @if(!$items==null)
                            @foreach($items as $item)
                            <option value="{{ $item->item_code }}">{{ $item->item_code .' - '.$item->item_discription }} </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-2 form-group">
                        <h6 for="usagemodel_qty">{{ _('Quantity') }}</h6>
                        <input type="number" class="form-control" id="qty" value="" min="0" />
                    </div>
                    <div class="col-2 form-group">
                        <br />
                        <button type="button" class="btn btn-primary btn-sm add" id="addbutton">Add</button>
                    </div>
                </div>

                @endif
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover" id="item_table">
                            <thead>
                                <th>Item Code</th>
                                <th>Description</th>
                                <th>Allocate Amount</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </thead>

                        </table>
                    </div>
                </div>
                <br />

                @endforeach
                @endif




            </div>
            <div class="card-footer">

            </div>
        </div>

    </div>
</div>


@endsection

@push('css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
@endpush

@push('js')
<!-- drop down search -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {});


    function getItemlist() {
        $('#item_table').dataTable().fnClearTable();
        $('#item_table').dataTable().fnDestroy();
        $('#item_table').DataTable({
            processing: true,
            serverSide: true,
            retrieve: true,
            paging: false,
            autoWidth: false,
            ajax: {
                url: "{{ route('inventory/projectTableData') }}",
                type: 'GET',
                data: {
                    'project': $('#project').val(),
                },
            },
            columns: [{
                    data: 'pia_item_code',
                    name: 'Item Code'
                },
                {
                    data: 'item_discription',
                    name: 'Description'
                },
                {
                    data: 'input',
                    name: 'Allocate Amount'
                },
                {
                    data: 'update',
                    name: 'Update',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'delete',
                    name: 'Delete',
                    orderable: false,
                    searchable: false
                },
            ],
            order: [
                [0, 'desc']
            ]
        });
    }

    $('#addbutton').unbind('click').bind('click', function(e) {
        if (!$('#usagemodel_dum').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select Item', AlertTypes.Info);
            return;
        }
        if (!$('#qty').val()) {
            demo.showCustomNotification('top', 'center', 'Quantity cannot be Null', AlertTypes.Info);
            return;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('inventory/projectstore') }}",
            data: {
                'pia_item_code': $('#usagemodel_dum').val(),
                'pia_project_id': $('#project').val(),
                'pia_qty': $('#qty').val(),
            },
            success: function(data, textStatus, jqXHR) {
             
                if (data['responce']) {
                    $('#usagemodel_dum').val('');
                    $('#qty').val('');

                    getItemlist();

                } else {
                    demo.showCustomNotification('top', 'center', data['message'], AlertTypes.Danger);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                demo.showCustomNotification('top', 'center', 'Error occurred!' + errorThrown, AlertTypes.Danger);
            }

        });
    });


    $('#item_table').unbind('click').on('click', '.update', function () {
    var currentRow = $(this).closest("tr");
    var datax = $('#item_table').DataTable().row(currentRow).data();

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "{{ route('inventory/projectupdate') }}",
        data: {
            'pia_item_code':datax['pia_item_code'],
            'project': $('#project').val(),
            'qty': $(this).closest('tr').find('.qtyinput').val(),
        },
        success: function (data, textStatus, jqXHR) {
            if (data['responce']) {
                getItemlist()
            } else {
                demo.showCustomNotification('top', 'center', data['message'], AlertTypes.Danger);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            demo.showCustomNotification('top', 'center', 'Error occurred!' + errorThrown, AlertTypes.Danger);
        }

    });


});


$('#item_table').unbind('click').on('click', '.delete', function () {
    var currentRow = $(this).closest("tr");
    var datax = $('#item_table').DataTable().row(currentRow).data();

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "{{ route('inventory/projectdelete') }}",
        data: {
            'pia_item_code':datax['pia_item_code'],
            'project': $('#project').val()
        },
        success: function (data, textStatus, jqXHR) {
            if (data['responce']) {
                getItemlist()
            } else {
                demo.showCustomNotification('top', 'center', data['message'], AlertTypes.Danger);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            demo.showCustomNotification('top', 'center', 'Error occurred!' + errorThrown, AlertTypes.Danger);
        }

    });


});
</script>
@endpush