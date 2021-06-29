<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
session_start();
require_once '../sql_db.php';
require_once 'ut-session.php';
require_once '../class/UsualToolCMS_DB.php';
require_once '../class/UsualToolCMS_INC.php';
require_once '../class/UsualToolCMS_Water.php';
$setup=UsualToolCMSDB::queryData("cms_setup","weburl,indexoss","","","1","0")["querydata"][0];
    $weburl=$setup["weburl"];
    $indexoss=$setup["indexoss"];
$wrow=UsualToolCMSDB::queryData("cms_water","","","","1","0")["querydata"][0];
    $water=$wrow["water"];
    $water_type=$wrow["water_type"];
    $water_place=$wrow["water_place"];
    $water_textcolor=$wrow["water_textcolor"];
    $water_textsize=$wrow["water_textsize"];
    $water_text=$wrow["water_text"];
    $water_png="../assets/".$wrow["water_png"]."";
if(!empty($_GET['l'])){
    $l=str_replace("..","",$_GET['l']);
}else{
    $l="images/upload/other";
}
$typeArr = array('jpg','jpeg','gif','png','zip','rar','mp4','mp3','m3u8','lrc','ico');
$path = "../assets/".$l."/";
if($_GET['get']=="upimg"&&isset($_POST)){
    $file=$_FILES['file'];
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $name_tmp = $_FILES['file']['tmp_name'];
    if(empty($name)) {
    echo json_encode(array("error"=>"You have not selected a picture!"));
    exit;
    }
    $type = strtolower(substr(strrchr($name, '.'), 1));
    if (!in_array($type, $typeArr)) {
    echo json_encode(array("error"=>"Please upload JPG/PNG/GIF!"));
    exit;
    }
    $pic_name = time() . rand(10000, 99999) . "." . $type; 
    $pic_url = $path . $pic_name;
    if($indexoss=="alioss"):
                require_once '../plugins/alioss/index.php';
                $utcmsoss = new UTCMSOSS();
                $obj = $utcmsoss->UTCMSToAliOSS($file,".".$type."");
                if ($obj['status'] == true){
                    echo json_encode(array("error"=>"0","pic"=>$obj['path'],"name"=>$pic_name));
                }else{
                    echo json_encode(array("error"=>"Upload error, check OSS configuration!"));
                }
    elseif($indexoss=="qiniuoss"):
                require_once '../plugins/qiniuoss/index.php';
                $utcmsoss = new UTCMSOSS();
                $obj = $utcmsoss->UTCMSToQINIUOSS($file,".".$type."");
                if ($obj['status'] == true){
                    echo json_encode(array("error"=>"0","pic"=>$obj['path'],"name"=>$pic_name));
                }else{
                    echo json_encode(array("error"=>"Upload error, check OSS configuration!"));
                }
    else:
    if (move_uploaded_file($name_tmp, $pic_url)){
        if($water==1):
            if($water_type=="text"):
                UsualToolCMSWater::markWater($water_type,$pic_url,$water_text,$water_place,$water_textcolor,$water_textsize);
            elseif($water_type=="image"):
                UsualToolCMSWater::markWater($water_type,$pic_url,$water_png,$water_place);
            endif;
        endif;
        echo json_encode(array("error"=>"0","pic"=>str_replace("../","".$weburl."/",$pic_url),"name"=>$pic_name));
    }else{
    echo json_encode(array("error"=>"Upload error, check server configuration!"));
    }
    endif;
}
if($_POST['get']=="delimg"){
    $imgsrc = UsualToolCMS::sqlcheck(str_replace("..","",$_POST['imgurl']));
    if(in_array(substr($imgsrc,-4),array(".jpg",".png",".gif"))):
        UsualToolCMS::unlinkfile($imgsrc);
        echo"1";
    else:
        echo"0";
    endif;
}