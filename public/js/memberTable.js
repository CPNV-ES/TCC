//TODO: - enlever flèche
//      - bouton option
//      - enlever sort sur bouton column
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

 var table = $('#members-table').DataTable();

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
