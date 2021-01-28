<?php
$lg=UsualToolCMS::sqlcheck($_GET["lg"]);
$x=UsualToolCMS::sqlcheck($_GET["x"]);
if($x=="m"){
    $key=$_POST["key"];
    $value=$_POST["value"];
    $keys=$_POST["keys"];
    $values=$_POST["values"];
    $langs=array_combine($key,$value);
    $langx=array_combine($keys,$values);
    $langjson=json_encode(array("s"=>$langx,"l"=>$langs),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    //$langjsons=iconv("GB2312","UTF-8",$langjson);
    file_put_contents("../lang/lg-".$lg.".json",$langjson);
    echo "<script>alert('语言包保存成功!');window.location.href='?m=system&u=a_system_lang.php'</script>";
}
?>
<h2>提取&保存语言包</h2>
<?php
if(empty($x)):
    $languages=UsualToolCMS::auth($authcode,$authapiurl,"language");
    $language=explode("|",$languages);
    ?>
        <div id="list">
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
    <?php
    for($i=0;$i<count($language);$i++):
        $langcon=explode("_",$language[$i]);
        echo"<tr><td><a href='?m=system&u=a_system_lang_add.php&x=add&lg=".$langcon[0]."'>".$langcon[1]."</a></td></tr>";
    endfor;
    ?>
        </table>
        <p>注意：免费授权官方更新中英2种语言包，其他语言包可从社区下载；商业授权官方更新10+语言包。</p>
        </div>
<?php endif;?>
<?php
if($x=="add"):
    $lgjson=file_get_contents("http://cms.usualtool.com/down/language/lg-".$lg.".json");
    $lgdata=json_decode($lgjson,true);
    $data=$lgdata["l"];
    $datas=$lgdata["s"];
    ?>
    <form action="?m=system&u=a_system_lang_add.php&x=m&lg=<?php echo$lg;?>" method="post" name="form1">
    <div id="list">
    <table width="100%"><tr><td>
    <input name="submit" class="btn" type="submit" value="保存语言包" />
    </td></tr></table>
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
     <tr>
      <th width="10%" align="center">删除</th>
      <th width="20%" align="left">参数</th>
      <th width="70%" align="left" >对应值</th>
     </tr>
    <?php
    foreach($datas as $keys=>$values){
        echo"<tr>";
        echo"<td align='center'>语言参数</td>";
        echo"<td align='left'><input type='text' name='keys[]' value='".$keys."' class='inpMain' style='width:50%;color:red;'></td>";
        echo"<td><input type='text' name='values[]' value='".$values."' class='inpMain' style='width:80%;color:black;'></td></tr>";
    }
    foreach($data as $key=>$value){
        echo"<tr>";
        echo"<td align='center'><a class='btnDel' href='javascript:delete();' onclick='var table=this.parentNode.parentNode.parentNode;table.removeChild(this.parentNode.parentNode);'>删除</a></td>";
        echo"<td align='left'><input type='text' name='key[]' value='".$key."' class='inpMain' style='width:50%;color:red;'></td>";
        echo"<td><input type='text' name='value[]' value='".$value."' class='inpMain' style='width:80%;color:black;'></td></tr>";
    }
    ?>
    <table id='langinput'  width='100%' border='0' cellpadding='10' cellspacing='0' class='tablebasic'></table>
    </table>
    <table width="100%"><tr><td>
    <input name="submit" class="btn" type="submit" value="保存语言包" />
    </td></tr></table>
    </div>
    </form>
<?php endif;?>