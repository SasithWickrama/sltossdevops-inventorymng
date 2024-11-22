@extends('layouts.app', ['page' => __('Project Inbox'), 'pageSlug' => 'majorProjectInbox'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">{{ _('Job Inbox') }}</h5>
            </div>

            <div class="card-body">

                <form action="" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-3">
                            <h6 for="project_status">{{ _('Project Status') }}</h6>
                            <select name="project_status" id="project_status" class="form-control">
                                <option value=""></option>
                                @if(!$proStatus==null)
                                @foreach($proStatus as $data)
                                <option value="{{ $data }}">{{ $data }}</option>
                                @endforeach
                                @endif
                            </select>
                            @include('alerts.feedback', ['field' => 'project_status'])
                        </div>
                        <div class="col-3">
                            <h6 for="service_type">{{ _('Project Service Type') }}</h6>
                            <select name="service_type" id="service_type" class="form-control">
                                <option value=""></option>
                                @if(!$serviceType==null)
                                @foreach($serviceType as $data)
                                <option value="{{ $data }}">{{ $data }}</option>
                                @endforeach
                                @endif
                            </select>
                            @include('alerts.feedback', ['field' => 'service_type'])
                        </div>
                        <div class="col-3">
                            <h6 for="doc_pros_id">{{ _('Document Pro ID') }}</h6>
                            <select name="doc_pros_id" id="doc_pros_id" class="form-control">
                                <option value=""></option>
                                @if(!$docProsId==null)
                                @foreach($docProsId as $data)
                                <option value="{{ $data }}">{{ $data }}</option>
                                @endforeach
                                @endif
                            </select>
                            @include('alerts.feedback', ['field' => 'doc_pros_id'])
                        </div>
                        <div class="col-3 form-group"></div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-3">
                            <h6 for="startdate">{{ _('Job Start From') }}</h6>
                            <input type='text' class="form-control" name="startdate" id="startdate" />
                            @include('alerts.feedback', ['field' => 'startdate'])
                        </div>
                        <div class="col-3">
                            <h6 for="enddate">{{ _('Job Start To') }}</h6>
                            <input type="text" name="enddate" id="enddate" class="form-control" value="">
                            </select>
                            @include('alerts.feedback', ['field' => 'enddate'])
                        </div>
                        <div class="col-3 form-group"></div>
                        <div class="col-3 form-group">
                            <br />
                            <button type="button" id="getdatabtn" class="btn btn-primary btn-sm" data-dismiss="modal">Get Data</button>
                        </div>

                    </div>

                </form>

                <br />

                <div class="table-responsive">
                    <table class="table table-hover" id="project_record_table">
                        <thead>
                            <th>Project ID</th>
                            <th>Name</th>
                            <th>Service Type</th>
                            <th>Start Date</th>
                            <th>Status</th>
                            <th>View</th>
                        </thead>

                    </table>
                </div>


            </div>
            <div class="card-footer">

            </div>
        </div>

    </div>
</div>

@endsection

@push('css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
@endpush

@push('js')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>


<script>
    $(document).ready(function() {
        $("#startdate").datepicker({
            dateFormat: 'yy/mm/dd',
            maxDate: 0,
            minDate: new Date("2022/07/01"),
        });
        $("#enddate").datepicker({
            dateFormat: 'yy/mm/dd',
            maxDate: 0,
            minDate: new Date("2022/07/01"),
        });

    });

    $('#getdatabtn').unbind('click').bind('click', function(e) {
        if ($('#project_status').val() == "" && $('#service_type').val() == "" && $('#doc_pros_id').val() == "" && $('#startdate').val() == "" && $('#enddate').val() == "" ) {
            demo.showCustomNotification('top', 'center', 'Please Select  at least one field', AlertTypes.Info);
            return;
        }

        $('#project_record_table').dataTable().fnClearTable();
        $('#project_record_table').dataTable().fnDestroy();

        $('#project_record_table').DataTable({
            processing: true,
            serverSide: true,
            retrieve: true,
            paging: true,
            autoWidth: false,
            responsive: true,
            dom: 'Bfrtip',
            ajax: {
                url: "{{ route('majorProjectRecords') }}",
                type: 'GET',
                data: {
                    'project_status': $('#project_status').val(),
                    'service_type': $('#service_type').val(),
                    'doc_pros_id': $('#doc_pros_id').val(),
                    'startdate': $('#startdate').val(),
                    'enddate': $('#enddate').val(),
                },
            },
            columns: [{
                    data: 'major_doc_pros_id',
                    name: 'Project ID'
                },
                {
                    data: 'major_pros_name',
                    name: 'Name'
                },
                {
                    data: 'major_pros_svtype',
                    name: 'Service Type'
                },
                {
                    data: 'major_pros_createdate',
                    name: 'Start Date'
                },
                {
                    data: 'major_pros_status',
                    name: 'Status'
                },
                {
                    data: 'view',
                    name: 'View',
                    orderable: false,
                    searchable: false
                },
            ],
            order: [
                [0, 'desc']
            ]
        });



    });
</script>
@endpush