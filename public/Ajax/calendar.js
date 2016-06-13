function calendar(div)
{

    this.calendarDiv    = div;
    this.bookingDate    = "";
    this.bookingStart   = "";
    this.bookingEnd     = "";
    this.courtId        = "";

    this.init();

}

calendar.prototype =
{

    init: function()
    {
        self = this;
        $.get('/booking', function (response)
        {

            // Get value of court configuration
            //---------------------------------
            $(response).each(function(item, value)
            {
                new booking(self.calendarDiv, value);
            });

            self.modalDisplay();
            self.makeReservation();
        });
    },

    modalDisplay: function()
    {
        self = this;

        $("#calendar").on("click", "td", function()
        {

            // If case already booking, doesn't display modal
            //-----------------------------------------------
            if (!$(this).hasClass('danger'))
            {

                self.cell            = this;
                self.bookingDate     = $(this).data('date');
                self.bookingStart    = $(this).data('start');
                self.bookingEnd      = $(this).data('end');
                self.courtId         = $(this).data('court');


                // Convert for user
                //-----------------
                bookingDateArray    = self.bookingDate.split("-");
                bookingDateAjax     = bookingDateArray[2] + "." + bookingDateArray[1] + "." + bookingDateArray[0];


                // Title
                //------
                $('#myModalLabel').html("Réservation");


                // Resume of booking
                //------------------
                $('#modal-resume').html("<div class='row' style='text-align:center'><div class='col-md-4'>Le <b>" + bookingDateAjax + "</b></div>"
                    + "<div class='col-md-4'>De  <b>" + self.bookingStart + "</b> à <b>" + self.bookingEnd + "</b></div>" +
                    "<div class='col-md-4'>Court n°<b>" + self.courtId + "</b></div><br/></div>");


                // If person is login
                //-------------------
                if (is_logged)
                {

                    // Convert for database
                    //---------------------
                    self.bookingDate = self.bookingDate + " " + self.bookingStart + ":00";


                    // Get members list
                    //-----------------
                    $.get("/admin/members?hours=" +  self.bookingStart + ":00", function (data)
                    {

                        // Clear modal content
                        //--------------------
                        $("#modal-panel").empty();
                        $("#modal-content").empty();


                        // Create panel header
                        //--------------------
                        panel = $('<ul class="nav nav-tabs" role="tablist">').appendTo("#modal-panel");
                        panel.append('<li role="presentation" class="active"><a href="#members" aria-controls="members" role="tab" data-toggle="tab">Membre</a></li>');
                        panel.append('<li role="presentation"><a href="#invit" aria-controls="invit" role="tab" data-toggle="tab">Invité</a></li>');

                        panelContent        = $('<div class="tab-content">').appendTo("#modal-content");
                        panelContentMembers = $('<div role="tabpanel" class="tab-pane active" id="members">').appendTo(panelContent);
                        panelContentInvit   = $('<div role="tabpanel" class="tab-pane" id="invit">').appendTo(panelContent);
                        /////////////////////////////////////////////


                        // Display select with members informations
                        //-----------------------------------------
                        select = $("<select id='memberList' class='form-control'>").appendTo(panelContentMembers);
                        select.append("<option value='0'>Sélectionner un-e partenaire</option>");

                        $(data).each(function (index, item) {
                            select.append("<option value='" + item.id + "'>" + item.last_name + " " + item.first_name + "</option>");
                        });
                        //////////////////////////////////////////////


                        // Display for invited person
                        //---------------------------
                        form = $("<form>").appendTo(panelContentInvit);
                        form.append("<div class='form-group'><input type='text' name='first_name' class='form-control' placeholder='Nom'/></div>");
                        form.append("<div class='form-group'><input type='text' name='last_name' class='form-control' placeholder='Prénom'/></div>");
                        form.append("<div class='form-group'><input type='text' name='email' class='form-control' placeholder='E-mail'/></div>");
                        //////////////////////////////////////////////

                    });
                }

                // Display modal and button 'Réserver'
                //------------------------------------
                $('#myModal').modal('show');

                $('#booking').show();
            }
        });
    },

    makeReservation: function()
    {
        self = this;

        // Action on "Réservation" button
        //-------------------------------
        $('#booking').unbind('click').click(function ()
        {
            data = {};

            // If button is display
            //---------------------
            if(panelContentMembers.hasClass('active'))
            {

                if($("#memberList").val() == 0)
                {
                    alert("Veuillez sélectionner un partenaire");
                }
                else
                {

                    // Set data for member with member
                    // --------------------------------
                    data['fk_member_2'] = $("#memberList").val();
                    data['date_hours']  = self.bookingDate;
                    data['fk_court']    = self.courtId;


                    // Send request
                    //-------------
                    $.ajax({
                        url: 'booking',
                        type: 'POST',
                        data: data,
                        success: function(e)
                        {
                            var name = $("#memberList option:selected").text().split(' ');
                            $('#booking').hide();
                            $('#modal-panel').empty();
                            $('#modal-content').html('<div class="alert alert-info" role="alert">'+ e[0] +'</div>');

                            $('#myModal').on('hidden.bs.modal', function ()
                            {
                                if(e[1])
                                {
                                    $(self.cell).addClass('danger');
                                    $(self.cell).html("<div class='booking'>" + member_last_name + " - " + name[0] + "</div>");
                                }
                            });
                        }
                    });
                }
            }
        });
    }
};
calendar.prototype.constructor = calendar;




// Plugin Definition //
$.fn.calendar = function(options)
{
    if( typeof options == 'string'){
        var plugin = this.data('calendar');
        if(plugin){
            var r = plugin[options].apply(plugin, Array.prototype.slice.call( arguments, 1 ) );
            if(r) return r
        }
        return this
    }

    options = $.extend({}, $.fn.calendar.defaults, options);

    return this.each(function(){
        var plugin = $.data(this, 'calendar');
        if( ! plugin ){
            plugin = new calendar(this, options);
            $.data(this, 'calendar', plugin);
        }
    });
};
$.fn.calendar.defaults = {
    // Plugin options ...
};


/*
 Call
 */
$('#calendar').calendar();