<h2>
<a href="?m=admin&u=a_admin_role.php" class="actionBtn">角色管理</a> 
<a href="?m=admin&u=a_adminx.php&t=add" class="actionBtn">添加管理员</a> 
管理员列表
</h2>
<div id="ut-auto">
<table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
     <tr>
      <th width="10%" align="left">账户</th>
      <th align="center">头像</th>
      <th>角色权限</th>
      <th align="center">创建时间</th>
      <th align="center">操作</th>
     </tr>
<?php
$list=UsualToolCMSDB::queryData("cms_admin","","","","","0")["querydata"];
foreach($list as $row):
    echo"<tr><td>".$row["username"]."</td><td align=center><img src='".$row["icon"]."' width=30 height=30></td>";
    echo"<td align=center>".UsualToolCMSDB::queryData("cms_admin_role","rolename","id='".$row["roleid"]."'","","","0")["querydata"][0]["rolename"]."</td>";
    echo"<td align=center>".$row["createtime"]."</td><td align=center><a href='?m=admin&u=a_adminx.php&t=mon&id=".$row["id"]."'>编辑</a>";
    echo" | <a href='?m=admin&u=a_adminx.php&t=del&id=".$row["id"]."'>删除</a></td></tr>";
endforeach;
?>
</table>
</div>