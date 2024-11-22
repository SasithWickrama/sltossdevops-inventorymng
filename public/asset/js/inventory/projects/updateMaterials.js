$('#updatebtn').unbind('click').bind('click', function (e) {
    updateMaterial();
});



function updateMaterial() {
    $('#item_records_table').dataTable().fnClearTable();
    $('#item_records_table').dataTable().fnDestroy();
    $('#item_records_table').DataTable({
        processing: true,
        serverSide: true,
        retrieve: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        dom: 'Bfrtip',
        ajax: {
            url: "itemRequestListforReserv",// "projectItemRecords",
            type: 'GET',
            data: {
                'project': $('#project').val(),
                'status': $('#status').text()
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
            data: 'dia_finalqty',
            name: 'Assigned'
        },
        {
            data: 'dia_totused',
            name: 'Used'
        },
        {
            data: 'dia_totwaste',
            name: 'Wasted'
        },
        {
            data: 'dia_totcoiled',
            name: 'Coiled'
        },
        {
            "render": function (data, type, row, meta) {
                return parseFloat(row['dia_finalqty']) - parseFloat(row['dia_totused'])  
                -parseFloat(row['dia_totwaste']) -parseFloat(row['dia_totcoiled'])  ;

            }
        },
        {
            data: 'save',
            name: 'update',
            orderable: false,
            searchable: false
        },
        ],
        order: [
            [0, 'desc']
        ]
    });
}


function materialListTable(id) {
    $('#usagemodel_table').dataTable().fnClearTable();
    $('#usagemodel_table').dataTable().fnDestroy();
    $('#usagemodel_table').DataTable({
        processing: true,
        serverSide: true,
        retrieve: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        ajax: {
            url: "usageSummary",// "projectItemRecords",
            type: 'GET',
            data: {
                'project': $('#project').val(),
                'item_code': id
            },
        },
        columns: [{
            data: 'di_lot_no',
            name: 'Lot No'
        },
        {
            data: 'di_drum_no',
            name: 'Drum No'
        },
        {
            data: '\'used\'',
            name: 'Used'
        },
        {
            data: '\'waste\'',
            name: 'Wasted'
        },
        {
            data: '\'coiled\'',
            name: 'Coiled'
        },
        ],
        order: [
            [0, 'desc']
        ]
    });
}


$('#item_records_table').unbind('click').on('click', '.btn', function () {
    var currentRow = $(this).closest("tr");
    var data = $('#item_records_table').DataTable().row(currentRow).data();

    $('#usagemodel_itemid').text(data['pia_item_code']);
    $('#usagemodel_discription').text(data['item_discription']);
    $('#usagemodel_assigned').text(data['dia_finalqty']);
    $('#usagemodel_used').text(data['dia_totused']);
    $('#usagemodel_waste').text(data['dia_totwaste']);
    $('#usagemodel_coiled').text(data['dia_totcoiled']);    
    $('#usagemodel_available').text(0);
    $('#usagemodel_qty').text("");

        
    populateModelLotDetails(data['pia_item_code']);
    materialListTable(data['pia_item_code']);

    $('#usage_modal').modal('show');

});


function populateModelLotDetails(data) {
    $("#usagemodel_dum").empty();
    $("#usagemodel_dum").append("<option value=''></option>");

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: "itemLotList",
        data: {
            'dil_item_code': data,
            'depot': $('#depot').val()
        },
        success: function (data, textStatus, jqXHR) {
            $.each(data, function (i, item) {
                $('#usagemodel_dum').append(
                    "<option value='" + item.di_id + "'>" +  item.di_lot_no + "</option>");
            });

        },
        error: function (jqXHR, textStatus, errorThrown) {
            demo.showCustomNotification('top', 'center', 'Error occurred!' + errorThrown, AlertTypes.Danger);
        }

    });
}

$('#usagemodel_dum').on('change', function () {
    if ($('#usagemodel_dum').val()) {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: "itemAvailableQty",
            data: {
                'item_id': $('#usagemodel_dum').val(),
                'depot': $('#depot').val()
            },
            success: function (data, textStatus, jqXHR) {
                $('#usagemodel_available').text(data[0].di_tot_qty - data[0].di_used_qty 
                    - data[0].di_waste_qty - data[0].di_coiled_qty);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                demo.showCustomNotification('top', 'center', 'Error occurred!' + errorThrown, AlertTypes.Danger);
                $('#usagemodel_available').text(0);
            }

        });
    }
});



$('#updateusagebtn').unbind('click').on('click',  function () {
    if (!$('#usagemodel_type').val()) {
        demo.showCustomNotification('top', 'center', 'Please Select a Usage Type', AlertTypes.Info);
        return;
    }
    if (!$('#usagemodel_qty').val()) {
        demo.showCustomNotification('top', 'center', 'Quantity cannot be null', AlertTypes.Info);
        return;
    }
    if( parseFloat( $('#usagemodel_qty').val() ) > parseFloat(  $('#usagemodel_available').text())) {
        demo.showCustomNotification('top', 'center', 'Quantity cannot exceed Available Amount', AlertTypes.Info);
        return;
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "storeUpdate",
        data: {
            'dil_di_id': $('#usagemodel_dum').val(),
            'dil_project_id': $('#project').val(),
            'dil_qty': $('#usagemodel_qty').val(),
            'dil_rec_type': $('#usagemodel_type').val(),
        },
        success: function (data, textStatus, jqXHR) {
            
            $('#usagemodel_type').val('');
            $('#usagemodel_dum').val('');
            $('#usagemodel_qty').val('');
            $('#usagemodel_available').val('');
            
            updateMaterial();
            materialListTable($('#usagemodel_itemid').text()) 
        },
        error: function (jqXHR, textStatus, errorThrown) {
            demo.showCustomNotification('top', 'center', 'Error occurred!' + errorThrown, AlertTypes.Danger);
        }

    });

});