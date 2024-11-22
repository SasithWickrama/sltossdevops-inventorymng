<div class="modal fade" id="cprojects_modal" tabindex="-1" role="dialog" aria-labelledby="cprojects_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="cproject_modal_label">{{ _('Child Projects') }}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="tim-icons icon-simple-remove"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                <div class="table-responsive">
                    <table class="table table-hover" id="project_record_table">
                        <thead>
                            <th>LEA</th>
                            <th>Project ID</th>
                            <th>Name</th>                            
                            <th>Service Type</th>                            
                            <th>Status</th>
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