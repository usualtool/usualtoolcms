<h2>
<a href="?m=admin&u=a_admin.php" class="actionBtn">管理员</a> 
登陆日志
</h2>
<div id="ut-auto">
<table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic" id="ut-table">
<thead>
     <tr>
      <th width="40%" align="left">登陆时间</th>
      <th width="20%" align="left">管理员账号</th>
      <th width="40%" align="center">IP地址</th>
     </tr>
     </thead>
     <tbody>
<?php
$pagenum=10;
$page=UsualToolCMS::sqlcheck($_GET["page"]);
if(!empty($page)):$page=$_GET["page"];else:$page=1;endif;
$pagelink="?m=$mod&u=$url";
$minid=$pagenum*($page-1);
$list=UsualToolCMSDB::queryData("cms_admin_log","","","logintime desc","$minid,$pagenum","0");
$total=$list["querynum"];
$totalpage=ceil($total/$pagenum);
foreach($list["querydata"] as $rows):
echo"<tr><td>".$rows["logintime"]."</td><td>".$rows["adminusername"]."</td><td align=center>".$rows["ip"]."</td></tr>";
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