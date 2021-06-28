<?php
header('content-type:application/json;charset=utf8');
require_once(dirname(__FILE__).'/'.'../sql_db.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_INC.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_DB.php');
$setup=UsualToolCMSDB::queryData("cms_setup","authcode,weburl,indexlanguage","","","1","0")["querydata"][0];
    $authcode=$setup["authcode"];
    $weburlx=$setup["weburl"];
    $indexlg=$setup["indexlanguage"];
    $endstr=substr($weburlx,strlen($weburlx)-1);
    if($endstr=="/"):
        $weburl=substr($weburlx,0,strlen($weburlx)-1);
    else:
        $weburl=$weburlx;
    endif;
$lang=UsualToolCMS::sqlcheck($_GET["lang"]);
if($lang==1):
    $lg=1;
	if(!empty($_COOKIE['UTCMSLanguage'])):
		$language=UsualToolCMS::sqlcheck($_COOKIE['UTCMSLanguage']);
	else:
		$language=$indexlg;setcookie("UTCMSLanguage",$indexlg);
	endif;
else:
	$lg=0;
endif;
$auth=UsualToolCMS::sqlcheck($_GET["auth"]);
$table=UsualToolCMS::sqlcheck($_GET["table"]);
$field=UsualToolCMS::sqlcheck($_GET["field"]);
$where=str_replace("%27","'",UsualToolCMS::sqlcheck(str_replace("'","%27",str_replace("]","",str_replace("[","",$_GET["where"])))));
$limit=UsualToolCMS::sqlcheck($_GET["limit"]);
$order=UsualToolCMS::sqlcheck($_GET["order"]);
$class=UsualToolCMS::sqlcheckx($_GET["class"]);
if($authcode==$auth):
    $data=UsualToolCMSDB::queryData(
    $table,
    $field,
    $where,
    $order,
    $limit,
	$lg
    );
    $querydata=$data["querydata"];
    if($class==1):
        for($i=0;$i<count($querydata);$i++):
            $catname=UsualToolCMSDB::queryData("".$table."_cat","classname","id='".$querydata[$i]['catid']."'","","")["querydata"][0]['classname'];
            $catnames[]=array("catname"=>$catname);
        endfor;
        UsualToolCMS::arraymerge($querydata,$catnames);
    endif;
    $jsondata=str_replace("http:","https:",str_replace(',assets\/images',','.$weburl.'\/assets\/images',str_replace('"assets\/images','"'.$weburl.'\/assets\/images',json_encode($querydata,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT))));
    echo$jsondata;
endif;