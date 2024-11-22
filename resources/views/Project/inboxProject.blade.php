@extends('layouts.app', ['page' => __('Job Inbox'), 'pageSlug' => 'jobInbox'])

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
                            <h6 for="contractor">{{ _('Contractor') }}</h6>
                            <select name="contractor" id="contractor" class="form-control" required>
                                @if(!strcmp($user->USERWG, "SLT") == 0 )
                                <option value="{{ $user->USERWG }}">{{ $user->USERWG }}</option>
                                @else

                                <option value=""></option>
                                @if(!$contractors==null)
                                @foreach($contractors as $data)
                                <option value="{{ $data }}">{{ $data }}</option>
                                @endforeach
                                @endif

                                @endif
                            </select>
                            @include('alerts.feedback', ['field' => 'contractor'])
                        </div>
                        <div class="col-3">
                            <h6 for="status">{{ _('Job Status') }}</h6>
                            <select name="status" id="status" class="form-control">
                                <option value=""></option>
                                @if(!$projectstatus==null)
                                @foreach($projectstatus as $data)
                                <option value="{{ $data }}">{{ $data }}</option>
                                @endforeach
                                @endif
                            </select>
                            @include('alerts.feedback', ['field' => 'status'])
                        </div>
                        <div class="col-3">
                            <h6 for="lea">{{ _('LEA') }}</h6>
                            <select name="lea" id="lea" class="form-control">
                                <option value=""></option>
                                @if(!$lea==null)
                                @foreach($lea as $data)
                                <option value="{{ $data }}">{{ $data }}</option>
                                @endforeach
                                @endif
                            </select>
                            @include('alerts.feedback', ['field' => 'lea'])
                        </div>
                        <div class="col-3">
                            <h6 for="service_type">{{ _('Service Type') }}</h6>
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
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-3">
                            <h6 for="type">{{ _('Job Type') }}</h6>
                            <select name="type" id="type" class="form-control">
                                <option value=""></option>
                                @if(!$jobType==null)
                                @foreach($jobType as $data)
                                <option value="{{ $data }}">{{ $data }}</option>
                                @endforeach
                                @endif
                            </select>
                            @include('alerts.feedback', ['field' => 'type'])
                        </div>
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
                            <th>LEA</th>
                            <th>Job ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Service Type</th>
                            <th>Start Date</th>
                            <th>Status</th>
                            <th>Contractor</th>
                            <th>Project Name</th>
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
        if ($('#contractor').val() == "") {
            demo.showCustomNotification('top', 'center', 'Please Select a Contractor', AlertTypes.Info);
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
                url: "{{ route('jobRecords') }}",
                type: 'GET',
                data: {
                    'contractor': $('#contractor').val(),
                    'lea': $('#lea').val(),
                    'status': $('#status').val(),
                    'service_type': $('#service_type').val(),
                    'type': $('#type').val(),
                    'startdate': $('#startdate').val(),
                    'enddate': $('#enddate').val(),
                },
            },
            columns: [{
                    data: 'pros_lea',
                    name: 'LEA'
                },
                {
                    data: 'pros_id',
                    name: 'Job ID'
                },
                {
                    data: 'pros_name',
                    name: 'Name'
                },
                {
                    data: 'pros_type',
                    name: 'Type'
                },
                {
                    data: 'pros_svtype',
                    name: 'Service Type'
                },
                {
                    data: 'pros_createdate',
                    name: 'Start Date'
                },
                {
                    data: 'pros_status',
                    name: 'Status'
                },
                {
                    data: 'contractor',
                    name: 'Contractor'
                },
                {
                    data: 'projectName',
                    name: 'Project Name'
                },
                {
                    data: 'view',
                    name: 'View',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [0, 'desc']
            ]
        });



    });
</script>
@endpush