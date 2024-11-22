@push('js')
<script>
    function loadDropdown(contractor,fieldData){
        $.ajax({
                    
                url:"{{ route('getProjectDropdown') }}",
                type:"GET",
                data:{"con_name": contractor},
                beforeSend: function() {
                    if(fieldData != null){
                        $("#loader").show();
                    }
                },
                success:function (data) {
                    // console.log(data)
                    $('#parent_id').empty();
                    $('#pd_depot_id').empty();
                    $('#parent_id').append('<option value=""></option>')
                    $('#pd_depot_id').append('<option value=""></option>')
                    $.each(data.parentProjects,function(index,parent){
                        $('#parent_id').append('<option value="'+parent.pros_id+'">'+parent.pros_id+'-'+parent.pros_name+'</option>');
                    })
                    $.each(data.inventoryDepot,function(index,depot){
                        $('#pd_depot_id').append('<option value="'+depot+'">'+depot+' - '+index+'</option>');
                    })
                    if(fieldData != null){
                        // console.log(fieldData)
                        $('#pa_value').val(fieldData['contractor']),
                        $('#pros_svtype').val(fieldData['pros_svtype']),
                        $('#pros_type').val(fieldData['pros_type']),
                        $('#pros_target_enddate').val(fieldData['pros_target_enddate']),
                        $('#pros_lea').val(fieldData['pros_lea']),
                        $('#pros_name').val(fieldData['pros_name']),
                        $('#parent_id').val(fieldData['parent']),
                        $('#pd_depot_id').val(fieldData['depot']),
                        $('#pros_status').val(fieldData['pros_status']),
                        $('#pa_old_value').val(fieldData['contractor']),
                        $('#pd_status').val(fieldData['depot_status']),
                        $('#major_project_id').val(fieldData['major_project'])
                        $('#workGroupSelect').val(fieldData['contractor'])
                        $('#major_pros_id').val(fieldData['major_project'])
                        if(fieldData['pros_type'] == 'CHILD'){
                            $('#parent_id_gp').removeClass("d-none");
                            $('#parent-job-btn').removeClass('d-none')
                            $('#child-job-btn').addClass('d-none')
                        }else{
                            $('#parent_id_gp').addClass("d-none");
                            $('#child-job-btn').removeClass('d-none')
                            $('#parent-job-btn').addClass('d-none')
                        }
                        $('#major_project_id').selectpicker('refresh')
                        if(fieldData['pros_status'] == "IN-PROGESS" || fieldData['pros_status'] == "COMPLETED"){
                            $('#major_project_id').prop("disabled", true);
                            $('#pa_value').prop("disabled", true);
                            $('#pd_depot_id').prop("disabled", true);
                            $('#pros_svtype').prop("disabled", true);
                            $('#pros_type').prop("disabled", true);
                            $('#parent_id').prop("disabled", true);
                            $('#pros_status').prop("disabled", true);
                            $('#pros_lea').prop("disabled", true);
                            $('#pros_name').prop("disabled", true);
                            $('#pros_target_enddate').prop("disabled", true);
                            $('#major_project_id').selectpicker('refresh')
                        }

                    }
                    if(fieldData != null){
                        $("#loader").hide()
                        $("#page-content").show()
                        $('#details').removeClass('d-none')
                    }

                    
                },
                error: function(xhr, status, error){
                    $('#parent_id').empty();
                    $('#pd_depot_id').empty();
                    console.log("Error!" + xhr.status);
                },           
        })
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
                    <form id="fm-search" action="{{ route('updateProjectView') }}" method="POST" autocomplete="off">
                        @csrf
                        <!-- @method('put') -->
                        <!-- @include('alerts.success', ['key' => 'password_status']) -->
                        <div class="form-row align-items-center">
                            <div class="form-group{{ $errors->has('pros_id') ? ' has-danger' : '' }} col-md-6">
                                <label>{{ __('Project') }} </label>
                                <select class="form-control{{ $errors->has('pros_id') ? ' is-invalid' : '' }}" id="pros_id_id" name="pros_id">
                                    @if($user->USERWG == 'SLT')
                                        <option value=""></option>
                                        @if(!$project_list_all==null)
                                            @foreach($project_list_all as  $key => $pros )
                                                <option value="{{ $pros -> pros_id }}">{{ $pros -> pros_id }}{{ __(' - ') }}{{ $pros -> pros_name }}</option>
                                            @endforeach
                                        @endif
                                    @else
                                        <option value=""></option>
                                        @if(!$project_list_all==null)
                                            @foreach($project_list_all as  $key => $pros )
                                                @if($pros -> contractor == $user->USERWG && $pros -> pros_type == 'CHILD')
                                                    <option value="{{ $pros -> pros_id }}">{{ $pros -> pros_id }}{{ __(' - ') }}{{ $pros -> pros_name }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                </select>
                                @include('alerts.feedback', ['field' => 'pros_id'])
                            </div>
                            <div class="form-group col-md-4">
                                <button type="submit" class="btn btn-primary" id="get_project_btn" >
                                    {{ __('Get Data') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif
                    @if($page_type == 'update' )
                        @if(!$project==null)
                            @foreach($project as $data)
                                @push('js')
                                    <script>
                                        $('#pros_id_id').val('{{ $data->pros_id }}')
                                        if('{{ $data->pros_status }}' == 'IN-PROGESS'){
                                                $('#complete_job_btn').removeClass('d-none')
                                        }
                                        if('{{ $data->pros_status }}' == 'INITIATED'){
                                            console.log("okf")
                                            $('#update_project_btn').removeClass('d-none')
                                        }
                                        $('#inventory-details-btn').removeClass('d-none')
                                        $('#project-details-btn').removeClass('d-none')
                                        var fn_data = {!! json_encode($data) !!};
                                        loadDropdown(fn_data['contractor'],fn_data)
                                    </script>
                                @endpush
                            @endforeach
                        @endif
                    @endif
                    <hr>
                    <div id="details" class="@if($page_type == 'update'){{ 'd-none' }}@endif">
                        <form method="post" action="" autocomplete="off">

                            @csrf
                            <!-- @method('put') -->
                            <!-- @include('alerts.success', ['key' => 'password_status']) -->
                            <div class="form-row">
                                <div class="form-group{{ $errors->has('major_project_id') ? ' has-danger' : '' }} col-md-3">
                                    <label>{{ __('Project ID') }} </label>
                                    <select class="form-control{{ $errors->has('major_project_id') ? ' is-invalid' : '' }} selectpicker" data-live-search="true" id="major_project_id" name="major_project_id">
                                        <option value=""></option>
                                        @if(!$majorProjects==null)
                                            @foreach($majorProjects as $major_pros_id => $major_pros_name)
                                                <option value="{{ $major_pros_id  }}">{{ $major_pros_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @include('alerts.feedback', ['field' => 'major_project_id'])
                                </div>
                                <div class="form-group{{ $errors->has('pa_value') ? ' has-danger' : '' }} col-md-3">
                                    <label>{{ __('Contractor') }} </label>
                                    <select class="form-control{{ $errors->has('pa_value') ? ' is-invalid' : '' }}" id="pa_value" name="pa_value">
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
                                    @include('alerts.feedback', ['field' => 'pa_value'])
                                </div>
                                <div class="form-group{{ $errors->has('pd_depot_id') ? ' has-danger' : '' }} col-md-3">
                                    <label>{{ __('Main Inventory') }} </label>
                                    <select class="form-control{{ $errors->has('pd_depot_id') ? ' is-invalid' : '' }}" id="pd_depot_id" name="pd_depot_id">
                                        <option value=""></option>
                                    </select>
                                    @include('alerts.feedback', ['field' => 'pd_depot_id'])
                                </div>
                                <div class="form-group{{ $errors->has('pros_svtype') ? ' has-danger' : '' }} col-md-3">
                                    <label>{{ __('Service Type') }}  </label>
                                    <select class="form-control{{ $errors->has('pros_svtype') ? ' is-invalid' : '' }}" id="pros_svtype" name="pros_svtype">
                                        <option value=""></option>
                                        @if(!$serviceType==null)
                                            @foreach($serviceType as $data)
                                                <option value="{{ $data }}">{{ $data }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @include('alerts.feedback', ['field' => 'pros_svtype'])
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group{{ $errors->has('pros_type') ? ' has-danger' : '' }} col-md-4">
                                    <label>{{ __('Job Type') }} </label>
                                    <select class="form-control{{ $errors->has('pros_type') ? ' is-invalid' : '' }}" id="pros_type" name="pros_type">
                                        <option value=""></option>
                                        @if(!$projectType==null && !$user==null)
                                            @foreach($projectType as $data)
                                                @if($user->USERWG != 'SLT' && $data == 'PARENT')
                                                @else
                                                    <option value="{{ $data }}">{{ $data }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    @include('alerts.feedback', ['field' => 'pros_type'])
                                </div>
                                <div id="parent_id_gp" class="form-group{{ $errors->has('parent_id') ? ' has-danger' : '' }} col-md-4 d-none">
                                    <label>{{ __('Parent Job') }} </label>
                                    <select class="form-control{{ $errors->has('parent_id') ? ' is-invalid' : '' }}" id="parent_id" name="parent_id" >
                                        <option value=""></option>
                                    </select>
                                    @include('alerts.feedback', ['field' => 'parent_id'])
                                </div>
                                @if($page_type == 'update' )
                                <div class="form-group{{ $errors->has('pros_status') ? ' has-danger' : '' }} col-md-4">
                                    <label>{{ __('Job Status') }} </label>
                                    <select class="form-control{{ $errors->has('pros_status') ? ' is-invalid' : '' }}" id="pros_status" name="pros_status">
                                        <option value=""></option>
                                        @if(!$ProjectStatus==null)
                                            @foreach($ProjectStatus as $data)
                                                <option value="{{ $data }}">{{ $data }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @include('alerts.feedback', ['field' => 'pros_status'])
                                </div>
                                @endif
                            </div>
                            <div class="form-row">
                                <div class="form-group{{ $errors->has('pros_lea') ? ' has-danger' : '' }} col-md-4">
                                    <label>{{ __('LEA') }} </label>
                                    <select class="form-control{{ $errors->has('pros_lea') ? ' is-invalid' : '' }}" id="pros_lea" name="pros_lea">
                                        <option value=""></option>
                                        @if(!$lea==null)
                                            @foreach($lea as $data)
                                                <option value="{{ $data }}">{{ $data }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @include('alerts.feedback', ['field' => 'pros_lea'])
                                </div>
                                <div class="form-group{{ $errors->has('pros_name') ? ' has-danger' : '' }} col-md-4">
                                    <label>{{ __('Job Name') }} </label>
                                    <input type="text" name="pros_name" id="pros_name" class="form-control{{ $errors->has('pros_name') ? ' is-invalid' : '' }}"  value="" placeholder="{{ __('Project Name') }}">
                                    @include('alerts.feedback', ['field' => 'pros_name'])
                                </div>
                                <div class="form-group{{ $errors->has('pros_target_enddate') ? ' has-danger' : '' }} col-md-4">
                                    <label>{{ __('Target End Date') }} </label>
                                    <input type="text" id="pros_target_enddate" name="pros_target_enddate" class="form-control{{ $errors->has('pros_target_enddate') ? ' is-invalid' : '' }}"  value="" placeholder="{{ __('Target End Date') }}">
                                    @include('alerts.feedback', ['field' => 'pros_target_enddate'])
                                </div>
                            </div>
                            <div class="form-row d-none">
                                <div class="form-group{{ $errors->has('pa_old_value') ? ' has-danger' : '' }} col-md-4">
                                    <label>{{ __('Old Contracter') }} </label>
                                    <input type="text" name="pa_old_value" id="pa_old_value" class="form-control{{ $errors->has('pa_old_value') ? ' is-invalid' : '' }}"  value="" placeholder="{{ __('Project Name') }}">
                                    @include('alerts.feedback', ['field' => 'pa_old_value'])
                                </div>
                                <div class="form-group{{ $errors->has('pd_status') ? ' has-danger' : '' }} col-md-4">
                                    <label>{{ __('Old Contracter') }} </label>
                                    <input type="text" name="pd_status" id="pd_status" class="form-control{{ $errors->has('pd_status') ? ' is-invalid' : '' }}"  value="" placeholder="{{ __('Project Name') }}">
                                    @include('alerts.feedback', ['field' => 'pd_status'])
                                </div>
                            </div>
                            <div class="form-row justify-content-end ">
                                <button type="button" class="btn btn-primary btn-sm @if($page_type == 'update'){{ 'd-none' }}@endif" id="{{ $page_type }}_project_btn">
                                    {{ $btn_name }}
                                </button>
                            </div>
                        </form>
                        <div class="form-row justify-content-end">
                            <div class="col">
                                <button type="submit"  id="child-job-btn" class="edit btn btn-primary d-none btn-sm">{{ __('Child Jobs') }}</button>
                                <button type="submit"  id="parent-job-btn" class="edit btn btn-primary d-none btn-sm">{{ __('Parent Job') }}</button>  
                            </div>
                            <div class="col text-right">
                                @if($page_type == 'update' )
                                    <button type="button" class="btn btn-primary btn-sm d-none" id="complete_job_btn">
                                        {{__('Complete Job') }}
                                    </button>
                                @endif
                            </div>
                            <div class="col text-right">
                                <form action="{{ route('updateMajorProjectView') }}" method="POST"> 
                                    @csrf
                                    <input type="hidden" id="major_pros_id" name="major_pros_id"  value="" >
                                    <button type="submit"  id="project-details-btn" class="edit btn btn-primary d-none btn-sm">{{ __('Project Details') }}</button>
                                </form>
                            </div>
                            <div class="col text-right">
                                <form action="{{ route('maininventory') }}" method="POST"> 
                                    @csrf
                                    <input type="hidden" id="workGroupSelect" name="workGroupSelect"  value="" >
                                    <button type="submit"  id="inventory-details-btn" class="edit btn btn-primary d-none btn-sm">{{ __('Inventory Details') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">

                </div>
            </div>
        </div>
    </div>
@endsection

@include('Project.jobListModel')

@push('css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@endpush


@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        $("#pros_target_enddate").datepicker({
            dateFormat: 'yy/mm/dd',
            minDate: 0,
        });

        $('select[name="pros_type"]').on('change',function(e) {
         
            var type = e.target.value;
            if(type == 'CHILD'){
                $('#parent_id_gp').removeClass("d-none");
            }else{
                $('#parent_id_gp').addClass("d-none");
            }
            $('#parent_id').val('')

        });

        $('select[name="pa_value"]').on('change',function(e) {
         
            var contractor = e.target.value;
            var data = null;
            loadDropdown(contractor,data)
           
        });

    });
</script>
@endpush

@push('js')
<script>
    //complete job
    $('#complete_job_btn').unbind('click').bind('click', function(e) {

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "PUT",
        url: "{{ route('completeJob') }}",
        data: {
            'PROSS_ID' : $('#pros_id_id').val(),
            'PROS_TYPE' : $('#pros_type').val()
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
        if (!$('#major_project_id').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Major Project', AlertTypes.Info);
            return;
        }
        if (!$('#pa_value').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Contracter', AlertTypes.Info);
            return;
        }
        if (!$('#pd_depot_id').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Main Inventory', AlertTypes.Info);
            return;
        }
        if (!$('#pros_svtype').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Service Type', AlertTypes.Info);
            return;
        }
        if (!$('#pros_type').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Project Type', AlertTypes.Info);
            return;
        }
        if ($('#pros_type').val() == 'CHILD' && !$('#parent_id').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Parent Project', AlertTypes.Info);
            return;
        }
        if (!$('#pros_lea').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a LEA', AlertTypes.Info);
            return;
        }
        if (!$('#pros_name').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Project Name', AlertTypes.Info);
            return;
        }
        if (!$('#pros_target_enddate').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Target End Date', AlertTypes.Info);
            return;
        }
       
       
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('createProject') }}",
            data: {
                'PROS_SVTYPE' : $('#pros_svtype').val(),
                'PROS_TYPE' : $('#pros_type').val(),
                'PROS_TARGET_ENDDATE' : $('#pros_target_enddate').val(),
                'PROS_LEA' : $('#pros_lea').val(),
                'PROS_NAME' : $('#pros_name').val(),
                'PARENT_ID': $('#parent_id').val(),
                'PD_DEPOT_ID': $('#pd_depot_id').val(),
                'PA_VALUE': $('#pa_value').val(),
                'MAJOR_PROJECT_ID': $('#major_project_id').val()
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
    $('#update_project_btn').unbind('click').bind('click', function(e) {
        // console.log($('#parent_id').val())
        if (!$('#major_project_id').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Major Project', AlertTypes.Info);
            return;
        }
        if (!$('#pa_value').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Contracter', AlertTypes.Info);
            return;
        }
        if (!$('#pd_depot_id').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Main Inventory', AlertTypes.Info);
            return;
        }
        if (!$('#pros_svtype').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Service Type', AlertTypes.Info);
            return;
        }
        if (!$('#pros_type').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Project Type', AlertTypes.Info);
            return;
        }
        if ($('#pros_type').val() == 'CHILD' && !$('#parent_id').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Parent Project', AlertTypes.Info);
            return;
        }
        if (!$('#pros_lea').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a LEA', AlertTypes.Info);
            return;
        }
        if (!$('#pros_name').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Project Name', AlertTypes.Info);
            return;
        }
        if (!$('#pros_target_enddate').val()) {
            demo.showCustomNotification('top', 'center', 'Please Select a Target End Date', AlertTypes.Info);
            return;
        }
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "PUT",
            url: "{{ route('updateProject') }}",
            data: {
                'PROSS_ID' : $('#pros_id_id').val(),
                'PROS_SVTYPE' : $('#pros_svtype').val(),
                'PROS_TYPE' : $('#pros_type').val(),
                'PROS_TARGET_ENDDATE' : $('#pros_target_enddate').val(),
                'PROS_LEA' : $('#pros_lea').val(),
                'PROS_NAME' : $('#pros_name').val(),
                'PARENT_ID': $('#parent_id').val(),
                'PD_DEPOT_ID': $('#pd_depot_id').val(),
                'PA_VALUE': $('#pa_value').val(),
                'PROS_STATUS': $('#pros_status').val(),
                'PA_OLD_VALUE': $('#pa_old_value').val(),
                'PD_STATUS': $('#pd_status').val(),
                'MAJOR_PROJECT_ID': $('#major_project_id').val()

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


    $('#parent-job-btn').unbind('click').bind('click', function(e) {
        $('#jobs_list_modal_label').text('Parent Job');
        updateProjectlist('jobList');
    });

    $('#child-job-btn').unbind('click').bind('click', function(e) {
        $('#jobs_list_modal_label').text('Child Job');
        updateProjectlist('jobList');
    });


    function updateProjectlist(myurl) {
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
                    'project': $('#pros_id_id').val(),
                    'project_type': $('#pros_type').val(),
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

        $('#jobs_list_modal').modal('show');

    }

</script>
@endpush