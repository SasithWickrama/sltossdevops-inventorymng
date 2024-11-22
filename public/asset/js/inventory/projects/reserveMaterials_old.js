$('#reservbtn').unbind('click').bind('click', function (e) {
    loadRequestedItems();
});







$('#model_rqmaterial').on('change', function () {
    if ($('#model_rqmaterial').val()) {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: "itemAvailableQty",
            data: {
                'item_id': $('#model_rqmaterial').val(),
            },
            success: function (data, textStatus, jqXHR) {

                $('#model_available').text(data[0].di_tot_qty - data[0].di_reserved_qty);


            },
            error: function (jqXHR, textStatus, errorThrown) {
                demo.showCustomNotification('top', 'center', 'Error occurred!' + errorThrown, AlertTypes.Danger);
                $('#model_available').text(0);
            }

        });
    }
});


function loadRequestedItems() {
    $('#item_reqforrev_table').dataTable().fnClearTable();
    $('#item_reqforrev_table').dataTable().fnDestroy();
    $('#item_reqforrev_table').DataTable({
        initComplete: function (settings, json) {
            if ($('.modal.show').length) {
                var data = $('#item_reqforrev_table').DataTable().row(parseInt($('#model_row').text())).data();
                $('#model_reservtot').text(data['dil_resv_qty']);
                $('#model_diff').text(parseFloat($('#model_reqty').text()) - parseFloat($('#model_reservtot').text()))

            }

        },
        processing: true,
        serverSide: true,
        retrieve: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
             'csv', 'excel', 'pdf'
        ],
        ajax: {
            url: "itemRequestListforReserv",
            type: 'GET',
            data: {
                'project': $('#project').val()
            },
        },
        columns: [{
            data: 'item_code',
            name: 'Item Code'
        },
        {
            data: 'item_discription',
            name: 'Description'
        },
        {
            data: 'item_messurement',
            name: 'Unit'
        },
        {
            data: 'dil_qty',
            name: 'Request Amount'
        },
        {
            data: 'dil_resv_qty',
            name: 'Allocate Amount'
        },
        {
            data: 'update',
            name: 'Update',
            orderable: false,
            searchable: false
        },
        ],
        order: [
            [0, 'desc']
        ]
    });
}


$('#item_reqforrev_table').unbind('click').on('click', '.btn', function () {
    let data = $(this).closest('tr').find('td');

    $('#model_itemid').text(data[0].innerHTML);
    $('#model_discription').text(data[1].innerHTML);
    $('#model_reqty').text(data[3].innerHTML);
    $('#model_reservtot').text(data[4].innerHTML);
    $('#model_row').text($(this).closest('tr').index());
    $('#model_diff').text(parseFloat($('#model_reqty').text()) - parseFloat($('#model_reservtot').text()))



    populateModelLotDetails(data[0].innerHTML);
    populateModelTable(data[0].innerHTML);
    $('#reserv-material_modal').modal('show');

});


function populateModelLotDetails(data) {
    $("#model_rqmaterial").empty();
    $("#model_rqmaterial").append("<option value=''></option>");

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: "itemLotList",
        data: {
            'dil_item_code': data
        },
        success: function (data, textStatus, jqXHR) {
            $.each(data, function (i, item) {
                $('#model_rqmaterial').append(
                    "<option value='" + item.di_id + "'>" + item.di_item_code + " - " + item.di_lot_no + "</option>");
            });

        },
        error: function (jqXHR, textStatus, errorThrown) {
            demo.showCustomNotification('top', 'center', 'Error occurred!' + errorThrown, AlertTypes.Danger);
        }

    });
}


