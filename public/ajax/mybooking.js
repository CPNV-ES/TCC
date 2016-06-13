/**
 * Created by stephane.martignier on 03.06.2016.
 */

$('.confirm').click(function ()
{
    var self = this;
    if(confirm("Êtes-vous sûr ?"))
    {
        $.ajax({
            url: '/mybooking/'+ $(this).data('id'),
            type: 'DELETE',
            success: function(e)
            {
                if(e)
                {
                    $('#message').html('<div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Réservation correctement supprimée.</div>');
                    $(self).parents('tr').fadeOut();
                }
                else
                {
                    $('#message').html('<div class="alert alert-danger alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Problème lors de la suppression de votre réservation.</div>');
                }
            }
        });
    }
});
