/**
 * Created by stephane.martignier on 01.06.2016.
 */

function disponibility(div, id)
{
    this.disponibilityDiv   = div;
    this.bookingDate        = "";
    this.bookingStart       = "";
    this.bookingEnd         = "";
    this.courtId            = id;
    this.init();
}

disponibility.prototype =
{

    init: function()
    {
        var self = this;
        $.get('/booking?courtID=' + self.courtId, function (response)
        {

            // Get value of court configuration
            //---------------------------------
            $(response).each(function(item, value)
            {
                self.display(value);
            });

        });
    },

    display: function(dataCourt)
    {

        var self            = this;
        var data            = [];
        var members         = [];
        var k               = 0;
        var bookingStart   = dataCourt['config']['start_time'];
        var bookingEnd     = dataCourt['config']['end_time'];
        var reservation    = dataCourt['reservation'];
        var bookingLine    = parseInt(bookingEnd.substr(0, 2)) - parseInt(bookingStart.substr(0, 2));
        var date           = new Date();

        date.setHours(parseInt(bookingStart));

        var disponibilityWindow  = $('<div/>').addClass('table table-responsive').appendTo($(this.disponibilityDiv));
        var disponibilityTable   = $('<table/>').addClass('table table-hover table-condensed table-bordered').appendTo($(disponibilityWindow));


        // Create array of the date_hours reservation
        //-------------------------------------------
        $(reservation).each(function(index, value)
        {
            members[k] = [];
            data.push(value['data']['date_hours']);
            members[k]['member_1'] = value['member_1'];
            members[k]['member_2'] = value['member_2'];
            k++;
        });


        // Header of table
        //----------------
        var thead   = $('<thead/>').appendTo(disponibilityTable);
        var tr      =  $('<tr>').appendTo(thead);
        $('<th align="center"/>').appendTo(tr).html("Heures");
        $('<th align="center"/>').appendTo(tr).html((date.getUTCDate()) +"."+ (date.getUTCMonth()+1) +"."+ date.getUTCFullYear());
        //////////////////


        // Content of table
        //-----------------
        var tbody = $('<tbody/>').appendTo($(disponibilityTable));


        // Line
        //-----
        for (j = 0; j < bookingLine; j++)
        {
            var newdate = new Date(this.date);
            newdate.setDate(newdate.getDate() + j);

            // Display hours
            //--------------
            var tr = $('<tr/>').appendTo($(tbody));
            $('<th/>').appendTo($(tr)).html(date.getHours() + j + ":00");
            //////////////////////////////////////////////


            // Define settings html5 data
            //---------------------------
            var rowDate =  date.getUTCFullYear() +"-"+ ("0"+(date.getUTCMonth()+1)).slice(-2) +"-"+("0"+(date.getUTCDate())).slice(-2);
            var td      = $('<td/>').appendTo($(tr)).attr("data-start", ("0"+(date.getHours() + j)).slice(-2) +":00").attr("data-end", ("0"+(date.getHours() + j+1)).slice(-2) + ":00").attr("data-date", rowDate).attr("data-court", self.courtId)
            //////////////////////////////////////////////


            // Check current day:hour with reservation list
            //---------------------------------------------
            if($.inArray(date.getUTCFullYear() +"-"+ ("0"+(date.getUTCMonth()+1)).slice(-2) +"-"+ ("0"+(date.getUTCDate())).slice(-2)+" "+("0"+(date.getHours() + j)).slice(-2) + ":00:00", data) != -1)
            {
                var index = $.inArray(date.getUTCFullYear() +"-"+ ("0"+(date.getUTCMonth()+1)).slice(-2) +"-"+ ("0"+(date.getUTCDate())).slice(-2)+" "+("0"+(date.getHours() + j)).slice(-2) + ":00:00", data)

                td.addClass('danger');

                if (is_logged)
                {
                    td.html("<div class='booking'>" + members[index]['member_1'] + " - " + members[index]['member_2'] + "</div>");
                }
            }
            //////////////////////////////////////////////

        }
        ////////////////////
    }
};
disponibility.prototype.constructor = disponibility;


// Plugin Definition //
$.fn.disponibility = function(options)
{
    if( typeof options == 'string'){
        var plugin = this.data('disponibility');
        if(plugin){
            var r = plugin[options].apply(plugin, Array.prototype.slice.call( arguments, 1 ) );
            if(r) return r
        }
        return this
    }

    options = $.extend({}, $.fn.disponibility.defaults, options);

    return this.each(function(){
        var plugin = $.data(this, 'disponibility');
        if( ! plugin ){
            plugin = new disponibility(this, options);
            $.data(this, 'disponibility', plugin);
        }
    });
};
$.fn.disponibility.defaults = {
    // Plugin options ...
};


/*
 Call
 */
new disponibility('#jqxcourt1', 1);
new disponibility('#jqxcourt2', 2);
