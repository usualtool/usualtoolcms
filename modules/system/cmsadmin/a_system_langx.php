<?php
$lg=UsualToolCMS::sqlcheck($_GET["lg"]);
$lgs=str_replace(".json","",$lg);
$x=UsualToolCMS::sqlcheck($_GET["x"]);
if($x=="m"){
    $key=$_POST["key"];
    $value=$_POST["value"];
    $keys=$_POST["keys"];
    $values=$_POST["values"];
    $langs=array_combine($key,$value);
    $langx=array_combine($keys,$values);
    $langjson=json_encode(array("s"=>$langx,"l"=>$langs),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    file_put_contents("../lang/".$lgs.".json",$langjson);
    echo "<script>alert('语言包保存成功!');window.location.href='?m=system&u=a_system_langx.php&lg=$lg'</script>";
}
$lgjson=file_get_contents("../lang/".$lgs.".json");
$lgdata=json_decode($lgjson,true);
$data=$lgdata["l"];
$datas=$lgdata["s"];
?>
	<form action="?m=system&u=a_system_langx.php&x=m&lg=<?php echo$lg;?>" method="post" name="form1">
    <div id="list">
	<table width="100%"><tr><td>
	<input name="submit" class="btn" type="submit" value="保存语言包" />  
	<input type="button" value="扩展键&值" class="btnGray" onclick="addlang()" />
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
<input type="button" value="扩展键&值" class="btnGray" onclick="addlang()"/>
</td></tr></table>
</div>
</form>
</div>
</div>
<?php
if($x=="del"){
    UsualToolCMS::unlinkfile("../lang/".$lgs.".json");
    echo "<script>alert('卸载成功!');window.location.href='?m=system&u=a_system_lang.php'</script>";
}
?>
<script type="text/javascript">
function addlang(){
	document.getElementById("langinput").innerHTML+="<tr><td width='10%' align='center'><a class='btnDel' href='javascript:delete();' onclick='var table=this.parentNode.parentNode.parentNode;table.removeChild(this.parentNode.parentNode);'>删除</a></td><td width='20%'><input type='text' name='key[]' value='' class='inpMain' style='width:50%;color:red;'></td><td width='70%'><input type='text' name='value[]' value='' class='inpMain' style='width:80%;color:black;'></td></tr>";
	window.scrollTo(0,document.body.scrollHeight);
}
</script>