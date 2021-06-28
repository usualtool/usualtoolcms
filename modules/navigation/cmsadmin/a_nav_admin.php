<?php
$x=$_GET["x"];
if($x=="m"){
    $navid=UsualToolCMS::sqlchecks($_POST["navid"]);
    $ids=implode(",",$navid);
    $look=UsualToolCMS::sqlchecks($_POST["look"]);
    $looks=implode(",",$look);
    $idx= explode(",",$ids);
    $lookx= explode(",",$looks);
    for($i=0;$i<count($idx);$i++){
        UsualToolCMSDB::updateData("cms_mod",array("look"=>$lookx[$i]),"id='$idx[$i]'");
    }
    echo "<script>alert('后端导航设置成功!');window.location.href='?m=navigation&u=a_nav_admin.php'</script>";
    $mysqli->close();
}
if($x=="add"){
    $d=implode(",",UsualToolCMS::sqlchecks($_POST["d"]));
    $o=implode(",",UsualToolCMS::sqlchecks($_POST["o"]));
    $n=implode(",",UsualToolCMS::sqlchecks($_POST["n"]));
    $u=implode(",",UsualToolCMS::sqlchecks($_POST["u"]));
    $ds= explode(",",$d); 
    $os= explode(",",$o); 
    $ns= explode(",",$n); 
    $us= explode(",",$u);  
    for($i=0;$i<count($ds);$i++) {
        $ordernum=$os[$i]; 
        $linkname=$ns[$i]; 
        $linkurl=$us[$i]; 
        $id=$ds[$i]; 
        if($id=="x"){
                UsualToolCMSDB::insertData("cms_nav",array(
                    "place"=>"cmsadmin",
                    "ordernum"=>$ordernum,
                    "linkname"=>$linkname,
                    "linkurl"=>$linkurl,
                    "target"=>"_self",
                    "planid"=>0));
        }else{
                UsualToolCMSDB::updateData("cms_nav",array(
                    "place"=>"cmsadmin",
                    "ordernum"=>$ordernum,
                    "linkname"=>$linkname,
                    "linkurl"=>$linkurl,
                    "target"=>"_self"),"id='$id'");
        }
    }
    echo "<script>window.location.href='?m=navigation&u=a_nav_admin.php'</script>";
} 
if($x=="del"){
    UsualToolCMSDB::delData("cms_nav","id='".UsualToolCMS::sqlcheckx($_GET["id"])."'");
    echo "<script>window.location.href='?m=navigation&u=a_nav_admin.php'</script>";
}
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td valign="top">
<h2>后端导航</h2>
     <form action="?m=navigation&u=a_nav_admin.php&x=m" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
<?php
if(preg_match("/($Ospat)/i", $Uagent)):
$p=2;
else:
$p=4;
endif;
$mod=UsualToolCMSDB::queryData("cms_mod","id,bid,modname,modurl","bid=0","","","0")["querydata"];
foreach($mod as $modrow){
    $moddata=UsualToolCMSDB::queryData("cms_mod","id,bid,modname,modurl,isopen,look","isopen=1 and bid='".$modrow["id"]."'","","","0");
    if($moddata["querynum"]>0):
        echo"<tr><td colspan='".$p."'>".$modrow["modname"]."";
        echo"</td></tr><tr>";
        foreach($moddata["querydata"] as $xmodrow):
            if($xmodrow["look"]=="1"):
                echo"<td><a href='?m=navigation&u=".$xmodrow["modurl"]."' style='color:red;'>".$xmodrow["modname"]."</a>: <input type=hidden name='navid[]' value='".$xmodrow["id"]."'><select name='look[]'><option value='1' selected>显示</option><option value='0'>隐藏</option></select></td>";
            else:
                echo"<td><a href='?m=navigation&u=".$xmodrow["modurl"]."' style='color:red;'>".$xmodrow["modname"]."</a>: <input type=hidden name='navid[]' value='".$xmodrow["id"]."'><select name='look[]'><option value='1'>显示</option><option value='0' selected>隐藏</option></select></td>";
            endif;
            if($xmodrow["xu"]%$p==0):
                echo"</tr><tr>";
            endif;
        endforeach;
        echo"</tr>";
    else:
        echo"<tr><td colspan='".$p."'><a href='?m=navigation&u=".$modrow["modurl"]."'>".$modrow["modname"]."</a></td></tr>";
    endif;
}
?>
     <tr><td colspan="<?php echo$p;?>" align="center"><input type="submit" name="submit" class="btn" value="保存设置"/></td></tr>
     </table>
    </form>
	</td>
	<td id="cmsnone" style="width:2%;" valign="top"></td>
	<td id="cmsnone" style="width:50%;" valign="top">
	<h2>自定义后端导航<span style='font-size:11px;'> (仅对CMS平台有效)</span></h2>
	 <form action="?m=navigation&u=a_nav_admin.php&x=add" method="post">
     <div id="ut-auto">
     <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tablebasic">
      <tr>
	  <th width="8%"></th>
       <th width="10%" align="left">排序</th>
       <th width="20%" align="left">名称</th>
       <th align=left>链接地址</th>
      </tr>
<?php
$list=UsualToolCMSDB::queryData("cms_nav","","place='cmsadmin'","ordernum asc","","0")["querydata"];
foreach($list as $row){
    echo"<tr>";
    echo"<td><input type=hidden name='d[]' value='".$row["id"]."'><a style='color:red;' href='?m=navigation&u=a_nav_admin.php&x=del&id=".$row["id"]."'>删除</a></td>";
    echo"<td><input type=text name='o[]' value='".$row["ordernum"]."' class='inpMain' style='width:80%'></td>";
    echo"<td><input type=text name='n[]' value='".$row["linkname"]."' class='inpMain' style='width:95%'></td>";
    echo"<td><input type=text name='u[]' value='".$row["linkurl"]."' class='inpMain' style='width:95%'></td>";
    echo"</tr>";
}
echo"<table id='tablea' width='100%' border='0' cellpadding='10' cellspacing='0' class='tablebasic'></table>";
?>	 
</div>
     <table width='100%' border='0' cellpadding='0' cellspacing='0' height=60>
	 <tr><td>
	 <input type="button" value="增加" class="btnGray" onclick="addtablex('tablea')" />
	 &nbsp;&nbsp;
	 <input type="submit" value="保存" class="btn">
	 </td></tr>
	 </table>
	 </form>
	</td>
	</tr>
	</table>
<script type="text/javascript">
	var k=9999;	
    function addtablex(table){  
        var table;
        k++;
        document.getElementById(""+table+"").innerHTML+="<tr><td width='8%'></td><td width='10%'><input type=hidden name='d[]' value='x'><input type=text name='o[]' class='inpMain' value='1' style='width:80%;text-align:center;'></td><td width='20%'><input type=text name='n[]' class='inpMain' style='width:95%'></td><td><input type=text name='u[]' class='inpMain' style='width:95%'></td></tr>";
    }
</script>