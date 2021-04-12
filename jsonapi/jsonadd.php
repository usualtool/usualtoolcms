<?php
header('content-type:application/json;charset=utf8');
require_once(dirname(__FILE__).'/'.'../sql_db.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_INC.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_DB.php');
$setup=UsualToolCMSDB::queryData(
"cms_setup",
"authcode,weburl,indexlanguage",
"",
"",
"1",
"0")["querydata"][0];
    $authcode=$setup["authcode"];
    $weburl=substr($setup["weburl"],0,-1);
    $indexlg=$setup["indexlanguage"];
if(!empty($_COOKIE['UTCMSLanguage'])):
    $language=UsualToolCMS::sqlcheck($_COOKIE['UTCMSLanguage']);
else:
    $language=$indexlg;setcookie("UTCMSLanguage",$indexlg);
endif;
$t=UsualToolCMS::sqlcheck($_GET["t"]);
$auth=UsualToolCMS::sqlcheck($_GET["auth"]);
$table=UsualToolCMS::sqlcheck($_GET["table"]);
$where=str_replace("%27","'",UsualToolCMS::sqlcheck(str_replace("'","%27",str_replace("]","",str_replace("[","",$_GET["where"])))));
if($authcode==$auth):
	if(empty($t) || $t=="add"):
        if(UsualToolCMSDB::insertData($table,$_POST)):
		    echo "1";
		else:
		    echo "0";
		endif;
	elseif($t=="mon"):
		if(UsualToolCMSDB::updateData($table,$_POST,$where)):
			echo "1";
		else:
			echo "0";
		endif;
	elseif($t=="del"):
		if(UsualToolCMSDB::delData($table,$where)):
			echo "1";
		else:
			echo "0";
		endif;
	endif;
else:
    echo "0";
endif;