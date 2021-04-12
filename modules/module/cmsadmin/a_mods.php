<table width=100% align=center>
<tr><td>
<ul id="pageMain">
<?php
$do=UsualToolCMS::sqlcheck($_GET["do"]);
$mod=$mysqli->query("select * from `cms_mod` where bid>0 order by ordernum desc");
while($modrow=mysqli_fetch_array($mod)):
    $catname=mysqli_fetch_array(mysqli_query($mysqli,"SELECT modname FROM `cms_mod` where id='".$modrow["bid"]."'"))[0];
    $zmods=file_get_contents("../modules/".$modrow["modid"]."/usualtoolcms.config");
    $zmodtype=UsualToolCMS::str_substr("<modtype>","</modtype>",$zmods);
    if($zmodtype==1):
        $type="公共";
        $typex="g";
    elseif($zmodtype==2):
        $type="私有";
        $typex="z";
    elseif($zmodtype==3):
        $type="<font color=red>底层模块</font>";
        $typex="s";
    else:
        $type="未知";
        $typex="w";
    endif;
    if($modrow["bid"]==3):
        echo"<li class='modli' style='background-color:#FFFFCC;'>";
    else:
        echo"<li class='modli' style='background-color:#EEEEEE;'>";
    endif;
    echo"".$modrow["modname"]."<br>".$catname."<br>".$type."<br>";
    if($modrow["bid"]==3):
        echo"<a href='?m=".$modrow["modid"]."&u=".$modrow["modurl"]."' onclick='navclick(".$modrow["id"].")'>使用</a>";
    else:
        echo"<a href='?m=".$modrow["modid"]."&u=".$modrow["modurl"]."'onclick='navclick(".$modrow["id"].")'>使用</a> | <a href='?m=module&u=a_modsx.php&id=".$modrow["modid"]."&t=resetup&do=".$typex."'>卸载</a>";
    endif;
    echo"</li>";
endwhile;
?>
</ul>
</td></tr></table>
    <script type="text/javascript" src="js/usualtool-cms-2.0.js"></script>
    <script type="text/javascript">
     $(function(){ $(".idTabs").idTabs(); }); 
    </script>
    <div class="idTabs">
      <ul class="tab">
	  	<li><a href="?m=module&u=a_mods.php&do=g" <?php if($do=="g"||(empty($do)))echo"class=selected";?>>官方模块</a></li>
		<li><a href="?m=module&u=a_mods.php&do=z" <?php if($do=="z")echo"class=selected";?>>私有模块</a></li>
		<li><a href="?m=module&u=a_mods.php&do=o" style="background-color:rgb(224, 224, 224);color:white;">✔接收模块</a></li>
		</ul>
<div class="items">
<?php if(empty($do)||$do=="g"):?>
<div id="g">
<p style="line-height:40px;">注意：请谨慎卸载模块，卸载前请备份数据。<a target="_blank" href="//cms.usualtool.com/mokuai.php">更多模块请点击这里</a></p>
<div id="containet">
    <ul id="pageMain">
<?php
$modules=UsualToolCMS::auth($authcode,$authapiurl,"modules");
preg_match_all( "/\<module\>(.*?)\<\/module\>/s",$modules,$moduleblocks);
foreach($moduleblocks[1] as $module):
    preg_match_all( "/\<id\>(.*?)\<\/id\>/",$module,$id);  
    preg_match_all( "/\<catid\>(.*?)\<\/catid\>/",$module,$catid);   
    preg_match_all( "/\<title\>(.*?)\<\/title\>/",$module,$title);
    preg_match_all( "/\<isfree\>(.*?)\<\/isfree\>/",$module,$isfree);
    $catname=mysqli_fetch_array(mysqli_query($mysqli,"SELECT modname FROM `cms_mod` where id='".$catid[1][0]."'"))[0];
    $modnum=mysqli_num_rows(mysqli_query($mysqli,"SELECT id FROM `cms_mod` where modid='".$id[1][0]."'"));
    if(!empty($modnum) && $modnum>0):
        $state="<font color=red>已安装</font>";
    else:
        $state="<a href='?m=module&u=a_modsx.php&id=".$id[1][0]."&t=setup&do=g'>安装模块</a>";
    endif;
    echo"<li class='modli'>";
    echo"".$title[1][0]."<br>".$catname."<br>".$isfree[1][0]."<br>".$state."";
    echo"</li>";
