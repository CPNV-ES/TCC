
url = "/admin/ajax/members";

var source =
{
    datatype: "json",
    datafields: [
        { name: 'last_name',    type: 'string' },
        { name: 'first_name',   type: 'string' },
        { name: 'email',        type: 'string' },
        { name: 'mobile_phone', type: 'string' },
        { name: 'login',        type: 'string' },
        { name: 'created_at',   type: 'date'},
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
        filterable: true,
        columns: [
            { text: 'Nom',          datafield: 'last_name',     width: '12%' },
            { text: 'Prénom',       datafield: 'first_name',    width: '12%' },
            { text: 'Email',        datafield: 'email',         width: '20%' },
            { text: 'Téléphone',    datafield: 'mobile_phone',  width: '18%' },
            { text: 'Login',        datafield: 'login',         width: '10%' },
            { text: 'Inscription',  datafield: 'created_at',    width: '28%',  cellsformat: 'dd.MM.yyyy' }
        ]
    });

