<?php
$x=$_GET["x"];
if($x=="a"){
    if(UsualToolCMSDB::insertData("cms_cactask",array(
    "cacname"=>UsualToolCMS::sqlcheck($_POST["cacname"]),
    "cacstr"=>UsualToolCMS::sqlcheckv($_POST["cacstr"])))):
        echo "<script>alert('创建成功!');window.location.href='?m=cactool&u=a_cactool_task.php'</script>";
    else:
        echo "<script>alert('创建失败!');window.location.href='?m=cactool&u=a_cactool_task.php'</script>";
    endif;
}
if($x=="d"){
    if(UsualToolCMSDB::delData("cms_cactask","id='".UsualToolCMS::sqlcheckx($_GET["id"])."'")):
        echo "<script>alert('删除成功!');window.location.href='?m=cactool&u=a_cactool_task.php'</script>";
    else:
        echo "<script>alert('删除失败!');window.location.href='?m=cactool&u=a_cactool_task.php'</script>";
    endif;
}
?>
<h2>CAC任务</h2>
<form action="?m=cactool&u=a_cactool_task.php&x=a" method="post">
<table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
		  <tr>
           <td align="right" width=20%>名称</td>
           <td>
            <input type="text" name="cacname" class="inpMain" style="width:30%;">
           </td>
          </tr>
          <tr>
           <td align="right">命令</td>
           <td>
            <textarea class="inpMain" style="width:80%;height:60px;" name="cacstr"></textarea>
           </td>
          </tr>
          <tr>
          <td></td>
          <td>
           <input name="submit" class="btn" type="submit" value="创建快捷命令" />
          </td>
         </tr>
</table>
</form>
<div id="ut-auto" style="margin-top:15px;">
<table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
<thead>
     <tr>
      <th width="30%" align="left">任务名称</th>
      <th width="50%" align="left">执行命令</th>
      <th width="20%" align="center"></th>
     </tr>
     </thead>
     <tbody>
<?php
$pagenum=10;
$page=UsualToolCMS::sqlcheck($_GET["page"]);
if(!empty($page)):$page=$_GET["page"];else:$page=1;endif;
$pagelink="?m=$mod&u=$url";
$minid=$pagenum*($page-1);
$list=UsualToolCMSDB::queryData("cms_cactask","","","id desc","$minid,$pagenum","0");
$total=$list["querynum"];
$totalpage=ceil($total/$pagenum);
foreach($list["querydata"] as $rows):
echo"<tr><td>".$rows["cacname"]."</td>";
echo"<td>".$rows["cacstr"]."<input type='hidden' id='php".$rows["id"]."' value='".$rows["cacstr"]."'></td>";
echo"<td align=center><a style='color:red;' onclick='phprun(".$rows["id"].")'>执行</a> | ";
echo"<a href='?m=cactool&u=a_cactool_task.php&x=d&id=".$rows["id"]."'>删除</a></td></tr>";
endforeach;
?>
    </tbody>
</table>
</div>
<div class="pager">
<?php
$subPages=new pager($totalpage,$page,$pagenum,$pagelink);
echo $subPages->showpager();
?>
</div>
<script>
function phprun(id){
    var o=$("#php"+id+"").val();
    $.post("<?php echo$weburl;?>/modules/cactool/ut-dos.php",{o:o},function(result){
          alert(result.replace(/<[\/p]+>/g,"\n"));
    });
}
</script>