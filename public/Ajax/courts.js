
url = "/admin/config/courts";

var source =
{
    datatype: "json",
    datafields: [
        { name: 'name',                         type: 'string' },
        { name: 'indor',                        type: 'string' },
        { name: 'start_time',                   type: 'date' },
        { name: 'end_time',                     type: 'date' },
        { name: 'booking_window_member',        type: 'int' },
        { name: 'booking_window_not_member',    type: 'int' },
        { name: 'created_at',                   type: 'date' },
        { name: 'updated_at',                   type: 'date' },
    ],
    id: 'id',
    url: url,
    root: 'data'
};

// Disabled editing row
//---------------------
rowEdit = function (row) {
    return false;
}
///////////////////////

var dataAdapter = new $.jqx.dataAdapter(source);
$("#jqxcourts").jqxGrid(
    {
        width: '100%',
        source: dataAdapter,
        selectionmode: 'multiplerowsextended',
        sortable: true,
        autoheight: true,
        showfilterrow: true,
        filterable: true,
        editable: true,
        columns: [
            { text: 'Nom',                                      datafield: 'name',                          width: '10%' },
            { text: 'Indor',                                    datafield: 'indor',                         width: '10%' },
            { text: 'Heure d\'ouverture',                       datafield: 'start_time',                    width: '12%',  cellsformat: 'HH:mm'},
            { text: 'Heure de fermeture',                       datafield: 'end_time',                      width: '12%',  cellsformat: 'HH:mm' },
            { text: 'Fenêtre de reservation membre',            datafield: 'booking_window_member',         width: '20%' },
            { text: 'Fenêtre de reservation non membre',        datafield: 'booking_window_not_member',     width: '20%' },
            { text: 'Ajouté le',                                datafield: 'created_at',                    width: '8%', cellbeginedit: rowEdit,  cellsformat: 'dd.MM.yyyy' },
            { text: 'Mis à jour le',                            datafield: 'updated_at',                    width: '8%', cellbeginedit: rowEdit, cellsformat: 'dd.MM.yyyy' }
        ]
    });

$('#jqxcourts').on('cellendedit', function (event) {
    if(event.args.datafield == 'name' || event.args.datafield == 'indor' || event.args.datafield == 'start_time' || event.args.datafield == 'end_time' || event.args.datafield == 'booking_window_member' || event.args.datafield == 'booking_window_not_member'){
        data = {};
        data['name'] = event.args.datafield;
        data['value'] = event.args.value;
        //Format the value if it's the start_time or the end_time
        if(event.args.datafield == 'start_time' || event.args.datafield == 'end_time')
        {
            data['value'] = ("0" + event.args.value.getHours()).slice(-2) + ":" + ("0" + event.args.value.getMinutes()).slice(-2);
        }
        $.ajax({
            url: '/admin/config/courts/'+ event.args.row.uid,
            type: 'PUT',
            data: data,
            success: function(e)
            {
                $('#jqxcourts').jqxGrid('updatebounddata');

                if(e == "true")
                {
                    $('#message').html('<div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Modification enregistrée</div>');
                }
                else
                {
                    $('#message').html('<div class="alert alert-danger alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Problème lors de l\'enregistrement de la modificaiton</div>');
                }
            }
        });
    }
});