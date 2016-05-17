function calendar(div, options)
{
    this.bookingDiv     = div;
    this.bookingRow     = options.row;
    this.bookingStart   = options.start;
    this.bookingEnd     = options.end;
    this.bookingCourt   = options.court;
    this.bookingLine    = parseInt(this.bookingEnd.substr(0, 2)) - parseInt(this.bookingStart.substr(0, 2));
    this.date           = new Date();

    this.init();

}

calendar.prototype =
{

    init: function()
    {
        this.bookingWindow  = $('<div/>').addClass('table table-responsive').appendTo($(this.bookingDiv));
        this.booking        = $('<table/>').addClass('table table-hover table-condensed table-bordered').appendTo($(this.bookingWindow));

        this.makeBookingWindow();
    },

    makeBookingWindow: function()
    {
        var i = 0;
        var j = 0;

        var date = new Date();

        date.setHours(parseInt(this.bookingStart));

        // Header of table
        //----------------
        thead = $('<thead/>').appendTo(this.booking)
        tr =  $('<tr>').appendTo(thead)
        $('<th align="center"/>').appendTo(tr).html("Heures");

        for (i = 0; i < this.bookingRow; i++)
        {
            $('<th align="center"/>').appendTo(tr).html((this.date.getUTCDate() + i) +"."+ (this.date.getUTCMonth()+1) +"."+ this.date.getUTCFullYear());
        }
        //////////////////


        // Content of table
        //-----------------
        tbody = $('<tbody/>').appendTo($(this.booking))

        for (j = 0; j < this.bookingLine; j++)
        {
            tr = $('<tr/>').appendTo($(tbody));
            $('<th/>').appendTo($(tr)).html(date.getHours() + j + ":00");

            for (i = 0; i < this.bookingRow; i++)
            {
                this.rowDate = (this.date.getUTCDate() + i) +"."+ (this.date.getUTCMonth()+1) +"."+ this.date.getUTCFullYear()
                $('<td/>').appendTo($(tr)).attr("start", date.getHours() + j + ":00").attr("end", date.getHours() + (j+1) + ":00").attr("date", this.rowDate).attr("court", this.bookingCourt)
            }
        }
        ////////////////////
    },
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
$('#booking1').calendar({row: '3', start: '07:00', end: '23:00', court: '1'});


$("#booking1").on("click", "td", function()
{

    $('#myModalLabel').html("Réservation");
    $('#resume').html("<div class='row' style='text-align:center'><div class='col-md-4'>Le <b>"+ $(this).attr('date')+ "</b></div>"
        +"<div class='col-md-4'>De  <b>"+ $(this).attr('start') +"</b> à <b>"+ $(this).attr('end')+"</b></div>"+
        "<div class='col-md-4'>Court n°<b>"+ $(this).attr('court')+"</b></div><br/></div>");



    if(is_login)
    {
        $('#resume').append("Bonjour");

    }

    $('#myModal').modal('toggle');


});