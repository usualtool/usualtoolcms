<?php
require_once(dirname(__FILE__).'/'.'../conn.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_Spider.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_WeChat.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_AliOpen.php');
require_once(dirname(__FILE__).'/'.'ut-session.php');
$authcode=$setup["authcode"];
$authapiurl=$setup["authapiurl"];
if(!empty($_COOKIE['navleft'])):
    $navid=$_COOKIE['navleft'];
else:
    $navid="0";
endif;
$theroleid=UsualToolCMSDB::queryData("cms_admin","","id='$adminuserid' and username='$adminuser'","","","0")["querydata"][0]["roleid"];
$admin=UsualToolCMSDB::queryData("cms_admin_role","rolename,ranges","id='$theroleid'","","","0")["querydata"];
foreach($admin as $adminrow):
    $adminrolename=$adminrow["rolename"];
    $adminrange=$adminrow["ranges"];
endforeach;
$rolex=UsualToolCMSDB::queryData("cms_mod","","bid<>0","","","0")["querydata"];
foreach($rolex as $rolexrow):
    $roleurl=substr(str_replace(".php","",$rolexrow["modurl"]),1);
    $rolesx="".$rolesx.",".$roleurl."";
endforeach;
$roles=substr($rolesx,1);
$roleone=explode(",",$adminrange);
$roletwo=explode(",",$roles);
$diffrole=array_merge(array_diff($roleone,$roletwo),array_diff($roletwo,$roleone));
$diffroles=implode(",",$diffrole);
if(!empty($_GET['u'])):
    $dpage=substr($_GET['u'],1,4);
    if(UsualToolCMS::contain($dpage,$diffroles)):
        header("location:index.php");
        exit();
    endif;
endif;
$lang=Lang();
?>
<!doctype html>
<html lang="zh-CN" dir="ltr">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="content-language" content="zh-CN" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index,follow" />
<meta name="keywords" content="UsualToolCMS,UTCMS,UTF,UsualTool.com" />
<meta name="description" content="UsualTool.com" />
<meta name="rating" content="general" />
<meta name="author" content="UsualTool.com" />
<meta name="copyright" content="HuangHui && ChengDu KangfeiDunte Network Technology Co., Ltd." />
<meta name="generator" content="HuangHui,usualtool@qq.com" />
<title>CMS Admin</title>
<link rel="stylesheet" type="text/css" href="../assets/css/cmsadmin.css">
<link rel="stylesheet" type="text/css" href="../assets/css/dialog.css">
<?php if($setup["cmscolor"]>1):?><link rel="stylesheet" type="text/css" href="../assets/css/cms-<?php echo$setup["cmscolor"];?>css.css"><?php endif;?>
<link rel="stylesheet" type="text/css" href="../assets/css/datatable.css">
<link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="../assets/css/buttons.datatables.min.css"/>
<script type="text/javascript" src="../assets/js/jquery.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery.tab.js"></script>
<script type="text/javascript" src="../assets/js/usualtool-cms-2.0.js"></script>
<script type="text/javascript" src="../assets/js/dialog.js" charset="utf-8"></script>
<script type="text/javascript" src="../assets/js/plupload.full.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery.datatables.min.js"></script>
<script type="text/javascript" src="../assets/js/datatables.buttons.min.js"></script>
<script type="text/javascript" src="../assets/js/buttons.html5.min.js "></script> 
<script type="text/javascript" src="../assets/js/jszip.min.js"></script>  
<script type="text/javascript" src="../assets/js/buttons.print.min.js"></script> 
</head>
<body>
<aside class="myCanvasNav canvi-navbar">
<div class="canvi-user-info" id="navhead">
<div class="canvi-user-info__data">
<a href="#" class="canvi-user-info__meta"><img src="../assets/images/cmslogo.png" alt="logo"></a>
<div class="canvi-user-info__close" onClick="t.close();"></div>
</div>
</div>
<ul class="canvi-navigation">
<?php
$cmsnavx=UsualToolCMSDB::queryData("cms_nav","","place='cmsadmin'","ordernum asc","","0")["querydata"];
foreach($cmsnavx as $cmsrowx):
    echo"<p><a href='".$cmsrowx["linkurl"]."' style='padding:0px 0px;'><b><i class='fa fa-circle-o-notch'></i> ".$cmsrowx["linkname"]."</b></a></p>";
