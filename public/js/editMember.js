/*
Author: I. Goujgali
date : 26.01.17
Description: This is the JS used in the member edit form (/admin/members/ID/edit): 1st part: it locks or unlocks the form
             the 2nd part is a rule to ask if the mail is already used by a member and the last part is just the code to
             launch also an client side verification of the form when we clicked on the submit button . (doesn't work actually)
*/

$(document).ready(function(){
    $("#btn-member-save").hide();
    $("#form-edit-member :input").each(function(){
        if($(this).prev().prop('type') != "button")$(this).prop('disabled', true);
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

//actually not used
// VERIF.RULES.mailOk={
//         control:(data)=>{
//             var mailOk = true;
//             $.ajax({
//                 url: '/admin/members/checkmail',
//                 headers: {'X-CSRF-TOKEN':$('input[name=_token]').val()},
//                 data: {email: $('input[name=email]').val(),idMember: $('input[name=member-id]').val() },
//                 type: 'POST',
//                 datatype: 'JSON',
//                 success: function (resp) {
//                     //console.log(resp.response);
//                     mailOk = resp.response;
//                     reponseReturn(true);
//                 }
//             });
//
//
//             function reponseReturn(val)
//             {
//                 console.log(val);
//                 return val;
//             }
//         },
// errorText:'Cette adresse mail est déjà utilisée'
// }

//client side verification when we clicked on submit
let btn=document.getElementById('btn-member-save');
VERIF.verifOnCLick(btn,'form-edit-member','edit-group-for');