<h2>提取过程中，不要关闭浏览器......</h2>
<?php
$sign=UsualToolCMS::sqlcheck($_GET["sign"]);
$c=UsualToolCMS::sqlcheck($_GET["c"]);
$z=UsualToolCMS::sqlcheck($_GET["z"]);
$zipurl=UsualToolCMS::sqlcheck($_GET["zipurl"]);
$zipname=UsualToolCMS::sqlcheck($_GET["zipname"]);
$file=UsualToolCMS::sqlcheck(str_replace("..","",$_GET["file"]));
$delfile=UsualToolCMS::sqlcheck($_GET["delfile"]);
$updatesql=UsualToolCMS::sqlcheck($_GET["updatesql"]);
if(!empty($_GET["id"])):
    $id=UsualToolCMS::sqlcheck(str_replace("../","",$_GET["id"]));
else:
    $signs=explode("_",$sign);
    $id=$signs[1];
endif;
if(!empty($zipurl)):
    echo"<p>正在执行安装...</p>";
    $downs=UsualToolCMS::auth($authcode,$authapiurl,$sign);
    $tempurl=UsualToolCMS::str_substr("<tpurl>","</tpurl>",$downs);
    $url=UsualToolCMS::str_substr("<downurl>","</downurl>",$downs);
    $save_dir = "../templete";  
    $filename =basename($url); 
    $res = UsualToolCMS::getfile($url, $save_dir, $filename,1);
    if(!empty($res)):
        UsualToolCMS::auth($authcode,$authapiurl,$sign);
        echo"<p>正在写入模板文件...</p>";
        echo "<script>window.location.href='?m=templete&u=a_templetet.php&id=$id&c=$c&z=$z&file=$filename&zipname=usualtoolcms'</script>";
    else:
        echo "<script>alert('安装失败!请检查templete目录是否有修改（写入）权限!或者您不具有安装权限,请联系客服!');window.history.back(-1);</script>";
    endif;
endif;
if(!empty($zipname)):
    UsualToolCMS::auth($authcode,$authapiurl,"templetedel-".str_replace(".zip","",$file)."");
    $zip=new ZipArchive;
    if($zip->open("../templete/".$file."")===TRUE): 
        $zip->extractTo('../templete/');
        $zip->close();
        echo "<script>window.location.href='?m=templete&u=a_templetet.php&id=$id&c=$c&z=$z&file=$file&delfile=usualtoolcms'</script>";
    else:
        echo "<script>alert('解压失败!请检查templete目录是否有修改（写入）权限!');window.history.back(-1);</script>";
    endif;
endif;
if(!empty($delfile)):
    echo"<p>正在删除模板包...</p>";
    unlink("../templete/".$file."");
    echo "<script>window.location.href='?m=templete&u=a_templetet.php&id=$id&c=$c&z=$z&updatesql=usualtoolcms'</script>";
endif;
if(!empty($updatesql)):
    echo"<p>正在更新模板数据...</p>";
    $paths="templete/".$id."";
    $sqls="INSERT INTO `cms_templete` (`paths`, `isopen`, `title`, `version`, `author`) VALUES ('$paths',0,'$c','1.0','---')";
    if(UsualToolCMSDB::insertData("cms_templete",array(
        "paths"=>$paths,
        "isopen"=>0,
        "title"=>$c,
        "version"=>"1.0",
        "author"=>"---"))):
        echo "<script>alert('模板安装成功,请在已提取的模板中启用!');window.location.href='?m=templete&u=a_templete.php'</script>";
    else:
        echo "<script>alert('虽然模板已经安装到templete目录,但模板入库失败,请手动更改模板路径!');window.location.href='?m=templete&u=a_templete.php'</script>";
    endif;
endif;
?>