endforeach;
$sxmod=UsualToolCMSDB::queryData("cms_mod","id,bid,modid,modname,modurl,backitem","look=1 and isopen=1","id desc","","0")["querydata"];
foreach($sxmod as $sxmodrow):
        echo"<p onclick='opennav(".$sxmodrow["id"].")'><b><i class='fa fa-circle-o-notch'></i> ".$sxmodrow["modname"]."</b></p>";
        $sbid=$sxmodrow["bid"];
        $sid=$sxmodrow["modid"];
        $sname=$sxmodrow["modname"];
        $snav=$sxmodrow["backitem"];
        $snavarr=explode(",",$snav);
        echo"<ul class='closenav' id='nav".$sxmodrow["id"]."' style='display:none;'>";
        foreach($snavarr as $snv):
            $snavs=explode(":",$snv);
            $suarr=explode("php",$snavs[1]);
            $surl=$suarr[0]."php";
            $sget=str_replace("?","",$suarr[1]);
            ?>
            <li>
                <a href="ut-view-module.php?m=<?php echo$sid;?>&u=<?php echo$surl;?>&<?php echo$sget;?>" class="canvi-navigation__item" onclick="navclick('<?php echo$sxmodrow["id"];?>')">
                <span class="canvi-navigation__text"><i class="fa fa-th-large" aria-hidden="true"></i> <?php echo$snavs[0];?></span>
                </a>
            </li>
        <?php
        endforeach;
        echo"</ul>";
    endforeach;
    ?>
</ul>
</aside>
<div id="cmswrap">
 <div id="cmshead">
 <div id="head">
  <div class="logo"><a href="./">CMS Admin Panel</a></div>
  <div class="navx">
<ul>
    <li class="noRight"><a href="ut-login.php?do=out"><i class="fa fa-power-off" aria-hidden="true"></i> 退出</a></li>
</ul>
   <ul class="navRight">
   <li><main class="js-canvi-content canvi-content"><a class="js-canvi-open-button--left btn"><i class="fa fa-navicon" aria-hidden="true"></i> 栏目</a></main></li>
   </ul>
  </div>
<div class="nav">
    <ul>
    <li><a href="../" target="_blank"><i class="fa fa-television" aria-hidden="true"></i> 查看网站</a></li>
    <?php
        echo"<li class='M'><a href='JavaScript:void(0);'><i class='fa fa-th-large'></i> 栏目</a><div class='drop mTopad'>";
        $xmod=UsualToolCMSDB::queryData("cms_mod","id,bid,modid,modname,modurl,isopen","look=1 and isopen=1 and bid>0","id desc","","0")["querydata"];
        foreach($xmod as $xmodrow):
        ?>
            <a href='ut-view-module.php?m=<?php echo$xmodrow["modid"];?>&u=<?php echo$xmodrow["modurl"];?>' onclick="navclick('<?php echo$xmodrow["id"];?>')"><?php echo$xmodrow["modname"];?></a>
        <?php
        endforeach;
        echo"</div></li>";
     ?>
    </ul>
   <ul class="navRight">
    <li class="M noLeft"><a href="JavaScript:void(0);"><i class="fa fa-user-circle" aria-hidden="true"></i> <?php echo$adminuser;?></a>
     <div class="drop mUser">
      <a href="ut-view-module.php?m=admin&u=a_adminx.php&t=mon&id=<?php echo$adminuserid;?>"><i class="fa fa-edit" aria-hidden="true"></i> 编辑账户</a>
     </div>
    </li>
    <li class="noRight"><a href="ut-login.php?do=out"><i class="fa fa-power-off" aria-hidden="true"></i> 登出</a></li>
   </ul>
  </div>
 </div>
