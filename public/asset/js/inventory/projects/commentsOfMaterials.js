
$('#commentbtn').unbind('click').bind('click', function (e) {
    loadComments();

});


function loadComments() {
    $('#comment_table').dataTable().fnClearTable();
    $('#comment_table').dataTable().fnDestroy();
    $('#comment_table').DataTable({
        processing: true,
        serverSide: true,
        retrieve: true,
        paging: true,
        autoWidth: false,
        responsive: true,
            ajax: {
            url: "getInvComment",
            type: 'GET',
            data: {
                'project': $('#project').val(),
            },
        },
        columns: [{
            data: 'pdc_date',
            name: 'Time Stamp'
        },
        {
            data: 'pdc_user',
            name: 'User'
        },
        {
            data: 'pdc_text',
            name: 'Text'
        },
        {
            data: 'pdc_id',
            visible: false,
            searchable: false,
        },
        ],
        order: [
            [3, 'desc']
        ]
    });
}


$('#commentaddbtn').unbind('click').bind('click', function (e) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "setInvComment",
        data: {
            'projectid': $('#project').val(),
            'text': $('#text').val(),
            'comtype': $('#commtype').val(),
        },
        success: function (data, textStatus, jqXHR) {
            if (data['responce']) {
                //alert(data['message']);
                $('#text').val("");
                loadComments();
            } else {
                alert(data['message']);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error occurred!' + errorThrown);
        }

    });

});