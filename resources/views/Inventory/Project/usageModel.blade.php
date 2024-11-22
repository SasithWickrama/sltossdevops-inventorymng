<div class="modal fade" id="usage_modal" tabindex="-1" role="dialog" aria-labelledby="usage_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="depot_edit_modal_label">{{ _('Update Usage') }}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="tim-icons icon-simple-remove"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('project') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-2 form-group">
                            <h6 for="usagemodel_itemid">{{ _('Item ID') }}</h6>
                            <h5 id="usagemodel_itemid"></h5>
                            <input class="form-control" id="usagemodel_diid" value="" hidden />
                        </div>
                        <div class="col-6 form-group">
                            <h6 for="usagemodel_discription">{{ _('Description') }}</h6>
                            <h5 id="usagemodel_discription"></h5>
                        </div>
                        <div class="col-4 form-group">
                            <!-- <h6 for="usagemodel_lotno">{{ _('Lot Number') }}</h6>
                            <h5 id="usagemodel_lotno"></h5> -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3 form-group">
                            <h6 for="usagemodel_assigned">{{ _('Assigned') }}</h6>
                            <h5 id="usagemodel_assigned"></h5>
                        </div>
                        <div class="col-3 form-group">
                            <h6 for="usagemodel_used">{{ _('Total Used') }}</h6>
                            <h5 id="usagemodel_used"></h5>
                        </div>
                        <div class="col-3 form-group">
                            <h6 for="usagemodel_waste">{{ _('Total Waste') }}</h6>
                            <h5 id="usagemodel_waste"></h5>
                        </div>
                        <div class="col-3 form-group">
                            <h6 for="usagemodel_coiled">{{ _('Total Coiled') }}</h6>
                            <h5 id="usagemodel_coiled"></h5>
                        </div>
                    </div>

                    <div class="row" id="usagediv">
                        <div class="col-2 form-group{{ $errors->has('project') ? ' has-danger' : '' }}">
                            <h6 for="usagemodel_type">{{ _('Usage Type') }}</h6>
                            <select name="usagemodel_type" id="usagemodel_type" class="form-control" data-live-search="true">
                                <option value=""></option>
                                <option value="USED">USED</option>
                                <option value="WASTE">WASTE</option>
                                <option value="COILED">COILED</option>
                            </select>
                        </div>
                        <div class="col-5 ">
                            <h6 for="usagemodel_dum">{{ _('Lot Number') }}</h6>
                            <select name="usagemodel_dum" id="usagemodel_dum" class="form-control" data-live-search="true">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-2 form-group">
                            <h6 for="usagemodel_available">{{ _('Available') }}</h6>
                            <h5 id="usagemodel_available"></h5>
                        </div>
                        <div class="col-2 form-group">
                            <h6 for="usagemodel_qty">{{ _('Quantity') }}</h6>
                            <input type="number" class="form-control" id="usagemodel_qty" value="" />
                        </div>
                    </div>
                    <div class="row" id="btndiv">
                    <div class="col-10 form-group"></div>
                        <div class="col-2 form-group">
                            <br />
                            <button type="button" class="btn btn-primary btn-sm update" id="updateusagebtn">Update</button>
                        </div>

                    </div>

                    <div class="row">
                    <div class="table-responsive">
                            <table class="table table-hover" id="usagemodel_table">
                                <thead>
                                    <th>Lot No</th>
                                    <th>Dum No</th>
                                    <th>Used</th>
                                    <th>Wasted</th>
                                    <th>Coiled</th>
                                </thead>

                            </table>
                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer justify-content-md-end">
                <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>