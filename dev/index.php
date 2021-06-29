<?php require_once 'ut-top.php';?>
<div id="cmsmain">
<?php require_once 'ut-message.php';?>  
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
     <td valign="top" class="pr">
      <div class="indexBox">
       <div class="boxTitle">基本信息</div>
       <ul>
        <table width="100%" border="0" cellspacing="0" cellpadding="7" class="tablebasic">
         <tr>
          <td align="center"><a href="ut-view-module.php?m=module&u=a_mods.php" style="color:red;">安装模块</a></td>
          <td align="center">CMS版本</td>
          <td id="utrn"><?php echo$setup["copyright"];?> Release</td>
         </tr>
         <tr>
          <td align="center"><a href="ut-view-module.php?m=plugin&u=a_api.php" style="color:red;">安装插件</a></td>
          <td align="center">安装国别</td>
          <td><?php echo$setup["country"];?></td>
         </tr>
         <tr>
          <td align="center"><a href="ut-view-module.php?m=templete&u=a_templete.php" style="color:red;">安装模板</a></td>
          <td align="center">前台编码</td>
          <td>UTF-8</td>
         </tr>
         <tr>
          <td align="center"><a href="ut-update.php">更新系统</a></td>
          <td align="center">站点模板</td>
          <td><?php echo$template;?></td>
         </tr>
         <tr>
          <td align="center"><a href="http://bbs.usualtool.com">疑难问答</a></td>
          <td align="center">安装日期</td>
          <td id="utrn"><?php echo$setup["installtime"];?></td>
         </tr>
        </table>
       </ul>
      </div>
     </td>
     <td valign="top" class="pl" id="cmsnone">
      <div class="indexBox">
       <div class="boxTitle">后端登陆记录</div>
       <ul>
        <table width="100%" border="0" cellspacing="0" cellpadding="7" class="tablebasic">
         <tr>
          <th width="15%">管理员</th>
          <th width="35%">IP地址</th>
          <th width="50%">登陆时间</th>
         </tr>
<?php
    $adminlogin=UsualToolCMSDB::queryData("cms_admin_log","","","logintime desc","0,4","0")["querydata"];
    foreach($adminlogin as $loginrecord){
        echo"<tr><td align=center>".$loginrecord["adminusername"]."</td><td align=center>".$loginrecord["ip"]."</td><td align=center>".date('Y-m-d',strtotime($loginrecord["logintime"]))."</td></tr>";
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
    <div class="boxTitle">服务器信息</div>
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
   <div class="indexBox">
    <div class="boxTitle">UT Develop</div>
    <ul>
     <table width="100%" border="0" cellspacing="0" cellpadding="7" class="tablebasic">
      <tr>
       <td width="150"> 发布公司 </td>
       <td id="utrn"><a href="http://www.cdkfdt.cn" target="_blank">ChengDu KangfeiDunte Network Technology Co.,Ltd.</a></td>
      </tr>
      <tr>
       <td> 技术支持 </td>
       <td id="utrn"><a href="http://cms.usualtool.com" target="_blank">http://cms.usualtool.com </a></td>
      </tr>
      <tr>
       <td> 使用授权协议 </td>
       <td id="utrn"><a href="http://cms.usualtool.com/license.php" target="_blank">http://cms.usualtool.com/license.php</a></td>
      </tr>
     </table>
    </ul>
   </div> 
  </div>
 </div>

<?php
require_once 'ut-bot.php';?>