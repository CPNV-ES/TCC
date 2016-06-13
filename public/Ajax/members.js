// Get Subscription
//-----------------
var self = this;
this.DropDownList = [];
$.get('/admin/config/subscriptions', function(data)
{
    $(data).each(function (index, value)
    {
        self.DropDownList.push({value: value['id'], label: value['status']});
    }).promise().done(Display());
});


function Display()
{

    var DropDownListSource =
    {
        datatype: "array",
        datafields: [
            {name: 'label', type: 'string'},
            {name: 'value', type: 'string'}
        ],
        localdata: DropDownList
    }

    var DropDownListAdapter = new $.jqx.dataAdapter(DropDownListSource, {
        autoBind: true
    });

    url = "/admin/members";

    var source =
    {
        datatype: "json",
        datafields: [
            { name: 'last_name',            type: 'string' },
            { name: 'first_name',           type: 'string' },
            { name: 'email',                type: 'string' },
            { name: 'mobile_phone',         type: 'string' },
            { name: 'login',                type: 'string' },
            { name: 'currentStatusName',    type: 'string'},
            { name: 'created_at',           type: 'date'   },
            { name: 'administrator',        type: 'bool'   },
            { name: 'validate',             type: 'bool'   },
            { name: 'active',               type: 'bool'   },
            { name: 'to_verify',            type: 'bool'   },
            { name: 'status', value: 'DropDownListSource', values: { source: DropDownListAdapter.records, value: 'value', name: 'label' } }
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
            editable: true,
            columns: [
                { text: 'Nom',          datafield: 'last_name',         width: '9%', cellbeginedit: rowEdit },
                { text: 'Prénom',       datafield: 'first_name',        width: '9%', cellbeginedit: rowEdit },
                { text: 'Email',        datafield: 'email',             width: '19%', cellbeginedit: rowEdit },
                { text: 'Téléphone',    datafield: 'mobile_phone',      width: '10%', cellbeginedit: rowEdit },
                { text: 'Login',        datafield: 'login',             width: '12%', cellbeginedit: rowEdit },
                { text: 'Status',       datafield: 'status', userid: 'id', displayfield: 'currentStatusName', width: '13%', columntype:'dropdownlist', createeditor: function (row, value, editor) {
                    editor.jqxDropDownList({source: DropDownListAdapter, displayMember: 'label', valueMember: 'value'});
                }},
                { text: 'Inscription',  datafield: 'created_at',        width: '8%',  cellsformat: 'dd.MM.yyyy', cellbeginedit: rowEdit },
                { text: 'Admin',        datafield: 'administrator',     width: '5%',  columntype:'checkbox'},
                { text: 'Validé',       datafield: 'validate',          width: '5%',  columntype:'checkbox'},
                { text: 'Activé',       datafield: 'active',            width: '5%',  columntype:'checkbox'},
                { text: 'Verifié',      datafield: 'to_verify',         width: '5%',  columntype:'checkbox'},
            ]
        });
}

// Edit of member in admin panel
//------------------------------
$('#jqxmember').on('cellendedit', function (event)
{

    var data = {};

    if(event.args.datafield == 'validate' || event.args.datafield == 'administrator' || event.args.datafield == 'active' || event.args.datafield == 'to_verify')
    {
        data['name'] = event.args.datafield;
        data['value'] = event.args.value;
        var url = '/admin/members/'+ event.args.row.uid;
    }
    else
    {
        data['status_id']   = event.args.value.value;
        data['email']       = event.args.row.email;
        var url = '/admin/members/'+ event.args.row.email;
    }

    $.ajax({
        url: url,
        type: 'PUT',
        data: data,
        success: function(e)
        {
            $('#jqxmember').jqxGrid('updatebounddata');

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
});



