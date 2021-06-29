<?php
if($_GET["t"]=="set"){
    UsualToolCMSDB::updateData("cms_setup",array("delplugindb"=>UsualToolCMS::sqlcheckx($_POST["delplugindb"])),"id='".UsualToolCMS::sqlcheckx($_POST["id"])."'");
    echo "<script>alert('设置成功!');window.location.href='?m=plugin&u=a_api_set.php'</script>";
}
?>
<h2>插件操作设置</h3>
<form action="?m=plugin&u=a_api_set.php&t=set" method="post" id="form1" name="form1">
<input type="hidden" name="id" value="<?php echo$setup["id"];?>" />
<table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
    <tr>
        <td align="right">卸载插件是否同步删除相关表</td>
        <td>
            <label for="site_closed_0"><input type="radio" name="delplugindb" value="1" <?php if($setup["delplugindb"]==1):echo"checked";endif;?>>是</label>
            <label for="site_closed_0"><input type="radio" name="delplugindb" value="0" <?php if($setup["delplugindb"]==0):echo"checked";endif;?>>否</label>
        </td>
    </tr>
    <tr>
        <td width=20%></td><td><input name="submit" class="btn" type="submit" value="保存设置" /></td>
    </tr>
</table>
</form>