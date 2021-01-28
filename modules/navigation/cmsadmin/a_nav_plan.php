<?php
$t=$_GET["t"];
if($t=="mon"){
    $ids=implode(",",UsualToolCMS::sqlchecks($_POST["id"]));
    $names=implode(",",UsualToolCMS::sqlchecks($_POST["name"]));
    $idx= explode(",",$ids); 
    $namex= explode(",",$names); 
    for($i=0;$i<count($idx);$i++) {
        $id=$idx[$i]; 
        $name=$namex[$i]; 
        $sqls="update cms_nav_plan set name='$name' where id='$id'";
        $mysqli->query($sqls);
    }
    echo "<script>window.location.href='?m=navigation&u=a_nav_plan.php'</script>";
}
if($t=="add"){
    $name=UsualToolCMS::sqlcheck($_POST["name"]);
    $sql="INSERT INTO cms_nav_plan (name) VALUES ('$name')";
    if($mysqli->query($sql) == TRUE):echo "<script>alert('创建成功!');window.location.href='?m=navigation&u=a_nav_plan.php'</script>";
    else:echo "<script>alert('创建失败!');window.location.href='?m=navigation&u=a_nav_plan.php&t=add'</script>";
    endif;
    $mysqli->close();
}
?>
<h2>导航方案管理</h2>
<div id="ut-auto">
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
     <tr>
      <th align="right" width="20%">操作</th>
      <th align="center" width="8%">当前方案</th>
      <th align="left">方案名称</th>
      </tr>
<form action="?m=navigation&u=a_nav_plan.php&t=mon" method="post" id=form1 name=form1>
<?php
$plan=$mysqli->query("select * from cms_nav_plan");
while($planrow=mysqli_fetch_array($plan)):
?>
    <tr> 
        <td align=right><a href='?m=navigation&u=a_nav_plan.php&t=del&id=<?php echo$planrow["id"];?>'>删除</a></td>  
        <td align="center"><?php if($planrow["indexplan"]==1):echo" 是";endif;?></td>
        <td align=left>
        <input type=hidden name="id[]" value="<?php echo$planrow["id"];?>">
        <input type=text name="name[]" value="<?php echo$planrow["name"];?>" size="50" class="inpMain">
        </td>  
        </tr>
<?php endwhile;?>
<tr> 
    <td align=right></td>  
	<td></td>
    <td align=left>
	<input type="submit" value="编辑方案" class="btn">
	</td>
	</tr>
	</form>
<form action="?t=add" method="post" id=form2 name=form2>
<tr> 
    <td align=right>新增</td> 
	<td></td>
    <td align=left>
	<input type=text name="name" value="" size="50" class="inpMain">
	</td>  
    </tr>
<tr> 
    <td align=right></td>  
	<td></td>
    <td align=left>
	<input type="submit" value="创建方案" class="btn">
	</td>
	</tr>
	</form>
          </table>
          </div>
<?php
if($t=="del"){
    $id=UsualToolCMS::sqlcheckx($_GET["id"]);
    $querys="select * from cms_nav where planid='$id'";
    $datas=mysqli_query($mysqli,$querys);
    if(mysqli_num_rows($datas)!==0){
        echo "<script>alert('警告:该方案下存在导航，请先清除该方案下的导航再执行删除!');window.location.href='?m=navigation&u=a_nav_plan.php'</script>";
    }else{
        $result=mysqli_query($mysqli,"DELETE FROM cms_nav_plan WHERE id='$id'");
        if(!$result){
            echo "<script>alert('删除失败!');window.location.href='?m=navigation&u=a_nav_plan.php'</script>";
        }else{
            echo "<script>alert('删除成功!');window.location.href='?m=navigation&u=a_nav_plan.php'</script>";
        }
    }
}
?>
       </div>
 </div>