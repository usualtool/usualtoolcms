<h2>
<a href="?m=admin&u=a_admin_rolex.php&t=add" class="actionBtn">添加角色</a>  
<a href="?m=admin&u=a_admin.php" class="actionBtn">管理员</a>管理员角色
</h2>
<div id="ut-auto">
   <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
     <tr>
      <th width="10%" align="center">角色ID</th>
      <th width="20%" align="center">角色名称</th>
      <th>权限范围</th>
	  <th width="20%" align="center"></th>
     </tr>
<?php
$list=UsualToolCMSDB::queryData("cms_admin_role","","","","","0")["querydata"];
foreach($list as $row):
    $ranges=explode(',',$row["ranges"]);
    echo"<tr><td align=center>".$row["id"]."</td><td align=center>".$row["rolename"]."</td>";
    echo "<td>";
    for($x=0;$x<=count($ranges);$x++){
        echo "".UsualToolCMSDB::queryData("cms_mod","","modurl='a".$ranges[$x].".php' and bid<>0","","","0")["querydata"][0]["modname"]." ";
    }
    echo"</td>";
    echo"<td align=center><a href='?m=admin&u=a_admin_rolex.php&t=mon&id=".$row["id"]."'>编辑</a>";
    echo" | <a href='?m=admin&u=a_admin_rolex.php&t=del&id=".$row["id"]."'>删除</a></td></tr>";
endforeach;
?>
</table>
</div>