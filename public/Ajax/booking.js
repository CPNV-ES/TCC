function booking(div, data)
{
    this.calendarDiv    = div;
    this.bookingRow     = data['config']['booking_window'];
    this.bookingStart   = data['config']['start_time'];
    this.bookingEnd     = data['config']['end_time'];
    this.courtId        = data['config']['id'];
    this.courtName      = data['config']['name'];
    this.reservation    = data['reservation'];
    this.bookingLine    = parseInt(this.bookingEnd.substr(0, 2)) - parseInt(this.bookingStart.substr(0, 2));
    this.date           = new Date();

    this.init();

}

booking.prototype =
{

    init: function()
    {
        this.colmd6         = $('<div/>').addClass('col-md-6').appendTo($(this.calendarDiv));
        $(this.colmd6).html("<h4>" + this.courtName + "</h4>");
        this.bookingWindow  = $('<div/>').addClass('table table-responsive').appendTo($(this.colmd6));
        this.booking        = $('<table/>').addClass('table table-hover table-condensed table-bordered').appendTo($(this.bookingWindow));

        this.makeBookingWindow();
    },

    makeBookingWindow: function()
    {
        var self = this;
        var i = 0;
        var j = 0;
        var data = [];

        var date = new Date();
        date.setHours(parseInt(this.bookingStart));

        // Create array of the date_hours reservation
        //-------------------------------------------
        $(self.reservation).each(function(index, value)
        {
            data.push(value['date_hours']);
        });


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
                this.rowDate =  this.date.getUTCFullYear() +"-"+ ("0"+(self.date.getUTCMonth()+1)).slice(-2) +"-"+(this.date.getUTCDate() + i);

                td = $('<td/>').appendTo($(tr)).attr("data-start", ("0"+(date.getHours() + j)).slice(-2) +":00").attr("data-end", ("0"+(date.getHours() + j+1)).slice(-2) + ":00").attr("data-date", self.rowDate).attr("data-court", self.courtId)


                if($.inArray(self.date.getUTCFullYear() +"-"+ ("0"+(self.date.getUTCMonth()+1)).slice(-2) +"-"+ (self.date.getUTCDate() + i)+" "+("0"+(date.getHours() + j)).slice(-2) + ":00:00", data) != -1)
                {
                    td.addClass('danger');
                }
            }
        }
        ////////////////////
    },
};
booking.prototype.constructor = booking;




// Plugin Definition //
$.fn.booking = function(options)
{
    if( typeof options == 'string'){
        var plugin = this.data('booking');
        if(plugin){
            var r = plugin[options].apply(plugin, Array.prototype.slice.call( arguments, 1 ) );
            if(r) return r
        }
        return this
    }

    options = $.extend({}, $.fn.booking.defaults, options);

    return this.each(function(){
        var plugin = $.data(this, 'booking');
        if( ! plugin ){
            plugin = new booking(this, options);
            $.data(this, 'booking', plugin);
        }
    });
};
$.fn.booking.defaults = {
    // Plugin options ...
};