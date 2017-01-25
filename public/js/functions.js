/**
 * Created by Struan.FORSYTH on 13.01.2017.
 */

$(document).ready(function () {
   $(".option").click(function () {
       switch ($(this).data("action")) {

           case "edit":
               window.location = $(this).data("url");
               break;

           case "delete-court":
               if (confirm("Etes-vous sûr de vouloir supprimer le court : " + $(this).data("court") + " ?")) {
                   $(".delete").submit();
               }
               else {
                   return false;
               }
               break;

            case "delete-season":
                if (confirm("Etes-vous sûr de vouloir supprimer la saison : " + $(this).data("season") + " ?")) {
                    $(".delete").submit();
                }
                else {
                    return false;
                }
                break;

            case "delete-subscription":
                if (confirm("Etes-vous sûr de vouloir supprimer la cotisation : " + $(this).data("subscription") + " ?")) {
                    $(".delete").submit();
                }
                else {
                    return false;
                }
                break;

        }
    });

   $("#btnCourtCheck").click(function () {
        if (VERIF.verifGroup("courtCheck")) {
            document.forms["courtForm"].submit();
        }
    });

    $("#btnSeasonCheck").click(function () {
        if (VERIF.verifGroup("seasonCheck")) {
            document.forms["seasonForm"].submit();
        }
    });

    $("#btnSubscriptionCheck").click(function () {
        if (VERIF.verifGroup("subscriptionCheck")) {
            document.forms["subscriptionForm"].submit();
        }
    });

});
