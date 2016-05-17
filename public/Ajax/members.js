
url = "/admin/members";

var source =
{
    datatype: "json",
    datafields: [
        { name: 'last_name',    type: 'string' },
        { name: 'first_name',   type: 'string' },
        { name: 'email',        type: 'string' },
        { name: 'mobile_phone', type: 'string' },
        { name: 'login',        type: 'string' },
        { name: 'created_at',   type: 'date'   },
        { name: 'administrator',type: 'bool'   },
        { name: 'validate',     type: 'bool'   },
        { name: 'active',       type: 'bool'   },
        { name: 'to_verify',    type: 'bool'   },
    ],
    id: 'id',
    url: url,
    root: 'data'
};

var dataAdapter = new $.jqx.dataAdapter(source);
$("#jqxmember").jqxGrid(
    {
        width: '100%',
        source: dataAdapter,
        selectionmode: 'multiplerowsextended',
        sortable: true,
        autoheight: true,
        showfilterrow: true,
        localization: getLocalization('fr'),
        filterable: true,
        columns: [
            { text: 'Nom',          datafield: 'last_name',     width: '15%' },
            { text: 'Prénom',       datafield: 'first_name',    width: '15%' },
            { text: 'Email',        datafield: 'email',         width: '20%' },
            { text: 'Téléphone',    datafield: 'mobile_phone',  width: '10%' },
            { text: 'Login',        datafield: 'login',         width: '12%' },
            { text: 'Inscription',  datafield: 'created_at',    width: '8%',  cellsformat: 'dd.MM.yyyy' },
            { text: 'Admin',        datafield: 'administrator', width: '5%',  columntype:'checkbox'     },
            { text: 'Validé',       datafield: 'validate',      width: '5%',  columntype:'checkbox'     },
            { text: 'Activé',       datafield: 'active',        width: '5%',  columntype:'checkbox'     },
            { text: 'Verifié',      datafield: 'to_verify',     width: '5%',  columntype:'checkbox'     },
            ]
    });

$('#jqxmember').on('cellclick', function (event) {
    if(event.args.datafield == 'validate' || event.args.datafield == 'administrator' || event.args.datafield == 'active' || event.args.datafield == 'to_verify'){
        data = {};
        data['name'] = event.args.datafield;
        data['value'] = event.args.value;
        $.ajax({
            url: '/admin/members/'+ event.args.row.bounddata.uid,
            type: 'PUT',
            data: data,
            success: function(e) {
                location.reload();
            }
        });

    }
});