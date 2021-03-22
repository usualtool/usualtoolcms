<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
session_start();
require_once '../sql_db.php';
require_once 'ut-session.php';
$setup=$mysqli->query("select weburl,indexoss from `cms_setup` limit 1");
while($setuprow=mysqli_fetch_array($setup)):
    $weburl=$setuprow["weburl"];
    $indexoss=$setuprow["indexoss"];
endwhile;
if(!empty($_POST['l'])){
    $l=str_replace("..","",$_POST['l']);
}
else{
    $l="images/upload/other";
}
$path = "../assets/".$l."/";
$file = $_FILES['file'];
$name = $file['name'];
$type = strtolower(substr($name,strrpos($name,'.')+1));
$fname="".date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT).".".$type."";
$pic_url = $path . $fname;
$allow_type = array('jpg','jpeg','gif','png','zip','rar','mp4','mp3','m3u8','lrc');
if(!in_array($type, $allow_type)){
    echo "The file format is incorrect!<br>|-|The file format is incorrect!";
}elseif(!is_uploaded_file($file['tmp_name'])){
    echo "Illegal source of documents!<br>|-|Illegal source of documents!";
}else{
    if($indexoss=="alioss"):
        require_once '../plugins/alioss/index.php';
        $utcmsoss = new UTCMSOSS();
        $obj = $utcmsoss->UTCMSToAliOSS($file,".".$type."");
        if ($obj['status'] == true){echo "File uploaded successfully!<br>|-|".$obj['path']."";}else{echo "File upload failed!<br>|-|";}
    elseif($indexoss=="qiniuoss"):
        require_once '../plugins/qiniuoss/index.php';
        $utcmsoss = new UTCMSOSS();
        $obj = $utcmsoss->UTCMSToQINIUOSS($file,".".$type."");
        if ($obj['status'] == true){echo "File uploaded successfully!<br>|-|".$obj['path']."";}else{echo "File upload failed!<br>|-|";}
    else:
    if (move_uploaded_file($file['tmp_name'],$pic_url)){
        echo "File uploaded successfully!<br>|-|".str_replace("../","".$weburl."/",$pic_url)."";}else{echo "File upload failed!<br>|-|";}
    endif;
}