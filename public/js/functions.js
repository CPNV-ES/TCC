/**
 * Created by Struan.FORSYTH on 17.01.2017.
 */

$(document).ready(function () {
    $(".option").click(function () {
        switch ($(this).data("action")) {

            case "edit":
                window.location = $(this).data("url");
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
});