endforeach;
?>
</ul>
</div>
</div>
<?php
elseif($do=="z"):?>
<div id="z">
<p style="line-height:40px;">注意：请谨慎卸载模块，卸载前请备份数据。</p>
<table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
<tr bgcolor="#eeeeee"><td><b>模块名称</b></td><td><b>模块类别</b></td><td><b>模块标志</b></td><td><b>状态</b></td></tr>
<?php
function zzreadmod($path = '.'){
    include('../sql_db.php');
    $current_dir = opendir($path);
    while(($file = readdir($current_dir)) !== false) {
        $sub_dir = "".$path."/".$file."";
        if($file == '.' || $file == '..') {
        continue;
        }elseif(is_dir($sub_dir)){
        zzreadmod($sub_dir);
        }elseif(UsualToolCMS::contain("usualtoolcms.config",$file)){
            $zzmods=file_get_contents("".$path."/".$file."");
            $zzmodtype=UsualToolCMS::str_substr("<modtype>","</modtype>",$zzmods);
            if($zzmodtype==2):
                $zzid=UsualToolCMS::str_substr("<id>","</id>",$zzmods);
                $zzcatid=UsualToolCMS::str_substr("<itemid>","</itemid>",$zzmods);
                $zztitle=UsualToolCMS::str_substr("<modname>","</modname>",$zzmods);
                $zzauther=UsualToolCMS::str_substr("<auther>","</auther>",$zzmods);
                $zzurl=UsualToolCMS::str_substr("<modurl>","</modurl>",$zzmods);
                $modnum=mysqli_num_rows(mysqli_query($mysqli,"SELECT id FROM `cms_mod` where modid='".$zzid."'"));
                if(!empty($modnum) && $modnum>0):
                    $zzstate="<font color=red>已安装</font>";
                else:
                    $zzstate="<a href='?m=module&u=a_modsx.php&id=".$zzid."&do=z&upname=usualtoolcms'>安装模块</a>";
                endif;
                echo"<tr><td>".$zztitle."</td>";
                $result=$mysqli->query("select * from `cms_mod` where id='".$zzcatid."'");
                while($row=$result->fetch_row()):
                    $zzcatname=$row[3];
                endwhile;
                echo"<td>".$zzcatname."</td>";
                echo"<td>".$zzid."</td>";
                echo"<td>".$zzstate."</td>";
                echo"</tr>";
            endif;
        }
    }
}
zzreadmod('../modules/');
?>
</table>
</div>
<?php
elseif($do=="o"):?>
<div id="o">
<p style="line-height:40px;">注意：请谨慎卸载模块，卸载前请备份数据。<a target="_blank" href="//cms.usualtool.com/mokuai.php">更多模块请点击这里</a></p>
<?php 
$modules=UsualToolCMS::auth($authcode,$authapiurl,"moduleorder");
preg_match_all("/\<module\>(.*?)\<\/module\>/s",$modules,$moduleblocks); 
?>
<table width=100% align=center class="tablebasic" cellpadding="8">
<tr>
<th width="20%" align="left">唯一标识</th>
<th width="20%">接收时间</th>
<th align="left">模块名称</th>
<th width="15%">操作</th>
</tr>
<?php
foreach($moduleblocks[1] as $module):
    preg_match_all( "/\<moduleid\>(.*?)\<\/moduleid\>/",$module,$moduleid);  
    preg_match_all( "/\<orderid\>(.*?)\<\/orderid\>/",$module,$orderid);  
    preg_match_all( "/\<id\>(.*?)\<\/id\>/",$module,$id);  
    preg_match_all( "/\<title\>(.*?)\<\/title\>/",$module,$title);
    preg_match_all( "/\<ordertime\>(.*?)\<\/ordertime\>/",$module,$ordertime);
    echo"<tr><td>".$orderid[1][0]."</td>";
    echo"<td align=center>".$ordertime[1][0]."</td>";
    echo"<td><a href='//cms.usualtool.com/mokuai_read.php?id=".$module[1][0]."' target='_blank'>".$title[1][0]."</a></td>";
    $modnum=mysqli_num_rows(mysqli_query($mysqli,"SELECT id FROM `cms_mod` where modid='".$id[1][0]."'"));
    if(!empty($modnum) && $modnum>0):
        echo"<td align=center style='color:red;'>已安装</td></tr>";
    else:
        echo"<td align=center><a href='?m=module&u=a_modsx.php&sign=moduleorder-".$id[1][0]."&t=setup&do=o'>安装模块</a></td></tr>";
    endif;
endforeach;
?>
</table>
</div>
<?php endif;?>
</div>
</div>