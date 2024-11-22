@extends('layouts.app', ['page' => __('Inventory Job Data'), 'pageSlug' => 'inventoryProject'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">{{ _('Job Inventory Details') }}</h5>
            </div>

            <div class="card-body">

                <form action="{{ route('project') }}" method="POST" autocomplete="off" id="target">
                    @csrf
                    <div class="row">
                        <div class="col-4 form-group{{ $errors->has('project') ? ' has-danger' : '' }}">
                            <h6 for="project">{{ _('Job ID') }}</h6>
                            <input type="text" name="project" id="project" class="form-control{{ $errors->has('project') ? ' is-invalid' : '' }}" value="" placeholder="{{ __('Job ID') }}" required>
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
                    $('#project').val('{{ $data->pros_id }}');
                </script>
                @endpush
                <fieldset>
                    <input type="text" name="depot" id="depot" class="form-control" value="{{ $depot->pd_depot_id }}" hidden>

                    <div class="row">
                        <div class="col-5 form-group">
                            <h6 for="projectName">{{ _('Job Name') }}</h6>
                            <h5 id="projectName">{{ $data->pros_name }} </h5>
                        </div>
                        <div class="col-3 form-group">
                            <h6 for="projectType">{{ _('Job Type') }}</h6>
                            <h5 id="projectType">{{ $data->pros_type }}</h5>
                        </div>
                        <div class="col-2 form-group">
                            <h6 for="serviceType">{{ _('Service Type') }}</h6>
                            <h5 id="serviceType">{{ $data->pros_svtype }}</h5>
                        </div>
                        <div class="col-2 form-group">
                            <h6 for="lea">{{ _('LEA') }}</h6>
                            <h5 id="lea">{{ $data->pros_lea }}</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4 form-group">
                            <h6 for="startDate">{{ _('Start Date') }}</h6>
                            <h5 id="startDate">{{ $data->pros_createdate }}</h5>
                        </div>
                        <div class="col-4 form-group">
                            <h6 for="status">{{ _('Status') }}</h6>
                            <h5 id="status">{{ $data->pros_status }}</h5>
                        </div>
                        <div class="col-4 form-group">
                            <h6 for="statusDate">{{ _('Status Date') }}</h6>
                            <h5 id="statusDate">{{ $data->pros_statusdate }}</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4 form-group">
                            <h6 for="contractor">{{ _('Contractor') }}</h6>
                            <h5 id="contractor">
                                @if(!$contractor==null)
                                {{ _($contractor->pa_value) }}
                                @endif
                            </h5>
                        </div>
                        <div class="col-3 form-group">
                            <h6 for="invstatus">{{ _('Job Inventory Status') }}</h6>
                            <h5 id="invstatus">
                                @if(!$invstatus==null)
                                {{ _($invstatus[0]->pd_status) }}
                                @endif
                            </h5>
                        </div>
                        <div class="col-2 form-group">
                            @if(!$result==null)
                            @if(!$invstatus==null)
                            @if(!strcmp($invstatus[0]->pd_status, "SYN_ERP") == 0 && !strcmp($result[0]->pros_type, "CHILD") == 0)
                            @if(!strcmp($invstatus[0]->pd_status, "FORZEN") == 0)
                            @if(strcmp($user->USERWG, "SLT") == 0 && strcmp($invstatus[0]->permission, "SLT") == 0 )
                            <button type="button" class="btn btn-primary btn-sm" id="proceed">Complete</button>
                            @endif
                            @if(!strcmp($user->USERWG, "SLT") == 0 && strcmp($invstatus[0]->permission, "") == 0 )
                            <button type="button" class="btn btn-primary btn-sm" id="proceed">Complete</button>
                            @endif
                            @endif
                            @endif
                            @endif
                            @endif
                        </div>
                        <div class="col-2 form-group">
                            @if(!$invstatus==null)
                            @if(strcmp($user->USERWG, "SLT") == 0 && strcmp($invstatus[0]->pd_status, "CHK_MATERIAL") == 0 && $invstatus[0]->pd_check_count < 2 )==0 ) <button type="button" class="btn btn-danger btn-sm" id="rejectbtn">Reject</button>
                                @endif
                                @endif
                        </div>
                    </div>

                </fieldset>

                <br />
                <div class="row">
                    <div class="col-4 form-group">
                        <button type="button" class="btn btn-primary btn-sm" id="childProjectsbtn">Child Job</button>
                    </div>
                    <div class="col-4 form-group">
                        <button type="button" class="btn btn-primary btn-sm" id="parentProjectsbtn">Parent Job</button>
                    </div>
                    <div class="col-4 form-group">
                        @if(!$invstatus==null)
                        @if(strcmp($user->USERWG, "SLT") == 0 && !strcmp($invstatus[0]->pd_status, "FORZEN") == 0 )
                        <button type="button" class="btn btn-danger btn-sm" id="proceedfreez">Freeze Inventory</button>
                        @endif
                        @if(strcmp($user->USERWG, "SLT") == 0 && strcmp($invstatus[0]->pd_status, "FORZEN") == 0)
                        <button type="button" class="btn btn-primary btn-sm" id="proceed">Activate Inventory</button>
                        @endif
                        @endif
                    </div>
                </div>

                @endforeach
                @endif



                <br />
                @if (!$invstatus==null)
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-4" id="historybtn">History of Materials</a></li>
                        <li><a href="#tabs-5" id="summarybtn">Summary of Materials</a></li>
                        <li><a href="#tabs-6" id="commentbtn">Comments</a></li>

                        @if(!strcmp($user->USERWG, "SLT") == 0 )
                        @if(strcmp($invstatus[0]->pd_status, "REQ_MATERIAL") == 0 )
                        <li><a href="#tabs-1" id="reqbtn">Request Matreials</a></li>
                        @endif
                        @endif
                        @if(strcmp($user->USERWG, "SLT") == 0 )
                        @if(strcmp($invstatus[0]->pd_status, "RES_MATERIAL") == 0 )
                        <li><a href="#tabs-2" id="reservbtn">Allocate Materials</a></li>
                        @endif
                        @endif
                        @if(strcmp($user->USERWG, "SLT") == 0 )
                        @if(strcmp($invstatus[0]->pd_status, "CON_MATERIAL") == 0 )
                        <li><a href="#tabs-7" id="confbtn">Confirm Matreials</a></li>
                        @endif
                        @endif
                        @if(!strcmp($user->USERWG, "SLT") == 0 )
                        @if(strcmp($invstatus[0]->pd_status, "UPD_MATERIAL") == 0 )
                        <li><a href="#tabs-3" id="updatebtn">Update Materials</a></li>
                        @endif
                        @endif
                    </ul>
                    @if(strcmp($user->USERWG, "SLT") == 0 )
                    @if(strcmp($invstatus[0]->pd_status, "RES_MATERIAL") == 0 )
                    <div id="tabs-2">
                        <div class="row">
                            <div class="col-12 form-group">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="item_request_table">
                                        <thead>
                                            <th>Item Code</th>
                                            <th>Description</th>
                                            <th>Approved Total Amount</th>
                                            <th>Allocatable Amount</th>
                                            <th>Allocate Amount</th>
                                            <th>Update</th>
                                        </thead>

                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    @endif
                    @endif
                    @if(!strcmp($user->USERWG, "SLT") == 0 )
                    @if(strcmp($invstatus[0]->pd_status, "REQ_MATERIAL") == 0 )
                    <div id="tabs-1">
                        <div class="table-responsive">
                            <table class="table table-hover" id="item_reqforrev_table">
                                <thead>
                                    <th>Item Code</th>
                                    <th>Description</th>
                                    <th>Allocate Amount</th>
                                    <th>Requesting Amount</th>
                                    <th>Update</th>
                                </thead>

                            </table>
                        </div>
                    </div>
                    @endif
                    @endif
                    @if(!strcmp($user->USERWG, "SLT") == 0 )
                    @if(strcmp($invstatus[0]->pd_status, "UPD_MATERIAL") == 0 )
                    <div id="tabs-3">
                        <div class="table-responsive">
                            <table class="table table-hover" id="item_records_table">
                                <thead>
                                    <th>Item Code</th>
                                    <th>Description</th>
                                    <th>Assigned</th>
                                    <th>Used</th>
                                    <th>Wasted</th>
                                    <th>Coiled</th>
                                    <th>Remaining</th>
                                    <th>Update</th>
                                </thead>

                            </table>
                        </div>
                    </div>
                    @endif
                    @endif
                    <div id="tabs-4">
                        <div class="table-responsive">
                            <table class="table table-hover" id="item_history_records_table">
                                <thead>
                                    <th>Date</th>
                                    <th>Item Code</th>
                                    <th>Description</th>
                                    <th>Lot Number</th>
                                    <th>Unit</th>
                                    <th>Operation</th>
                                    <th>Amount</th>
                                    <th>User</th>
                                </thead>

                            </table>
                        </div>
                    </div>
                    <div id="tabs-5">
                        <div class="table-responsive">
                            <table class="table table-hover" id="summary_table">
                                <thead>
                                    <th>Item Code</th>
                                    <th>Description</th>
                                    <th>Assigned</th>
                                    <th>Used</th>
                                    <th>Wasted</th>
                                    <th>Coiled</th>
                                    <th>Remaining</th>
                                    <th></th>
                                </thead>

                            </table>
                        </div>
                    </div>
                    <div id="tabs-6">
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
                    @if(strcmp($user->USERWG, "SLT") == 0 )
                    @if(strcmp($invstatus[0]->pd_status, "CON_MATERIAL") == 0 )
                    <div id="tabs-7">

                        <div class="row">
                            <div class="col-12 form-group">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="item_confirm_table">
                                        <thead>
                                            <th>Item Code</th>
                                            <th>Description</th>
                                            <th>Allocated Amount</th>
                                            <th>Requesting Amount</th>
                                            <th>Confirm Amount</th>
                                            <th>Update</th>
                                        </thead>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>


                @endif
            </div>
            <div class="card-footer">

            </div>
        </div>

    </div>
</div>


@include('Inventory.Project.usageModel')
@include('Inventory.Project.cProjectsModel')
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

<script src="{{asset('asset/js/inventory/projects/reserveMaterials.js')}}"></script>
<script src="{{asset('asset/js/inventory/projects/historyOfMaterials.js')}}"></script>
<script src="{{asset('asset/js/inventory/projects/requestMaterials.js')}}"></script>
<script src="{{asset('asset/js/inventory/projects/confirmMaterials.js')}}"></script>
<script src="{{asset('asset/js/inventory/projects/updateMaterials.js')}}"></script>
<script src="{{asset('asset/js/inventory/projects/summaryOfMaterials.js')}}"></script>
<script src="{{asset('asset/js/inventory/projects/commentsOfMaterials.js')}}"></script>
<script>
    $(document).ready(function() {
        $("#tabs").tabs();
        loadHistory();
    });

    $('#childProjectsbtn').unbind('click').bind('click', function(e) {
        $('cproject_modal_label').text('Child Projects');
        updateProjectlist('childProjects');
    });

    $('#parentProjectsbtn').unbind('click').bind('click', function(e) {
        $('cproject_modal_label').text('Parent Projects');
        updateProjectlist('parentProjects');
    });

    $('#proceed').unbind('click').bind('click', function(e) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('changeInvStatus') }}",
            data: {
                'project': $('#project').val(),
                'invstatus': $('#invstatus').text(),
                'fozen': "",
                'reasign': "",
                'type': $('#projectType').val(),
            },
            success: function(data, textStatus, jqXHR) {
                if (data['responce']) {
                    $("#target").submit();
                } else {
                    alert(data['message']);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error occurred!' + errorThrown);
            }

        });
    });




    $('#proceedfreez').unbind('click').bind('click', function(e) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('changeInvStatus') }}",
            data: {
                'project': $('#project').val(),
                'invstatus': $('#invstatus').text(),
                'fozen': "true",
                'reasign': "",
                'type': $('#projectType').val(),
            },
            success: function(data, textStatus, jqXHR) {
                if (data['responce']) {
                    $("#target").submit();
                } else {
                    alert(data['message']);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error occurred!' + errorThrown);
            }

        });
    });



    $('#rejectbtn').unbind('click').bind('click', function(e) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('changeInvStatus') }}",
            data: {
                'project': $('#project').val(),
                'invstatus': $('#invstatus').text(),
                'fozen': "",
                'reasign': "true",
                'type': $('#projectType').val(),
            },
            success: function(data, textStatus, jqXHR) {
                if (data['responce']) {
                    $("#target").submit();
                } else {
                    alert(data['message']);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error occurred!' + errorThrown);
            }

        });
    });




    function updateProjectlist(myurl) {
        $('#project_record_table').dataTable().fnClearTable();
        $('#project_record_table').dataTable().fnDestroy();
        $('#project_record_table').DataTable({
            processing: true,
            serverSide: true,
            retrieve: true,
            paging: false,
            autoWidth: false,
            ajax: {
                url: myurl,
                type: 'GET',
                data: {
                    'project': $('#project').val(),
                    'status': $('#status').text()
                },
            },
            columns: [{
                    data: 'pros_lea',
                    name: 'LEA'
                },
                {
                    data: 'pros_id',
                    name: 'Project ID'
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

        $('#cprojects_modal').modal('show');
    }
</script>
@endpush