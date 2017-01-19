$(document).ready(function(){
    $("#btn-member-save").hide();
    $("#form-edit-member :input").each(function(){
        if($(this).prev().prop('type') != "button")$(this).prop('disabled', true);
        //else alert($(this).prev().prop('id'));
    });

    $("#btn-member-edit").prop('disabled',false);
});
lockedForm= true;
$("#btn-member-edit").on('click',function(){
    if(lockedForm)
    {
        $("#btn-member-save").show();
        $("#btn-member-edit").html('Vérouiller');
        $("#form-edit-member :input").each(function(){
            if($(this).prev().prop('type') != 'button')$(this).prop('disabled', false);
        });
        $("#btn-member-edit").prop('disabled',false);
        lockedForm = false;
    }
    else
    {
        $("#btn-member-save").hide();
        $("#btn-member-edit").html('Dévérouiller');
        $("#form-edit-member :input").each(function(){
            if($(this).prev().prop('type') != 'button') $(this).prop('disabled', true);
        });
        $("#btn-member-edit").prop('disabled',false);
        lockedForm = true;

    }
});