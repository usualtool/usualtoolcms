<?php
$l=$_GET["l"];
$do=$_GET["do"];
if($do=="update"){
    $id=UsualToolCMS::sqlcheck($_POST["id"]);
    $webname=UsualToolCMS::sqlcheck($_POST["webname"]);
    $weburl=UsualToolCMS::sqlcheck($_POST["weburl"]);
    $webkeyword=UsualToolCMS::sqlcheck($_POST["webkeyword"]);
    $webdescribe=UsualToolCMS::sqlcheck($_POST["webdescribe"]);
    $weblogo=UsualToolCMS::sqlcheck($_POST["weblogo"]);
    $webisclose=UsualToolCMS::sqlcheck($_POST["webisclose"]);
    $develop=UsualToolCMS::sqlcheck($_POST["develop"]);
    $cmscolor=UsualToolCMS::sqlcheck($_POST["cmscolor"]);
    $webicp=UsualToolCMS::sqlcheck($_POST["webicp"]);
    $webple=UsualToolCMS::sqlcheck($_POST["webple"]);
    $address=UsualToolCMS::sqlcheck($_POST["address"]);
    $webtel=UsualToolCMS::sqlcheck($_POST["webtel"]);
    $webfax=UsualToolCMS::sqlcheck($_POST["webfax"]);
    $webqq=UsualToolCMS::sqlcheck($_POST["webqq"]);
    $salts=UsualToolCMS::sqlcheck($_POST["salts"]);
    $usercookname=UsualToolCMS::sqlcheck($_POST["usercookname"]);
    $webemail=UsualToolCMS::sqlcheck($_POST["webemail"]);
    $webother=UsualToolCMS::sqlcheck($_POST["webother"]);
    $indexmodule=UsualToolCMS::sqlcheck($_POST["indexmodule"]);
    $indexunit=UsualToolCMS::sqlcheck($_POST["indexunit"]);
    //////
    $mailsmtp=UsualToolCMS::sqlcheck($_POST["mailsmtp"]);
    $mailport=UsualToolCMS::sqlcheck($_POST["mailport"]);
    $mailaccount=UsualToolCMS::sqlcheck($_POST["mailaccount"]);
    $mailpassword=UsualToolCMS::sqlcheck($_POST["mailpassword"]);
    //////
    $indexoss=UsualToolCMS::sqlcheck($_POST["indexoss"]);
    $indexeditor=UsualToolCMS::sqlcheck($_POST["indexeditor"]);
    //////
    $siteurl=$_POST["siteurl"];
    $siteurlx=$_POST["siteurlx"];
    $water=UsualToolCMS::sqlcheck($_POST["water"]);
    $water_type=UsualToolCMS::sqlcheck($_POST["water_type"]);
    $water_textcolor=UsualToolCMS::sqlcheck($_POST["water_textcolor"]);
    $water_textsize=UsualToolCMS::sqlcheck($_POST["water_textsize"]);
    $water_text=UsualToolCMS::sqlcheck($_POST["water_text"]);
    $water_png=UsualToolCMS::sqlcheck(str_replace($siteurlx,"",str_replace($siteurl,"",$_POST["water_png"])));
    $water_place=UsualToolCMS::sqlcheck($_POST["water_place"]);
    //////
    if($l=="main"){
        UsualToolCMSDB::updateData("cms_setup",array(
        "webisclose"=>$webisclose,
        "develop"=>$develop,
        "cmscolor"=>$cmscolor,
        "usercookname"=>$usercookname,
        "salts"=>$salts,
        "webname"=>$webname,
        "weburl"=>$weburl,
        "webkeyword"=>$webkeyword,
        "webdescribe"=>$webdescribe,
        "weblogo"=>$weblogo,
        "webicp"=>$webicp,
        "webple"=>$webple,
        "address"=>$address,
        "webtel"=>$webtel,
        "webfax"=>$webfax,
        "webqq"=>$webqq,
        "webemail"=>$webemail,
        "indexunit"=>$indexunit,
        "indexmodule"=>$indexmodule),"id='$id'");
	}
    if($l=="oss"){
        UsualToolCMSDB::updateData("cms_setup",array("indexoss"=>$indexoss),"id='$id'");
	}
    if($l=="editor"){
        UsualToolCMSDB::updateData("cms_setup",array("indexeditor"=>$indexeditor),"id='$id'");
	}
    if($l=="mail"){
        UsualToolCMSDB::updateData("cms_setup",array(
            "mailsmtp"=>$mailsmtp,
            "mailport"=>$mailport,
            "mailaccount"=>$mailaccount,
            "mailpassword"=>$mailpassword),"id='$id'");
	}
    if($l=="water"){
        UsualToolCMSDB::updateData("cms_water",array(
            "water"=>$water,
            "water_type"=>$water_type,
            "water_place"=>$water_place,
            "water_textcolor"=>$water_textcolor,
            "water_textsize"=>$water_textsize,
            "water_text"=>$water_text,
            "water_png"=>$water_png),"id='$id'");
	}
    if($l=="search"){
        $ids=implode("-UT-",$_POST["sid"]);
        $dbs=implode("-UT-",$_POST["sdb"]);
        $fields=implode("-UT-",$_POST["sfield"]);
        $wheres=implode("-UT-",$_POST["swhere"]);
        $pages=implode("-UT-",$_POST["spage"]);
        $idx=explode("-UT-",$ids);
        $dbx=explode("-UT-",$dbs);
        $fieldx=explode("-UT-",$fields);
        $wherex=explode("-UT-",$wheres);
        $pagex=explode("-UT-",$pages);
		for($s=0;$s<=count($dbx);$s++){
            if($idx[$s]=="x"){
                UsualToolCMSDB::insertData("cms_search_set",array(
                    "dbs"=>$dbx[$s],
                    "fields"=>$fieldx[$s],
                    "wheres"=>$wherex[$s],
                    "pages"=>$pagex[$s]));
            }else{
                UsualToolCMSDB::updateData("cms_search_set",array(
                    "dbs"=>$dbx[$s],
                    "fields"=>$fieldx[$s],
                    "wheres"=>$wherex[$s],
                    "pages"=>$pagex[$s]),"id='".$idx[$s]."'");
            }
            $mysqli->multi_query($sql);
			}
	}
    if($l=="redis" || $l=="sockets"){
        $info = file_get_contents("../sql_db.php"); 
        foreach($_POST as $k=>$v){ 
            if(is_numeric($v)):
            $info = preg_replace("/define\('{$k}',([0-9]*)\)/","define('{$k}',{$v})",$info); 
            else:
            $info = preg_replace("/define\('{$k}','(.*)'\)/","define('{$k}','{$v}')",$info); 
            endif;
        } 
        file_put_contents("../sql_db.php",$info); 
    }
    echo "<script>window.location.href='?m=system&u=a_system.php&l=$l'</script>";
}
?>
    <script type="text/javascript">
     $(function(){ $(".idTabs").idTabs(); }); 
    </script>
    <div class="idTabs">
      <ul class="tab">
        <li><a href="#main" <?php if($l=="main"||empty($l)):echo"class=selected";endif;?>>◇ 基础设置 ◇</a></li>
        <li><a href="#water" <?php if($l=="water"):echo"class=selected";endif;?>>◇ 水印设置 ◇</a></li>
		<li><a href="#mail" <?php if($l=="mail"):echo"class=selected";endif;?>>◇ 邮件服务 ◇</a></li>
        <li><a href="#search" <?php if($l=="search"):echo"class=selected";endif;?>>◇ 搜索设置 ◇</a></li>
        <li><a href="#oss" <?php if($l=="oss"):echo"class=selected";endif;?>>◇ 对象存储 ◇</a></li>
		<li><a href="#editor" <?php if($l=="editor"):echo"class=selected";endif;?>>◇ 富文本编辑器 ◇</a></li>
        <li><a href="#redis" <?php if($l=="redis"):echo"class=selected";endif;?>>◇ Redis设置 ◇</a></li>
        <li><a href="#sockets" <?php if($l=="sockets"):echo"class=selected";endif;?>>◇ Sockets设置 ◇</a></li>
      </ul>
      <div class="items">
        <div id="main">
         <form action="?m=system&u=a_system.php&do=update&l=main" method="post" id=form1 name=form1 onsubmit="return check();">
	     <input type="hidden" name="id" value="<?php echo$setup["id"];?>" />
         <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic"> 
          <tr>
          <td align="right" width=20%>是否关闭网站</td>
          <td>
           <label for="site_closed_0">
            <input type="radio" name="webisclose" value="0" <?php if($setup["webisclose"]==0){echo"checked=true";}?>>
            否</label>
           <label for="site_closed_1">
            <input type="radio" name="webisclose" value="1" <?php if($setup["webisclose"]==1){echo"checked=true";}?>>
            是</label>
           </td>
         </tr>
		 <tr>
          <td align="right" width=20%>开发模式</td>
          <td>
            <label for="site_closed_0">
            <input type="radio" name="develop" value="0" <?php if($setup["develop"]==0){echo"checked=true";}?>>
            否</label>
           <label for="site_closed_1">
            <input type="radio" name="develop" value="1" <?php if($setup["develop"]==1){echo"checked=true";}?>>
            是</label>
            </td>
         </tr>
		 <tr>
           <td align="right" width=20%>站点首页</td>
           <td>
            <input type="text" name="indexmodule" value="<?php echo$setup["indexmodule"];?>" size=80 class="inpMain" />
			<br>默认为index，亦可为其他模块的页面，如articles，请不要带文件名后缀。
            </td>
          </tr>
		  <tr>
           <td align="right" width=20%>后端色彩</td>
           <td>
            <label for="site_closed_0">
            <input type="radio" name="cmscolor" value="1" <?php if($setup["cmscolor"]==1){echo"checked=true";}?>>
            蓝色</label>
           <label for="site_closed_1">
            <input type="radio" name="cmscolor" value="2" <?php if($setup["cmscolor"]==2){echo"checked=true";}?>>
            黑色</label>
           <label for="site_closed_1">
            <input type="radio" name="cmscolor" value="3" <?php if($setup["cmscolor"]==3){echo"checked=true";}?>>
            橘色</label>
           <label for="site_closed_1">
            <input type="radio" name="cmscolor" value="4" <?php if($setup["cmscolor"]==4){echo"checked=true";}?>>
            绿色</label>
           <label for="site_closed_1">
            <input type="radio" name="cmscolor" value="5" <?php if($setup["cmscolor"]==5){echo"checked=true";}?>>
            土色</label>
            </td>
         </tr>
		 <tr>
           <td align="right" width=20%>用户COOKIES/SESSION前缀</td>
           <td>
            <input type="text" name="usercookname" value="<?php echo$setup["usercookname"];?>" size=80 class="inpMain" />
           </td>
          </tr>
		  <tr>
           <td align="right" width=20%>初始密码盐值</td>
           <td>
            <input type="text" name="salts" value="<?php echo$setup["salts"];?>" size=80 class="inpMain" />
           </td>
          </tr>
		  <tr>
           <td align="right" width=20%>余额账户默认货币</td>
           <td>
            <input type="text" name="indexunit" value="<?php echo$setup["indexunit"];?>" size=20 class="inpMain" /> 注意修改，若系统运行中期修改将导致用户余额账户属性的更改，造成损失。
            </td>
          </tr>
          <tr>
          <td align="right" width=20%>网站名称</td>
          <td>
            <input type="text" name="webname" value="<?php echo$setup["webname"];?>" size="40" class="inpMain" /> 若填写web则按照语言包解析网站名，否则按实际填写显示。<a href="?m=system&u=a_system_lang.php" style="color:red;">编辑语言包</a>
          </td>
         </tr>
         <tr>
          <td align="right">网站地址</td>
          <td>
            <input type="text" name="weburl" value="<?php echo$setup["weburl"];?>" size="80" class="inpMain" />
          </td>
         </tr>
         <tr>
          <td align="right">网站关键字</td>
          <td>
          <input type="text" name="webkeyword" value="<?php echo$setup["webkeyword"];?>" size="80" class="inpMain" />
          </td>
         </tr>
         <tr>
          <td align="right">网站描述</td>
          <td>
          <input type="text" name="webdescribe" value="<?php echo$setup["webdescribe"];?>" size="80" class="inpMain" />
          </td>
         </tr>
         <tr>
          <td align="right">网站标志</td>
          <td>
            <span id="weblogohtml"><img src="<?php echo$setup["weblogo"];?>" height=80 width=80></span>
            <input type="hidden" name="weblogo" id="weblogo" value="<?php echo$setup["weblogo"];?>">
            <input type="file" class="btn" name=file style="width:40%;">
            <input type="button" value="上传" class="btn" onclick="doupload('1','weblogo')" />
            </td>                              
          </td>
         </tr>
         <tr>
          <td align="right">工信部备案号</td>
          <td>
           <input type="text" name="webicp" value="<?php echo$setup["webicp"];?>" size="80" class="inpMain" />
          </td>
         </tr>
         <tr>
          <td align="right">公安部备案号</td>
          <td>
           <input type="text" name="webple" value="<?php echo$setup["webple"];?>" size="80" class="inpMain" />
          </td>
         </tr>
		 <tr>
          <td align="right">联系地址</td>
          <td>
         <input type="text" name="address" value="<?php echo$setup["address"];?>" size="80" class="inpMain" />
          </td>
         </tr>
         <tr>
          <td align="right">电子邮件</td>
          <td>
           <input type="text" name="webemail" value="<?php echo$setup["webemail"];?>" size="80" class="inpMain" />
          </td>
         </tr>
         <tr>
          <td align="right">客服电话</td>
          <td>
           <input type="text" name="webtel" value="<?php echo$setup["webtel"];?>" size="80" class="inpMain" />
          </td>
         </tr>
         <tr>
          <td align="right">传真</td>
          <td>
           <input type="text" name="webfax" value="<?php echo$setup["webfax"];?>" size="80" class="inpMain" />
          </td>
         </tr>
         <tr>
          <td align="right">客服QQ号码</td>
          <td>
           <input type="text" name="webqq" value="<?php echo$setup["webqq"];?>" size="80" class="inpMain" />
           </td>
         </tr>
         <tr>
          <td width=20%></td>
          <td>
           <input name="submit" class="btn" type="submit" value="更新设置" />
          </td>
         </tr>
        </table>
		</form>
        </div>
      <div id="oss">
       <form action="?m=system&u=a_system.php&do=update&l=oss" method="post" id=form5 name=form5 onsubmit="return check();">
	   <input type="hidden" name="id" value="<?php echo$setup["id"];?>" />
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
         <tr>
          <td align="right" width=20%>对象存储设置<br>请通过插件安装</td>
          <td>
            <label for="site_closed_0">
            <input type="radio" name="indexoss" value="utcms" <?php if($setup["indexoss"]=="utcms"){echo"checked=true";}?>>
            本地</label>
			<?php if(is_dir("../plugins/alioss")):?>
           <label for="site_closed_1">
            <input type="radio" name="indexoss" value="alioss" <?php if($setup["indexoss"]=="alioss"){echo"checked=true";}?>>
            阿里云OSS</label>
			<?php endif;?>
			<?php if(is_dir("../plugins/qiniuoss")):?>
           <label for="site_closed_1">
            <input type="radio" name="indexoss" value="qiniuoss" <?php if($setup["indexoss"]=="qiniuoss"){echo"checked=true";}?>>
            七牛OSS</label>
			<?php endif;?>
			</td>
         </tr>
         <tr>
          <td width=20%></td>
          <td>
           <input name="submit" class="btn" type="submit" value="更新设置" />
          </td>
         </tr>
        </table>
		</form>
        </div>
      <div id="editor">
       <form action="?m=system&u=a_system.php&do=update&l=editor" method="post" id=form2 name=form2 onsubmit="return check();">
	   <input type="hidden" name="id" value="<?php echo$setup["id"];?>" />
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
         <tr>
          <td align="right" width=20%>富文本编辑器设置<br>请通过插件安装</td>
          <td>
			<?php if(is_dir("../plugins/ueditor")):?>
           <label for="site_closed_1">
            <input type="radio" name="indexeditor" value="ueditor" <?php if($setup["indexeditor"]=="ueditor"){echo"checked=true";}?>>
            百度编辑器（UEditor）</label>
			<?php endif;?>
			<?php if(is_dir("../plugins/ckeditor")):?>
           <label for="site_closed_1">
            <input type="radio" name="indexeditor" value="ckeditor" <?php if($setup["indexeditor"]=="ckeditor"){echo"checked=true";}?>>
            CK编辑器（CKEditor）</label>
			<?php endif;?>
			<?php if(is_dir("../plugins/kindeditor")):?>
           <label for="site_closed_1">
            <input type="radio" name="indexeditor" value="kindeditor" <?php if($setup["indexeditor"]=="kindeditor"){echo"checked=true";}?>>
            kind编辑器（kindeditor）</label>
			<?php endif;?>
			</td>
         </tr>
         <tr>
         <td width=20%></td>
          <td>
           <input name="submit" class="btn" type="submit" value="更新设置" />
          </td>
         </tr>
        </table>
		</form>
        </div>
       <div id="mail">
	   <form action="?m=system&u=a_system.php&do=update&l=mail" method="post" id=form3 name=form3 onsubmit="return check();">
	   <input type="hidden" name="id" value="<?php echo$setup["id"];?>" />
       <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
        <tr>
          <td align="right" width=20%>邮件服务</td>
          <td>
           <label for="mail_service_1">
            <input type="radio" name="mail_service" id="mail_service_1" value="1" checked="true">
            SMTP发信服务</label> 
           </td>
         </tr>
         <tr>
          <td align="right">SMTP服务器</td>
          <td>
           <input type="text" name="mailsmtp" value="<?php echo$setup["mailsmtp"];?>" size="80" class="inpMain" />
           </td>
         </tr>
         <tr>
          <td align="right">服务器端口</td>
          <td>
           <input type="text" name="mailport" value="<?php echo$setup["mailport"];?>" size="80" class="inpMain" />
          </td>
         </tr>
         <tr>
          <td align="right">发件邮箱</td>
          <td>
           <input type="text" name="mailaccount" value="<?php echo$setup["mailaccount"];?>" size="80" class="inpMain" />
          </td>
         </tr>
     <tr>
          <td align="right">发件邮箱密码</td>
          <td>
           <input type="text" name="mailpassword" value="<?php echo$setup["mailpassword"];?>" size="80" class="inpMain" />
          </td>
         </tr>
         <tr>
          <td width=20%></td>
          <td>
           <input name="submit" class="btn" type="submit" value="更新设置" />
          </td>
         </tr>
        </table>
        </form>
        </div>
