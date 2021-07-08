<?php
$t=$_GET["t"];
$x=$_GET["x"];
if($x=="a"){
    if(UsualToolCMSDB::insertData("cms_admin_role",array(
        "rolename"=>UsualToolCMS::sqlcheck($_POST["rolename"]),
        "ranges"=>implode(",",UsualToolCMS::sqlchecks($_POST["ranges"]))))):
    echo "<script>alert('角色已添加成功!');window.location.href='?m=admin&u=a_admin_role.php'</script>";
    else:
        echo "<script>alert('角色添加失败!');window.location.href='?m=admin&u=a_admin_rolex.php&t=add'</script>";
    endif;
}
if($x=="m"){
    if(UsualToolCMSDB::updateData("cms_admin_role",array(
        "rolename"=>UsualToolCMS::sqlcheck($_POST["rolename"]),
        "ranges"=>implode(",",UsualToolCMS::sqlchecks($_POST["ranges"]))),"id='".UsualToolCMS::sqlcheckx($_POST["id"])."'")):
        echo "<script>alert('角色修改成功!');window.location.href='?m=admin&u=a_admin_role.php'</script>";
    else:
        echo "<script>alert('角色修改失败!');window.location.href='?m=admin&u=a_admin_rolex.php&t=mon&id=".$id."'</script>";
    endif;
}
?>
<h2><a href="?m=admin&u=a_admin_role.php" class="actionBtn">返回列表</a>角色添加/编辑</h2>
<?php if($t=="add"){?>
     <form action="?m=admin&u=a_admin_rolex.php&x=a" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
      <tr>
       <td width="12%" align="right">角色名称</td>
       <td>
        <input type="text" name="rolename" size="40" class="inpMain" />
       </td>
      </tr>
	   <tr>
       <td width="12%" align="right">权限范围</td>
       <td style="line-height:25px;">
---------------------------底层模块权限---------------------------<Br>
        <?php
        $modx=UsualToolCMSDB::queryData("cms_mod","","bid=3","","","0")["querydata"];
        foreach($modx as $rowx):
            $rolename=$rowx["modname"];
            $roleurl=substr(str_replace(".php","",$rowx["modurl"]),1);
            ?>
            <input type="checkbox" value="<?php echo$roleurl;?>" name="ranges[]"> <?php echo$rolename;?> 
        <?php endforeach;?>
        <br>---------------------------挂载模块权限---------------------------<Br>
        <?php
        $modv=UsualToolCMSDB::queryData("cms_mod","","bid<>3 and bid<>0","","","0")["querydata"];
        foreach($modv as $rowv):
            $rolenamev=$rowv["modname"];
            $roleurlv=substr(str_replace(".php","",$rowv["modurl"]),1);
            ?>
            <input type="checkbox" value="<?php echo$roleurlv;?>" name="ranges[]"> <?php echo$rolenamev;?> 
        <?php endforeach;?>
       </td>
      </tr>
      <tr>
       <td></td>
       <td>
        <input type="submit" name="submit" class="btn" value="提交"/>
       </td>
      </tr>
     </table>
    </form>
<?php 
}
if($t=="mon"){
    $id=UsualToolCMS::sqlcheck($_GET["id"]);
    $result=UsualToolCMSDB::queryData("cms_admin_role","","id='$id'","","","0")["querydata"];
    foreach($result as $row){
        $ranges=explode(',',$row["ranges"]);
        ?>
             <form action="?m=admin&u=a_admin_rolex.php&x=m" method="post">
             <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
              <tr>
               <td width="12%" align="right">角色名称</td>
               <td>
                <input type="text" name="rolename" size="40" class="inpMain" value="<?php echo$row["rolename"];?>"/>
               </td>
              </tr>
               <tr>
               <td width="12%" align="right">权限范围</td>
               <td style="line-height:25px;">
        ---------------------------底层模块权限---------------------------<Br>
        <?php
        $modx=UsualToolCMSDB::queryData("cms_mod","","bid=3","","","0")["querydata"];
        foreach($modx as $rowx):
            $rolename=$rowx["modname"];
            $roleurl=substr(str_replace(".php","",$rowx["modurl"]),1);
            ?>
            <input type="checkbox" value="<?php echo$roleurl;?>" name="ranges[]" <?php if(in_array($roleurl,$ranges)){ echo"checked";}?>> <?php echo$rolename;?> 
        <?php endforeach;?>
        <br>---------------------------挂载模块权限---------------------------<Br>
        <?php
        $modv=UsualToolCMSDB::queryData("cms_mod","","bid<>3 and bid<>0","","","0")["querydata"];
        foreach($modv as $rowv):
            $rolenamev=$rowv["modname"];
            $roleurlv=substr(str_replace(".php","",$rowv["modurl"]),1);
            ?>
            <input type="checkbox" value="<?php echo$roleurlv;?>" name="ranges[]" <?php if(in_array($roleurlv,$ranges)){ echo"checked";}?>> <?php echo$rolenamev;?> 
        <?php endforeach;?>
               </td>
              </tr>
              <tr>
               <td></td>
               <td>
                <input type="hidden" name="id" value="<?php echo$row["id"];?>" />
                <input type="submit" name="submit" class="btn" value="提交" />
               </td>
              </tr>
             </table>
            </form>
    <?php 
    }
}
if($t=="del"){
    $id=UsualToolCMS::sqlcheckx($_GET["id"]);
    $rolenum=UsualToolCMSDB::queryData("cms_admin_role","","","","","0")["querynum"];
    if($rolenum==1||$id==1):
        echo "<script>alert('角色删除失败,已经是最后一条记录或默认记录不能删除!');window.location.href='?m=admin&u=a_admin_role.php'</script>";
    else:
        if(UsualToolCMSDB::delData("cms_admin_role","id='$id'")):
            echo "<script>alert('角色删除成功!');window.location.href='?m=admin&u=a_admin_role.php'</script>";
        else:
            echo "<script>alert('角色删除失败!');window.location.href='?m=admin&u=a_admin_role.php'</script>";
        endif;
    endif;
}
$mysqli->close();
?>