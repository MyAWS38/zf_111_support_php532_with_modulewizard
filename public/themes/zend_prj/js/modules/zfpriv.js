var splitedited = "";
var splitkres = "";
$(document).ready(function(){
    if (window.location.hash){
            splitkres = window.location.hash;
            splitkres = splitkres.replace("#","");
            
            loaddataafterload();
            
            splitpage = splitkres.split("/role_id/");
                
            $("#role_id").val(splitpage[1]).attr('selected', 'selected');
    } else {
        loaddataonload();         
    }
    
 
});

function collectdata()
{
    var data = '';
    data  +=  "/role_id/"+$("#role_id").val();
    
    return data;
}

function loaddataonload()
{
        data = collectdata(); 
        
        $("#loader").load(base_url+"/zfpriv/index/loader"+data, function() {
            window.location.hash = data;        
        });
}

function loaddataafterload()
{        
        $("#loader").load(base_url+"/zfpriv/index/loader"+splitkres, function() {
            window.location.hash = splitkres;        
        });
}

function loaddata()
{
    $(".process").fadeIn("slow");
    $(".process").html("<font color=red>Process...</font>");
        
    data = collectdata(); 
                                                                       
    $("#loader").load(base_url+"/zfpriv/index/loader"+data, function() {
        window.location.hash = data;
        $(".process").html('<font color=red>Data Loaded</font>');
        $(".process").fadeTo(900,1);
        $(".process").fadeOut("slow");
    });   
}