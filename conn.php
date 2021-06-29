<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
ini_set('magic_quotes_gpc',0);
define('WEB_PATH',dirname(__FILE__));
define('UTF_PATH',WEB_PATH.'/'.'modules/UTFrame');
session_start();
require_once(WEB_PATH.'/'.'sql_db.php');
require_once(WEB_PATH.'/'.'class/UsualToolCMS_DB.php');
require_once(WEB_PATH.'/'.'class/UsualToolCMS_INC.php');
require_once(WEB_PATH.'/'.'class/UsualToolCMS_Temp.php');
require_once(WEB_PATH.'/'.'class/UsualToolCMS_Page.php');
require_once(WEB_PATH.'/'.'class/UsualToolCMS_Mail.php');
require_once(WEB_PATH.'/'.'class/UsualToolCMS_Tree.php');
if(UsualToolCMS::isetup()==false):header("location:./setup/");exit();endif;
/*----Read UT Default Seting----*/
$setup=UsualToolCMSDB::queryData("cms_setup","","","","1","0")["querydata"][0];
    $weburl=$setup["weburl"];
    $webnames=$setup["webname"];
    $webemail=$setup["webemail"];
    $webkeyword=$setup["webkeyword"];
    $weblogo=$setup["weblogo"];
    $webdescribe=$setup["webdescribe"];
    $webisclose=$setup["webisclose"];
    $webqq=$setup["webqq"];
    $webtel=$setup["webtel"];
    $webfax=$setup["webfax"];
    $webicp=$setup["webicp"];
    $webple=$setup["webple"];
    $indexunit=$setup["indexunit"];
    $indexoss=$setup["indexoss"];
    $indexeditor=$setup["indexeditor"];
    $indexlanguage=$setup["indexlanguage"];
	$indexmodule=$setup["indexmodule"];
    $languagex=explode(",",$setup["language"]);
    $usercookname=$setup["usercookname"];
    $salts=$setup["salts"];
    $address=$setup["address"];
    $articlesku=$setup["articlesku"];
    $goodssku=$setup["goodssku"];
    $develop=$setup["develop"];
    $template=$setup["template"];
    $openhtml=$setup["openhtml"];
    $Uagent=$_SERVER["HTTP_USER_AGENT"];
    $Ospat="mobile";
if($openhtml==1):
    if(WEB_PATH==getcwd()):
        header("location:./html/".str_replace("php","html",substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1))."");exit();
    endif;
endif;
for($ll=0;$ll<count($languagex);$ll++):
    $langdata[]=array("langtype"=>$languagex[$ll],"langname"=>LangSet("speak",$languagex[$ll]));
endfor;
if(!empty($_COOKIE['UTCMSLanguage'])):
    $language=UsualToolCMS::sqlcheck($_COOKIE['UTCMSLanguage']);
    else:
        if($indexlanguage=="big5"):
            $language="zh";setcookie("UTCMSLanguage","zh");setcookie("chinaspeak","big5");
        else:
            $language=$indexlanguage;setcookie("UTCMSLanguage",$indexlanguage);setcookie("chinaspeak","");
        endif;
endif;
if($webnames=="web"):
    $webname=LangSet($webnames);
else:
    $webname=$webnames;
endif;
if($webisclose==1 && strpos($_SERVER['PHP_SELF'],"dev")===FALSE && strpos($_SERVER['PHP_SELF'],"cms")===FALSE && $_GET["ut"]!=="updating"):
    echo"<script>window.location.href='updating.html'</script>";
endif;
/*----Read UT Member----*/
if(isset($_SESSION[''.$usercookname.'user'])&&isset($_SESSION[''.$usercookname.'userid'])&&isset($_SESSION[''.$usercookname.'usermail'])):
    $uid=$_SESSION[''.$usercookname.'userid'];$user=$_SESSION[''.$usercookname.'user'];$usermail=$_SESSION[''.$usercookname.'usermail'];
    $users=UsualToolCMSDB::queryData("cms_users_level as A,cms_users as B","","A.id=B.level and B.id='$uid' and B.level>0","","","0");
    if($users["querynum"]==1):
        $level=$usersrow["level"];
        $levelname=$usersrow["levelname"];
        $discount=$usersrow["discount"];
    else:
        $level=0;$levelname="0";$discount=100;
    endif;
else:
    $uid="";$user="";$usermail="";$level="";$levelname="";$discount="";
endif;
if(!empty($uid)):
    $shopcartnum=UsualToolCMSDB::queryData("cms_goods_cart","id","uid='$uid'","","","0")["querynum"];
else:
    $shopcartnum=0;
endif;
/*----Read UT miniProgram----*/
if(strpos($_SERVER['HTTP_USER_AGENT'],'miniProgram')!==false):
    if(!empty($_GET["openid"])):
        $_SESSION['openid']=UsualToolCMS::sqlcheck($_GET["openid"]);
    endif;
    if(isset($_SESSION['openid'])):
        $udata=UsualToolCMSDB::queryData("cms_users","id,username","wwpass='".$_SESSION['openid']."'","","1","0")["querydata"][0];
        $_SESSION[''.$usercookname.'user']=$udata["username"];
        $_SESSION[''.$usercookname.'userid']=$udata['id'];
        $_SESSION[''.$usercookname.'usermail']="1";
    endif;
endif;
/*----Read UT template----*/
$mytpl=new UsualToolTemp($develop,$template."/skin/",$template."/cache/");
$planid=UsualToolCMSDB::queryData("cms_nav_plan","id","indexplan=1","","1","0")["querydata"][0]["id"];
/*----Read UT Basic Setup----*/
$mytpl->runin(
    array("rewrite","langdata","weblogo","temproot","webname","weburl","webkeywords","webdescription","webicp","webple","address","webtel","webfax","webemail","webqq"),
    array(REWRITE,$langdata,$weblogo,$template,$webname,$weburl,$webkeyword,$webdescribe,$webicp,$webple,$address,$webtel,$webfax,$webemail,$webqq)
);
/*----Read UT Navigation----*/
$mytpl->runin(
    array("tnavs","inavs","bnavs"),
    array(
        UsualToolCMSDB::queryData("cms_nav","linkname,linkurl","place='top' and planid='$planid'","ordernum asc","","0")["querydata"],
        UsualToolCMSDB::queryData("cms_nav","linkname,linkurl","place='index' and planid='$planid'","ordernum asc","","0")["querydata"],
        UsualToolCMSDB::queryData("cms_nav","linkname,linkurl","place='bottom' and planid='$planid'","ordernum asc","","0")["querydata"])
);
/*----Read UT MemberLogin----*/
$mytpl->runin(
    array("uid","user","usermail","level","levelname","discount","shopcartnum"),
    array($uid,$user,$usermail,$level,$levelname,$discount,$shopcartnum)
);