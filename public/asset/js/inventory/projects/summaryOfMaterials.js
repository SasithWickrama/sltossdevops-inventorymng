
$('#summarybtn').unbind('click').bind('click', function (e) {
    loadSummary();

});


function loadSummary() {
    $('#summary_table').dataTable().fnClearTable();
    $('#summary_table').dataTable().fnDestroy();
    $('#summary_table').DataTable({
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
            "render": function (data, type, row, meta) {
                return "<button type=\"button\" class=\"btn btn-primary btn-sm\" id=\"commentaddbtn\">Details</button>"  ;

            }
        },
        
        ],
        order: [
            [0, 'desc']
        ]
    });
}


$('#summary_table').unbind('click').on('click', '.btn', function () {
    var currentRow = $(this).closest("tr");
    var data = $('#summary_table').DataTable().row(currentRow).data();

    $('#usagemodel_itemid').text(data['pia_item_code']);
    $('#usagemodel_discription').text(data['item_discription']);
    $('#usagemodel_assigned').text(data['dia_finalqty']);
    $('#usagemodel_used').text(data['dia_totused']);
    $('#usagemodel_waste').text(data['dia_totwaste']);
    $('#usagemodel_coiled').text(data['dia_totcoiled']);  
    $('#depot_edit_modal_label').text("Usage Breakdown");   

    $('#usagediv').hide();
    $('#btndiv').hide();
    
    materialListTable(data['pia_item_code']);

    $('#usage_modal').modal('show');

});