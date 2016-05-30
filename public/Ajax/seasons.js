
url = "/admin/config/seasons";

var source =
{
    datatype: "json",
    datafields: [
        { name: 'begin_date',   type: 'date' },
        { name: 'end_date',     type: 'date' },
        { name: 'created_at',   type: 'date'},
        { name: 'updated_at',   type: 'date'},
    ],
    id: 'id',
    url: url,
    root: 'data'
};

var dataAdapter = new $.jqx.dataAdapter(source);
$("#jqxseasons").jqxGrid(
    {
        width: '100%',
        source: dataAdapter,
        selectionmode: 'multiplerowsextended',
        sortable: true,
        autoheight: true,
        showfilterrow: true,
        filterable: true,
        columns: [
            { text: 'Date début',     datafield: 'begin_date',      width: '25%',  cellsformat: 'dd.MM.yyyy' },
            { text: 'Date fin',       datafield: 'end_date',        width: '25%',  cellsformat: 'dd.MM.yyyy' },
            { text: 'Créee le',       datafield: 'created_at',      width: '25%',  cellsformat: 'dd.MM.yyyy' },
            { text: 'Mise à jour le', datafield: 'updated_at',      width: '25%',  cellsformat: 'dd.MM.yyyy' }
        ]
    });