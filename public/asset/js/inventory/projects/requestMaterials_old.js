
$('#reqbtn').unbind('click').bind('click', function (e) {

    loadRequestItems();
});

$('#requestbtn').unbind('click').bind('click', function (e) {
    if (!$('#material').val()) {
        demo.showCustomNotification('top', 'center', 'Please Select a Matrial', AlertTypes.Info);
        return;
    }
    if (!$('#qty').val()) {
        demo.showCustomNotification('top', 'center', 'Quantity cannot be null', AlertTypes.Info);
        return;
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "itemRequestSave",
        data: {
            'dil_item_code': $('#material').val(),
            'dil_project_id': $('#project').val(),
            'dil_qty': $('#qty').val()
        },
        success: function (data, textStatus, jqXHR) {
          //  alert('Success!');
            $('#material').val('');
            $('#material').selectpicker('refresh');
            $('#qty').val('');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            demo.showCustomNotification('top', 'center', 'Error occurred!' + errorThrown, AlertTypes.Danger);
        }

    });
    loadRequestItems();
});


function loadRequestItems() {
    $('#item_request_table').dataTable().fnClearTable();
    $('#item_request_table').dataTable().fnDestroy();
    $('#item_request_table').DataTable({
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
            url: "itemRequestList",
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
            "render": function (data, type, row, meta) {
                $("#material option[value=\"" + row['item_code'] + "\"]").remove();
                $('#material').selectpicker('refresh');
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

        ],
        order: [
            [0, 'desc']
        ]
    });
}




$('#item_request_table').unbind('click').on('click', '.delete', function () {
    alert("swdwscd");
    var currentRow = $(this).closest("tr");
    var data = $('#item_request_table').DataTable().row(currentRow).data();
    //console.log(data['di_id']);

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "DELETE",
        url: "deleteRequest",
        data: {
            'project': $('#project').val(),
            'dil_item_code': data['item_code']
        },
        success: function (data, textStatus, jqXHR) {
            if (data['responce']) {
              //  alert(data['message']);
                loadRequestItems();
            } else {
                //alert(data['message']);
                demo.showCustomNotification('top', 'center', data['message'], AlertTypes.Danger);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            demo.showCustomNotification('top', 'center', 'Error occurred!' + errorThrown, AlertTypes.Danger);
        }

    });

});


$('#item_request_table').unbind('click').on('click', '.update', function () {
    var currentRow = $(this).closest("tr");
    var data = $('#item_request_table').DataTable().row(currentRow).data();
    //console.log(data['dil_qty']);
    // console.log($(this).closest('tr').find('.qtyinput').val());

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "PUT",
        url: "updateRequest",
        data: {
            'project': $('#project').val(),
            'qty': $(this).closest('tr').find('.qtyinput').val(),
            'dil_item_code': data['item_code']
        },
        success: function (data, textStatus, jqXHR) {
            if (data['responce']) {
               // alert(data['message']);
                loadRequestItems();
            } else {
                demo.showCustomNotification('top', 'center', data['message'], AlertTypes.Danger);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            demo.showCustomNotification('top', 'center', 'Error occurred!' + errorThrown, AlertTypes.Danger);
        }

    });


});