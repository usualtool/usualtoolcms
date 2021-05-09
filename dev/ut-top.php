<?php
require_once(dirname(__FILE__).'/'.'../conn.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_Spider.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_Page.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_Tree.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_WeChat.php');
require_once(dirname(__FILE__).'/'.'ut-session.php');
$setuprow=UsualToolCMSDB::queryData("cms_setup","authcode,authapiurl,copyright,installtime,country,cmscolor","","","1","0")["querydata"][0];
    $authcode=$setuprow["authcode"];
    $authapiurl=$setuprow["authapiurl"];
    $copyright=$setuprow["copyright"];
    $installtime=$setuprow["installtime"];
    $country=$setuprow["country"];
    $cmscolor=$setuprow["cmscolor"];
if(!empty($_COOKIE['navleft'])):
    if($_COOKIE['navleft']>10):
        $navid=4;
    else:
        $navid=$_COOKIE['navleft'];
    endif;
    $navrid=$_COOKIE['navleft'];
else:
    $navid="0";
endif;
$admin=$mysqli->query("select rolename,ranges from `cms_admin_role` where id=(select roleid from `cms_admin` where id=$adminuserid and username='$adminuser')");
while($adminrow=mysqli_fetch_array($admin)):
    $adminrolename=$adminrow["rolename"];
    $adminrange=$adminrow["ranges"];
endwhile;
$rolex=$mysqli->query("select * from `cms_mod` where bid<>0");
while($rolexrow=mysqli_fetch_array($rolex)):
    $roleurl=substr(str_replace(".php","",$rolexrow["modurl"]),1);
    $rolesx="".$rolesx.",".$roleurl."";
endwhile;
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
<title>UT Develop V8.0</title>
<link rel="stylesheet" type="text/css" href="../assets/css/cmsadmin.css">
<link rel="stylesheet" type="text/css" href="../assets/css/dialog.css">
<?php if($cmscolor>1):?><link rel="stylesheet" type="text/css" href="../assets/css/cms-<?php echo$cmscolor;?>css.css"><?php endif;?>
<link rel="stylesheet" type="text/css" href="../assets/css/datatable.css">
<link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="../assets/css/buttons.datatables.min.css"/>
<script type="text/javascript" src="../assets/js/jquery.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery.tab.js"></script>
<script type="text/javascript" src="../assets/js/usualtool-cms-2.0.js"></script>
<script type="text/javascript" src="../assets/js/dialog.js" charset="utf-8"></script>
<script type="text/javascript" src="../assets/js/plupload.full.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery.dataTables.min.js"></script>
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
<a href="#" class="canvi-user-info__meta"><img src="../assets/images/devlogo.png" alt="logo"></a>
<div class="canvi-user-info__close" onClick="t.close();"></div>
</div>
</div>
<ul class="canvi-navigation">
<?php
$mod=$mysqli->query("select id,bid,modid,modname,modurl from `cms_mod` where bid='0'");
while($modrow=$mod->fetch_row()){
    $smod="SELECT id,bid,modid,modname,modurl,isopen from `cms_mod` WHERE bid='$modrow[0]'";
    $smods=mysqli_query($mysqli,$smod);
    if(mysqli_num_rows($smods)>0):
        echo"<p onclick='opennav(".$modrow[0].")'><b><i class='fa fa-cube'></i> ".$modrow[3]."</b></p><ul class='closenav' id='nav".$modrow[0]."' style='display:none;'>";
        $xmod=$mysqli->query($smod);
        while($xmodrow=$xmod->fetch_row()):
            ?>
            <li>
            <a href="ut-view-module.php?m=<?php echo$xmodrow[2];?>&u=<?php echo$xmodrow[4];?>" class="canvi-navigation__item" onclick="navclick('<?php echo$xmodrow[0];?>')"><span class="canvi-navigation__text"><i class="fa fa-th-large" aria-hidden="true"></i> <?php echo$xmodrow[3];?></span>
            </a>
            </li>
        <?php
        endwhile;
        echo"</ul>";
    else:
        echo"<p><a href='ut-view-module.php?m=".$modrow[2]."&u=".$modrow[4]."'><i class='fa fa-cube'></i> <b>".$modrow[3]."</b></a></p>";
    endif;
}
?>
</ul>
</aside>
<div id="cmswrap">
 <div id="cmshead">
 <div id="head">
  <div class="logo"><a href="./"><img src="../assets/images/devlogo.png" alt="logo"></a></div>
  <div class="navx">
<ul>
    <li class="noRight"><a href="ut-login.php?do=out"><i class="fa fa-power-off" aria-hidden="true"></i> 退出</a></li>
</ul>
   <ul class="navRight">
   <li><main class="js-canvi-content canvi-content"><a class="js-canvi-open-button--left btn"><i class="fa fa-navicon" aria-hidden="true"></i> 功能</a></main></li>
   </ul>
  </div>
