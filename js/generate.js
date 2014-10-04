$(document).ready(function(){
    var list = new Array();
    $("img").on("click",function(){
        $(this).parent().hide();
        var pid = $(this).attr("data-pid");
        list.push(pid);
        $("#list").text("["+list+"]");
    });
});
