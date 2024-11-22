<div class="modal fade" id="reserv-material_modal" tabindex="-1" role="dialog" aria-labelledby="reserv-material_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="depot_edit_modal_label">{{ _('Allocate Matreials') }}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="tim-icons icon-simple-remove"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-2 form-group">
                        <h6 for="model_itemid">{{ _('Item ID') }}</h6>
                        <h5 id="model_itemid"></h5>
                    </div>
                    <div class="col-6 form-group">
                        <h6 for="model_discription">{{ _('Description') }}</h6>
                        <h5 id="model_discription"></h5>
                    </div>
                    <div class="col-4 form-group">

                    </div>
                </div>

                <div class="row">
                    <div class="col-4 form-group">
                        <h6 for="model_reqty">{{ _('Request Amount') }}</h6>
                        <h5 id="model_reqty"></h5>
                    </div>
                    <div class="col-4 form-group">
                        <h6 for="model_Allocatetot">{{ _('Allocate Total Amount') }}</h6>
                        <h5 id="model_reservtot"></h5>
                        <h5 id="model_row" hidden></h5>
                    </div>
                    <div class="col-4 form-group">
                        <h6 for="model_diff">{{ _('Diffrence') }}</h6>
                        <h5 id="model_diff"></h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 form-group">
                        <h6 for="contractor">{{ _('Lot Number') }}</h6>
                        <select name="model_rqmaterial" id="model_rqmaterial" class="form-control" data-live-search="true">
                            <option value=""></option>

                        </select>
                    </div>
                    <div class="col-2 form-group">
                        <h6 for="model_available">{{ _('Available Qty') }}</h6>
                        <h5 id="model_available"></h5>
                    </div>

                    <div class="col-2 form-group">
                        <h6 for="model_qty">{{ _('Allocated Qty') }}</h6>
                        <input type="number" class="form-control" id="model_qty" value="" />
                    </div>
                    <div class="col-2 form-group">
                        <br />
                        <button type="button" class="btn btn-primary btn-sm" id="modelreservbtn">Allocate</button>
                    </div>
                </div>
                <br />

                <div class="row">
                    <div class="col-12 form-group">
                        <div class="table-responsive">
                            <table class="table table-hover" id="item_requested_model_table">
                                <thead>
                                    <th>Lot Number</th>
                                    <th>Amount</th>
                                    <th>Delete</th>
                                    <th>Update</th>
                                    <th>ID</th>
                                </thead>

                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer justify-content-md-end">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



@push('js')

@endpush