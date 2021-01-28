<?php
$x=$_GET["x"];
if(!empty($x)):
    $navid=UsualToolCMS::sqlchecks($_POST["navid"]);
    $ids=implode(",",$navid);
    $isopens=UsualToolCMS::sqlchecks($_POST["isopen"]);
    $opens=implode(",",$isopens);
endif;
if($x=="m"){
    $idx= explode(",",$ids);
    $open= explode(",",$opens);
    for($i=0;$i<count($idx);$i++){
        $isopen=$open[$i];
        $id=$idx[$i];
        $sqls="UPDATE `cms_mod` set isopen='$isopen' where id='$id'";
        $mysqli -> multi_query($sqls);
    }
    echo "<script>alert('模块启用/关闭成功!');window.location.href='?m=module&u=a_mod.php'</script>";
    $mysqli->close();
}
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td valign="top">
<h2>模块开关</h2>
     <form action="?m=module&u=a_mod.php&x=m" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
<?php
if(preg_match("/($Ospat)/i", $Uagent)):
$p=2;
else:
$p=6;
endif;
$mod=$mysqli->query("select id,bid,modid,modname,modurl from `cms_mod` where bid='0' and id<>'3'");
while($modrow=$mod->fetch_row()){
    $smod="SELECT id,bid,modid,modname,modurl,isopen from`cms_mod` WHERE bid='$modrow[0]'";
    $smods=mysqli_query($mysqli,$smod);
    if(mysqli_num_rows($smods)>0):
        echo"<tr><td colspan='".$p."'>".$modrow[3]."</td></tr><tr>";
        $i=0;
        $xmod=$mysqli->query($smod);
        while($xmodrow=$xmod->fetch_row()):
            $i=$i+1;
            if($xmodrow[5]=="1"):
                echo"<td><a href='?m=".$xmodrow[2]."&u=".$xmodrow[4]."' style='color:red;'>".$xmodrow[3]."</a>: <input type=hidden name='navid[]' value='$xmodrow[0]'><select name='isopen[]'><option value='1' selected>启用</option><option value='0'>关闭</option></select></td>";
            else:
                echo"<td><a href='?m=".$xmodrow[2]."&u=".$xmodrow[4]."' style='color:red;'>".$xmodrow[3]."</a>: <input type=hidden name='navid[]' value='$xmodrow[0]'><select name='isopen[]'><option value='1'>启用</option><option value='0' selected>关闭</option></select></td>";
            endif;
            if($i%$p==0):
                echo"</tr><tr>";
            endif;
        endwhile;
        echo"</tr>";
    else:
    echo"<tr><td colspan='".$p."'><a href='".$modrow[4]."'>".$modrow[3]."</a></td></tr>";
    endif;
}
?>
     <tr>
	 <td colspan="<?php echo$p;?>" align="center">
	 <input type="submit" name="submit" class="btn" value="保存设置"/> 
	 &nbsp;&nbsp;
     <a class="btnGray" href="?m=module&u=a_mods.php">安装模块</a> 
	 </td></tr>
     </table>
    </form>
<table width="100%" border="0" cellpadding="8" cellspacing="0" style="margin-top:15px;background-color:#D1F2EB;height:120px;color:black;">
<tr>
<td align="center"><a target="_blank" href="http://cms.usualtool.com/mokuai.php" style="color:black;font-size:18px;">前往UT模块市场，挑选更多实用模块......</a></td>
</tr>
</table>
	</td>
	<td id="cmsnone" style="width:2%;" valign="top"></td>
	<td id="cmsnone" style="width:40%;" valign="top">
	<h2>模块缺损报告</h2>
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="tablebasic">
     <tr>
      <th align="center">模块</th>
      <th align="left">检测结果</th>     
     </tr>

<?php
$qs=0;
$mod=$mysqli->query("select * from `cms_mod` where bid>0 and bid<>3 order by ordernum desc");
while($modrow=mysqli_fetch_array($mod)):
    $zmodid=$modrow["modid"];
    $ztitle=$modrow["modname"];
    $befoitem=$modrow["befoitem"];
    $befoitems=explode(",",$befoitem);
    $backitem=$modrow["backitem"];
    $backitems=explode(",",$backitem);
    echo"<tr style='line-height:25px;'><td align=center>".$ztitle."</td>";
    if($befoitem!="NULL"):
        for($k=0;$k<count($befoitems);$k++):
            if(strpos($befoitems[$k],"dir:")===false):
            if(file_exists("../modules/".$zmodid."/".$befoitems[$k]."")):
            //echo"".$befoitems[$k]."Yes<br>";
            else:
            if(is_dir("../modules/".$zmodid."/".$befoitems[$k]."")):
            else:
            $qs=$qs+1;
            echo"<font color=red>".$befoitems[$k]."缺损</font><br>";
            endif;
            endif;
            endif;
        endfor;
    endif;
    for($t=0;$t<count($backitems);$t++):
        $backitemx=explode(":",$backitems[$t]);
        $backitemv=explode("?",$backitemx[1]);
        if(file_exists("../modules/".$zmodid."/cmsadmin/".$backitemv[0]."")):
        //echo"".$backitemv[0]."Yes<br>";
        else:
            $qs=$qs+1;
            echo"<font color=red>".$backitemv[0]."缺损</font><br>";
        endif;
    endfor;
    if($qs==0):
        echo"<td>本模块无缺损文件</td>";
    endif;
        echo"</tr>";
endwhile;
?>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-top:15px;padding:10px;">
<tr style="font-size:13px;color:#666;"><td>安装模块时，请修改modules目录为可写模式。若无写入权限，可下载模块到模块目录，且修改modtype值为2再执行安装。</td></tr>
</table>
	</td>
	</tr>
	</table>