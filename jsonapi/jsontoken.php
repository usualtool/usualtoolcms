<?php
require_once(dirname(__FILE__).'/'.'../sql_db.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_DB.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_WeChat.php');
$type=$_GET["type"];
if($type=="public"):
    $row=UsualToolCMSDB::queryData("cms_wechat","","","","1","0")["querydata"][0];
    $id=$row["id"];
    $appline=$row["appline"];
    $appid=$row["appid"];
    $appsecret=$row["appsecret"];
    $apptoken=$row["apptoken"];
    $appaeskey=$row["appaeskey"];
    $mywechat=new UsualToolWeChat($appline,$appid,$appsecret,$apptoken);
elseif($type=="routine"):
    $row=UsualToolCMSDB::queryData("cms_routine","","","","1","0")["querydata"][0];
    $id=$row["id"];
    $appline=$row["appline"];
    $rteid=$row["rteid"];
    $rtesecret=$row["rtesecret"];
    $rtetoken=$row["rtetoken"];
    $rteaeskey=$row["rteaeskey"];
    $mywechat=new UsualToolWeChat($appline,$rteid,$rtesecret,$rtetoken);
endif;
if(!isset($_GET['echostr'])):
    $mywechat->responseMsg();
else:
    $mywechat->valid();
endif;