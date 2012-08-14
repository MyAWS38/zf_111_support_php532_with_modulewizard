var splitedited = "";
var splitkres = "";
var page = 0;
$(document).ready(function(){
   if (window.location.hash){
            splitkres = window.location.hash;
            splitkres = splitkres.replace("#","");
            
            splitpage = splitkres.split("/page/");            
            page = splitpage[1];
            
            splittermodif = splitkres.split("/modified/");
            splitedited = splittermodif[1];
        }
        
    if (splitedited == ""){    
            loaddataonload(page);        
    } else {
            loaddataafterload(); 
    }        
});

function collectdata()
{
    var data = '';
    data  +=  "/role_id/"+$("#role_id").val();
    
    return data;
}

function loaddataonload(page)
{
        data = collectdata(); 
        data += "/page/"+page;
        
        $("#loader").load(base_url+"/zfusers/index/loader"+data, function() {
            window.location.hash = data;        
        });
}

function loaddataafterload()
{        
        $("#loader").load(base_url+"/zfusers/index/loader"+splitkres, function() {
            window.location.hash = splitkres;        
        });
}

function loaddata()
{
    $(".process").fadeIn("slow");
    $(".process").html("<font color=red>Process...</font>");
        
    data   = collectdata(); 
    data  +="/page/0";
    
    $("#loader").load(base_url+"/zfusers/index/loader"+data, function() {
        window.location.hash = data;
        $(".process").html('<font color=red>Data Loaded</font>');
        $(".process").fadeTo(900,1);
        $(".process").fadeOut("slow");
    });   
}

function ajaxLoadContent(urls,id_data)
{
     $("#loader").load(urls, function() {            
          splitkres = urls.split("loader"); splitpage = splitkres[1].split("/page/");
          page = splitpage[1]; urlawal = splitpage[0]; tambahpage = "/page/"+page;
          window.location.hash = urlawal+tambahpage;       
     });
}


function showedit(keyrow)
{
    $(".usershide_"+keyrow).show();
    $(".users_"+keyrow).hide();
    
    $(".frmadd").attr({"action":base_url+'/zfusers/index/update',"method":"post"});    
}

function cancel(keyrow)
{
    $(".usershide_"+keyrow).hide();
    $(".users_"+keyrow).show();
}

function addusers()
{
    $(".usersadd").show();    
    $(".frmadd").attr({"action":base_url+'/zfusers/index/save',"method":"post"});    
}

function canceladd()
{
    $(".usersadd").hide();
}

function confirmdelete(id)
{
    var answer = confirm('The Dependency data will be deleted too!!\nAre you sure ? ');
    if (answer){
        $.post(base_url+'/zfusers/index/remove/id/'+id, function(data){
            window.location.reload(false);
        });
    }
}

function savedata()
{
    $.post(base_url+'/zfusers/index/save',{
        user_name: $("#user_name_add").val(),
        passwd : $("#passwd_add").val(),
        information:$("#information_add").val(),
        is_active:(($('input[name=is_active_add]').is(':checked')) ? 1 : 0) ,
        role_id:$(".role_id").val()
        },function(data)
    {
        window.location.reload(false);
    });
}

function updatedata(keyrow)
{
    $.post(base_url+'/zfusers/index/update',{
        user_name: $(".user_name_"+keyrow).val(),
        passwd : $(".passwd_"+keyrow).val(),
        information:$(".information_"+keyrow).val(),
        is_active:(($('input[name=is_active_'+keyrow+']').is(':checked')) ? 1 : 0) ,        
        user_id:$(".user_id_"+keyrow).val()
        },function(data)
    {
        window.location.reload(false);
    });
}
