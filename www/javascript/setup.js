$(document).ready( function() {
    
    $("#driver").change(function() {
        $.get("/setup.php/config/" + $("#driver").val() ,"", function(data){
           $("#driverConfig").html(data);
        }, "html");
        
    });
    
});