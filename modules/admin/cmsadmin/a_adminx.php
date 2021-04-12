<?php
$t=$_GET["t"];
$x=$_GET["x"];
if($x=="a"){
    $password=UsualToolCMS::sqlcheck($_POST["password"]);
    $password_confirm=UsualToolCMS::sqlcheck($_POST["password_confirm"]);
    if($password==$password_confirm):$passwords=sha1($salts.$password);
    if(UsualToolCMSDB::insertData("cms_admin",array(
    "roleid"=>UsualToolCMS::sqlcheckx($_POST["roleid"]),
    "username"=>UsualToolCMS::sqlcheck($_POST["username"]),
    "password"=>$passwords,
    "salts"=>$salts,
    "icon"=>UsualToolCMS::sqlcheck($_POST["icon"]),
    "createtime"=>date('Y-m-d H:i:s',time())))):
        echo "<script>alert('管理员已添加成功!');window.location.href='?m=admin&u=a_admin.php'</script>";
    endif;
    else:
        echo "<script>alert('添加失败,请检查两次密码是否一致!');window.location.href='?m=admin&u=a_adminx.php&t=add'</script>";
    endif;
}
if($x=="m"){
    $password=UsualToolCMS::sqlcheck($_POST["password"]);
    $password_confirm=UsualToolCMS::sqlcheck($_POST["password_confirm"]);
    if($password==$password_confirm):$passwords=sha1($salts.$password);
        if(UsualToolCMSDB::updateData("cms_admin",array(
        "roleid"=>UsualToolCMS::sqlcheckx($_POST["roleid"]),
        "password"=>$passwords,
        "salts"=>$salts),"id='".UsualToolCMS::sqlcheckx($_POST["id"])."'")):
            echo "<script>alert('密码修改成功!');window.location.href='?m=admin&u=a_admin.php'</script>";
        endif;
    else:echo "<script>alert('请检查两次密码是否一致!');window.location.href='?m=admin&u=a_adminx.php&t=mon&id=".$id."'</script>";
    endif;
}
?>
    <h2><a href="?m=admin&u=a_admin.php" class="actionBtn">返回列表</a>管理员添加/编辑</h2>
<?php if($t=="add"){?>
     <form action="?m=admin&u=a_adminx.php&x=a" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
      <tr>
       <td width="20%" align="right">头像</td>
       <td>
        <input type="radio" name="icon" value="../assets/images/male.png" checked><img src="../assets/images/male.png" style="width:40px;"> 
        <input type="radio" name="icon" value="../assets/images/female.png"><img src="../assets/images/female.png" style="width:40px;"> 
       </td>
      </tr>
      <tr>
       <td width="20%" align="right">管理员名称</td>
       <td>
        <input type="text" name="username" size="40" class="inpMain" />
       </td>
      </tr>
	   <tr>
       <td align="right">管理员角色</td>
       <td>
<select name="roleid">
<?php
$admin=$mysqli->query("select id,rolename,ranges from `cms_admin_role`");
while($adminrow=mysqli_fetch_array($admin)):
echo"<option value='".$adminrow["id"]."'>".$adminrow["rolename"]."</option>";
endwhile;
?>
</select>
       </td>
      </tr>
      <tr>
       <td align="right">密码</td>
       <td>
        <input type="password" name="password" size="40" class="inpMain" />
       </td>
      </tr>
      <tr>
       <td align="right">确认密码</td>
       <td>
        <input type="password" name="password_confirm" size="40" class="inpMain" />
       </td>
      </tr>
      <tr>
       <td></td>
       <td>
        <input type="submit" name="submit" class="btn" value="提交" />
       </td>
      </tr>
     </table>
    </form>
<?php 
}
if($t=="mon"){
$id=UsualToolCMS::sqlcheckx($_GET["id"]);
$result=$mysqli->query("select * from cms_admin where id='$id'");
while($row=$result->fetch_row()){
?>
     <form action="?m=admin&u=a_adminx.php&x=m" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
      <tr>
       <td width="20%" align="right">管理员名称</td>
       <td>
        <?php echo $row[2] ;?>
       </td>
      </tr>
	   <tr>
       <td align="right">管理员角色</td>
       <td>
<select name="roleid">
<?php
$admin=$mysqli->query("select id,rolename,ranges from `cms_admin_role`");
while($adminrow=mysqli_fetch_array($admin)):
echo"<option value='".$adminrow["id"]."'>".$adminrow["rolename"]."</option>";
endwhile;
?>
</select>
       </td>
      </tr>
      <tr>
       <td align="right">密码</td>
       <td>
        <input type="password" name="password" size="40" class="inpMain"/>
       </td>
      </tr>
      <tr>
       <td align="right">确认密码</td>
       <td>
        <input type="password" name="password_confirm" size="40" class="inpMain"/>
       </td>
      </tr>
      <tr>
       <td></td>
       <td>
        <input type="hidden" name="id" value="<?php echo$row[0];?>" />
        <input type="submit" name="submit" class="btn" value="提交" />
       </td>
      </tr>
     </table>
    </form>
<?php 
}
}
if($t=="del"){
$adminnum=mysqli_num_rows(mysqli_query($mysqli,"SELECT id FROM `cms_admin`"));
if($adminnum==1):
echo "<script>alert('管理员删除失败,已经是最后一条记录!');window.location.href='?m=admin&u=a_admin.php'</script>";
else:
if(UsualToolCMSDB::delData("cms_admin","id='".UsualToolCMS::sqlcheckx($_GET["id"])."'")):
echo "<script>alert('管理员删除成功!');window.location.href='?m=admin&u=a_admin.php'</script>";
else:echo "<script>alert('管理员删除失败!');window.location.href='?m=admin&u=a_admin.php'</script>";
endif;
endif;
}
$mysqli->close();
?>