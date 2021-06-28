<?php
$do=UsualToolCMS::sqlcheck($_GET["do"]);
if(!empty($_GET["planid"])):
    $planid=UsualToolCMS::sqlcheckx($_GET["planid"]);
else:
    $planid=1;
endif;
$a=UsualToolCMS::sqlcheck($_GET["a"]);
if($a=="t"){
    $d=implode(",",UsualToolCMS::sqlchecks($_POST["d"]));
    $p=implode(",",UsualToolCMS::sqlchecks($_POST["p"]));
    $x=implode(",",UsualToolCMS::sqlchecks($_POST["x"]));
    $w=implode(",",UsualToolCMS::sqlchecks($_POST["w"]));
    $l=implode(",",UsualToolCMS::sqlchecks($_POST["l"]));
    $c=implode(",",UsualToolCMS::sqlchecks($_POST["c"]));
    $ds= explode(",",$d); 
    $ps= explode(",",$p); 
    $xs= explode(",",$x); 
    $ws= explode(",",$w); 
    $ls= explode(",",$l); 
    $cs= explode(",",$c); 
    for($i=0;$i<count($ds);$i++) {
        $place=$ps[$i]; 
        $ordernum=$xs[$i]; 
        $linkname=$ws[$i]; 
        $linkurl=$ls[$i]; 
        $id=$ds[$i]; 
        $target=$cs[$i]; 
        if($id=="x"){
            UsualToolCMSDB::insertData("cms_nav",array(
                "place"=>$place,
                "ordernum"=>$ordernum,
                "linkname"=>$linkname,
                "linkurl"=>$linkurl,
                "target"=>$target,
                "planid"=>$planid));
        }else{
            UsualToolCMSDB::updateData("cms_nav",array(
                "place"=>$place,
                "ordernum"=>$ordernum,
                "linkname"=>$linkname,
                "linkurl"=>$linkurl,
                "target"=>$target),"id='$id'");
        }
    }
    echo "<script>window.location.href='?m=navigation&u=a_nav.php&do=$do&planid=$planid'</script>";
} 
if($a=="del"){
    UsualToolCMSDB::delData("cms_nav","id='".UsualToolCMS::sqlcheckx($_GET["id"])."'");
    echo "<script>window.location.href='?m=navigation&u=a_nav.php&do=$do&planid=$planid'</script>";
}
?>
<h2>前端导航<a href="?m=navigation&u=a_nav_plan.php" class="actionBtn">前端导航方案</a></h2>
 <div class="utwin-mask" onclick="utwin.closeAll()" id="mask-model">关闭</div>
 <div class="utwin utwin-model" id="model">
  <div class="utwin-title">内容模块前端导航选择器</div>
  <div class="utwin-content">
  <input id="utwin-text" type="hidden">
  <p><?php if(REWRITE==0):?>当前属动态连接<?php else:?>当前属伪静态连接<?php endif;?>,可安装 <a href="ut-view-module.php?m=plugin&u=a_api.php">URL路由管理</a> 插件进行转换。</p>
<?php
$list=UsualToolCMSDB::queryData("cms_mod","modid,modname,befoitem","bid=1","","","0")["querydata"];
foreach($list as $xmodrow):
    $links=explode(",",$xmodrow["befoitem"])[0];
    $link=str_replace(".php","",$links);
    if(strpos($links,'.php')!==false && strpos($links,'/')===false):
        if(REWRITE==0):
            $thelink="index.php?ut=".$link;
        else:
            $thelink=$link.".html";
        endif;
        echo"<span style='color:red;' onclick=choosenav($('#utwin-text').val(),'".$xmodrow["modname"]."','".$thelink."')>选择</span> | ".$xmodrow["modname"]." : ".$thelink."<br>";
    endif;
endforeach;
?>
  </div>
</div>
<p style="margin:5px 0;">方案模型: 
<select onchange="window.location=this.value;">
<option value="#">选择导航方案</option>
<?php
$plan=UsualToolCMSDB::queryData("cms_nav_plan","","","","","0")["querydata"];
foreach($plan as $planrow):
?>
    <option value="?m=navigation&u=a_nav.php&do=<?php echo$do;?>&planid=<?php echo$planrow["id"];?>"<?php if($planrow["id"]==$planid):echo" selected";endif;?>><?php echo$planrow["name"];?></option>
<?php endforeach;?>
</select>
</p>
<script>
function keyup(k){
    var k;
    var word=$("#word"+k+"").val();
    setTimeout(function(){
        $.getJSON("../jsonapi/jsonlang.php?word="+word+"",function(data){
            $.each(data,function(i,n){
                $("#jiexi"+k+"").html(""+n+"");
            });
        });
    },1000);
}
</script>
    <script type="text/javascript">
     $(function(){ $(".idTabs").idTabs(); }); 
    </script>
