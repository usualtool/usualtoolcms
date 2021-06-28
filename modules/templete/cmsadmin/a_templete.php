<?php
$do=UsualToolCMS::sqlcheck($_GET["do"]);
$planname=UsualToolCMSDB::queryData("cms_nav_plan","","indexplan=1","","1","0")["querydata"][0]["name"];
$oid=UsualToolCMSDB::queryData("cms_templete","","isopen=1","","","0")["querydata"][0]["id"];
?>
<h2>已提取模板</h2>
<div class="page">
<table width=100% align=center>
<tr><td>
<?php
if(preg_match("/($Ospat)/i",$Uagent)) {
    $k=4;
}else{
    $k=6;
}
$list=UsualToolCMSDB::queryData("cms_templete","","","","","0")["querydata"];
foreach($list as $rs){
    echo"<form action='?m=templete&u=a_templetex.php&t=open&paths=".$rs['paths']."&id=".$rs['id']."&oid=".$oid."' method='post' id='form".$i."' name='form".$i."'><dl class=child1>";
    echo"<dt>".$rs['title']."</dt>";
    echo"<dd style='color:#999999;line-height:35px;'>".$rs['version']."</dd>";
    echo"<dd style='color:#999999;line-height:35px;'>".$rs['paths']."</dd>";
    if($rs['isopen']==0){
        echo"<dd style='color:#999999;line-height:30px;'>导航方案:<select style='width:80px;height:25px;padding:0 0;' name='plan'>";
        $listx=UsualToolCMSDB::queryData("cms_nav_plan","","","","","0")["querydata"];
        foreach($listx as $planrow):
            echo"<option value='".$planrow["id"]."'>".$planrow["name"]."</option>";
        endforeach;
        echo"</select></dd>";
    }else{
        echo"<dd style='color:#999999;line-height:30px;'>导航方案:".$planname."</dd>";
    }
    echo"<dd style='line-height:35px;'><a href='?m=templete&u=a_templetex.php&t=mon&id=".$rs['id']."'>编辑</a>";
    if($rs['isopen']==0){
        echo" | <input type='submit' value='启用' class='btn' style='padding:0 10px;'>";
        echo" | <a href='?m=templete&u=a_templetex.php&t=del&paths=".$rs['paths']."&id=".$rs['id']."'>删除</a></dd>";
    }else{
        echo"&nbsp;&nbsp;<font style='color:red;'>已启用</font>";
    }
    echo"</dl></form>";
    if ($rs["xu"]==$k){
        echo"</td></tr><table width=100% align=center><tr><td>";
    }
}
?>
</td></tr></table>
    <script type="text/javascript">
     $(function(){ $(".idTabs").idTabs(); }); 
    </script>
    <div class="idTabs">
      <ul class="tab">
	  	<li><a href="?m=templete&u=a_templete.php&do=g" <?php if($do=="g"||(empty($do)))echo"class=selected";?>>官方模板</a></li>
		<li><a href="?m=templete&u=a_templete.php&do=o" style="background-color:rgb(224, 224, 224);color:white;">✔接收模板</a></li>
		</ul>
<?php if(empty($do)||$do=="g"):?>
<p style="line-height:40px;"><a target="_blank" href="//cms.usualtool.com/pifu.php">注意：网络波动可能影响通讯，请自行做好备份，必要时请手动升级。更多模板请点击这里。</a></p>
<div id="containet">
    <ul id="pageMain">
