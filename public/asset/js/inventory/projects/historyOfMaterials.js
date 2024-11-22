
    $('#historybtn').unbind('click').bind('click', function(e) {
        loadHistory();
        
    });


    function loadHistory(){
        $('#item_history_records_table').dataTable().fnClearTable();
        $('#item_history_records_table').dataTable().fnDestroy();
        $('#item_history_records_table').DataTable({
            processing: true,
            serverSide: true,
            retrieve: true,
            paging: true,
            autoWidth: false,            
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            ajax: {
                url: "itemHistory",
                type: 'GET',
                data: {
                    'project': $('#project').val()
                },
            },
            columns: [{
                    data: 'dil_update_date',
                    name: 'Date'
                },
                {
                    data: 'item code',
                    name: 'Item Code'
                },
                {
                    data: 'discription',
                    name: 'Description'
                },
                {
                    data: 'lot number',
                    name: 'Lot Number'
                },
                {
                    data: 'measuerment',
                    name: 'Unit'
                },
                {
                    data: 'dil_rec_type',
                    name: 'Operation'
                },
                {
                    data: 'dil_qty',
                    name: 'Amount'
                },
                {
                    data: 'dil_update_by',
                    name: 'User'
                }
            ],
            order: [
                [0, 'desc']
            ]
        });
    }