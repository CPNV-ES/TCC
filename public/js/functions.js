/**
 * Created by Struan.FORSYTH on 13.01.2017.
 */

$(document).ready(function () {
   $(".option").click(function () {
       switch ($(this).data("action")) {

           case "edit":
               window.location = $(this).data("url");
               break;

           case "delete":
               var courtName = $("table tbody tr td").first().text();
               if (confirm("Etes-vous sûr de vouloir supprimer le court : " + courtName + " ?")) {
                   $(".delete").submit();
               }
               else {
                   return false;
               }
               break;

       }
   });
});