<?php 
$temps=UsualToolCMS::auth($authcode,$authapiurl,"tempapi");
preg_match_all( "/\<temp\>(.*?)\<\/temp\>/s",$temps,$tempblocks); 
foreach($tempblocks[1] as $temp):
    preg_match_all( "/\<id\>(.*?)\<\/id\>/",$temp,$id);  
    preg_match_all( "/\<auther\>(.*?)\<\/auther\>/",$temp,$auther);
    preg_match_all( "/\<tpname\>(.*?)\<\/tpname\>/",$temp,$tempname);
    preg_match_all( "/\<tppic\>(.*?)\<\/tppic\>/",$temp,$temppic);
    preg_match_all( "/\<tpurl\>(.*?)\<\/tpurl\>/",$temp,$tempurl);
    preg_match_all( "/\<lang\>(.*?)\<\/lang\>/",$temp,$lang);
    preg_match_all( "/\<isfree\>(.*?)\<\/isfree\>/",$temp,$isfree);
    echo"<li class='templi'>";
    echo"<img width=150 height=200 src='//cms.usualtool.com/".$temppic[1][0]."'><p align=left style='padding-left:25px;'>".$tempname[1][0]."<br>".$isfree[1][0]."<br>适用语种:".$lang[1][0]."<br>";
    $paths="templete/".$tempurl[1][0]."";
    $tq="SELECT id FROM `cms_templete` WHERE paths='$paths'";
    $tqrow=mysqli_query($mysqli,$tq);
    if(mysqli_num_rows($tqrow)==1):
    echo"<font color=red>已提取</font>";
    else:
    echo"<a href='?m=templete&u=a_templetet.php&sign=templete_".$tempurl[1][0]."&c=".$tempname[1][0]."&z=".$auther[1][0]."&zipurl=usualtoolcms' style='color:green;text-decoration:underline;'>应用模板</a>";
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
            curNum: <?php if(preg_match("/($Ospat)/i", $Uagent)):echo"6";else:echo"5";endif;?>,
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
<?php
elseif($do=="o"):
    $temps=UsualToolCMS::auth($authcode,$authapiurl,"temporder");
    preg_match_all( "/\<temp\>(.*?)\<\/temp\>/s",$temps,$tempblocks); 
    ?>
    <p style="line-height:40px;"><a target="_blank" href="//cms.usualtool.com/pifu.php">注意：网络波动可能影响通讯，请自行做好备份，必要时请手动升级。更多模板请点击这里。</a></p>
    <table width=100% align=center class="tablebasic" cellpadding="8">
    <tr>
    <th width="20%" align="left">唯一标识</th>
    <th width="20%">接收时间</th>
    <th align="left">模板名称</th>
    <th width="15%">操作</th>
    </tr>
    <?php
    foreach($tempblocks[1] as $temp):
        preg_match_all( "/\<tempid\>(.*?)\<\/tempid\>/",$temp,$tempid);  
        preg_match_all( "/\<orderid\>(.*?)\<\/orderid\>/",$temp,$orderid);  
        preg_match_all( "/\<tpurl\>(.*?)\<\/tpurl\>/",$temp,$tpurl);  
        preg_match_all( "/\<title\>(.*?)\<\/title\>/",$temp,$title);
        preg_match_all( "/\<ordertime\>(.*?)\<\/ordertime\>/",$temp,$ordertime);
        echo"<tr><td>".$orderid[1][0]."</td>";
        echo"<td align=center>".$ordertime[1][0]."</td>";
        echo"<td><a href='//cms.usualtool.com/pifu_read.php?id=".$tempid[1][0]."' target='_blank'>".$title[1][0]."</a></td>";
        echo"<td align=center>";
        $paths="templete/".$tpurl[1][0]."";
        $tq="SELECT id FROM cms_templete WHERE paths='$paths'";
        $tqrow=mysqli_query($mysqli,$tq);
        if(mysqli_num_rows($tqrow)==1):
            echo"<font color=red>已提取</font>";
        else:
            echo"<a href='?m=templete&u=a_templetet.php?sign=temporder_".$tpurl[1][0]."&c=".$title[1][0]."&z=订购类&zipurl=usualtoolcms' style='color:green;text-decoration:underline;'>应用模板</a>";
        endif;
        echo"</td>";
    endforeach;
    ?>
    </table>
<?php endif;?>
</div>
</div>