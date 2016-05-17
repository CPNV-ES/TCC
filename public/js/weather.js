$.getJSON("http://www.prevision-meteo.ch/services/json/chavornay-vd", function (json)
{
    $('#weather_picture').html("<img src='"+json.current_condition.icon_big+"'/>");
    $('#weather_tmp').html(json.current_condition.tmp+"°");
    $('#weather_condition').html(json.current_condition.condition);
    $('#weather_wind').html("Vent de "+ json.current_condition.wnd_dir +" "+ json.current_condition.wnd_spd +" km/h");

});