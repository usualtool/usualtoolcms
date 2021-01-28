<?php
require_once 'ut-top.php';
$t=UsualToolCMS::sqlcheck($_GET["t"]);
$i=UsualToolCMS::sqlcheck(str_replace("..","",$_GET["i"]));
$zipname=UsualToolCMS::sqlcheck($_GET["zipname"]);
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
        echo "<script>window.location.href='?i=$i&copyname=usualtoolcms'</script>";
    else:
        echo "<script>alert('Decompression failed!');window.location.href='ut-update.php'</script>";
    endif;
endif;
if(!empty($copyname)):
    $olddir="../update/".$i."/";
    $enddir="../";
    UsualToolCMS::movedir($olddir,$enddir);
    echo "<p>File updated successfully</p>";
    echo "<script>window.location.href='?i=$i&delname=usualtoolcms'</script>";
endif;
if(!empty($delname)):
    UsualToolCMS::deldir("../update/".$i."/");
    unlink("../update/".$i.".zip");
    echo "<script>alert('Online update complete!');window.location.href='ut-update.php'</script>";
endif;
?>
<div id="cmsmain">
<?php require_once 'ut-message.php';?>  
<div id="index" class="mainbox">
<h2><i class="fa fa-spinner" aria-hidden="true"></i> Online update</h2>
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
for($i=0;$i<count($updates);$i++):
    $updatecon=explode("^",$updates[$i]);
    ?>
             <tr>
              <?php if($updatecon[0]=="nodata"):?>
              <td>---</td>
              <td>---</td>
              <td align="center">---</td>
              <?php else:?>
              <td><?php echo$updatecon[0];?></td>
              <td style="word-break:break-all;"><a href="http://cms.usualtool.com/down/update/<?php echo$updatecon[1];?>.zip">http://cms.usualtool.com/down/update/<?php echo$updatecon[1];?>.zip</a></td>
              <td align="center"><a href="?i=<?php echo$updatecon[1];?>&t=setup" style="color:red;">Install</a></td>
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
  </div>
 </div>
<?php
require_once 'ut-bot.php';?>