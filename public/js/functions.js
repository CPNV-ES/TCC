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
                if (confirm("Etes-vous sûr de vouloir supprimer la saison du " + $(this).data("seasonstart") + " au " + $(this).data("seasonend") + " ?")) {
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

/*
IGI function used to lock/unlock form
*   @param  String idForm, id of the form. As to looked like #idForm,
*   @param  String idBtnEdit is the button used to edit the form
*   @param  String IdBtnSave is the button used to save the form
*   @param  String locked boolean defined if the form is locked or not at the beggining. Usefull if we have to unlock the form
*                  because of backend error (laravel error)
*   @param  Callback function
*
*/
function lockForm(idForm, idBtnEdit, idBtnSave, locked, callBackFunction)
{
    if (typeof(locked)==='undefined') locked = true;
    callBackFunction = callBackFunction || function(){};
    lockedForm = false;
    if(locked)
    {
        $(idBtnSave).hide();
        $(idForm+" :input").each(function(){
            if($(this).prev().prop('type') != "button") $(this).prop('disabled', true);
        });
        $(idBtnEdit).prop('disabled',false);
    }
    else {

        $(idBtnEdit).html('Annuler');
    }
    $(idBtnEdit).on('click',function(){

        if (callBackFunction !== undefined)  callBackFunction(locked);
        if(locked)
        {
            $(idBtnSave).show();
            $(idBtnEdit).html('Annuler');
            $(idForm+" :input").each(function(){
                if($(this).prev().prop('type') != 'button') $(this).prop('disabled', false);
            });
            $(idBtnEdit).prop('disabled',false);
            locked = false;

        }
        else
        {
            $(idBtnSave).hide();
            $(idBtnEdit).html('Modifier');
            $(idForm+" :input").each(function(){
                if($(this).prev().prop('type') != 'button') $(this).prop('disabled', true);
            });
            $(idBtnEdit).prop('disabled',false);
            locked = true;
        }
    });
}