</div>
<div id="cmsleft">
<div id="menu">
<ul class="top">
<li><a href="./" onclick="navclick('0')"><i class="home"></i><em>管理首页</em></a></li>
</ul>
<div class="subnavbox">
<?php
$cmsnav=UsualToolCMSDB::queryData("cms_nav","","place='cmsadmin'","ordernum asc","","0")["querydata"];
foreach($cmsnav as $cmsrow):
?>
	<div class="subnav"><i class='fa fa-globe'></i> 
    <a href="<?php echo$cmsrow["linkurl"];?>">
    <?php echo$cmsrow["linkname"];?>
    </a>
    </div>
<?php
endforeach;
$n=0;
$nav=UsualToolCMSDB::queryData("cms_mod","id,bid,modid,modname,modurl,backitem","look=1 and isopen=1","id desc","","0")["querydata"];
foreach($nav as $navrow):
    $n=$n+1;
    if(!empty($navid) && $navid>0):
        if($navrow["id"]==$navid):
            $class="currentdd currentdt";
            $style="display:block;";
        else:
            $class="";
            $style="";
        endif;
    else:
        if($n==1):
             $class="currentdd currentdt";
             $style="display:block;";
        else:
             $class="";
             $style="";
        endif;
    endif;
    ?>
	<div class="subnav <?php echo$class;?>"><i class='fa fa-globe'></i> 
    <a href="ut-view-module.php?m=<?php echo$navrow["modid"];?>&u=<?php echo$navrow["modurl"];?>" onclick="navclick('<?php echo$navrow["id"];?>')">
    <?php echo$navrow["modname"];?>
    </a>
    </div>
	<ul class="navcontent" style="<?php echo$style;?>">
<?php
    $xxbid=$navrow["bid"];
    $xxid=$navrow["modid"];
    $xxname=$navrow["modname"];
    $xxnav=$navrow["backitem"];
    $xnavarr=explode(",",$xxnav);
    foreach($xnavarr as $nv):
        $xnavs=explode(":",$nv);
        $uarr=explode("php",$xnavs[1]);
        $url=$uarr[0]."php";
        $get=str_replace("?","",$uarr[1]);
		echo"<li><a href='ut-view-module.php?m=".$xxid."&u=".$url."&".$get."' onclick=navclick('".$navrow["id"]."')><span class='fa fa-clone'></span> ".$xnavs[0]."</a></li>";
    endforeach;
    ?>
	</ul>
<?php endforeach;?>
    <p align="center" style="background-color:#FFFACD;height:35px;"></p>
</div>
</div>
</div>
<script type="text/javascript" src="../assets/js/nav.js"></script>
<script type="text/javascript">
$(function(){
	$(".subnav").click(function(){
		$(this).toggleClass("currentdd").siblings(".subnav").removeClass("currentdd");
		$(this).toggleClass("currentdt").siblings(".subnav").removeClass("currentdt");
		$(this).next(".navcontent").slideToggle(300).siblings(".navcontent").slideUp(500);
	})	
})
var t = new Canvi({
    content: ".js-canvi-content",
    isDebug: !1,
    navbar: ".myCanvasNav",
    openButton: ".js-canvi-open-button--left",
    position: "left",
    pushContent: !1,
    speed: "0.2s",
    width: "100vw",
    responsiveWidths: [
        {
            breakpoint: "600px",
            width: "280px"
        }, {
            breakpoint: "1280px",
            width: "320px"
        }, {
            breakpoint: "1600px",
            width: "380px"
        } 
    ]
})
function opennav(id){
    var id;
    $(".closenav").css("display","none");
    $("#nav"+id+"").css("display","inline");
}
</script>