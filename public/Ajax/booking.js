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

        // Set div
        //--------
        this.colmd6         = $('<div/>').addClass('col-md-6').appendTo($(this.calendarDiv));
        $(this.colmd6).html("<h4>" + this.courtName + "</h4>");
        this.bookingWindow  = $('<div/>').addClass('table table-responsive').appendTo($(this.colmd6));
        this.booking        = $('<table/>').addClass('table table-hover table-condensed table-bordered').appendTo($(this.bookingWindow));
        ///////////////////////////////////

        this.display();
    },

    display: function()
    {
        var self        = this;
        var k           = 0;
        var data        = [];
        var members     = [];
        var date        = new Date();
        date.setHours(parseInt(this.bookingStart));



        // Create array of the date_hours reservation
        //-------------------------------------------
        $(self.reservation).each(function(index, value)
        {
            members[k] = [];
            data.push(value['data']['date_hours']);
            members[k]['member_1'] = value['member_1'];
            members[k]['member_2'] = value['member_2'];
            k++;
        });
        //////////////////////////////////////////////


        // Header of table
        //----------------
        thead   = $('<thead/>').appendTo(this.booking)
        tr      = $('<tr>').appendTo(thead)
        $('<th align="center"/>').appendTo(tr).html("Heures");

        for (i = 0; i < this.bookingRow; i++)
        {
            var newdate = new Date(this.date);
            newdate.setDate(newdate.getDate() + i);

            $('<th align="center"/>').appendTo(tr).html((newdate.getUTCDate()) +"."+ (newdate.getUTCMonth()+1) +"."+ newdate.getUTCFullYear());
        }
        //////////////////////////////////////////////


        // Content of table
        //-----------------
        tbody = $('<tbody/>').appendTo($(this.booking))


        // Line
        //-----
        for (j = 0; j < this.bookingLine; j++)
        {
            // Display hours
            //--------------
            tr = $('<tr/>').appendTo($(tbody));
            $('<th/>').appendTo($(tr)).html(date.getHours() + j + ":00");
            //////////////////////////////////////////////


            // Row
            //----
            for (i = 0; i < this.bookingRow; i++)
            {
                var newdate = new Date(this.date);
                newdate.setDate(newdate.getDate() + i);


                // Add data html5 information
                //---------------------------
                var rowDate =  newdate.getUTCFullYear() +"-"+ ("0"+(newdate.getUTCMonth()+1)).slice(-2) +"-"+("0"+(newdate.getUTCDate())).slice(-2);
                td = $('<td/>').appendTo($(tr)).attr("data-start", ("0"+(date.getHours() + j)).slice(-2) +":00").attr("data-end", ("0"+(date.getHours() + j+1)).slice(-2) + ":00").attr("data-date", rowDate).attr("data-court", self.courtId)
                //////////////////////////////////////////////


                // Check current day:hour with reservation list
                //---------------------------------------------
                if($.inArray(newdate.getUTCFullYear() +"-"+ ("0"+(newdate.getUTCMonth()+1)).slice(-2) +"-"+ ("0"+(newdate.getUTCDate())).slice(-2)+" "+("0"+(date.getHours() + j)).slice(-2) + ":00:00", data) != -1)
                {
                    var index = $.inArray(newdate.getUTCFullYear() +"-"+ ("0"+(newdate.getUTCMonth()+1)).slice(-2) +"-"+ ("0"+(newdate.getUTCDate())).slice(-2)+" "+("0"+(date.getHours() + j)).slice(-2) + ":00:00", data);

                    td.addClass('danger');
                    
                    if (is_logged)
                    {
                        td.html("<div class='booking'>" + members[index]['member_1'] + " - " + members[index]['member_2'] + "</div>");
                    }
                }
                //////////////////////////////////////////////
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