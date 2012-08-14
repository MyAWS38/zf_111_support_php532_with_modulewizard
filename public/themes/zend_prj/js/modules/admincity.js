/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


var splitedited = "";
var splitkres = "";
var page = 0;
$(document).ready(function(){
    if (window.location.hash){
        splitkres = window.location.hash;
        splitkres = splitkres.replace("#!","");
        splitkres = splitkres.replace("%","%25");

        splitpage = splitkres.split("/page/");
        page = splitpage[1];

    }

    if (splitkres == ""){
        loaddataonload(page);
    } else {
        loaddataafterload();

        keywordexplode = splitkres.split("/keyword/");
        splitnopage = keywordexplode[1].split("/page/");

        $("input[name=keyword]").val( splitnopage[0] );
    }

    $("#searchbutton").click(function(){
        loaddata();
    });

    $("input[name=keyword]").keyup(function(event){

        if (event.keyCode == '13'  ){
            $("#searchbutton").click();
        }

    });

    showall();
    $("#showall").click(function(){
       window.location.href = base_url+'/admin/admincity/';
    });
});

function showall()
{
    if ( ! $.trim( $("input[name=keyword]").val() ) == "" ){
        $("#showall").show();
    }
}


function collectdata()
{
    var data = '';
    data  +=  "/keyword/"+$("input[name=keyword]").val();

    return data;
}

function loaddataonload(page)
{
    data = collectdata();
    data += "/page/"+page;

    $("#loader").load(base_url+"/admin/admincity/loader"+data, function() {
      window.location.hash = "!"+data;
    });
}

function loaddataafterload()
{
    splitkres = splitkres.replace("#!","");
    $("#loader").load(base_url+"/admin/admincity/loader"+splitkres, function() {
       window.location.hash = "!"+splitkres;
    });
    showall();
}

function loaddata()
{
    $(".process").fadeIn("slow");
    $(".process").html("<font color=red>Process...</font>");

    data   = collectdata();
    data  +="/page/0";

    $("#loader").load(base_url+"/admin/admincity/loader"+data, function() {
        window.location.hash = "!"+data;
        $(".process").html('<font color=red>Data Loaded</font>');
        $(".process").fadeTo(900,1);
        $(".process").fadeOut("slow");
    });

     showall();
}



function ajaxLoadContent(urls,id_data)
{
    $("#loader").load(urls, function() {
        splitkres = urls.split("loader");
        splitpage = splitkres[1].split("/page/");
        page = splitpage[1];
        urlawal = splitpage[0];
        tambahpage = "/page/"+page;
        window.location.hash = "!"+urlawal+tambahpage;
    });
}

function addcity()
{
    $(".cityadd").show();
    $(".frmadd").attr({"action":base_url+'/admin/admincity/save',"method":"post"});
}

function canceladd()
{
    $(".cityadd").hide();
}

function savedata()
{
    $.post(base_url+'/admin/admincity/save',{
        city_name: $("#city_name_add").val(),
        city_id:$(".city_id").val()
        },function(data)
    {
        window.location.reload(false);
    });
}

function updatedata(keyrow)
{
    $.post(base_url+'/admin/admincity/update',{
        city_name: $(".city_name_"+keyrow).val(),
        city_id:$(".city_id_"+keyrow).val()
        },function(data)
    {
        window.location.reload(false);
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