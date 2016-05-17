
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

var dataAdapter = new $.jqx.dataAdapter(source);
$("#jqxcourts").jqxGrid(
    {
        width: '90%',
        source: dataAdapter,
        selectionmode: 'multiplerowsextended',
        sortable: true,
        autoheight: true,
        showfilterrow: true,
        filterable: true,
        columns: [
            { text: 'Nom',                                      datafield: 'name',                          width: '10%' },
            { text: 'Indor',                                    datafield: 'indor',                         width: '10%' },
            { text: 'Heure d\'ouverture',                       datafield: 'start_time',                    width: '12%',  cellsformat: 'HH:mm'},
            { text: 'Heure de fermeture',                       datafield: 'end_time',                      width: '12%',  cellsformat: 'HH:mm' },
            { text: 'Fenêtre de reservation membre',            datafield: 'booking_window_member',         width: '20%' },
            { text: 'Fenêtre de reservation non membre',        datafield: 'booking_window_not_member',     width: '20%' },
            { text: 'Ajouté le',                                datafield: 'created_at',                    width: '8%',  cellsformat: 'dd.MM.yyyy' },
            { text: 'Mis à jour le',                            datafield: 'updated_at',                    width: '8%',  cellsformat: 'dd.MM.yyyy' }
        ]
    });

