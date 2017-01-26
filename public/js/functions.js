/**
 * Created by Struan.FORSYTH on 13.01.2017.
 * Modified by Ilias.GOUJGALI on 26.01.2017
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
});

//IGI lock/ unlock form
var lockedForm= true;
function lockForm(idForm, idBtnEdit, idBtnSave)
{

    $(idBtnSave).hide();
    $(idForm+" :input").each(function(){
        if($(this).prev().prop('type') != "button")$(this).prop('disabled', true);
    });
    $(idBtnEdit).prop('disabled',false);
    $(idBtnEdit).on('click',function(){
        if(lockedForm)
        {
            $(idBtnSave).show();
            $(idBtnEdit).html('Verrouiller');
            $(idForm+" :input").each(function(){
                if($(this).prev().prop('type') != 'button')$(this).prop('disabled', false);
            });
            $(idBtnEdit).prop('disabled',false);
            lockedForm = false;
        }
        else
        {
            $(idBtnSave).hide();
            $(idBtnEdit).html('Déverrouiller');
            $(idForm+" :input").each(function(){
                if($(this).prev().prop('type') != 'button') $(this).prop('disabled', true);
            });
            $(idBtnEdit).prop('disabled',false);
            lockedForm = true;

        }
    });
}