<?php
$wresult=UsualToolCMSDB::queryData("cms_water","","","","1","0")["querydata"];
foreach($wresult as $wrow):
?>
        <div id="water">
       <form action="?m=system&u=a_system.php&do=update&l=water" method="post" id="form4" name="form4">
	   <input type="hidden" name="siteurl" value="<?php echo$weburl;?>images/./">
	   <input type="hidden" name="siteurlx" value="<?php echo$weburl;?>/images/./">
	   <input type="hidden" name="id" value="<?php echo$wrow["id"];?>" />
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
         <tr>
          <td align="right" width=20%>是否开启水印功能</td>
          <td>
            <label for="site_closed_0">
            <input type="radio" name="water" value="0" <?php if($wrow["water"]==0){echo"checked=true";}?>>
            否</label>
            <label for="site_closed_1">
            <input type="radio" name="water" value="1" <?php if($wrow["water"]==1){echo"checked=true";}?>>
            是</label>
          </td>
         </tr>
         <tr>
          <td align="right" width=20%>水印类型</td>
          <td>
            <label for="site_closed_0">
            <input type="radio" name="water_type" value="text" <?php if($wrow["water_type"]=="text"){echo"checked=true";}?>>
            文字</label>
            <label for="site_closed_1">
            <input type="radio" name="water_type" value="image" <?php if($wrow["water_type"]=="image"){echo"checked=true";}?>>
            图片</label>
            </td>
         </tr>
         <tr>
          <td align="right" width=20%>水印位置</td>
          <td>
            <label><input type="radio" name="water_place" value="0" <?php if($wrow["water_place"]=="0"){echo"checked=true";}?>>随机</label> 
            <label><input type="radio" name="water_place" value="1" <?php if($wrow["water_place"]=="1"){echo"checked=true";}?>>上左</label> 
            <label><input type="radio" name="water_place" value="2" <?php if($wrow["water_place"]=="2"){echo"checked=true";}?>>上中</label> 
            <label><input type="radio" name="water_place" value="3" <?php if($wrow["water_place"]=="3"){echo"checked=true";}?>>上右</label> 
            <label><input type="radio" name="water_place" value="4" <?php if($wrow["water_place"]=="4"){echo"checked=true";}?>>中左</label> 
            <label><input type="radio" name="water_place" value="5" <?php if($wrow["water_place"]=="5"){echo"checked=true";}?>>中中</label> 
            <label><input type="radio" name="water_place" value="6" <?php if($wrow["water_place"]=="6"){echo"checked=true";}?>>中右</label> 
            <label><input type="radio" name="water_place" value="7" <?php if($wrow["water_place"]=="7"){echo"checked=true";}?>>下左</label> 
            <label><input type="radio" name="water_place" value="8" <?php if($wrow["water_place"]=="8"){echo"checked=true";}?>>下中</label> 
            <label><input type="radio" name="water_place" value="9" <?php if($wrow["water_place"]=="9"){echo"checked=true";}?>>下右</label> 
          </td>
         </tr>
		   <tr>
           <td align="right" width=20%>水印文字颜色</td>
           <td>
           <input type="text" name="water_textcolor" value="<?php echo$wrow["water_textcolor"];?>" size=80 class="inpMain" />
           </td>
          </tr>
		   <tr>
           <td align="right" width=20%>水印文字大小</td>
           <td>
           <input type="text" name="water_textsize" value="<?php echo$wrow["water_textsize"];?>" size=80 class="inpMain" />
           </td>
          </tr>
		   <tr>
           <td align="right" width=20%>水印文字</td>
           <td>
           <input type="text" name="water_text" value="<?php echo$wrow["water_text"];?>" size=80 class="inpMain" />
           </td>
          </tr>
		   <tr>
           <td align="right" width=20%>水印图片</td>
           <td>
            <span id="water_pnghtml"><img src="../assets/<?php echo$wrow["water_png"];?>" height=80 width=80></span>
            <input type="hidden" name="water_png" id="water_png" value="<?php echo$wrow["water_png"];?>">
            <input type="file" class="btn" name=file style="width:200px;">
            <input type="button" value="上传" class="btn" onclick="doupload('4','water_png')"/>
           </td>
          </tr>
         <tr>
