<?php
$t=$_GET["t"];
$x=$_GET["x"];
if(!empty($x)){
    $id=UsualToolCMS::sqlcheck($_POST["id"]);
    $langx=UsualToolCMS::sqlchecks($_POST["lang"]);
    $langs=implode(",",$langx);
    $indexlanguage=UsualToolCMS::sqlchecks($_POST["indexlanguage"]);
}
if($x=="m"){
    $sql="UPDATE `cms_setup` set language='$langs',indexlanguage='$indexlanguage' where id='$id'";
    if($mysqli->query($sql) == TRUE){
        echo "<script>alert('保存成功!');window.location.href='?m=system&u=a_system_lang_set.php'</script>";
    }else{
        echo "<script>alert('保存失败!');window.location.href='?m=system&u=a_system_lang_set.php'</script>";
    }
    $mysqli->close();
}
?>
<h2><a href="?m=system&u=a_system_lang.php" class="actionBtn">语言包管理</a>语言设置</h2>
<?php
$result=$mysqli->query("select id,language,indexlanguage from `cms_setup` limit 1");
while($row=$result->fetch_row()){
$id=$row[0];
$language=$row[1];
$language = explode(",", $language);
$indexlanguage=$row[2];
}
?>
    <form action="?m=system&u=a_system_lang_set.php&x=m" id="form" name="form" method="post">
     <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
<tr>
           <td align="right" width=20%>前端默认语言</td>
           <td>
	   <?php
	   for($i=0;$i<count($lang);$i++):
           if($indexlanguage==$lang[$i]['lgname']):
               echo"<input type='radio' name='indexlanguage' value='".$lang[$i]['lgname']."' checked='true'>".$lang[$i]['speak']." ";
           else:
               echo"<input type='radio' name='indexlanguage' value='".$lang[$i]['lgname']."'>".$lang[$i]['speak']." ";
           endif;
	   endfor;
	   ?>
                       </td>
          </tr>
      <tr>
       <td align="right">前端显示语言<br>多选</td>
       <td>
	   <?php
	   for($i=0;$i<count($lang);$i++):
           if(in_array($lang[$i]['lgname'],$language)):
               echo"<input type='checkbox' name='lang[]' value='".$lang[$i]['lgname']."' checked='true'>".$lang[$i]['speak']." ";
           else:
               echo"<input type='checkbox' name='lang[]' value='".$lang[$i]['lgname']."'>".$lang[$i]['speak']." ";
           endif;
	   endfor;
	   ?>
       </td>
      </tr>
      <tr>
       <td></td>
       <td>
	   <input type=hidden name="id" value="<?php echo$id;?>">
        <input name="submit" class="btn" type="submit" value="保存设置" />
       </td>
      </tr>
     </table>
    </form>