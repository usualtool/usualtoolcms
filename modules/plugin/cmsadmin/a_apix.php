<?php
$t=UsualToolCMS::sqlcheck($_GET["t"]);
$sign=UsualToolCMS::sqlcheck($_GET["sign"]);
$zipurl=UsualToolCMS::sqlcheck($_GET["zipurl"]);
$zipname=UsualToolCMS::sqlcheck($_GET["zipname"]);
$file=UsualToolCMS::sqlcheck(str_replace("..","",$_GET["file"]));
$delfile=UsualToolCMS::sqlcheck($_GET["delfile"]);
$updatesql=UsualToolCMS::sqlcheck($_GET["updatesql"]);
$ptype=UsualToolCMS::sqlcheckx($_GET["ptype"]);
if(!empty($_GET["id"])):
    $id=UsualToolCMS::sqlcheck(str_replace("../","",$_GET["id"]));
else:
    $signs=explode("-",$sign);
    $id=$signs[1];
endif;
$hookconfig="../plugins/".$id."/usualtoolcms.config";
if($t=="del"):
    if(!empty($zipurl)):
        echo"<p>正在执行卸载..</p>";
        if($setup["delplugindb"]==1):
            $hooks=file_get_contents($hookconfig);
            $uninstallsql=UsualToolCMS::str_substr("<uninstallsql><![CDATA[","]]></uninstallsql>",$hooks);
            $mysqli->multi_query($uninstallsql);
        endif;
        if(UsualToolCMSDB::delData("cms_plugins","id='$id'")):
            echo "<script>window.location.href='?m=".$mod."&u=a_apix.php&t=$t&id=$id&delfile=usualtoolcms'</script>";
        else:
            echo "<script>alert('插件卸载失败!');window.location.href='?m=".$mod."&u=a_api.php'</script>";
        endif;
    endif;
    if(!empty($delfile)):
        echo"<p>正在卸载残留文件</p>";
        UsualToolCMS::deldir("../plugins/".$id."/");
        rmdir("../plugins/".$id."/");
        echo "<script>alert('插件卸载成功!');window.location.href='?m=".$mod."&u=a_api.php'</script>";
    endif;
endif;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($t=="setup"&&$ptype=="2"):
    echo"<p>正在更新插件数据...</p>";
    $hooks=file_get_contents($hookconfig);
    $type=UsualToolCMS::str_substr("<type>","</type>",$hooks);
    $auther=UsualToolCMS::str_substr("<auther>","</auther>",$hooks);
    $title=UsualToolCMS::str_substr("<title>","</title>",$hooks);
    $pluginname=UsualToolCMS::str_substr("<pluginname>","</pluginname>",$hooks);
    $ver=UsualToolCMS::str_substr("<ver>","</ver>",$hooks);
    $description=UsualToolCMS::str_substr("<description>","</description>",$hooks);
    $installsql=UsualToolCMS::str_substr("<installsql><![CDATA[","]]></installsql>",$hooks);
    $resql="INSERT INTO `cms_plugins` (id,type,auther,title,pluginname,ver,description) VALUES ('$id','$type','$auther','$title','$pluginname','$ver','$description')";
    $ress=$mysqli->query($resql);
    if($ress):
    $mysqli->multi_query($installsql);
        echo "<script>alert('插件安装成功!');window.location.href='?m=".$mod."&u=a_api.php'</script>";
    else:
        echo "<script>alert('插件已安装,但插件入库失败,请联系官方处理!');window.location.href='?m=".$mod."&u=a_api.php'</script>";
    endif;
elseif($t=="setup"&&($ptype=="1"||empty($ptype))):
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if(!empty($zipurl)):
        echo"<p>正在执行安装...</p>";
        $downs=UsualToolCMS::auth($authcode,$authapiurl,$sign);
        $pluginid=UsualToolCMS::str_substr("<id>","</id>",$downs);
        $url=UsualToolCMS::str_substr("<downurl>","</downurl>",$downs);
        $save_dir = "../plugins";  
        $filename =basename($url); 
        $res = UsualToolCMS::getfile($url, $save_dir, $filename,1);
        if(!empty($res)):
            echo"<p>正在写入插件...</p>";
            echo "<script>window.location.href='?m=".$mod."&u=a_apix.php&t=$t&id=$id&file=$filename&zipname=usualtoolcms'</script>";
        else:
            echo "<script>alert('安装失败!请检查plugins目录是否有修改（写入）权限!或者您不具有安装权限,请联系客服!');window.location.href='?m=".$mod."&u=a_api.php'</script>";
        endif;
    endif;
    if(!empty($zipname)):
        UsualToolCMS::auth($authcode,$authapiurl,"plugindel-".str_replace(".zip","",$file)."");
        $zip=new ZipArchive;
        if($zip->open("../plugins/".$file."")===TRUE): 
            $zip->extractTo('../plugins/');
            $zip->close();
            echo "<script>window.location.href='?m=".$mod."&u=a_apix.php&t=$t&id=$id&file=$file&delfile=usualtoolcms'</script>";
        else:
            echo "<script>alert('解压失败!请检查plugins目录是否有修改（写入）权限!');window.location.href='?m=".$mod."&u=a_api.php'</script>";
        endif;
    endif;
    if(!empty($delfile)):
        echo"<p>正在删除插件包...</p>";
        unlink("../plugins/".$file."");
        echo "<script>window.location.href='?m=".$mod."&u=a_apix.php&t=$t&id=$id&updatesql=usualtoolcms'</script>";
    endif;
    if(!empty($updatesql)):
        echo"<p>正在更新插件数据...</p>";
        $hooks=file_get_contents($hookconfig);
        $type=UsualToolCMS::str_substr("<type>","</type>",$hooks);
        $auther=UsualToolCMS::str_substr("<auther>","</auther>",$hooks);
        $title=UsualToolCMS::str_substr("<title>","</title>",$hooks);
        $pluginname=UsualToolCMS::str_substr("<pluginname>","</pluginname>",$hooks);
        $ver=UsualToolCMS::str_substr("<ver>","</ver>",$hooks);
        $description=UsualToolCMS::str_substr("<description>","</description>",$hooks);
        $installsql=UsualToolCMS::str_substr("<installsql><![CDATA[","]]></installsql>",$hooks);
        if(UsualToolCMSDB::insertData("cms_plugins",array(
            "id"=>$id,
            "type"=>$type,
            "auther"=>"---",
            "title"=>$title,
            "pluginname"=>$pluginname,
            "ver"=>$ver,
            "description"=>$description))):
            $mysqli->multi_query($installsql);
            echo "<script>alert('插件安装成功!');window.location.href='?m=".$mod."&u=a_api.php'</script>";
        else:
            echo "<script>alert('插件已安装,但插件入库失败,请联系官方处理!');window.location.href='?m=".$mod."&u=a_api.php'</script>";
        endif;
    endif;
else:
    echo "<script>alert('安装插件失败,请重新安装!');window.location.href='?m=".$mod."&u=a_api.php'</script>";
endif;
?>