<div class="nav">
    <ul>
    <li><a href="../cms/" target="_blank"><i class="fa fa-code" aria-hidden="true"></i> CMS平台</a></li>
    <?php
    $mod=$mysqli->query("select id,bid,modid,modname,modurl from `cms_mod` where bid='0'");
    while($modrow=$mod->fetch_row()){
        if($modrow[0]==1):
            $topnavicon='<i class="fa fa-cube" aria-hidden="true"></i>';
        elseif($modrow[0]==2):
            $topnavicon='<i class="fa fa-handshake-o" aria-hidden="true"></i>';
        elseif($modrow[0]==3):
            $topnavicon='<i class="fa fa-wrench" aria-hidden="true"></i>';
        endif;
        $smod="SELECT id,bid,modid,modname,modurl,isopen from `cms_mod` WHERE bid='$modrow[0]'";
        $smods=mysqli_query($mysqli,$smod);
        if(mysqli_num_rows($smods)>0):
            echo"<li class='M'><a href='JavaScript:void(0);'>".$topnavicon." ".$modrow[3]."</a><div class='drop mTopad'>";
            $xmod=$mysqli->query($smod);
            while($xmodrow=$xmod->fetch_row()):
            ?>
                <a href='ut-view-module.php?m=<?php echo$xmodrow[2];?>&u=<?php echo$xmodrow[4];?>' onclick="navclick('<?php echo$xmodrow[0];?>')"><?php echo$xmodrow[3];?></a>
            <?php
            endwhile;
            echo"</div></li>";
        else:
            echo"<li><a href='".$modrow[4]."'>".$topnavicon." ".$modrow[3]."</a></li>";
        endif;
    }
    ?>
    </ul>
   <ul class="navRight">
    <li><a href="ut-update.php"><i class="fa fa-spinner" aria-hidden="true"></i> 更新</a></li>
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
<li><a href="./" onclick="navclick('0')"><i class="home"></i><em>控制台</em></a></li>
</ul>
</div>
<div id="menu_left">
<ul class="lanmu-list">
<?php
$nav=$mysqli->query("select id,bid,modid,modname,modurl,backitem from `cms_mod` where bid=3 order by ordernum asc");
while($navrow=$nav->fetch_row()):
    if($navrow[0]==$navid):
        $class="cur";
    else:
        $class="";
    endif;
    ?>
    <li class='<?php echo$class;?>'><a href="ut-view-module.php?m=<?php echo$navrow[2];?>&u=<?php echo$navrow[4];?>" onclick="navclick('<?php echo$navrow[0];?>')"><?php echo$navrow[3];?></a></li>
<?php endwhile;?>
</ul>
<p align='center' style="background-color:#FFFACD;height:35px;"><a href="javascript:;" class="navbtn" style="color:black;text-align:center;line-height:35px;">更多</a></p>
</div>
<div id="menu_right">
<ul>
<?php
if(empty($navrid)||$navrid==0):
    echo"<li><i class='fa fa-paper-plane-o'></i> <a target='_blank' href='../'><em>查看站点</em></a></li>";
    echo"<li><i class='fa fa-paint-brush'></i> <a target='_blank' href='http://cms.usualtool.com'><em>在线帮助</em></a></li>";
    echo"<li><i class='fa fa-comments-o'></i> <a target='_blank' href='http://bbs.usualtool.com'><em>站长社区</em></a></li>";
else:
    $xnavrow=UsualToolCMSDB::queryData("cms_mod","id,bid,modid,modname,backitem","id='$navrid'","","","0")["querydata"][0];
    $xxbid=$xnavrow["bid"];
    $xxid=$xnavrow["modid"];
    $xxname=$xnavrow["modname"];
    $xxnav=$xnavrow["backitem"];
    if($xxbid==1 || $xxbid==2):
    echo"<li style='background-color:#F5F5F5;text-align:center;font-size:13px;font-weight:bold;height:30px;line-height:30px;' class='top'>".$xxname."</li>";
    endif;
    $xnavarr=explode(",",$xxnav);
    foreach($xnavarr as $nv):
        $xnavs=explode(":",$nv);
        $uarr=explode("php",$xnavs[1]);
        $url=$uarr[0]."php";
        $get=str_replace("?","",$uarr[1]);
        echo"<li><i class='fa fa-clone'></i> <a href='ut-view-module.php?m=".$xxid."&u=".$url."&".$get."'>".$xnavs[0]."</a></li>";
    endforeach;
endif;
?>
</ul>
</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    var lheight=Math.floor($("#menu_left").outerHeight(true)/7);
    $("#menu_right").css("margin",""+lheight+"px 0px");
    var len=12;
    var arr=$(".lanmu-list li:not(:hidden)");
    if(arr.length<len){
        $(".navbtn").hide();
    }
    if(arr.length>len){
        $('.lanmu-list li:gt('+(len-1)+')').hide();
    }
    $(".navbtn").click(function(){
        var arr=$(".lanmu-list li:not(:hidden)");
        if(arr.length>len){
            $('.lanmu-list li:gt('+(len-1)+')').hide();
            $(".navbtn").html("更多");
        }
        else{
            $('.lanmu-list li:gt('+(len-1)+')').show();
            $(".navbtn").html("折叠");
        }
    });
});
</script>
<script type="text/javascript" src="../assets/js/nav.js"></script>
<script>
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