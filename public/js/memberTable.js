/*
Author : DataTable Crew
Modified by : I.Goujgali
last modification: 18.01.2017
Description: This code is used to initalised an make reseach in the member table
*/

//TODO: - enlever flèche

$(document).ready(function(){
  //This table contain the column to filter with a checkbox
  var chkBoxList = ['Actif','Validé'];
  var exclude = [''];


  // Setup - add a text input or dropdownlist to each footer cell
 $('#members-table tfoot th').each( function () {
     var title = $(this).text();
     if(chkBoxList.indexOf(title) != -1)
     {
       $(this).html('<select class="form-control"><option value="" selected>All</option><option value="Oui">Yes</option><option value="Non">No</option></select>');
     }
     else if(exclude.indexOf(title) != -1)
     {
        $(this).html(title);
     }
     else {
       $(this).html( '<input type="text" class="form-control" placeholder="Filtrer par '+title+'" />' );
     }

 } );

 table = $('#members-table').DataTable({
    //Column configuration
    "columnDefs": [
    {
        "targets": [ 0 ],
        "visible": false,
        "searchable":false
    },
    {
        "targets": [ 7 ],
        "searchable": false
    }
    ]

 });

 // Apply the search
 table.columns().every( function () {
     var that = this;

     $( ':input', this.footer() ).on( 'keyup change click', function () {

         if ( that.search() !== this.value ) {
             that
                 .search( this.value )
                 .draw();
         }
     });
 } );

 // put the filters at the top of the table.
 $('#members-table tfoot tr').appendTo('#members-table thead');
});

// display user details when we click on a row
$("#members-table tbody tr").on("click", function(event){
   var id = table.row(this).data()[0];
   window.location.replace('/admin/members/'+id+'/edit');
 });

