/*
Author : DataTable Crew
Modified by : I.Goujgali
last modification: 18.01.2017
Description: This code is used to initalised an make reseach in the member table
*/

$(document).ready(function(){
  //IGI - This table contains the columns to filter with a dropdown list
  var arrayDropDown = ['Actif','Infos à validé'];
  //IGI - This table contains the columns which don't need filter input
  var exclude = [''];


  // add a text input or dropdownlist to each footer cell
 $('#members-table tfoot th').each( function () {
     var title = $(this).text();
     //IGI- We check if title exists in one of tables arrayDropDown or exclude. if it exists in arrayDropDown we put a dropdown list if it exits in exclude we display the title
     //otherwise we display a simple input text
     if(arrayDropDown.indexOf(title) != -1)
     {
       $(this).html('<select class="form-control"><option value="" selected>Tous</option><option value="Oui">Oui</option><option value="Non">Non</option></select>');
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
      //https://datatables.net/reference/option/dom
     "dom": 'lrtip',
     //https://datatables.net/plug-ins/i18n/French
     "language":
     {
             "sProcessing":     "Traitement en cours...",
             "sSearch":         "Rechercher&nbsp;:",
             "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
             "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
             "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
             "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
             "sInfoPostFix":    "",
             "sLoadingRecords": "Chargement en cours...",
             "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
             "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
             "oPaginate": {
                 "sFirst":      "Premier",
                 "sPrevious":   "Pr&eacute;c&eacute;dent",
                 "sNext":       "Suivant",
                 "sLast":       "Dernier"
             },
             "oAria": {
                 "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
                 "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
             }
     },
    //IGI - Columns configuration - we don't display the id column and we don't search in the the columns id and action( button Voir info)
    "columnDefs": [
        {
            "targets": [ 0 ],
            "visible": false,
            "searchable":false
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

// IGI - when we clicked on a row we display the edit form of the members
$("#members-table tbody tr").on("click", function(event){
   var id = table.row(this).data()[0];
   window.location.replace('/admin/members/'+id+'/edit');
 });