function populateModelTable(data) {
    $('#item_requested_model_table').dataTable().fnClearTable();
    $('#item_requested_model_table').dataTable().fnDestroy();
    $('#item_requested_model_table').DataTable({
        processing: true,
        serverSide: true,
        retrieve: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        dom: 'Bfrtip',
        ajax: {
            url: "reservedItemList",
            type: 'GET',
            data: {
                'project': $('#project').val(),
                'dil_item_code': data
            },
        },
        columns: [{
            data: 'di_lot_no',
            name: 'Lot Number'
        },
        {
            "render": function (data, type, row, meta) {
                $("#model_rqmaterial option[value=\"" + row['di_id'] + "\"]").remove();
                //$('#model_rqmaterial').selectpicker('refresh');
                return "<input type=\"text\"  class=\"form-control qtyinput\" value=\"" + row['dil_qty'] + "\" >";

            }
        },
        {
            data: 'delete',
            name: 'Delete',
            orderable: false,
            searchable: false
        },
        {
            data: 'update',
            name: 'Update',
            orderable: false,
            searchable: false
        },
        {
            data: 'di_id',
            visible: false,
            searchable: false,
        },
        ],
        order: [
            [1, 'desc']
        ]
    });
}


$('#modelreservbtn').unbind('click').bind('click', function (e) {
    if (!$('#model_rqmaterial').val()) {
        demo.showCustomNotification('top', 'center', 'Please Select a Lot Number', AlertTypes.Info);
        return;
    }
    if (!$('#model_qty').val()) {
        demo.showCustomNotification('top', 'center', 'Quantity cannot be null', AlertTypes.Info);
        return;
    }
    if (parseFloat($('#model_qty').val()) > parseFloat($('#model_reqty').text())) {
        demo.showCustomNotification('top', 'center', 'You are Reserving more than Requested', AlertTypes.Warning);
    }

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "storeReserve",
        data: {
            'dil_di_id': $('#model_rqmaterial').val(),
            'dil_project_id': $('#project').val(),
            'dil_qty': $('#model_qty').val(),
            'dil_item_code': $('#model_itemid').text()
        },
        success: function (data, textStatus, jqXHR) {
            if (data['responce']) {
                loadRequestedItems()
                //alert(data['message']);
                populateModelLotDetails($('#model_itemid').text());
                populateModelTable($('#model_itemid').text());
            } else {
              //  alert(data['message']);
                demo.showCustomNotification('top', 'center', data['message'], AlertTypes.Danger);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            demo.showCustomNotification('top', 'center', 'Error occurred!' + errorThrown, AlertTypes.Danger);
        }

    });
});




//$('#item_requested_model_table').unbind('click').on('click', '.delete', function () {
    function requested_model_table_deleteItem(e){
    var currentRow = $(e).closest("tr");
    var data = $('#item_requested_model_table').DataTable().row(currentRow).data();

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "DELETE",
        url: "deleteReserve",
        data: {
            'project': $('#project').val(),
            'dil_item_code': data['di_id']
        },
        success: function (data, textStatus, jqXHR) {
            if (data['responce']) {
                loadRequestedItems()
                //alert(data['message']);
                populateModelLotDetails($('#model_itemid').text());
                populateModelTable($('#model_itemid').text());
            } else {
              //  alert(data['message']);
                demo.showCustomNotification('top', 'center', data['message'], AlertTypes.Danger);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            demo.showCustomNotification('top', 'center', 'Error occurred!' + errorThrown, AlertTypes.Danger);
        }

    });
    }
//});




$('#item_requested_model_table').unbind('click').on('click', '.update', function () {
    var currentRow = $(this).closest("tr");
    var data = $('#item_requested_model_table').DataTable().row(currentRow).data();

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "PUT",
        url: "updateReserve",
        data: {
            'project': $('#project').val(),
            'qty': $(this).closest('tr').find('.qtyinput').val(),
            'dil_item_code': data['di_id']
        },
        success: function (data, textStatus, jqXHR) {
            if (data['responce']) {
                loadRequestedItems()
               // alert(data['message']);
                populateModelLotDetails($('#model_itemid').text());
                populateModelTable($('#model_itemid').text());
            } else {
               // alert(data['message']);
               demo.showCustomNotification('top', 'center', data['message'], AlertTypes.Danger);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            demo.showCustomNotification('top', 'center', 'Error occurred!' + errorThrown, AlertTypes.Danger);
        }

    });

});


