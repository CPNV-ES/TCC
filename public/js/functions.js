/**
 * Created by Struan.FORSYTH on 13.01.2017.
 */

$(document).ready(function () {
   $(".clickable-row").click(function () {
       window.location = $(this).data("url");
   });
});