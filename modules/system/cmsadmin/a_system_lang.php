<?php
function readlang($path){
    $current_dir = opendir($path);
    while(($file = readdir($current_dir)) !== false) {
    $sub_dir = "".$path."/".$file."";
    if($file == '.' || $file == '..') {
    continue;
    }elseif(is_dir($sub_dir)){
    readlang($sub_dir);
    }elseif(UsualToolCMS::contain("json",$file)){
        $lgjson=file_get_contents("".$path."/".$file."");
        $type=str_replace(".json","",str_replace("lg-","",$file));
        echo"<tr><td>".$file."</td>";
        echo"<td>".(count(json_decode($lgjson,true),1)-2)."</td>";
        echo"<td>".LangSet('language',$type)."</td>";
        echo"<td>".LangSet('charset',$type)."</td>";
        echo"<td align=center><a href='?m=system&u=a_system_langx.php&lg=".$file."' style='color:red;'>编辑</a> | <a href='?m=system&u=a_system_langx.php&lg=".$file."&x=del' style='color:red;'>卸载</a></td>";
        echo"</tr>";
    }
    }
}
?>
    <h2><a href="?m=system&u=a_system_lang_add.php" class="actionBtn">提取官方语言包</a> 语言包</h2>
    <div id="list">
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
     <tr>
	  <th width="25%" align="left">语言文件</th>
	  <th width="15%" align="left" >参数数量</th>
	  <th width="15%" align="left">语言种类</th>
      <th width="15%" align="left">编码方式</th>
      <th align="center">操作</th>
     </tr>
<?php readlang('../lang/');?>
</table>