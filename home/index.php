<?php
require_once(dirname(dirname(__FILE__)).'/'.'conn.php');
define('USER_PATH',$weburl.'/'.'modules/member/home');
$auths=UsualToolCMSDB::authLogin();
$currentpage=UsualToolCMS::curpageurl();
if(!empty($_GET["ut"])):
    $ut=UsualToolCMS::sqlcheck($_GET["ut"]);
else:
    $ut="my-home";
endif;
if(defined('REWRITE')):
    if(REWRITE=="1"):
        $listlink="".$ut.".html?";
    else:
        $listlink="index.php?ut=".$ut."&";
    endif;
else:
    $listlink="".$ut.".html?";
endif;
$modules=json_decode(file_get_contents("../modules/module.config"));
$module=UsualToolCMS::modsearch("".$ut.".php",$modules);
$modpath="modules/".$module;
if(strpos($ut,"my-")!==false){
    $modulex=$module."/"."home";
}else{
    $modulex=$module;
}
if($module=="error"){
    require_once(UTF_PATH.'/'.'error.php');
}else{
	require_once(WEB_PATH.'/'.'modules/'.$modulex.'/'.$ut.'.php');
}
$mysqli->close();
?>