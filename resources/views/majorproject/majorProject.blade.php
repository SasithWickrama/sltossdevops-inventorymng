@push('js')
<script>

    function loadDropdown(fieldData){
            // console.log(fieldData)
            $('#major_pros_svtype').val(fieldData['major_pros_svtype']),
            $('#major_pros_target_enddate').val(fieldData['major_pros_target_enddate']),
            $('#major_doc_pros_id').val(fieldData['major_doc_pros_id']),
            $('#major_pros_name').val(fieldData['major_pros_name']),
            $('#major_pros_status').val(fieldData['major_pros_status'])

            // $('#sw_major_pros_svtype').text(fieldData[0][0].major_pros_svtype),
            // $('#sw_major_pros_target_enddate').text(fieldData[0][0].major_pros_target_enddate),
            // $('#sw_major_doc_pros_id').text(fieldData[0][0].major_doc_pros_id),
            // $('#sw_major_pros_name').text(fieldData[0][0].major_pros_name),
            // $('#sw_major_pros_status').text(fieldData[0][0].major_pros_status)
    }
  
</script>
@endpush

@section('content')

    <!-- loader -->
    <div id='loader' style='display: none;'>
        <h3>Loading ....</h3>
        <!-- <img src='reload.gif' width='32px' height='32px'> -->
    </div>
    <!-- loader -->

    <div id="page-content" class="row">
        <div class="col-md-12">
        
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h5 class="title">{{ $title }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body show-form" >
                    @if($page_type == 'update' )
                        <form  id="fm-search" action="{{ route('updateMajorProjectView') }}" method="POST" autocomplete="off">
                            @csrf
                            <!-- @method('put') -->
                            <!-- @include('alerts.success', ['key' => 'password_status']) -->
                            <div class="form-row align-items-center">
                                <div class="form-group{{ $errors->has('major_pros_id') ? ' has-danger' : '' }} col-md-6">
                                    <label class="h6">{{ __('Major Project') }} </label>
                                    <select class="form-control{{ $errors->has('major_pros_id') ? ' is-invalid' : '' }}" id="major_pros_id" name="major_pros_id">
                                        @if($user->USERWG == 'SLT')
                                            <option value=""></option>
                                            @if(!$projectList==null)
                                                @foreach($projectList as $major_pros_name => $major_pros_id  )
                                                    <option value="{{ $major_pros_id }}">{{ $major_pros_id }}{{ __(' - ') }}{{ $major_pros_name }}</option>
                                                @endforeach
                                            @endif
                                        @endif
                                    </select>
                                    @include('alerts.feedback', ['field' => 'major_pros_id'])
                                </div>
                                <div class="form-group col-md-4">
                                    <button type="submit" class="btn btn-primary" id="get_project_btn" >
                                        {{ __('Get Data') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                    <div id="details">
                        @if($page_type == 'create' )
                            <form method="post" action="" autocomplete="off">
                                @csrf
                                <!-- @method('put') -->
                                <!-- @include('alerts.success', ['key' => 'password_status']) -->
                                @include('majorproject.majorProjectForm',['form_type' => __('ADD')])
                        @endif
                        @if($page_type == 'update' )
                            @if(!$majorProject==null)
                                @foreach($majorProject as $data)
                                    @push('js')
                                        <script>
                                            $("#loader").hide()
                                            $("#page-content").show()
                                            $('#major_pros_id').val('{{ $data->major_pros_id }}')
                                            if('{{ $data->major_pros_status }}' == 'INPROGRESS'){
                                                $('#complete_project_btn').removeClass('d-none')
                                            }
                                            if('{{ $data->major_pros_status }}' == 'INITIATED'){
                                                console.log("okf")
                                                $('#update_project_btn').removeClass('d-none')
                                            }
                                            var fn_data = {!! json_encode($data) !!};
                                            loadDropdown(fn_data)
                                        </script>
                                    @endpush
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6>{{ __('Project Id : ') }} </h6>
                                            <h5 id="sw_major_doc_pros_id">{{ $data->major_doc_pros_id }}</h5>
                                        </div>
                                        <div class="col-sm-4">
                                            <h6>{{ __('Project Name : ') }} </h6>
                                            <h5 id="sw_major_pros_name">{{ $data->major_pros_name }}</h5>
                                        </div>
                                        <div class="col-sm-4">
                                            <h6>{{ __('Service Type : ') }}</h6>
                                            <h5 id="sw_major_pros_svtype">{{ $data->major_pros_svtype }}</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6>{{ __('Project Status : ') }}</h6>
                                            <h5 id="sw_major_pros_status">{{ $data->major_pros_status }}</h5>
                                        </div>
                                        <div class="col-sm-4">
                                            <h6>{{ __('Target End Date : ') }}</h6>
                                            <h5 id="sw_major_pros_target_enddate">{{ $data->major_pros_target_enddate }}</h5>
                                        </div>
                                    </div>
                                @endforeach
                                @if($data->major_pros_status == 'INITIATED' )
                                <div class="row">
                                    <div class="col-12 form-group">
                                        <p><small>** Please Inprogress the project to create Jobs</small></p>
                                    </div>
                                </div>
                                @endif
                                <div class="row">
                                    <div class="col-4 form-group">
                                        <!-- <button type="button" class="btn btn-primary btn-sm" id="childjobsbtn">Child Jobs</button> -->
                                    </div>
                                    <div class="col-4 form-group"> </div>
                                    <div class="col-4 form-group">
                                        <button type="button" class="btn btn-primary btn-sm float-right" id="parentjobsbtn">Parent Jobs</button>
                                    </div>
                                </div>
                            @endif
                        @endif
                        <div class="form-row float-right ">
                            @if($page_type == 'update' )
                                <button type="button" class="btn btn-primary btn-sm d-none" id="complete_project_btn">
                                    {{__('Complete Project') }}
                                </button>
                            @endif
                            <button type="button" class="btn btn-primary @if($page_type == 'update'){{ 'd-none' }}@endif" id="{{ $page_type }}_project_btn" @if($page_type == 'update') {{__('data-toggle=modal data-target=#major_pros_edit_modal') }} @endif>
                                {{ $btn_name }}
                            </button>
                        </div>
                        @if($page_type == 'create' )
                            </form>
                        @endif
                    </div>    
                </div>
                <div class="card-footer text-right">
                    
                </div>
            </div>

        </div>
    </div>
@endsection

@if($page_type == 'update' )
<!-- Edit modal -->
<div class="modal fade " id="major_pros_edit_modal" tabindex="-1" role="dialog" aria-labelledby="major_pros_edit_modal_label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="major_pros_edit_modal_label">{{ _('Edit Main Inventory') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="tim-icons icon-simple-remove"></i>
            </button>
        </div>
        <form method="post" action="" autocomplete="off">
            <div class="modal-body">
                @csrf
                <!-- @method('put') -->
                <!-- @include('alerts.success', ['key' => 'password_status']) -->
                @include('majorproject.majorProjectForm',['form_type' => __('EDIT')])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="{{ $page_type }}_save_project_btn" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
  </div>
</div>
@endif

@include('majorproject.jobListModel')

@push('css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush


@push('js')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        $("#major_pros_target_enddate").datepicker({
            dateFormat: 'yy/mm/dd',
            minDate: 0,
        });

    });
</script>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script>
    //complete project
    $('#complete_project_btn').unbind('click').bind('click', function(e) {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "PUT",
            url: "{{ route('completeMajorProject') }}",
            data: {
                'MAJOR_PROSS_ID' : $('#major_pros_id').val()
            },
            success: function(data, textStatus, jqXHR) {
                if(data['alert-type'] == 'success'){
                    alert('Project is Completed Successfully!');
                    location.reload();
                }else if(data['alert-type'] == 'fail complete'){
                    alert(data['message']);
                }else{
                    alert('Error occurred!');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error occurred!' + errorThrown);
            }

        });

    });

    //Create
    $('#create_project_btn').unbind('click').bind('click', function(e) {
        if (!$('#major_doc_pros_id').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a LEA', AlertTypes.Info);
            return;
        }
        if (!$('#major_pros_svtype').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Service Type', AlertTypes.Info);
            return;
        }
        if (!$('#major_pros_name').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Project Name', AlertTypes.Info);
            return;
        }
        if (!$('#major_pros_target_enddate').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Target End Date', AlertTypes.Info);
            return;
        }
       
       
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('createMajorProject') }}",
            data: {
                'MAJOR_PROS_SVTYPE' : $('#major_pros_svtype').val(),
                'MAJOR_PROS_TARGET_ENDDATE' : $('#major_pros_target_enddate').val(),
                'MAJOR_DOC_PROS_ID' : $('#major_doc_pros_id').val(),
                'MAJOR_PROS_NAME' : $('#major_pros_name').val()
            },
            success: function(data, textStatus, jqXHR) {
                if(data['alert-type'] == 'success'){
                    alert('Success!');
                    location.reload();
                }else{
                    alert('Error occurred!');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error occurred!' + errorThrown);
            }

        });
    });

    $('#fm-search').unbind('submit').bind('submit', function(e) {
        $("#loader").show();
        $("#page-content").hide();
    })

    //update project
    $('#update_save_project_btn').unbind('click').bind('click', function(e) {
        // console.log($('#parent_id').val())
        if (!$('#major_doc_pros_id').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a LEA', AlertTypes.Info);
            return;
        }
        if (!$('#major_pros_svtype').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Service Type', AlertTypes.Info);
            return;
        }
        if (!$('#major_pros_name').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Project Name', AlertTypes.Info);
            return;
        }
        if (!$('#major_pros_target_enddate').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Target End Date', AlertTypes.Info);
            return;
        }
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "PUT",
            url: "{{ route('updateMajorProject') }}",
            data: {
                'MAJOR_PROSS_ID' : $('#major_pros_id').val(),
                'MAJOR_PROS_SVTYPE' : $('#major_pros_svtype').val(),
                'MAJOR_PROS_TARGET_ENDDATE' : $('#major_pros_target_enddate').val(),
                'MAJOR_DOC_PROS_ID' : $('#major_doc_pros_id').val(),
                'MAJOR_PROS_NAME' : $('#major_pros_name').val(),
                'MAJOR_PROS_STATUS' : $('#major_pros_status').val(),
            },
            success: function(data, textStatus, jqXHR) {
                if(data['alert-type'] == 'success'){
                    alert(' Upadaet Success!');
                    location.reload();
                }else{
                    alert('Error occurred!');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error occurred!' + errorThrown);
            }

        });
    });

    // $('#childjobsbtn').unbind('click').bind('click', function(e) {
    //     $('jobs_list_modal_label').text('Child Jobs');
    //     updateProjectlist('MajorProjectChildJobs');
    // });
    function format(d) {
        // `d` is the original data object for the row
        return (
            '<table class="table table-hover" cellpadding="5" cellspacing="0" border="0" style="background: #f5f6fa;" id="job_record_table_child">'+
                '<thead>'+
                    '<th>LEA</th>'+
                    '<th>Job ID</th>'+
                    '<th>Name</th>'+                            
                    '<th>Service Type</th>'+                          
                    '<th>Status</th>'+
                    '<th>View</th>'+
                '</thead>'+
            '</table>'
        );
    }

    $('#parentjobsbtn').unbind('click').bind('click', function(e) {
        $('jobs_list_modal_label').text('Parent Jobs');
        updateProjectlist('MajorProjectParentJobs','MajorProjectParentChildJobs');
    });

    function updateProjectlist(myurl,childmyurl) {
        $('#job_record_table').dataTable().fnClearTable();
        $('#job_record_table').dataTable().fnDestroy();
        var job_record_table = $('#job_record_table').DataTable({
            processing: true,
            serverSide: true,
            retrieve: true,
            paging: false,
            autoWidth: false,
            ajax: {
                url: myurl,
                type: 'GET',
                data: {
                    'major_project': $('#major_pros_id').val(),
                },
            },
            columns: [
                {
                    className: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                },
                {
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
                    data: 'pros_svtype',
                    name: 'Service Type'
                },
                {
                    data: 'pros_status',
                    name: 'Status'
                },
                {
                    data: 'child_count',
                    name: 'Child Count'
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

        $('#jobs_list_modal').modal('show');

        $('#job_record_table tbody').on('click', 'td.dt-control', function () {
            console.log('awa');
            var tr = $(this).closest('tr');
            var row = job_record_table.row(tr);
    
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');

                $('#job_record_table_child').dataTable().fnClearTable();
                $('#job_record_table_child').dataTable().fnDestroy();
                $('#job_record_table_child').DataTable({
                    processing: true,
                    serverSide: true,
                    retrieve: true,
                    paging: false,
                    autoWidth: false,
                    searching: false,
                    dom: '<"top"i>rt<"bottom"flp><"clear">',
                    ajax: {
                        url: childmyurl,
                        type: 'GET',
                        data: {
                            'parent_project': row.data().pros_id,
                        },
                    },
                    columns: [
                        {
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
                            data: 'pros_svtype',
                            name: 'Service Type'
                        },
                        {
                            data: 'pros_status',
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
            }
        });

    }

</script>
@endpush