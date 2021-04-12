<h2>UT命令行及composer工具</h2>
<style>
#dosbody{background-color:#000033;color:white;min-height: 300px;padding:10px;}
body{cursor:text, default !important;}
p{line-height:15px !important;}
#writedos{width:95%;background-color:#000033 !important;color:#fff;outline:none; border:0;height:25px;font-size:16px;}
</style> 
<div id="dosbody">
<p>[<?php echo$_SESSION['usualtooladmin'];?>@localhost ~]#UTDOS INIT</P>
<span id="doslist"></span>
<input id="writedos">
</div>
<p style="margin-top:10px;">本工具需开通shell_exec函数，但仅能执行PHP命令行及composer命令，其他命令无效。</p>
<p style="margin-top:10px;">常用命令：php x.php 或 composer require x/x  </p>
<p style="margin-top:10px;">composer下载项目的路径为：/modules/cactool/vendor/项目名/</p>
<script>
$(document).ready(function(){
    $('#writedos').bind('keypress',function(event){
        if(event.keyCode == "13") {
            var o=$("#writedos").val();
            $.post("<?php echo$weburl;?>/modules/cactool/ut-dos.php",{o:o},function(result){
                  $("#doslist").append(result);
            });
        }
    });
    $("input").focus();
});
</script>