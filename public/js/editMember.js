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
        $("#btn-member-edit").html('Verrouiller');
        $("#form-edit-member :input").each(function(){
            if($(this).prev().prop('type') != 'button')$(this).prop('disabled', false);
        });
        $("#btn-member-edit").prop('disabled',false);
        lockedForm = false;
    }
    else
    {
        $("#btn-member-save").hide();
        $("#btn-member-edit").html('Déverrouiller');
        $("#form-edit-member :input").each(function(){
            if($(this).prev().prop('type') != 'button') $(this).prop('disabled', true);
        });
        $("#btn-member-edit").prop('disabled',false);
        lockedForm = true;

    }
});


VERIF.RULES.mailOk={
        control:(data)=>{
            var mailOk = true;
            $.ajax({
                url: '/admin/members/checkmail',
                headers: {'X-CSRF-TOKEN':$('input[name=_token]').val()},
                data: {email: $('input[name=email]').val(),idMember: $('input[name=member-id]').val() },
                type: 'POST',
                datatype: 'JSON',
                success: function (resp) {
                    //console.log(resp.response);
                    mailOk = resp.response;
                    reponseReturn(true);
                }
            });


            function reponseReturn(val)
            {
                console.log(val);
                return val;
            }
        },
errorText:'Cette adresse mail est déjà utilisée'
}
let btn=document.getElementById('btn-member-save');
VERIF.verifOnCLick(btn,'form-edit-member','edit-group-for');