<div class="idTabs">
     <ul class="tab">
      <li><a href="#index" <?php if($do=="index")echo"class=selected";?>>◇ 主导航栏 ◇</a></li>
      <li><a href="#top" <?php if($do=="top")echo"class=selected";?>>◇ 头部位置 ◇</a></li>
      <li><a href="#bottom" <?php if($do=="bottom")echo"class=selected";?>>◇ 尾部位置 ◇</a></li>
     </ul>
    <div id="index">
        <form action="?m=navigation&u=a_nav.php&do=index&planid=<?php echo$planid;?>&a=t" method="post" id=form1 name=form1>
        <?php
        $inav=$mysqli->query("select * from cms_nav where place='index' and planid='$planid' order by ordernum asc");
        ?>
        <div id="ut-auto">
             <table width="100%" border="0" cellpadding="10" cellspacing="0" class="tablebasic">
              <tr>
              <th width="5%"></th>
              <th width="10%" align="center">位置</th>
               <th width="5%" align="center">排序</th>
               <th width="10%" align="center">窗口</th>
               <th width="15%" align="center">自选</th>
               <th width="20%" align=left>名称(建议语言参数)</th>
               <th width="15%" align=center>语言解析</th>
               <th align="left">链接地址</th>
              </tr>
        <?php
        $a=0;
        while($irow=$inav->fetch_row()){
            $a=$a+1;
            echo"<tr><td><a style='color:red;' href='?m=navigation&u=a_nav.php&a=del&do=index&id=".$irow[0]."'>删除</a></td><td align=center><input type=hidden name='d[]' value='".$irow[0]."'>主栏<input type=hidden name='p[]' value='index'></td><td align=center><input type=text name='x[]' value='".$irow[4]."' style='width:98%;height:30px;text-align:center;border:1px solid #378888;'></td>";
            echo"<td align=center><select name='c[]'>";
            if($irow[5]=="_self"):
                echo"<option value='_self' selected>原窗口</option><option value='_blank'>新窗口</option></select>";
            else:
                echo"<option value='_self'>原窗口</option><option value='_blank' selected>新窗口</option></select>";
            endif;
            echo"</td>";
            echo"<td align=center><button type='button' class='btnGray' id='addmodnav' onclick=utwin.alert('model','index-".$a."')>模块选择器</button></td>";
            echo"<td><input type=text name='w[]' class='index-".$a."-name' value='".$irow[2]."' style='width:90%;height:30px;border:1px solid #378888;' id='word".$a."' onKeyUp='keyup(".$a.")'></td><td align='center' id='jiexi".$a."'>".LangData($irow[2])."</td><td><input type=text name='l[]' class='index-".$a."-link' value='".$irow[3]."' style='width:90%;height:30px;border:1px solid #378888;'></td></tr>";
        }
        echo"<table id='tablei'  width='100%' border='0' cellpadding='10' cellspacing='0' class='tablebasic'></table>";
        ?>
        </table>
            </div>
        <table width='100%' border='0' cellpadding='0' cellspacing='0' height=60>
        <tr><td><input type="button" value="增加主栏导航" class="btnGray" onclick="addtable('tablei','index','主栏','i')" />&nbsp;&nbsp;<input type="submit" value="保存设置" class="btn"></td></tr>
        </table>
        </form>
    </div>
    <div id="top">
        <form action="?m=navigation&u=a_nav.php&do=top&planid=<?php echo$planid;?>&a=t" method="post" id=form2 name=form2>
        <?php
        $tnav=$mysqli->query("select * from cms_nav where place='top' and planid='$planid' order by ordernum asc");
        ?>
        <div id="ut-auto">
             <table width="100%" border="0" cellpadding="10" cellspacing="0" class="tablebasic">
              <tr>
              <th width=5%></th>
              <th width="10%" align="center">位置</th>
               <th width="5%" align="center">排序</th>
               <th width="10%" align="center">窗口</th>
               <th width="15%" align="center">自选</th>
               <th width="20%" align=left>名称(语言包参数)</th>
               <th width="15%" align=center>语言解析</th>
               <th align=left>链接地址</th>
              </tr>
        <?php
        $b=0;
        while($trow=$tnav->fetch_row()){
            $b=$b+1;
            echo"<tr><td><a style='color:red;' href='?m=navigation&u=a_nav.php&a=del&do=top&id=".$trow[0]."'>删除</a></td><td align=center><input type=hidden name='d[]' value='".$trow[0]."'>头部<input type=hidden name='p[]' value='top'></td><td align=center><input type=text name='x[]' value='".$trow[4]."' style='width:98%;height:30px;text-align:center;border:1px solid #378888;'></td>";
            echo"<td align=center><select name='c[]'>";
            if($trow[5]=="_self"):
                echo"<option value='_self' selected>原窗口</option><option value='_blank'>新窗口</option></select>";
            else:
                echo"<option value='_self'>原窗口</option><option value='_blank' selected>新窗口</option></select>";
            endif;
            echo"</td>";
            echo"<td align=center><button type='button' class='btnGray' id='addmodnav' onclick=utwin.alert('model','top-".$b."')>模块选择器</button></td>";
            echo"<td><input type=text name='w[]' value='".$trow[2]."' class='top-".$b."-name' style='width:90%;height:30px;border:1px solid #378888;' id='word".$b."' onKeyUp='keyup(".$b.")'></td><td align='center' id='jiexi".$b."'>".LangData($trow[2])."</td><td><input type=text name='l[]' class='top-".$b."-link' value='".$trow[3]."' style='width:90%;height:30px;border:1px solid #378888;'></td></tr>";
        }
        echo"<table id='tablet'  width='100%' border='0' cellpadding='10' cellspacing='0' class='tablebasic'></table>";
        ?>
        <table width='100%' border='0' cellpadding='0' cellspacing='0' height=60>
        <tr><td><input type="button" value="增加头部导航" class="btnGray" onclick="addtable('tablet','top','头部','t')" />&nbsp;&nbsp;<input type="submit" value="保存设置" class="btn"></td></tr>
        </table>
        </div>
        </form>
    </div>
    <div id="bottom">
        <form action="?m=navigation&u=a_nav.php&do=bottom&planid=<?php echo$planid;?>&a=t" method="post" id=form3 name=form3>
        <?php
        $bnav=$mysqli->query("select * from cms_nav where place='bottom' and planid='$planid' order by ordernum asc");
        ?>
        <div id="ut-auto">
             <table width="100%" border="0" cellpadding="10" cellspacing="0" class="tablebasic">
              <tr>
              <th width=5%></th>
              <th width="10%" align="center">位置</th>
               <th width="5%" align="center">排序</th>
               <th width="10%" align="center">窗口</th>
               <th width="15%" align="center">自选</th>
               <th width="20%" align=left>名称(语言包参数)</th>
               <th width="15%" align=center>语言解析</th>
               <th align=left>链接地址</th>
              </tr>
        <?php
        $c=0;
        while($brow=$bnav->fetch_row()){
            $c=$c+1;
            echo"<tr><td><a style='color:red;' href='?m=navigation&u=a_nav.php&a=del&do=bottom&id=".$brow[0]."'>删除</a></td><td align=center><input type=hidden name='d[]' value='".$brow[0]."'>尾部<input type=hidden name='p[]' value='bottom'></td><td align=center><input type=text name='x[]' value='".$brow[4]."' style='width:98%;height:30px;text-align:center;border:1px solid #378888;'></td>";
            echo"<td align=center><select name='c[]'>";
            if($brow[5]=="_self"):
                echo"<option value='_self' selected>原窗口</option><option value='_blank'>新窗口</option></select>";
            else:
                echo"<option value='_self'>原窗口</option><option value='_blank' selected>新窗口</option></select>";
            endif;
            echo"</td>";
            echo"<td align=center><button type='button' class='btnGray' id='addmodnav' onclick=utwin.alert('model','bottom-".$c."')>模块选择器</button></td>";
            echo"<td><input type=text name='w[]' class='bottom-".$c."-name' value='".$brow[2]."' style='width:90%;height:30px;border:1px solid #378888;' id='word".$c."' onKeyUp='keyup(".$c.")'></td><td align='center' id='jiexi".$c."'>".LangData($brow[2])."</td><td><input type=text name='l[]' class='bottom-".$c."-link' value='".$brow[3]."' style='width:90%;height:30px;border:1px solid #378888;'></td></tr>";
        }
        echo"<table id='tableb'  width='100%' border='0' cellpadding='10' cellspacing='0' class='tablebasic'></table>";
        ?>
        </table>
        <table width='100%' border='0' cellpadding='0' cellspacing='0' height=60>
        <tr><td><input type="button" value="增加尾部导航" class="btnGray" onclick="addtable('tableb','bottom','尾部','b')" />&nbsp;&nbsp;<input type="submit" value="保存设置" class="btn"></td></tr>
        </table>
        </div>
        </form>
    </div>
