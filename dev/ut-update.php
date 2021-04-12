<?php
require_once 'ut-top.php';?>
<div id="cmsmain">
<?php require_once 'ut-message.php';?>  
<div id="index" class="mainbox">
<h2><i class="fa fa-spinner" aria-hidden="true"></i> Online update</h2>
<?php if(!empty($_GET["i"])):?>
<div>
<?php
$t=UsualToolCMS::sqlcheck($_GET["t"]);
$i=UsualToolCMS::sqlcheck(str_replace("..","",$_GET["i"]));
$zipname=UsualToolCMS::sqlcheck($_GET["zipname"]);
$sqlname=UsualToolCMS::sqlcheck($_GET["sqlname"]);
$copyname=UsualToolCMS::sqlcheck($_GET["copyname"]);
$delname=UsualToolCMS::sqlcheck($_GET["delname"]);
$upname=UsualToolCMS::sqlcheck($_GET["upname"]);
if($t=="setup"):
    echo"<p>Download in progress...</p>";
    $url="http://cms.usualtool.com/down/update/".$i.".zip";
    $save_dir = "../update";  
    $filename =basename($url); 
    $res = UsualToolCMS::getfile($url,$save_dir,$filename,1);
    if(!empty($res)):
        echo"<p>Writing update file...</p>";
        echo "<script>window.location.href='?i=$i&zipname=usualtoolcms'</script>";
    else:
        echo "<script>alert('Download failed!');window.location.href='ut-update.php'</script>";
    endif;
endif;
if(!empty($zipname)):
    $zip=new ZipArchive;
    if($zip->open("../update/".$i.".zip")===TRUE): 
        $zip->extractTo('../update/');
        $zip->close();
        echo "<p>Successfully decompressed</p>";
        echo "<script>window.location.href='?i=$i&sqlname=usualtoolcms'</script>";
    else:
        echo "<script>alert('Decompression failed!');window.location.href='ut-update.php'</script>";
    endif;
endif;
if(!empty($sqlname)):
    if(file_exists("../update/".$i."/usualtoolcms.config")):
    $up=file_get_contents("../update/".$i."/usualtoolcms.config");
    $thesql=UsualToolCMS::str_substr("<sql><![CDATA[","]]></sql>",$up);
    $res=$mysqli->multi_query($thesql);
    if($res):
    echo "<p>Successfully sql</p>";
    echo "<script>window.location.href='?i=$i&copyname=usualtoolcms'</script>"; 
    else:
    echo "<script>alert('The sql failed!');window.location.href='ut-update.php'</script>";    
    endif;
    else:
    echo "<script>window.location.href='?i=$i&copyname=usualtoolcms'</script>";     
    endif;
endif;    
if(!empty($copyname)):
    $olddir="../update/".$i."/";
    $enddir="../";
    UsualToolCMS::movedir($olddir,$enddir);
    echo "<p>File updated successfully</p>";
    UsualToolCMSDB::insertData("cms_update",array("updateid"=>$i,"updatetime"=>date('Y-m-d H:i:s',time())));    
    echo "<script>window.location.href='?i=$i&delname=usualtoolcms'</script>";
endif;
if(!empty($delname)):
    UsualToolCMS::deldir("../update/".$i."/");
    unlink("../update/".$i.".zip");
    echo "<script>alert('Online update complete!');window.location.href='ut-update.php'</script>";
endif;
?>
</div>
<?php else:?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="indexBoxTwo">
    <tr>
     <td valign="top" class="pr">
      <div class="indexBox">
       <ul>
        <table width="100%" border="0" cellspacing="0" cellpadding="7" class="tablebasic">
         <tr bgcolor="#eeeeee">
          <td width="40%">Update content</td>
            <td>Download url</td>
          <td width="20%" align="center">Install</td>
         </tr>
<?php
$update=UsualToolCMS::auth($authcode,$authapiurl,"update");
$updates=explode("|",$update);
for($k=0;$k<count($updates);$k++):
    $updatecon=explode("^",$updates[$k]);
    $updatenum=UsualToolCMSDB::queryData("cms_update","","updateid='".$updatecon[1]."'","","","0")["querynum"];
    ?>
             <tr>
              <?php if($updatecon[0]=="nodata"):?>
              <td>---</td>
              <td>---</td>
              <td align="center">---</td>
              <?php else:?>
              <td><?php echo$updatecon[0];?></td>
              <td style="word-break:break-all;"><a href="http://cms.usualtool.com/down/update/<?php echo$updatecon[1];?>.zip">http://cms.usualtool.com/down/update/<?php echo$updatecon[1];?>.zip</a></td>
              <td align="center">
                  <?php if($updatenum>0):?>
                  Installed
                  <?php else:?>
                  <a href="?i=<?php echo$updatecon[1];?>&t=setup" style="color:red;">Install</a>
                  <?php endif;?>
                  </td>
              <?php endif;?>
             </tr>
<?php endfor;?>
        </table>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="line-height:25px;margin-top:15px;">
         <tr><td>Site:<a target="_blank" href="//cms.usualtool.com">cms.usualtool.com</a></td></tr>
         <tr><td>Blog:<a target="_blank" href="//www.usualtool.com/blog">www.usualtool.com/blog</a></td></tr>
</table>
       </ul>
      </div>
     </td>
     <td valign="top" class="pl" id="cmsnone">
      <div class="indexBox">
       <div class="boxTitle">system information</div>
       <ul>
        <table width="100%" border="0" cellspacing="0" cellpadding="7" class="tablebasic">
<?php
$ups=UsualToolCMS::auth($authcode,$authapiurl,"upapi");
$up=explode("|",$ups);
$upcopy=$up[0];
$adv=$up[1];
?>
<tr>
<td>Installation:<?php echo$copyright;?></td>
<td>System:<?php echo$upcopy?></td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="line-height:25px;margin-top:15px;">
<?php
$advs=explode("^",$adv);
for($a=0;$a<count($advs);$a++):
?>
<tr style="margin-top:10px;">
<td><?php echo$advs[$a];?></td>
</tr>
<?php endfor;?>
</table>
       </ul>
      </div>
     </td>
    </tr>
   </table>
   <?php endif;?>
  </div>
 </div>
<?php
require_once 'ut-bot.php';?>