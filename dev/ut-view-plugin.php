<?php 
require_once 'ut-top.php';
if(!empty($_GET["hookid"])):$hookid=UsualToolCMS::sqlcheck($_GET["hookid"]);else:$hookid=UsualToolCMS::sqlcheck($_POST["hookid"]);endif;
$pluginpath="plugins/".$hookid;
$t=UsualToolCMS::sqlcheck($_GET["t"]);
$pluginname=UsualToolCMSDB::queryData("cms_plugins","","id='$hookid'","","","0")["querydata"][0]["pluginname"];
$hooks=file_get_contents("../plugins/".$hookid."/usualtoolcms.config");
$plugincode=UsualToolCMS::str_substr("<plugincode><![CDATA[","]]></plugincode>",$hooks);
?>
<div id="cmsmain">
<?php require_once 'ut-message.php';?>   
<div class="mainbox">
<h3><?php echo$pluginname;?></h3>
<?php if(!empty($plugincode)&&$plugincode!==0):?>
<?php eval("".$plugincode."");?>
<?php endif;?>
</div>
</div>
<script>$(function(){$("title").html("<?php echo$pluginname;?> - UT Develop")})</script>
<?php
$mysqli->close();
require_once 'ut-bot.php';
?>