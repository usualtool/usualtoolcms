<?php
$t=UsualToolCMS::sqlcheck($_GET["t"]);
$x=UsualToolCMS::sqlcheck($_GET["x"]);
if($x=="m"){
    $files=explode(".",str_replace("..","",$_POST["filename"]));
    $filename=$files[0];
    $dir=str_replace("..","",$_POST["dir"]);
    $content=UsualToolCMS::sqlcheckv($_POST["content"]);
    $id=UsualToolCMS::sqlcheckx($_POST["id"]);
    $tp=str_replace("..","",$_POST["tp"]);
    $contents=iconv("utf-8","utf-8",$content);
    $filenames="../".$dir."/".$filename.".cms";
    file_put_contents($filenames,$contents);
    echo "<script>alert('更新模板成功!');window.location.href='?m=templete&u=a_templetex.php&t=edit&id=$id&filename=$filename.cms&dir=$dir&tp=$tp'</script>";
}
?>
<h2><span>管理模板</span><span style="float:right;"><a href="#" style="color:red;font-size:15px;line-height:40px;" onclick="javascript: window.history.go(-1);return false;"><<< 返回上页</a></span></h2>
<table width=100% align=center style="font-size:15px;line-height:35px;">
<?php 
if($t=="mon"){
    $id=UsualToolCMS::sqlcheckx($_GET["id"]);
    $downfile=str_replace("..","",$_GET["dir"]);
    $open=$mysqli->query("select * from cms_templete where id='$id'");
    while($orow=$open->fetch_row()){
        $upfile=$orow[1];
    }
    if(empty($downfile)){
        $dir=$upfile;
    }else{
        $dir=$downfile;
    }
    $dirs="../".$dir."";
    $dd=opendir($dirs);
    while(($f=readdir($dd))!==false){
        if($f == "." || $f == ".."){
        continue;
    }
    $file=rtrim($dirs,"/")."/".$f;
    $f2=iconv("gb2312","utf-8",$f);
    $ext=substr(strrchr($file, '.'), 1);
    $filetime=filemtime($file);
    $filesize=filesize($file);
    if(filetype($file)=="dir"&&$f2!=="fonts"&&$f2!=="images"){
    if($f2=="css"){$fname="样式文件";}
    elseif($f2=="cache"){$fname="缓存文件";}
    elseif($f2=="skin"){$fname="模板文件";}
    elseif($f2=="img"){$fname="图片目录";}
    elseif($f2=="js"){$fname="js文件夹";}
    elseif($f2=="fonts"){$fname="字体目录";}
    elseif($f2=="images"){$fname="图片目录";}
    else{$fname="备用目录";}
    echo "<tr><td>".$fname.": <a href='?m=templete&u=a_templetex.php&dir=$dir/{$f2}&id=$id&t=mon'><font color=red>/{$f2}/</font></a></td></tr>";
    }elseif($f2=="fonts"||$f2=="images"||$ext=="gif"||$ext=="jpg"||$ext=="png"||$ext=="html"||$ext=="ttf"){echo"";
    }else{
    if($f2=="index.cms"){$fnames="首页模板";}
    elseif($f2=="head.cms"){$fnames="头部模板";}
    elseif($f2=="foot.cms"){$fnames="尾部模板";}
    elseif($f2=="articles.cms"){$fnames="文章列表模板";}
    elseif($f2=="article.cms"){$fnames="文章模板";}
    elseif($f2=="product.cms"){$fnames="产品模板";}
    elseif($f2=="products.cms"){$fnames="产品列表模板";}
    elseif($f2=="contact.cms"){$fnames="联系反馈模板";}
    elseif($f2=="custom.cms"){$fnames="自定义模板";}
    elseif($f2=="register.cms"){$fnames="注册模板";}
    elseif($f2=="login.cms"){$fnames="登录模板";}
    elseif(UsualToolCMS::contain(".css",$f2)===true){$fnames="样式文件";}
    elseif(UsualToolCMS::contain("cache_",$f2)===true){$fnames="缓存文件";}
    elseif(UsualToolCMS::contain(".js",$f2)===true){$fnames="js文件";}
    elseif(UsualToolCMS::contain(".ttf",$f2)===true){$fnames="字体文件";}
    else{$fnames="模板文件";}
    echo "<tr><td>".$fnames.": <a href='?m=templete&u=a_templetex.php&t=edit&dir=$dir&filename={$f2}&id=$id&tp=$upfile'><font color=green>/{$f2}</font></a></td></tr>";
    }
    }
}
//if结束
if($t=="edit"){
    $id=UsualToolCMS::sqlcheckx($_GET["id"]);
    $tp=str_replace("..","",$_GET["tp"]);
    $filenames=str_replace("..","",$_GET["filename"]);
    $dirs=str_replace("..","",$_GET["dir"]);
    $filename="../".$dirs."/".$filenames."";
    $yuanma=file_get_contents($filename);
    $yuanma=str_replace("<textarea","&lt;textarea",$yuanma);
    $yuanma=str_replace("</textarea>","&lt;/textarea&gt;",$yuanma);
    function format_bytes($size) { 
        $units = array('B','KB','MB','GB','TB'); 
        for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024; 
        return round($size, 0).$units[$i]; 
    } 
    echo "<form action='?m=templete&u=a_templetex.php&x=m' method='post' style='margin:0 0px;'>";
    echo "<input type=hidden name=tp value='".$tp."'>";
    echo "<input type=hidden name=filename value='".$filenames."'>";
    echo "<input type=hidden name=dir value='".$dirs."'>";
    echo "<input type=hidden name=id value='".$id."'>";
    echo "<tr><td height=30 style='word-wrap:break-word;word-break:break-all;'>Url:{$filenames}";
    if(preg_match("/($Ospat)/i", $Uagent )){echo "<br>";}else{echo "&nbsp;";}
    echo "编码格式:".UsualToolCMS::detect_encoding($filename)."";
    if(preg_match("/($Ospat)/i", $Uagent )){echo "<br>";}else{echo "&nbsp;";}
    echo "文件大小:".format_bytes(filesize($filename))."";
    if(preg_match("/($Ospat)/i", $Uagent )){echo "<br>";}else{echo "&nbsp;";}
    echo "修改日期:".date("Y-m-d H:i:s",filemtime($filename))."";
    echo "</td></tr><Tr><td>";
    echo "<textarea name='content' id='textarea' style='width:100%;font-size:13px;border: 1px solid #999999;color:#F00;overflow:auto;height:380px;'>$yuanma</textarea>";
    echo "</td></tr>";
    echo "<tr><td height=40><input type='submit' value='更新模板' class='btn'/></td></tr>";
    echo "</form>";
}
?>
</table>
<?php 
if($t=="del"){
    $id=UsualToolCMS::sqlcheck($_GET["id"]);
    $paths=str_replace("..","",$_GET["paths"]);
    if(UsualToolCMS::contain("templete",$paths)):
        echo"<p>正在删除</p>";
        UsualToolCMS::deldir("../".$paths."/");
        rmdir("../".$paths."/");
        $result=mysqli_query($mysqli,"DELETE FROM cms_templete WHERE id='$id'");
        if(!$result){
            echo "<script>alert('模板删除失败!');window.location.href='?m=templete&u=a_templete.php'</script>";
        }else{
            echo "<script>alert('模板删除成功!');window.location.href='?m=templete&u=a_templete.php'</script>";
        }
        $mysqli->close();
    endif;
}
if($t=="open"){
    $id=UsualToolCMS::sqlcheck($_GET["id"]);
    if(!empty($_GET["oid"])){
        $oid=UsualToolCMS::sqlcheck($_GET["oid"]);
    }else{
        $oid=0;
    }
    $paths=UsualToolCMS::sqlcheck($_GET["paths"]);
    $plan=UsualToolCMS::sqlcheck($_POST["plan"]);
    $sql1 = "UPDATE cms_setup set template='$paths' WHERE id=1";
    $sql2 = "UPDATE cms_templete set isopen=0 WHERE id='$oid'";
    if($mysqli->query($sql1) && $mysqli->query($sql2)){
        $result=mysqli_query($mysqli,"UPDATE cms_templete set isopen=1 WHERE id=$id");
        if(!$result){
            echo "<script>alert('模板开启失败!');window.location.href='?m=templete&u=a_templete.php'</script>";
        }else{
            $sql3 = "UPDATE cms_nav_plan set indexplan='0' WHERE id='$planid'";
            if($mysqli->query($sql3)):
                $sql4 = "UPDATE cms_nav_plan set indexplan='1' WHERE id='$plan'";
                $mysqli->query($sql4);
                echo "<script>alert('模板开启成功!');window.location.href='?m=templete&u=a_templete.php'</script>";
            endif;
        }
    }else{
        echo "<script>alert('模板开启失败!');window.location.href='?m=templete&u=a_templete.php'</script>";
    }
    $mysqli->close();
}
?>