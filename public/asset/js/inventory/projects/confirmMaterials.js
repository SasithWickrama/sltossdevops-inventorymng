$('#confbtn').unbind('click').bind('click', function (e) {
    loadConItems();
});



function loadConItems() {
    $('#item_confirm_table').dataTable().fnClearTable();
    $('#item_confirm_table').dataTable().fnDestroy();
    $('#item_confirm_table').DataTable({        
        processing: true,
        serverSide: true,
        retrieve: true,
        paging: true,
        autoWidth: false,
        responsive: true,
        dom: 'Bfrtip',
        ajax: {
            url: "itemRequestListforReserv",
            type: 'GET',
            data: {
                'project': $('#project').val()
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
            data: 'dia_initqty',
            name: 'Allocated Amount'
        },
        {
            data: 'dia_reqqty',
            name: 'Requesting Amount'
        },
        {
            "render": function (data, type, row, meta) {
                return "<input type=\"text\"  class=\"form-control qtyinput\" value=\""+row['dia_finalqty']+"\" >";

            }
        },        
        {
            data: 'save',
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


$('#item_confirm_table').unbind('click').on('click', '.btn', function () {
    var currentRow = $(this).closest("tr");
    var datax = $('#item_confirm_table').DataTable().row(currentRow).data();
console.log(datax);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "updateConfirm",
        data: {
            'dia_item_code':datax['pia_item_code'],
            'dia_project_id': $('#project').val(),
            'dia_finalqty': $(this).closest('tr').find('.qtyinput').val(),
        },
        success: function (data, textStatus, jqXHR) {
            if (data['responce']) {
                loadConItems()
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


