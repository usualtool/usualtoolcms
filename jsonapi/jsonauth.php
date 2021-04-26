<?php
header('content-type:application/json;charset=utf8');
require_once(dirname(__FILE__).'/'.'../sql_db.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_INC.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_DB.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_WeChat.php');
$setup=UsualToolCMSDB::queryData(
"cms_setup",
"authcode,weburl,indexlanguage",
"",
"",
"1",
"0")["querydata"][0];
    $authcode=$setup["authcode"];
    $weburl=substr($setup["weburl"],0,-1);
$type=UsualToolCMS::sqlcheck($_GET["type"]);
$auth=UsualToolCMS::sqlcheck($_GET["auth"]);
$code=UsualToolCMS::sqlcheck($_GET["code"]);
if($authcode==$auth):
	if($type=="wechat"):
		$autharr=UsualToolCMSDB::queryData("cms_routine","wxrteid,wxrtesecret","","","1","0")["querydata"][0];
		$appid=$autharr["wxrteid"];
		$appkey=$autharr["wxrtesecret"];
	    $result=file_get_contents("https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$appkey."&js_code=".$code."&grant_type=authorization_code");
	elseif($type=="wechat-crypt"):
        $appid=UsualToolCMSDB::queryData("cms_routine","wxrteid","","","1","0")["querydata"][0]["wxrteid"];
        $encrypteddata=$_POST["encrypteddata"];
        $iv=$_POST["iv"];
        $openid=UsualToolCMS::sqlcheck($_POST["openid"]);
        $thirdkey=$_POST["thirdkey"];
        $crypt=new UTWechatCrypt($appid,$thirdkey);
        $errCode=$crypt->decryptData($encrypteddata,$iv,$data);
        if($errCode==0):
            $result=$data;
        else:
            $result=$errCode;
        endif;
	else:
		$appid="";
		$appkey="";
		$result="0";
	endif;
	echo$result;
else:
    echo "0";
endif;