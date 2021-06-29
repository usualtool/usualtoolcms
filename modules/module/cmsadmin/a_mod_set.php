<?php
if($_GET["t"]=="set"){
    UsualToolCMSDB::updateData("cms_setup",array("delmoddb"=>UsualToolCMS::sqlcheckx($_POST["delmoddb"])),"id='".UsualToolCMS::sqlcheckx($_POST["id"])."'");
    echo "<script>alert('设置成功!');window.location.href='?m=module&u=a_mod_set.php'</script>";
}
?>
<h2>模块操作设置</h3>
<form action="?m=module&u=a_mod_set.php&t=set" method="post" id="form1" name="form1">
<input type="hidden" name="id" value="<?php echo$setup["id"];?>" />
<table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
    <tr>
        <td align="right">卸载模块是否同步删除相关表</td>
        <td>
            <label for="site_closed_0"><input type="radio" name="delmoddb" value="1" <?php if($setup["delmoddb"]==1):echo"checked";endif;?>>是</label>
            <label for="site_closed_0"><input type="radio" name="delmoddb" value="0" <?php if($setup["delmoddb"]==0):echo"checked";endif;?>>否</label>
        </td>
    </tr>
    <tr>
        <td width=20%></td><td><input name="submit" class="btn" type="submit" value="保存设置" /></td>
    </tr>
</table>
</form>