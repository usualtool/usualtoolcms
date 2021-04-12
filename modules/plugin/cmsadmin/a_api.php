<div class="page">
<div class="items">
<div id="a">
<table width=100% align=center style="margin-bottom:15px;">
<tr>
<?php
$do=UsualToolCMS::sqlcheck($_GET["do"]);
if(preg_match("/($Ospat)/i",$Uagent)){$k=5;}else{$k=7;}
$plugin=$mysqli->query("select * from `cms_plugins` order by hid desc");
$i=0;
while($pluginrow=mysqli_fetch_array($plugin)):
    echo"<td class='pluginli' style='background-color:#EEEEEE;'><a style='text-decoration:underline;' href='ut-view-plugin.php?hookid=".$pluginrow["id"]."'>".$pluginrow["title"]."</a><br>".$pluginrow["ver"]."<br>";
    echo"<a href='ut-view-plugin.php?hookid=".$pluginrow["id"]."'>管理</a>&nbsp;|&nbsp;<a href='?m=".$mod."&u=a_apix.php&sign=plugin-".$pluginrow["id"]."&t=del&zipurl=usualtoolcms'>卸载</a></td>";
    $i++;
    if($i%$k==0):
        echo"</tr><tr>";
    endif;
endwhile;
?>
</tr>
</table>
</div>
    <script type="text/javascript">
     $(function(){ $(".idTabs").idTabs(); }); 
    </script>
    <div class="idTabs">
      <ul class="tab">
	  	<li><a href="?m=plugin&u=a_api.php&do=a" <?php if($do=="a"||(empty($do)||$do=="g"))echo"class=selected";?>>官方插件</a></li>
 		<li><a href="?m=plugin&u=a_api.php&do=z" <?php if($do=="z")echo"class=selected";?>>私有插件</a></li>
 		<li><a href="?m=plugin&u=a_api.php&do=o" style="background-color:rgb(224, 224, 224);color:white;">✔接收插件</a></li>
		</ul>
<?php if(empty($do)||$do=="a"||$do=="g"):?>
<div id="g">
<p style="line-height:40px;"><a target="_blank" href="//cms.usualtool.com/chajian.php">注意：网络波动可能影响通讯，请自行做好备份，必要时请手动升级。更多插件请点击这里</a></p>
<div id="containet">
    <ul id="pageMain">
<?php 
$hooks=UsualToolCMS::auth($authcode,$authapiurl,"hookapi");
preg_match_all( "/\<hook\>(.*?)\<\/hook\>/s",$hooks,$hookblocks);
foreach($hookblocks[1] as $hook):
    preg_match_all( "/\<id\>(.*?)\<\/id\>/",$hook,$id);  
    preg_match_all( "/\<type\>(.*?)\<\/type\>/",$hook,$type);  
    preg_match_all( "/\<price\>(.*?)\<\/price\>/",$hook,$price);  
    preg_match_all( "/\<title\>(.*?)\<\/title\>/",$hook,$title);
    preg_match_all( "/\<picurl\>(.*?)\<\/picurl\>/",$hook,$picurl);
    preg_match_all( "/\<ver\>(.*?)\<\/ver\>/",$hook,$ver);
    preg_match_all( "/\<isfree\>(.*?)\<\/isfree\>/",$hook,$isfree);
    echo"<li class='pluginli'>";
    echo"<img style='width:80px;padding-top:10px;' width=80 height=80 src='//cms.usualtool.com/".$picurl[1][0]."'><p>".$title[1][0]."<br>".$isfree[1][0]."<br>";
    if(UsualToolCMS::searchdir("../plugins/".$id[1][0]."")==1):
        echo"<font color=red>已安装</font>";
    else:
        echo"<a href='?m=plugin&u=a_apix.php&sign=plugin-".$id[1][0]."&t=setup&zipurl=usualtoolcms' style='color:green;text-decoration:underline;'>应用插件</a>";
    endif;
    echo"</p></li>";
endforeach;
?>
</ul>
</div>
    <div id="pageBox">
        <span id="prev">上一页</span>
        <ul id="pageNav"></ul>
        <span id="next">下一页</span>
    </div>
<script type="text/javascript">
    $(function () {
        tabPage({
            pageMain: '#pageMain',
            pageNav: '#pageNav',
            pagePrev: '#prev',
            pageNext: '#next',
            curNum: 16,
            activeClass: 'active',
            ini: 0
        });
        function tabPage(tabPage) {
            var pageMain = $(tabPage.pageMain);
            var pageNav = $(tabPage.pageNav);
            var pagePrev = $(tabPage.pagePrev);
            var pageNext = $(tabPage.pageNext);
            var curNum = tabPage.curNum;
            var len = Math.ceil(pageMain.find("li").length / curNum);
            console.log(len);
            var pageList = '';
            var iNum = 0;
            for (var i = 0; i < len; i++) {
                pageList += '<a href="javascript:;">' + (i + 1) + '</a>';
            }
            pageNav.html(pageList);
            pageNav.find("a:first").addClass(tabPage.activeClass);
                pageNav.find("a").each(function(){
                    $(this).click(function () {
                        pageNav.find("a").removeClass(tabPage.activeClass);
                        $(this).addClass(tabPage.activeClass);
                        iNum = $(this).index();
                        $(pageMain).find("li").hide();
                        for (var i = ($(this).html() - 1) * curNum; i < ($(this).html()) * curNum; i++) {
                            $(pageMain).find("li").eq(i).show()
                        }
                    });
            })
            $(pageMain).find("li").hide();
            for (var i = 0; i < curNum; i++) {
                $(pageMain).find("li").eq(i).show()
            }
            pageNext.click(function () {
                $(pageMain).find("li").hide();
                if (iNum == len - 1) {
                    alert('已经是最后一页');
                    for (var i = (len - 1) * curNum; i < len * curNum; i++) {
                        $(pageMain).find("li").eq(i).show()
                    }
                    return false;
                } else {
                    pageNav.find("a").removeClass(tabPage.activeClass);
                    iNum++;
                    pageNav.find("a").eq(iNum).addClass(tabPage.activeClass);
//                    ini(iNum);
                }
                for (var i = iNum * curNum; i < (iNum + 1) * curNum; i++) {
                    $(pageMain).find("li").eq(i).show()
                }
            });
            pagePrev.click(function () {
                $(pageMain).find("li").hide();
                if (iNum == 0) {
                    alert('当前是第一页');
                    for (var i = 0; i < curNum; i++) {
                        $(pageMain).find("li").eq(i).show()
                    }
                    return false;
                } else {
                    pageNav.find("a").removeClass(tabPage.activeClass);
                    iNum--;
                    pageNav.find("a").eq(iNum).addClass(tabPage.activeClass);
                }
                for (var i = iNum * curNum; i < (iNum + 1) * curNum; i++) {
                    $(pageMain).find("li").eq(i).show()
                }
            })
        }
    })