</div>
<script type="text/javascript">
var k=9999;	
function addtable(table,place,word,l){  
	var table;
	var place;
	var word;
	var l;
	k++;
	document.getElementById(""+table+"").innerHTML+="<tr><td width=5%></td><td align='center' width='10%'><input type=hidden name='d[]' value='x'>"+word+"<input type='hidden' name='p[]' value='"+place+"'></td><td align='center' width='5%'><input type='text' name='x[]' style='width:98%;height:30px;text-align:center;border:1px solid #378888;' value='0'></td><td  width='10%' align=center><select name='c[]'><option value='_self'>原窗口</option><option value='_blank'>新窗口</option></select></td><td align=center width='15%'><button type='button' class='btnGray' id='addmodnav' onclick=utwin.alert('model','"+place+"-"+k+"')>模块选择器</button></td><td width='20%'><input type='text' name='w[]' class='"+place+"-"+k+"-name' style='width:90%;height:30px;border:1px solid #378888;' id='word"+k+"' onKeyUp=keyup('"+k+"')></td><td width='15%' align='center' id='jiexi"+k+"'></td><td><input type='text' name='l[]' class='"+place+"-"+k+"-link' style='width:90%;height:30px;border:1px solid #378888;'></td></tr>";
}
function choosenav(navid,name,link){
    var navid;
    var name;
    var link;
    $("."+navid+"-name").val(name);
    $("."+navid+"-link").val(link);
    setTimeout(function(){
        utwin.close("model")
    },1000);
}
</script>