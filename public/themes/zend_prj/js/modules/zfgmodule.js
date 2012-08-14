function showedit(keyrow)
{
    $(".gmodulehide_"+keyrow).show();
    $(".gmodule_"+keyrow).hide();
}

function cancel(keyrow)
{
    $(".gmodulehide_"+keyrow).hide();
    $(".gmodule_"+keyrow).show();
}

function addgmodule()
{
    $(".gmoduleadd").show();
}

function canceladd()
{
    $(".gmoduleadd").hide();
}

function confirmdelete(id)
{
    var answer = confirm('The Dependency data will be deleted too!!\nAre you sure ? ');
    if (answer){
        $.post(base_url+'/zfgroupmodules/index/remove/id/'+id, function(data){
            window.location.reload(false);
        });
    }
}