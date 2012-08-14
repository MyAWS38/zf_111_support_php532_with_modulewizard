    function installthis(id)
    {
         var answer = confirm('Are you sure ? ');
            if (answer){
                //delete img...
                $.post(base_url+'/zfmodules/index/installmod/id/'+id, function(data){
                        window.location.reload(false);
                });
        }
    }
    
    function installnotvalidthis(id)
    {
        var answer = confirm('Your module is not valid,\n if you continue, it\'s will be installed to another unique name,\n Are You sure  ?');
            if (answer){
                //delete img...
                $.post(base_url+'/zfmodules/index/installmodtovalid/id/'+id, function(data){
                        window.location.reload(false);
                });
        }
    }