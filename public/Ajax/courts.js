
url = "/admin/ajax/courts";

var source =
{
    datatype: "json",
    datafields: [
        { name: 'name',         type: 'string' },
        { name: 'indor',        type: 'string' },
        { name: 'created_at',   type: 'date' },
    ],
    id: 'id',
    url: url,
    root: 'data'
};

var dataAdapter = new $.jqx.dataAdapter(source);
$("#jqxcourts").jqxGrid(
    {
        width: '60%',
        source: dataAdapter,
        selectionmode: 'multiplerowsextended',
        sortable: true,
        autoheight: true,
        showfilterrow: true,
        filterable: true,
        columns: [
            { text: 'Nom',          datafield: 'name',     width: '20%' },
            { text: 'Indor',        datafield: 'indor',    width: '20%' },
            { text: 'Ajouté le',    datafield: 'created_at',    width: '60%',  cellsformat: 'dd.MM.yyyy' }
        ]
    });

