function showedit(keyrow)
{
    $(".rolehide_"+keyrow).show();
    $(".role_"+keyrow).hide();
}

function cancel(keyrow)
{
    $(".rolehide_"+keyrow).hide();
    $(".role_"+keyrow).show();
}

function addrole()
{
    $(".roleadd").show();
}

function canceladd()
{
    $(".roleadd").hide();
}

function confirmdelete(id)
{
    var answer = confirm('The Dependency data will be deleted too!!\nAre you sure ? ');
    if (answer){
        $.post(base_url+'/zfusers/index/removerole/id/'+id, function(data){
            window.location.reload(false);
        });
    }
}