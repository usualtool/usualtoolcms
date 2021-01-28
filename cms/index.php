<?php require_once 'ut-top.php';?>
<div id="cmsmain">
<div id="index" class="mainbox">
<?php
$searchdirrerult=UsualToolCMS::searchdir("../setup");
if($searchdirrerult=="1"){
    echo "<div class=warning>您还没有删除setup安装文件夹，建议您尽快删除setup文件夹。<a href='?do=deldir' style='color:red;'>立即删除</a></div>";
}
if($_GET["do"]=="deldir"){
    $deldirrerult=UsualToolCMS::deldir("../setup");
    if($deldirrerult=="1"){
        echo "<script>alert('删除安装目录成功!');window.location.href='./'</script>";
    }else{
        echo "<script>alert('删除安装目录失败!');window.location.href='./'</script>";
    }
}
?>
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="indexBoxTwo" id="list">
    <tr>
     <td valign="top" class="pr" style="width:45%;">
      <div class="indexBox">
       <div class="boxTitle" style="border-bottom:0px;margin-bottom:0px;"><i class="fa fa-superpowers" aria-hidden="true"></i> 快速导航</div>
       <ul>
        <table width="100%" border="0" cellspacing="0" style="border:1px solid #ddd;padding:0 15px;">
        <tr>
        <td style="line-height:35px;font-size:15px;">
<?php
$cmsnavs=$mysqli->query("select * from `cms_nav` where place='cmsadmin' order by ordernum asc");
while($cmsrows=mysqli_fetch_array($cmsnavs)):
echo"<p><a href='".$cmsrows["linkurl"]."'><b><i class='fa fa-cube'></i> ".$cmsrows["linkname"]."</b></a></p>";
endwhile;
    $cmod=$mysqli->query("SELECT id,bid,modid,modname,modurl,backitem from `cms_mod` WHERE look='1' and isopen='1' order by id desc");
    while($cmodrow=$cmod->fetch_row()):
        echo"<p><b><i class='fa fa-cube'></i> ".$cmodrow[3]."</b></p>";
        $sbid=$cmodrow[1];
        $sid=$cmodrow[2];
        $sname=$cmodrow[3];
        $snav=$cmodrow[5];
        $snavarr=explode(",",$snav);
        echo"<p>";
        foreach($snavarr as $snv):
            $snavs=explode(":",$snv);
            $suarr=explode("php",$snavs[1]);
            $surl=$suarr[0]."php";
            $sget=str_replace("?","",$suarr[1]);
            ?>
                <span style="padding-right:15px;">
                <a href="ut-view-module.php?m=<?php echo$sid;?>&u=<?php echo$surl;?>&<?php echo$sget;?>" onclick="navclick('<?php echo$cmodrow[0];?>')">
                <?php echo$snavs[0];?>
                </a> 
                </span>
        <?php
        endforeach;
        echo"</p>";
    endwhile;
    ?>
         </td>
         </tr>
        </table>
       </ul>
      </div>
     </td>
     <td valign="top" class="pl" id="cmsnone">
      <div class="indexBox">
       <div class="boxTitle" style="border-bottom:0px;margin-bottom:0px;"><i class="fa fa-id-badge" aria-hidden="true"></i> 管理登陆记录</div>
       <ul>
        <table width="100%" border="0" cellspacing="0" cellpadding="7" class="tablebasic">
         <tr>
          <th width="15%">管理员</th>
          <th width="35%">IP地址</th>
          <th width="50%">登陆时间</th>
         </tr>
<?php
$adminlogin=$mysqli->query("select * from `cms_admin_log` order by logintime desc limit 0,10");
while($loginrecord=$adminlogin->fetch_row()){
    echo"<tr><td align=center>".$loginrecord[1]."</td><td align=center>".$loginrecord[2]."</td><td align=center>".$loginrecord[3]."</td></tr>";
}
?>
 </table>
       </ul>
      </div>
     </td>
    </tr>
   </table>
<?php 
$SystemInfos=UsualToolCMS::getsysteminfo();
$SystemInfo=explode("|",$SystemInfos);
?>
   <div class="indexBox" id="list">
    <div class="boxTitle" style="border-bottom:0px;margin-bottom:0px;"><i class="fa fa-laptop" aria-hidden="true"></i> 服务器信息</div>
    <ul>
     <table width="100%" border="0" cellspacing="0" cellpadding="7" class="tablebasic">
      <tr>
       <td width="120" valign="top">架构平台</td>
       <td valign="top"><?php echo $SystemInfo[0];?></td>
       <td width="100" valign="top">PHP 版本</td>
       <td valign="top"><?php echo $SystemInfo[1];?></td>
       <td width="100" valign="top">远程文件获取</td>
       <td valign="top"><?php echo $SystemInfo[2];?></td>
      </tr>
      <tr>
       <td valign="top">文件上传限制</td>
       <td valign="top"><?php echo $SystemInfo[3];?></td>
       <td valign="top">脚本最大执行</td>
       <td valign="top"><?php echo $SystemInfo[4];?></td>
       <td valign="top">服务器时间</td>
       <td valign="top"><?php echo $SystemInfo[5];?></td>
      </tr>
     </table>
    </ul>
   </div>

  </div>
 </div>

<?php
require_once 'ut-bot.php';?>