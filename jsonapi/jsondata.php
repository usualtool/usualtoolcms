<?php
header('content-type:application/json;charset=utf8');
require_once(dirname(__FILE__).'/'.'../sql_db.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_INC.php');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_DB.php');
$setup=$mysqli->query("select authcode,weburl,indexlanguage from `cms_setup` limit 1");
while($setuprow=mysqli_fetch_array($setup)):
    $authcode=$setuprow["authcode"];
    $weburl=substr($setuprow["weburl"],0,-1);
    $indexlg=$setuprow["indexlanguage"];
endwhile;
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
    $jsondata=str_replace("http:","https:",str_replace(',images',','.$weburl.'\/images',str_replace('"images','"'.$weburl.'\/images',json_encode($querydata,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT))));
    echo$jsondata;
endif;