<td width=20%></td>
          <td>
           <input name="submit" class="btn" type="submit" value="保存设置" />
          </td>
         </tr>
        </table>
		</form>
        </div>
<?php
endforeach;?>
      <div id="search">
       <form action="?m=system&u=a_system.php&do=update&l=search" method="post" id="form6" name="form6">
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
          <tr><td>
          全库搜索设置说明：<br>
          表名即加入搜索的表<br>
          字段即需要查询的字段（此处不写id,id为查询默认字段，字段以逗号分割）<br>
          条件即查询条件（固定条件样式：字段 like [keyword]）<br>
          页名即记录对应的详情页面名，不包含后缀（如index.php?ut=article&id=1则填写article）<br>
          </td></tr>
          <tr>
          <td style="line-height:30px;">
          <?php
          $sresult=UsualToolCMSDB::queryData("cms_search_set","","","","","0")["querydata"];
          foreach($sresult as $srow){
          ?>
		      <input type="hidden" name="sid[]" value="<?php echo$srow["id"];?>" />
              表名: <input type="text" name="sdb[]" class="inpMain" style="width:15%;" value="<?php echo$srow["dbs"];?>"> - 
              字段: <input type="text" name="sfield[]" class="inpMain" style="width:15%;" value="<?php echo$srow["fields"];?>"> - 
              条件: <input type="text" name="swhere[]" class="inpMain" style="width:15%;" value="<?php echo$srow["wheres"];?>"> - 
              页名: <input type="text" name="spage[]" class="inpMain" style="width:15%;" value="<?php echo$srow["pages"];?>"><br>
          <?php }?>
          <span id="thesearch"></span>
          </td>
         </tr>
         <tr><td><input type="button" value="增加" class="btnGray" onclick="addtablev('thesearch')" /></td></tr>
         <tr>
          <td>
           <input name="submit" class="btn" type="submit" value="保存设置" />
          </td>
         </tr>
        </table>
		</form>
        </div>
	<script type="text/javascript">
	var k=9999;	
    function addtablev(table){  
        var table;
        k++;
        document.getElementById(""+table+"").innerHTML+="<br><input type='hidden' name='sid[]' value='x'>表名: <input type='text' name='sdb[]' class='inpMain' style='width:15%;'> - 字段: <input type='text' name='sfield[]' class='inpMain' style='width:15%;'> - 条件: <input type='text' name='swhere[]' class='inpMain' style='width:15%;'> - 页名: <input type='text' name='spage[]' class='inpMain' style='width:15%;'>";
    }
	</script>
  </div>
       <div id="redis">
	   <form action="?m=system&u=a_system.php&do=update&l=redis" method="post" id="form-redis" name="form-redis">
       <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
       <?php
        if(is_writable("../sql_db.php")){
        if(class_exists('Redis')){
        ?>
         <tr>
          <td colspan=2>
           若有PHP缓存设置，设置将在1-60秒内生效。
          </td>
         </tr>
         <tr>
          <td align="right">Redis是否开启</td>
          <td>
            <?php if(defined('UTREDIS')):?>
            <input type='radio' name='UTREDIS' value='0' <?php if(UTREDIS=='0'):echo"checked";endif;?>>关闭 
            <input type='radio' name='UTREDIS' value='1' <?php if(UTREDIS=='1'):echo"checked";endif;?>>开启
            <?php else:?>
            <input type='radio' name='UTREDIS' value='0' checked>关闭 
            <input type='radio' name='UTREDIS' value='1'>开启
            <?php endif;?>
           </td>
         </tr>
         <tr>
          <td align="right">Redis IP</td>
          <td>
           <input type='text' name='UTREDIS_HOST' class="inpMain" value='<?php if(defined('UTREDIS_HOST')):echo UTREDIS_HOST;else:echo"127.0.0.1";endif;?>'>
          </td>
         </tr>
         <tr>
          <td align="right">Redis 端口</td>
          <td>
           <input type='text' name='UTREDIS_PORT' class="inpMain" value='<?php if(defined('UTREDIS_PORT')):echo UTREDIS_PORT;else:echo"6379";endif;?>'>
          </td>
         </tr>
     <tr>
          <td align="right">Redis 验证密码</td>
          <td>
           <input type='text' name='UTREDIS_PASS' class="inpMain" value='<?php if(defined('UTREDIS_PASS')):echo UTREDIS_PASS;else:echo"UT";endif;?>'>
           <br>无密码验证请保持填写 UT
          </td>
         </tr>
     <tr>
          <td align="right">Redis键过期时间</td>
          <td>
           <input type='text' name='UTREDIS_TIME' class="inpMain" value='<?php if(defined('UTREDIS_TIME')):echo UTREDIS_TIME;else:echo"3600";endif;?>'> 秒
          </td>
         </tr>
         <tr>
          <td width=20%></td>
          <td>
           <input name="submit" class="btn" type="submit" value="保存设置" />
          </td>
         </tr>
         <?php }else{?>
        <tr><td>服务器未配置Redis服务，无法使用该功能。</td></tr>
        <?php }
               }else{
                   ?>
        <tr><td>sql_db.php无写入权限，请直接编辑该文件更改相关值。</td></tr>
           <?php
       }?>
        </table>
        </form>
        </div>
       <div id="sockets">
	   <form action="?m=system&u=a_system.php&do=update&l=sockets" method="post" id="form-redis" name="form-redis">
       <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic">
       <?php
        if(is_writable("../sql_db.php")){
        if(extension_loaded('sockets')){
        ?>
         <tr>
          <td colspan=2>
           若有PHP缓存设置，设置将在1-60秒内生效。
          </td>
         </tr>
         <tr>
          <td align="right">Sockets是否开启</td>
          <td>
            <?php if(defined('UTSOCKETS')):?>
            <input type='radio' name='UTSOCKETS' value='0' <?php if(UTSOCKETS=='0'):echo"checked";endif;?>>关闭 
            <input type='radio' name='UTSOCKETS' value='1' <?php if(UTSOCKETS=='1'):echo"checked";endif;?>>开启
            <?php else:?>
            <input type='radio' name='UTSOCKETS' value='0' checked>关闭 
            <input type='radio' name='UTSOCKETS' value='1'>开启
            <?php endif;?>
           </td>
         </tr>
         <tr>
          <td align="right">Sockets 内网IP</td>
          <td>
           <input type='text' name='UTSOCKETS_HOST' class="inpMain" value='<?php if(defined('UTSOCKETS_HOST')):echo UTSOCKETS_HOST;else:echo"127.0.0.1";endif;?>'>
          </td>
         </tr>
         <tr>
          <td align="right">Sockets 公网IP</td>
          <td>
           <input type='text' name='UTSOCKETS_WSIP' class="inpMain" value='<?php if(defined('UTSOCKETS_WSIP')):echo UTSOCKETS_WSIP;else:echo"127.0.0.1";endif;?>'>
          </td>
         </tr>
         <tr>
          <td align="right">Sockets 端口</td>
          <td>
           <input type='text' name='UTSOCKETS_PORT' class="inpMain" value='<?php if(defined('UTSOCKETS_PORT')):echo UTSOCKETS_PORT;else:echo"8080";endif;?>'>
          </td>
         </tr>
         <tr>
          <td width=20%></td>
          <td>
           <input name="submit" class="btn" type="submit" value="保存设置" />
          </td>
         </tr>
         <?php }else{?>
        <tr><td>服务器未配置Socket服务，无法使用该功能。</td></tr>
        <?php }
               }else{
                   ?>
        <tr><td>sql_db.php无写入权限，请直接编辑该文件更改相关值。</td></tr>
           <?php
       }?>
        </table>
        </form>
        </div>
</div>