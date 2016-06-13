
url = "/admin/config/subscriptions";

var source =
{
    datatype: "json",
    datafields: [
        { name: 'status',    type: 'string' },
        { name: 'amount',   type: 'string' },
        { name: 'created_at',   type: 'date'},
        { name: 'updated_at',   type: 'date'},
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
$("#jqxsubscriptions").jqxGrid(
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
            { text: 'Type',          datafield: 'status',       width: '15%' },
            { text: 'Montant',       datafield: 'amount',       width: '15%' },
            { text: 'Crée le',       datafield: 'created_at',   width: '35%', cellbeginedit: rowEdit,  cellsformat: 'dd.MM.yyyy' },
            { text: 'Mis à jour le', datafield: 'updated_at',   width: '35%', cellbeginedit: rowEdit,  cellsformat: 'dd.MM.yyyy' }
        ]
    });

$('#jqxsubscriptions').on('cellendedit', function (event)
{
    if(event.args.datafield == 'status' || event.args.datafield == 'amount'){
        data = {};
        data['name'] = event.args.datafield;
        data['value'] = event.args.value;
        $.ajax({
            url: '/admin/config/subscriptions/'+ event.args.row.uid,
            type: 'PUT',
            data: data,
            success: function(e)
            {
                $('#jqxsubscriptions').jqxGrid('updatebounddata');

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