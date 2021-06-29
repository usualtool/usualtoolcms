<h2>安装过程中，不要关闭浏览器......</h2>
<?php
$t=UsualToolCMS::sqlcheck($_GET["t"]);
$do=UsualToolCMS::sqlcheck($_GET["do"]);
$sign=UsualToolCMS::sqlcheck($_GET["sign"]);
$roleranges=UsualToolCMSDB::queryData("cms_admin_role","","id=1","","","0")["querydata"][0]["ranges"];
if(!empty($_GET["id"])):
    $id=UsualToolCMS::sqlcheck(str_replace("../","",$_GET["id"]));
else:
    $signs=explode("-",$sign);
    $id=$signs[1];
endif;
$zipname=UsualToolCMS::sqlcheck($_GET["zipname"]);
$copyname=UsualToolCMS::sqlcheck($_GET["copyname"]);
$dbname=UsualToolCMS::sqlcheck($_GET["dbname"]);
$upname=UsualToolCMS::sqlcheck($_GET["upname"]);
$retemp=UsualToolCMS::sqlcheck($_GET["retemp"]);
$file=UsualToolCMS::sqlcheck(str_replace("..","",$_GET["file"]));
$delfile=UsualToolCMS::sqlcheck($_GET["delfile"]);
$modconfig="../modules/".$id."/usualtoolcms.config";
if($t=="resetup"):
    echo"<p>清除数据及文件结构...</p>";
    $mods=file_get_contents($modconfig);
    $modurl=UsualToolCMS::str_substr("<modurl>","</modurl>",$mods);
    $befoitem=UsualToolCMS::str_substr("<befoitem>","</befoitem>",$mods);
    $backitem=UsualToolCMS::str_substr("<backitem>","</backitem>",$mods);
    $uninstallsql=UsualToolCMS::str_substr("<uninstallsql><![CDATA[","]]></uninstallsql>",$mods);
    if($befoitem!=="NULL"):
        $befoitemarr=explode(",",$befoitem);
        foreach($befoitemarr as $be):
            if(UsualToolCMS::contain("dir:",$be)===false):
                if(file_exists("../".$be."")):
                    unlink("../".$be."");
                endif;
                if(is_dir("../".$be."")):
                    UsualToolCMS::deldir("../".$be."");
                endif;
            endif;
        endforeach;
    endif;
    $jsonstr=file_get_contents("../modules/module.config");
    $data=json_decode($jsonstr,true);
    $jsondata=UsualToolCMS::delmodarray($data, $id);
    $jsonstrs=json_encode($jsondata);
    file_put_contents("../modules/module.config",$jsonstrs);
    UsualToolCMSDB::delData("cms_mod","modurl='$modurl'");
    $roleurl=substr(str_replace(".php","",$modurl),1);
    $delranges=str_replace(",".$roleurl."","",str_replace("".$roleurl.",","",$roleranges));
    UsualToolCMSDB::updateData("cms_admin_role",array("ranges"=>$delranges),"id=1");
    if($setup["delmoddb"]==1):
        $ress=$mysqli->multi_query($uninstallsql);
    else:
        $ress=1;
    endif;
    if($ress):
        echo"<p>清除数据及文件结构成功...</p>";
        echo "<script>window.location.href='?m=module&u=a_modsx.php&id=$id&do=$do&t=delfile'</script>";
    else:
        echo "<script>alert('模块卸载失败!');window.location.href='?m=module&u=a_mods.php&do=$do'</script>";
    endif;
endif;
if($t=="delfile"):
    UsualToolCMS::deldir("../modules/".$id."");
    echo "<script>alert('模块卸载成功!');window.location.href='?m=module&u=a_mods.php&do=$do'</script>";
endif;
if($t=="setup"&&$do!=="z"):
    echo"<p>正在执行安装...</p>";
    if(!empty($sign)):
        $downs=UsualToolCMS::auth($authcode,$authapiurl,"moduleorder-".$id."");
    else:
        $downs=UsualToolCMS::auth($authcode,$authapiurl,"module-".$id."");
    endif;
    $moduleid=UsualToolCMS::str_substr("<id>","</id>",$downs);
    $urls=UsualToolCMS::str_substr("<downurl>","</downurl>",$downs);
    if(UsualToolCMS::httpcode($urls)=="200"):
        $url=$urls;
    else:
        $url="".$weburl."/modules/".$id.".zip"; 
    endif;
    $save_dir = "../modules";  
    $filename =basename($url); 
    $res = UsualToolCMS::getfile($url,$save_dir,$filename,1);
    if(!empty($res)):
        echo"<p>正在写入模块文件...</p>";
        echo "<script>window.location.href='?m=module&u=a_modsx.php&id=$id&do=$do&file=$filename&zipname=usualtoolcms'</script>";
    else:
        echo "<script>alert('安装失败!请检查modules目录是否有修改（写入）权限!或者您不具有安装权限,请联系客服!');window.location.href='?m=module&u=a_mods.php&do=$do'</script>";
    endif;
endif;
if(!empty($zipname)):
    UsualToolCMS::auth($authcode,$authapiurl,"moduledel-".str_replace(".zip","",$file)."");
    $zip=new ZipArchive;
    if($zip->open("../modules/".$file."")===TRUE): 
        $zip->extractTo('../modules/');
        $zip->close();
        echo "<p>解压成功</p>";
        echo "<script>window.location.href='?m=module&u=a_modsx.php&id=$id&do=$do&file=$file&delfile=usualtoolcms'</script>";
    else:
        echo "<script>alert('解压失败!请检查modules目录是否有修改（写入）权限!');window.location.href='?m=module&u=a_mods.php&do=$do'</script>";
    endif;