</script>
</div>
<?php
elseif($do=="z"):?>
<div id="z">
<p style="line-height:40px;"><a target="_blank" href="//cms.usualtool.com/chajian.php">注意：网络波动可能影响通讯，请自行做好备份，必要时请手动升级。更多插件请点击这里</a></p>
<?php
function readplugin($path = '.'){
    include('../sql_db.php');
    $current_dir = opendir($path);
    while(($file = readdir($current_dir)) !== false) {
        $sub_dir = "".$path."/".$file."";
        if($file == '.' || $file == '..') {
        continue;
        }elseif(is_dir($sub_dir)){
        readplugin($sub_dir);
        }elseif(UsualToolCMS::contain("usualtoolcms.config",$file)){
            $zhooks=file_get_contents("".$path."/".$file."");
            $plugintype=UsualToolCMS::str_substr("<plugintype>","</plugintype>",$zhooks);
            if($plugintype==2):
                $zid=UsualToolCMS::str_substr("<id>","</id>",$zhooks);
                $zprice=UsualToolCMS::str_substr("<price>","</price>",$zhooks);
                $ztitle=UsualToolCMS::str_substr("<pluginname>","</pluginname>",$zhooks);
                $zauther=UsualToolCMS::str_substr("<auther>","</auther>",$zhooks);
                $zdescription=UsualToolCMS::str_substr("<description>","</description>",$zhooks);
                echo"<tr><td>".$ztitle."</td>";
                echo"<td align=center>".$zid."</td>";
                echo"<td>".$zdescription."</td>";
                $querys="SELECT id FROM `cms_plugins` WHERE id='$zid'";
                $datas=mysqli_query($mysqli,$querys);
                if(mysqli_num_rows($datas)==1):
                    echo"<td align=center style='color:red;'>已安装</td></tr>";
                else:
                    echo"<td align=center><a href='?m=plugin&u=a_apix.php&sign=plugin-".$zid."&t=setup&zipurl=usualtoolcms&ptype=2'>立即安装</a></td></tr>";
                endif;
                echo"</tr>";
            endif;
        }
    }
}
?>
<table width=100% align=center class="tablebasic" cellpadding="8">
<tr>
<th width="20%" align="left">插件名称</th>
<th width="10%">标志</th>
<th align=left>描述</th>
<th width="15%">操作</th>
</tr>
<?php
readplugin('../plugins/');
echo"</table>";
?>
</div>
<?php
elseif($do=="o"):?>
<p style="line-height:40px;"><a target="_blank" href="//cms.usualtool.com/chajian.php">注意：网络波动可能影响通讯，请自行做好备份，必要时请手动升级。更多插件请点击这里</a></p>
<div id="o">
<?php 
$hooks=UsualToolCMS::auth($authcode,$authapiurl,"pluginorder");
preg_match_all( "/\<hook\>(.*?)\<\/hook\>/s",$hooks,$hookblocks); 
?>
<table width=100% align=center class="tablebasic" cellpadding="8">
<tr>
<th width="20%" align="left">唯一标识</th>
<th width="20%">接收时间</th>
<th align="left">插件名称</th>
<th width="15%">操作</th>
</tr>
<?php
foreach($hookblocks[1] as $hook):
    preg_match_all( "/\<hookid\>(.*?)\<\/hookid\>/",$hook,$hookid);  
    preg_match_all( "/\<orderid\>(.*?)\<\/orderid\>/",$hook,$orderid);  
    preg_match_all( "/\<id\>(.*?)\<\/id\>/",$hook,$id);  
    preg_match_all( "/\<pluginname\>(.*?)\<\/pluginname\>/",$hook,$pluginname);
    preg_match_all( "/\<ordertime\>(.*?)\<\/ordertime\>/",$hook,$ordertime);
    echo"<tr><td>".$orderid[1][0]."</td>";
    echo"<td align=center>".$ordertime[1][0]."</td>";
    echo"<td><a href='//cms.usualtool.com/chajian_read.php?id=".$hookid[1][0]."' target='_blank'>".$pluginname[1][0]."</a></td>";
    if(UsualToolCMS::searchdir("../plugins/".$id[1][0]."")==1):
        echo"<td align=center style='color:red;'>已安装</td></tr>";
    else:
        echo"<td align=center><a href='?m=plugin&u=a_apix.php&sign=pluginorder-".$id[1][0]."&t=setup&zipurl=usualtoolcms'>云安装</a></td></tr>";
    endif;
endforeach;
?>
</table>
</div>
<?php endif;?>
</div>
</div>