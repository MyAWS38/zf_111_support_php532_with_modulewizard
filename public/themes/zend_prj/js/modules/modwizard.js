$(document).ready(function(){
    $("#tryagain").click(function(){
          $("input[tipe=input]").attr("disabled",false);
          $(".tryagain").hide();
    });
    
   $("#process").click(function(){
            var msg = "";
            
            var module_title = trim($("#module_title").val());
            var module_name = trim($("#module_name").val());
            var developer = trim($("#developer").val());
           
            if (module_title=="") msg += "Module Title must be entry <br>"; 
            //check have space...
            arexmod = module_name.split(" ");
            if (arexmod.length>1){
                msg += "<blink><font color=red>Module Name mayn't have space </font></blink><br>"; 
            }    
            
            if (module_name=="") msg += "Module Name must be entry <br>"; 
            if (developer=="") msg += "Developer must be entry<br>"; 

            if (msg!="") $(".msg").html("<blink><font color=red>"+msg+"</font></blink>"); else  {
                $(".msg").html('<font color=red><b>Process...</b></font>');
                //process ajax...
                $("input[tipe=input]").attr("disabled","disabled");
                $.post(base_url+'/zfmodwizard/index/generate',{
                    module_name : module_name,
                    module_title : module_title,
                    developer : developer,
                    groupmodule_id : $("#groupmodule_id").val()
                },function(data){
                    //set html ...
                    if (data=="ok"){
                        $(".msg").html("Module "+module_name+" successfully generated");
                    } else {
                        $(".msg").html(data);
                        $(".tryagain").show();
                    }
                });
            }
    });
});

function validatemodname(f)
{
    f.value = f.value.toLowerCase();
    if (!isAlpha(f.value)){
       f.value = f.value.substr(0, f.value.length -1);
    }
}