endif;
if(!empty($delfile)):
    echo"<p>正在删除模块包...</p>";
		unlink("../modules/".$file."");
		echo "<script>window.location.href='?m=module&u=a_modsx.php&id=$id&do=$do&retemp=usualtoolcms'</script>";
endif;
if(!empty($retemp)):
    if(is_dir("../modules/".$id."/templete")):
        echo"<p>正在构建模板路径...</p>";
        file_put_contents($modconfig,str_replace('templete/index',$template,file_get_contents($modconfig)));
        UsualToolCMS::editdir("../modules/".$id."/templete/index","../modules/".$id."/".$template."");
        echo "<script>window.location.href='?m=module&u=a_modsx.php&id=$id&do=$do&upname=usualtoolcms'</script>";
    else:
        echo "<script>window.location.href='?m=module&u=a_modsx.php&id=$id&do=$do&upname=usualtoolcms'</script>";
    endif;
endif;
if(!empty($upname)):
    $mods=file_get_contents($modconfig); 
    $installsql=UsualToolCMS::str_substr("<installsql><![CDATA[","]]></installsql>",$mods);
    $ress=$mysqli->multi_query($installsql);
    if($ress):
        echo "<p>构造表成功</p>";
        echo "<script>window.location.href='?m=module&u=a_modsx.php&id=$id&do=$do&dbname=usualtoolcms'</script>";
    else:
        echo "<p>构造表失败</p>";
        echo "<script>alert('构造表失败!或者您不具有安装权限,请联系客服!');window.location.href='?m=module&u=a_mods.php&do=$do'</script>";
    endif;
endif;
if(!empty($dbname)):
    $mods=file_get_contents($modconfig);
    $modid=UsualToolCMS::str_substr("<id>","</id>",$mods);
    $modname=UsualToolCMS::str_substr("<modname>","</modname>",$mods);
    $ordernum=UsualToolCMS::str_substr("<ordernum>","</ordernum>",$mods);
    $modurl=UsualToolCMS::str_substr("<modurl>","</modurl>",$mods);
    $befoitem=UsualToolCMS::str_substr("<befoitem>","</befoitem>",$mods);
    $backitem=UsualToolCMS::str_substr("<backitem>","</backitem>",$mods);
    $itemid=UsualToolCMS::str_substr("<itemid>","</itemid>",$mods);
    $roleurl=substr(str_replace(".php","",$modurl),1);
    $addranges="".$roleranges.",".$roleurl."";
    UsualToolCMSDB::updateData("cms_admin_role",array("ranges"=>$addranges),"id=1");
    $moddata=UsualToolCMSDB::queryData("cms_mod","","modurl='$modurl'","","1","0");
    if($moddata["querynum"]>0):
        $rows=$moddata["querydata"][0];
        $ress=UsualToolCMSDB::updateData("cms_mod",array("bid"=>$itemid,"modid"=>$modid,"befoitem"=>$befoitem,"backitem"=>$backitem),"id='".$rows["id"]."'");
    else:
    $ress=UsualToolCMSDB::insertData("cms_mod",array(
        "bid"=>$itemid,
        "modid"=>$modid,
        "modname"=>$modname,
        "modurl"=>$modurl,
        "isopen"=>1,
        "look"=>1,
        "ordernum"=>$ordernum,
        "befoitem"=>$befoitem,
        "backitem"=>$backitem));
    endif;
    if($ress):
        echo "<p>构造数据成功</p>";
        echo "<script>window.location.href='?m=module&u=a_modsx.php&id=$id&do=$do&copyname=usualtoolcms'</script>";
    else:
        echo "<p>构造数据失败</p>";
        echo "<script>alert('构造数据失败!');window.location.href='?m=module&u=a_mods.php&do=$do'</script>";
    endif;
endif;
if(!empty($copyname)):
    $mods=file_get_contents("../modules/".$id."/usualtoolcms.config");
    $befoitem=UsualToolCMS::str_substr("<befoitem>","</befoitem>",$mods);
    if($befoitem!=="NULL"):
        $befoitemarr=explode(",",$befoitem);
        foreach($befoitemarr as $be):
            if(UsualToolCMS::contain("dir:",$be)):
                $dir=explode("|",str_replace("dir:","",$be));
                for($i=0;$i<count($dir);$i++){
                    $olddir="../modules/".$id."/".$dir[$i]."/";
                    $enddir="../".$dir[$i]."/";
                    if(is_dir("../".$dir[$i]."/")):
                        UsualToolCMS::movedir($olddir,$enddir);
                        UsualToolCMS::deldir($olddir);
                    else:
                        UsualToolCMS::makedir("../".$dir[$i]."/");
                        UsualToolCMS::movedir($olddir,$enddir);
                        UsualToolCMS::deldir($olddir);
                    endif;
                }
            endif;
        endforeach;
    endif;
    $jsonstr=file_get_contents("../modules/module.config");
    $data=json_decode($jsonstr,true);
    $data["".$id.""]=$befoitem;
    $jsonstrs=json_encode($data);
    file_put_contents("../modules/module.config",$jsonstrs);
    echo "<p>安装成功</p>";
    echo "<script>alert('安装模块完成!');window.location.href='?m=module&u=a_mods.php&do=$do'</script>";
endif;
?>