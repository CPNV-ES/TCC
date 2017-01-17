/**
 * Created by Struan.FORSYTH on 17.01.2017.
 */

$(document).ready(function () {
    $(".option").click(function () {
        switch ($(this).data("action")) {

            case "edit":
                window.location = $(this).data("url");
                break;

            case "delete-season":
                if (confirm("Etes-vous sûr de vouloir supprimer la saison : " + $(this).data("season") + " ?")) {
                    $(".delete").submit();
                }
                else {
                    return false;
                }
                break;

        }
    });
});