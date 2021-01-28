<?php
header('content-type:application/json;charset=utf8');
require_once(dirname(__FILE__).'/'.'../class/UsualToolCMS_INC.php');
$words=UsualToolCMS::sqlcheck($_GET["word"]);
$lang=UsualToolCMS::sqlcheck($_GET["lang"]);
if(!empty($lang)):
    $lg=$lang;
else:
    $lg="zh";
endif;
$word=explode(",",$words);
for($i=0;$i<count($word);$i++){
$thisword[]=array("word"=>LangData($word[$i],$lg));
}
echo json_encode($thisword,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);