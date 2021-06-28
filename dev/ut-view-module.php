<?php 
require_once 'ut-top.php';
if(!empty($_GET["m"])):$mod=UsualToolCMS::sqlcheck($_GET["m"]);else:$mod=UsualToolCMS::sqlcheck($_POST["m"]);endif;
if(!empty($_GET["u"])):$url=UsualToolCMS::sqlcheck($_GET["u"]);else:$url=UsualToolCMS::sqlcheck($_POST["u"]);endif;
$modpath="modules/".$mod;
$modname=UsualToolCMSDB::queryData("cms_mod","","modid='$mod'","","","0")["querydata"][0]["modname"];
if($mod==""):
elseif($mod=="module"):
    $modicon="fa fa-cubes";
elseif($mod=="plugin"):
    $modicon="fa fa-cogs";
elseif($mod=="templete"):
    $modicon="fa fa-book";
elseif($mod=="navigation"):
    $modicon="fa fa-circle-o-notch";
elseif($mod=="cactool"):
    $modicon="fa fa-laptop";
elseif($mod=="admin"):
    $modicon="fa fa-user-circle";
elseif($mod=="system"):
    $modicon="fa fa-cog";
else:
    $modicon="fa fa-th-large";
endif;
?>
<div id="cmsmain">
    <?php require_once 'ut-message.php';?>   
    <div class="mainbox">
        <h3><i class="<?php echo$modicon;?>" aria-hidden="true"></i> <?php echo$modname;?></h3>
        <?php require_once '../modules/'.$mod.'/cmsadmin/'.$url;?>
    </div>
</div>
<script>$(function(){$("title").html("<?php echo$modname;?> - UT Develop")})</script>
<?php
$mysqli->close();
require_once 'ut-bot.php';
?>