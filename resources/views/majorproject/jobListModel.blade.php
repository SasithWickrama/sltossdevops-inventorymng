<div class="modal fade" id="jobs_list_modal" tabindex="-1" role="dialog" aria-labelledby="jobs_list_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="jobs_list_modal_label">{{ _('Parent Jobs') }}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="tim-icons icon-simple-remove"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover" id="job_record_table">
                            <thead>
                                <th></th>
                                <th>LEA</th>
                                <th>Job ID</th>
                                <th>Name</th>                            
                                <th>Service Type</th>                            
                                <th>Status</th>
                                <th>Child Count</th>
                                <th>View</th>
                            </thead>

                        </table>
                    </div>
                </div>
                <br />
            </div>
            <div class="modal-footer justify-content-md-end">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>