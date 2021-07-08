<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
require_once '../../conn.php';
    if(!empty($_GET['l'])){
        $l=str_replace("..","",$_GET['l']);
    }else{
        $l="images/upload/other";
    }
    $typeArr = array("docx", "doc", "jpg", "png", "xlsx", "xls");
    $path = WEB_PATH."/"."assets/".$l."/";
    $file=$_FILES['file'];
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $name_tmp = $_FILES['file']['tmp_name'];
    if(empty($name)){
        echo json_encode(array("error"=>"You have not selected any files!"));
        exit;
    }
    $type = strtolower(substr(strrchr($name, '.'), 1));
    if(!in_array($type, $typeArr)){
        echo json_encode(array("error"=>"Please upload the specified file!"));
        exit;
    }
    $pic_name = time() . rand(10000, 99999) . "." . $type; 
    $pic_url = $path . $pic_name;
    if(move_uploaded_file($name_tmp, $pic_url)){
        echo json_encode(array("error"=>"0","pic"=>str_replace("/assets/","".$weburl."/assets/",str_replace(WEB_PATH,"",$pic_url)),"name"=>$pic_name));
    }else{
        echo json_encode(array("error"=>"Configuration Error!"));
    }
?>