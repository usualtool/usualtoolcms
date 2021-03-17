<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
date_default_timezone_set('PRC');
session_start();
if(ini_get('magic_quotes_gpc')):
    function stripslashesRecursive(array $array){   
        foreach ($array as $k => $v):
            if(is_string($v)):$array[$k] = stripslashes($v);
            elseif(is_array($v)):$array[$k] = stripslashesRecursive($v);endif;
        endforeach;
        return $array;
    }
    $_GET=stripslashesRecursive($_GET);
    $_POST=stripslashesRecursive($_POST);
endif;
function get_config($file, $ini, $type="string"){ 
    if(!file_exists($file)) return false; 
    $str = file_get_contents($file); 
    if ($type=="int"){ 
        $config = preg_match("/".preg_quote($ini)."=(.*);/", $str, $res); 
        return $res[1]; 
    } 
    else{ 
        $config = preg_match("/".preg_quote($ini)."=\"(.*)\";/", $str, $res); 
        if($res[1]==null){ 
            $config = preg_match("/".preg_quote($ini)."='(.*)';/", $str, $res); 
        } 
        return $res[1]; 
    } 
} 
function update_config($file, $ini, $value,$type="string"){ 
    if(!file_exists($file)) return false; 
    $str = file_get_contents($file); 
    $str2=""; 
    if($type=="int"){ 
        $str2 = preg_replace("/".preg_quote($ini)."=(.*);/", $ini."=".$value.";",$str); 
    } 
    else{ 
        $str2 = preg_replace("/".preg_quote($ini)."=(.*);/",$ini."=\"".$value."\";",$str); 
    } 
    file_put_contents($file, $str2); 
}
$s=@file_get_contents("usualtoolcms.lock");
if($s=="lock"):header("location:../");exit();else:$k="only";endif;
$t=$_GET["t"];
$l=$_GET["l"];
$p=$_POST["p"];
$dbcontent=file_get_contents("../sql_db.php");
if($t=="db"&&$k=="only"){
    $dbfile=$_POST["dbfile"];
    $dbhost=$_POST["dbhost"];
    $dbname=$_POST["dbname"];
    $dbuser=$_POST["dbuser"];
    $dbpass=$_POST["dbpass"];
    update_config($dbfile, "dbhost", $dbhost);
    update_config($dbfile, "dbname", $dbname);
    update_config($dbfile, "dbuser", $dbuser);
    update_config($dbfile, "dbpass", $dbpass);
    $mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
    if($mysqli->connect_error){
        $_SESSION["setuporder"]="1";
        echo "<script>alert('数据库连接失败!');history.go(-1)</script>";
    }else{
        $_SESSION["setuporder"]="2";
        echo "<script>alert('数据库设置成功!');window.location.href='?l=sqlback'</script>";
    }
}
if($t=="sqlback"&&$k=="only"){
    $_SESSION["setuporder"]="3";
    include('../sql_db.php');
    $sqlcontent=$_POST['sqlcontent'];
    $res=$mysqli -> multi_query($sqlcontent);
    echo "<script>alert('数据库结构规划完成!');window.location.href='?l=setup'</script>";
}
if($t=="update"&&$k=="only"){
    $_SESSION["setuporder"]="4";
    include('../sql_db.php');
    $authcode=$_POST["authcode"];
    $webname=$_POST["webname"];
    $weblogo=$_POST["weblogo"];
    $weburl=$_POST["weburl"];
    $template=$_POST["template"];
    $webisclose=$_POST["webisclose"];
    $develop=$_POST["develop"];
    $usercookname=$_POST["usercookname"];
    $salts=$_POST["salts"];
    $indexunit=$_POST["indexunit"];
    $sqls="INSERT INTO `cms_setup` (authcode,webname,weblogo,weburl,template,webisclose,develop,usercookname,salts,indexunit,installtime) VALUES ('$authcode','$webname','$weblogo','$weburl','$template','$webisclose','$develop','$usercookname','$salts','$indexunit',now())";
    if ($mysqli->query($sqls) == TRUE) {
        echo "<script>alert('基础设置完成!');window.location.href='?l=admin'</script>";
    }else{
        echo "<script>alert('未设置成功!请重填!');window.location.href='?l=setup'</script>";
    }
}
if($t=="admin"&&$k=="only"){
    include('../conn.php');
    $_SESSION["setuporder"]="5";
    $username=$_POST["username"];
    $password=$_POST["password"];
    $passwords=sha1($salts.$password);
    if(!empty($username)&&!empty($password)):
        $sql="INSERT INTO cms_admin (roleid,username,password,salts,createtime) VALUES ('1','$username','$passwords','$salts',now())";
        if($mysqli->query($sql) == TRUE):
            file_put_contents("./usualtoolcms.lock","lock");
            echo "<script>alert('管理员已添加成功!');window.location.href='?l=welcome'</script>";
        endif;
    else:
    echo "<script>alert('账号或密码不能为空!');window.location.href='?l=admin'</script>";
    endif;
}
$liid="bg-success";
$liix="text-white";
if(empty($l)):$liid0=$liid;$liid0x=$liix;
elseif($l=="status"):$liid1=$liid;$liid1x=$liix;
elseif($l=="db"||$l=="sql"):$liid2=$liid;$liid2x=$liix;
elseif($l=="sqlback"):$liid3=$liid;$liid3x=$liix;
elseif($l=="setup"):$liid4=$liid;$liid4x=$liix;
elseif($l=="admin"):$liid5=$liid;$liid5x=$liix;
elseif($l=="welcome"):$liid6=$liid;$liid6x=$liix;
endif;
?>
<!DOCTYPE HTML>
<html>
<head>
<title>系统安装|UsualToolCMS|实用的内容管理框架</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="UsualToolCMS,cms" />
<meta name="description" content="Welcome UsualToolCMS." />
<link href="../assets/css/font-awesome.css" rel="stylesheet">
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
<script src="../assets/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../assets/js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <img src="../assets/logo.png" alt="">
        </div>
    </div>
</div>
<div class="clear"></div>
<div class="container">
    <div class="row">
       <div class="col-md-9 mb-2">
            <div class="border p-4">
<?php
//授权协议
if(empty($l)):
echo"<form name='form1' action='?l=status' method='post'>";
echo"<div class='form-group row'><div class='col-sm-12'><textarea style='width:100%;height:400px;line-height:30px;' readonly='readonly'>
为了使您正确并合法的使用成都康菲顿特网络科技有限公司(以下称本公司)旗下的UsualToolCMS内容管理系统(以下称本软件)，请您遵守中华人民共和国相关法律法规，在使用前务必仔细阅读清楚《免责授权使用协议》(以下称本协议)中的条款，且必须完全同意本协议所有条款才能使用本软件。
一、本协议适用于本软件所有版本(普称:UsualToolCMS,简称:UTCMS,软著登字第3138819号)，本公司对本协议拥有最终解释权。本软件唯一官方网站:cms.usualtool.com。
二、本协议许可的权利及义务 
1、本公司支持团体或个人使用本软件构建公益性、非盈利性的网站。
2、您可以通过本公司获得免费授权或者商业授权。 
3、您可以在本协议规定的约束和限制范围内修改本软件源代码或界面风格以适应您的网站要求。 
4、使用本软件构建网站，您拥有网站全部内容的所有权，并且您必须独立承担这些内容所产生的法律责任及法定义务，所涉及的一切争议纠纷和法律后果与本公司无关。 
5、获得商业授权之后，您可以将本软件应用于商业用途。若商业授权附带相关的技术支持内容，则可在技术支持期限内通过指定的方式获得指定范围内的技术支持服务。
6、所有授权用户享有反映和提出意见的权力，相关意见将被作为首要考虑，但没有一定被采纳的承诺或保证。
三、本协议规定的约束和限制 
1、未获商业授权之前，不得将本软件用于商业用途（包括但不限于企业网站、经营性网站、以营利为目的或实现盈利的网站），一经查证未授权而应用于商业用途的行为，本公司将保留追究的权利。
2、未经本公司许可，不得对本软件或与之关联的授权进行出租、出售、抵押或发放子许可证。
3、不管您的网站是否整体使用本软件，还是部份栏目使用本软件，在您使用了本软件的网站主页上必须保留相关版权信息链接。
4、未经本公司许可，禁止在本软件的整体或任何部分基础上以发展任何派生版本、修改版本或第三方版本用于重新分发。
5、不得使用本软件构建任何类型的非法网站，若经他人举报核实或政府部门通报，本公司有权单方面终止授权。
6、如果您未能遵守本协议的各项条款，您的授权将被终止，所被许可的权利将被收回，支付的相关费用不会退还，您还需要承担相应的法律责任。
四、有限担保和免责声明 
1、本软件及所附带的或可供安装的文件、服务是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。 
2、您出于自愿而使用本软件，您必须了解使用本软件的风险，独立承担使用本软件而造成的所有责任。
3、未购买相关技术服务之前，本公司不承诺对您提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生技术问题的相关责任。 
4、本软件附带的或可供安装的文件（含插件、模块、模板等），其版权可能不属于本公司，并且这些文件或许是未经过授权发布的，请务必参考版权方的使用要求合法的使用，本公司不承担与此相关的任何责任。
5、使用本软件附带的或可供安装的文件（插件、模块、模板等）可能会产生费用。当以上文件由第三方提供时，费用由第三方收取，若文件中包含有其他收费（如短信包等）亦非本公司收费，请参考收费方相关说明谨慎使用，本公司不提供任何形式的保证。
6、在适用法律允许的最大范围内，本公司在任何情况下不就因使用或不能使用本软件所发生的特殊的、意外的、非直接或间接的损失承担赔偿责任。即使已事先被告知该损害发生的可能性。
五、法律有效性及争议解决 
1、电子文本形式的《免责授权使用协议》如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始确认本协议条款并安装本软件，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止您的使用，责令停止损害，并保留追究相关责任的权力。
2、如果本协议的任一条款（或者条款的一部分）被法院或者相关政府部门裁定为无效、违法或者不具有法定强制力时，相关条款或者条款的一部分可被分离，其余部分则仍具有法律效力。
3、若与本公司之间发生任何纠纷或争议，首先应友好协商解决。若协商不成，任何一方均有权将纠纷或争议提交至本公司注册所在地具有管辖权的人民法院裁判。
六、其他
以上条款经本软件作者许可及确认，且本公司有权随时根据中华人民共和国有关法律、法规的变化、互联网的发展以及经营状况和经营策略的调整等修改本协议。若要继续使用本软件就有必要对最新的《免责授权使用协议》进行仔细阅读和确认。当发生有关争议时，以刊登在官方网站上的最新协议为准。";
echo"</textarea></div></div>";
echo"<div class='form-group row'><div class='col-sm-12'><input type='checkbox' id='regText'> 您需要同意<a target='_blank' href='//cms.usualtool.com/license.php'>《免责授权使用协议》</a></div></div>";
echo"<div class='form-group row'><div class='col-sm-12'><input type='hidden' name='p' value='status'><input type='submit' disabled id='regBtn' value='开始安装 UT!' class='btn btn-success'></div></div></form>";
//权限检测
elseif($p=="status"&&$p==$l):
function check($k){
    if(function_exists($k)):return "√符合";
    else:return "×不符合";endif;
}
$_SESSION["setuporder"]="1";
function getSystemInfo(){
    $sysos = $_SERVER["SERVER_SOFTWARE"];
    $sysversion = PHP_VERSION;
    $allowurl= ini_get("allow_url_fopen") ? "YES" : "NO";
    $max_upload = ini_get("file_uploads") ? ini_get("upload_max_filesize") : "Disabled";
    $max_ex_time= ini_get("max_execution_time")."s";
    date_default_timezone_set("Etc/GMT-8");
    $systemtime = date("Y-m-d H:i:s",time()); 
    $SystemInfo="".$sysos."|".$sysversion."|".$allowurl."|".$max_upload."|".$max_ex_time."|".$systemtime."";
    return $SystemInfo;
}
echo"<form name='form2' action='?l=db' method='post'>";
echo"<div class='form-group row'><div class='col-sm-12'>PHP环境检测</div></div>";
echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline col-md-nobr-3'>MYSQLI连接方式</label><div class='col-sm-9 col-md-nobr-7'>".check("mysqli_connect")."</div></div>";
echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline col-md-nobr-3'>获取远程文件</label><div class='col-sm-9 col-md-nobr-7'>".check("curl_init")."</div></div>";
echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline col-md-nobr-3'>打开本地文件</label><div class='col-sm-9 col-md-nobr-7'>".check("file_get_contents")."</div></div>";
echo"<div class='form-group row'><div class='col-sm-12'>请对站点目录开启读与写权限（具体参照安装文档）</div></div>";
echo"<div class='form-group row'><div class='col-sm-12'>伪静态路由环境配置</div></div>";
$SystemInfos=getSystemInfo();
$SystemInfo=explode("|",$SystemInfos);
echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'>架构平台</label><div class='col-sm-9'><font color=red>".$SystemInfo[0]."</font>，系统默认动态路由访问，若需要设置伪静态，请复制以下规则进行设置，<a target='_blank' href='../伪静态规则说明.txt'>设置教程</a><br>设置伪静态后，需要在后端安装『URL路由管理』插件来批量处理模板和导航中的地址。</div></div>";
echo"<div class='form-group row'><div class='col-sm-12'><textarea id='textarea' style='width:100%;height:120px;line-height:20px;' readonly='readonly'>";
if(strpos($SystemInfo[0],'IIS')!==false){?>
&lt;?xml version="1.0" encoding="UTF-8"?&gt;
<configuration>
    <system.webServer>
        <rewrite>
            <rules>
                      <rule name="UT规则 1">
                      <match url="^([a-zA-Z]+)\.html$" ignoreCase="false" />
                      <conditions logicalGrouping="MatchAll">
                      <add input="{QUERY_STRING}" pattern="^(.*)$" ignoreCase="false" />
                      </conditions>
                      <action type="Rewrite" url="index.php?ut={R:1}&amp;{C:1}" appendQueryString="false" />
                      </rule>
                      <rule name="UT规则 2">
                      <match url="^([a-zA-Z]+)-([0-9]+)\.html$" ignoreCase="false" />
                      <conditions logicalGrouping="MatchAll">
                      <add input="{QUERY_STRING}" pattern="^(.*)$" ignoreCase="false" />
                      </conditions>
                      <action type="Rewrite" url="index.php?ut={R:1}&amp;id={R:2}&amp;{C:1}" appendQueryString="false" />
                      </rule>
                      <rule name="UT规则 3">
                      <match url="^home/([a-zA-Z\-]+)\.html$" ignoreCase="false" />
                      <conditions logicalGrouping="MatchAll">
                      <add input="{QUERY_STRING}" pattern="^(.*)$" ignoreCase="false" />
                      </conditions>
                      <action type="Rewrite" url="home/index.php?ut={R:1}&amp;{C:1}" appendQueryString="false" />
                      </rule>
            </rules>
        </rewrite>
    </system.webServer>
</configuration>
<?php }elseif(strpos($SystemInfo[0],'nginx')!==false){?>
location / {
if (!-e $request_filename){
rewrite ^/([a-zA-Z]+)\.html$ /index.php?ut=$1 last;
rewrite ^/([a-zA-Z]+)-([0-9]+)\.html$ /index.php?ut=$1&id=$2 last;
rewrite ^/home/([a-zA-Z\-]+)\.html$ /home/index.php?ut=$1 last;
}
}
<?php }else{?>
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^([a-zA-Z]+)\.html$ index.php?ut=$1&%1
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^([a-zA-Z]+)-([0-9]+)\.html$ index.php?ut=$1&id=$2&%1
RewriteCond %{QUERY_STRING} ^(.*)$
RewriteRule ^home/([a-zA-Z\-]+)\.html$ home/index.php?ut=$1&%1
</IfModule>
<?php
}
echo"</textarea></div></div>";
echo"<div class='form-group row'><div class='col-sm-12'><input type='submit' class='btn btn-success' value='设置完毕,开始安装'></div></div>";
echo"</form>";
//保存数据库设置
elseif($l=="db"&&$_SESSION["setuporder"]=="1"):
    $dbfile="../sql_db.php";
    echo"<form name='form3' action='?t=db' method='post'>";
    echo"<input type='hidden' name='dbfile' value='$dbfile'>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'>服务器地址: </label><div class='col-sm-9'><input type='text' name='dbhost' value='".get_config($dbfile,'dbhost')."'  class='form-control'></div></div>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'>数据库名称: </label><div class='col-sm-9'><input type='text' name='dbname' value='".get_config($dbfile,'dbname')."'  class='form-control'></div></div>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'>数据库用户: </label><div class='col-sm-9'><input type='text' name='dbuser' value='".get_config($dbfile,'dbuser')."'  class='form-control'></div></div>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'>数据库密码: </label><div class='col-sm-9'><input type='text' name='dbpass' value='".get_config($dbfile,'dbpass')."'  class='form-control'></div></div>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'></label><div class='col-sm-9'><input type='submit' class='btn btn-success' value='保存数据库设置'></div></div>";
    echo"</form>";
//数据库布局
elseif($l=="sqlback"&&$_SESSION["setuporder"]=="2"):
    echo"<form name='form5' action='?t=sqlback' method='post'><ul>";
    echo"<div class='form-group row'><div class='col-sm-12'><textarea name='sqlcontent' id='textarea' style='width:100%;height:400px;line-height:30px;' readonly='readonly'>DROP TABLE IF EXISTS `cms_admin`;
    CREATE TABLE `cms_admin` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `roleid` int(11) NOT NULL DEFAULT '1',
      `username` varchar(50) NOT NULL,
      `password` varchar(100) NOT NULL,
      `salts` varchar(100) DEFAULT NULL,
      `icon` varchar(150) NOT NULL DEFAULT '../assets/images/male.png',
      `createtime` datetime NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `id` (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
    DROP TABLE IF EXISTS `cms_admin_log`;
    CREATE TABLE `cms_admin_log` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `adminusername` varchar(50) NOT NULL,
      `ip` varchar(50) NOT NULL,
      `logintime` datetime NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `id` (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
    DROP TABLE IF EXISTS `cms_admin_role`;
    CREATE TABLE `cms_admin_role` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `rolename` varchar(100) NOT NULL,
      `ranges` varchar(250) NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `id` (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
    DROP TABLE IF EXISTS `cms_mod`;
    CREATE TABLE IF NOT EXISTS `cms_mod` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `bid` int(11) DEFAULT '0',
      `modid` varchar(50) DEFAULT NULL,
      `modname` varchar(100) DEFAULT NULL,
      `modurl` varchar(200) DEFAULT NULL,
      `isopen` int(11) DEFAULT '0',
      `ordernum` int(11) DEFAULT '0',
      `look` int(11) DEFAULT '0',
      `othername` varchar(100) DEFAULT NULL,
      `befoitem` varchar(250) DEFAULT NULL,
      `backitem` varchar(250) DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `id` (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
    DROP TABLE IF EXISTS `cms_nav`;
    CREATE TABLE `cms_nav` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `place` varchar(10) NOT NULL,
      `linkname` varchar(20) NOT NULL,
      `linkurl` varchar(150) NOT NULL,
      `ordernum` int(11) NOT NULL DEFAULT '0',
      `target` varchar(20) DEFAULT '_self',
      `planid` int(11) NOT NULL DEFAULT '1',
      PRIMARY KEY (`id`),
      UNIQUE KEY `id` (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
    DROP TABLE IF EXISTS `cms_nav_plan`;
    CREATE TABLE `cms_nav_plan` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(50) NOT NULL,
      `indexplan` int(11) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`),
      UNIQUE KEY `id` (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
    DROP TABLE IF EXISTS `cms_plugins`;
    CREATE TABLE IF NOT EXISTS `cms_plugins` (
      `hid` int(11) NOT NULL AUTO_INCREMENT,
      `id` varchar(50) DEFAULT NULL,
      `type` varchar(50) DEFAULT NULL,
      `auther` varchar(50) DEFAULT NULL,
      `title` varchar(50) DEFAULT NULL,
      `pluginname` varchar(50) DEFAULT NULL,
      `ver` varchar(10) DEFAULT NULL,
      `description` text,
      PRIMARY KEY (`hid`),
      UNIQUE KEY `hid` (`hid`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
    DROP TABLE IF EXISTS `cms_search`;
    CREATE TABLE IF NOT EXISTS `cms_search` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `hit` int(11) DEFAULT '1',
      `keyword` varchar(100) DEFAULT NULL,
      `lang` varchar(20) DEFAULT 'zh',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
    DROP TABLE IF EXISTS `cms_search_set`;
    CREATE TABLE IF NOT EXISTS `cms_search_set` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `dbs` varchar(50) DEFAULT NULL,
      `fields` varchar(100) DEFAULT NULL,
      `wheres` varchar(200) DEFAULT NULL,
      `pages` varchar(50) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
    DROP TABLE IF EXISTS `cms_setup`;
    CREATE TABLE `cms_setup` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `authcode` varchar(100) DEFAULT NULL,
      `authapiurl` varchar(200) NOT NULL DEFAULT 'http://cms.usualtool.com/authurl.php',
      `copyright` varchar(10) DEFAULT '8.0',
      `country` varchar(20) DEFAULT 'zh-cn',
      `charset` varchar(10) DEFAULT 'utf-8',
      `cmscolor` int(11) DEFAULT '4',
      `template` varchar(30) NOT NULL DEFAULT 'templete/default',
      `webname` varchar(50) NOT NULL,
      `weburl` varchar(50) NOT NULL,
      `webkeyword` varchar(100) DEFAULT NULL,
      `webdescribe` varchar(200) DEFAULT NULL,
      `weblogo` varchar(150) DEFAULT NULL,
      `webisclose` int(11) NOT NULL DEFAULT '0',
      `develop` int(11) NOT NULL DEFAULT '0',
      `webicp` varchar(25) DEFAULT NULL,
      `webple` varchar(25) DEFAULT NULL,
      `address` varchar(200) DEFAULT NULL,
      `webtel` varchar(15) DEFAULT NULL,
      `webfax` varchar(15) DEFAULT NULL,
      `webqq` varchar(15) DEFAULT NULL,
      `webemail` varchar(25) DEFAULT NULL,
      `webother` varchar(255) DEFAULT NULL,
      `articlesku` varchar(255) DEFAULT 'author,source',
      `goodssku` varchar(255) DEFAULT 'spec,color',
      `priceretain` int(11) DEFAULT '2',
      `mailsmtp` varchar(100) DEFAULT NULL,
      `mailport` int(11) DEFAULT '465',
      `mailaccount` varchar(50) DEFAULT NULL,
      `mailpassword` varchar(50) DEFAULT NULL,
      `usercookname` varchar(50) NOT NULL DEFAULT 'usualtool_',
      `salts` varchar(100) DEFAULT 'usualtoolcms',
      `webtitle` varchar(20) DEFAULT NULL,
      `language` varchar(250) DEFAULT 'zh,en',
      `indexlanguage` varchar(50) DEFAULT 'zh',
      `indexunit` varchar(50) DEFAULT 'CNY',
      `indexoss` varchar(100) DEFAULT 'utcms',
      `indexeditor` varchar(100) DEFAULT 'utcms',
	  `indexmodule` varchar(100) DEFAULT 'index',
      `openhtml` int(11) DEFAULT '0',
      `installtime` datetime NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `id` (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
    DROP TABLE IF EXISTS `cms_templete`;
    CREATE TABLE `cms_templete` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `paths` varchar(50) DEFAULT NULL,
      `isopen` int(11) NOT NULL DEFAULT '0',
      `title` varchar(20) DEFAULT NULL,
      `version` varchar(10) DEFAULT NULL,
      `author` varchar(20) DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `id` (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
    DROP TABLE IF EXISTS `cms_update`;
    CREATE TABLE `cms_update` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `updateid` int(11) DEFAULT NULL,
      `updatetime` datetime DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `id` (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
    DROP TABLE IF EXISTS `cms_water`;
    CREATE TABLE IF NOT EXISTS `cms_water` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `water` int(11) DEFAULT '0',
      `water_type` varchar(20) DEFAULT 'text',
      `water_place` int(11) DEFAULT '5',
      `water_textcolor` varchar(20) DEFAULT '#B5B5B5',
      `water_textsize` int(11) DEFAULT '14',
      `water_text` varchar(50) DEFAULT 'usualtoolcms',
      `water_png` varchar(150) DEFAULT 'logo.png',
      PRIMARY KEY (`id`),
      UNIQUE KEY `id` (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
    INSERT INTO `cms_admin_role` VALUES ('1', '创始人','_mod,_api,_system,_nav,_templete,_cactool,_admin');
    INSERT INTO `cms_admin_role` VALUES ('2', '操作员', '_mod,_api,_nav,_templete');
    INSERT INTO `cms_nav_plan` VALUES ('1', '默认方案','1');
    INSERT INTO `cms_templete` VALUES ('1', 'templete/index', '1', '官方默认模板', '8.0', '官方');
    INSERT INTO `cms_nav` VALUES ('1', 'top', 'register', 'index.php?ut=register', '0','_self','1');
    INSERT INTO `cms_nav` VALUES ('2', 'top', 'login', 'index.php?ut=login', '1','_self','1');
    INSERT INTO `cms_nav` VALUES ('3', 'index', 'index', './', '0','_self','1');
    INSERT INTO `cms_nav` VALUES ('4', 'index', 'search', 'index.php?ut=search', '1','_self','1');
    INSERT INTO `cms_water` VALUES ('1', '0', 'text', '8', '#B5B5B5', '20', 'UsualToolCMS', 'logo.png');
    INSERT INTO `cms_mod` VALUES (1, 0, '', '内容', '', 0, 0, 0, '', '', '');
    INSERT INTO `cms_mod` VALUES (2, 0, '', '交互', '', 0, 0, 0, '', '', '');
    INSERT INTO `cms_mod` VALUES (3, 0, '', '配置', '', 0, 0, 0, '', '', '');
    INSERT INTO `cms_mod` VALUES (4, 3, 'module', '模块', 'a_mod.php', 1, 93, 0, '', '', '模块管理:a_mod.php,安装模块:a_mods.php');
    INSERT INTO `cms_mod` VALUES (5, 3, 'plugin', '插件', 'a_api.php', 1, 94, 0, '', '', '插件管理:a_api.php');
    INSERT INTO `cms_mod` VALUES (6, 3, 'templete', '模板', 'a_templete.php', 1, 95, 0, '', '', '模板管理:a_templete.php');
    INSERT INTO `cms_mod` VALUES (7, 3, 'navigation', '导航', 'a_nav.php', 1, 96, 0, '', '', '前端导航:a_nav.php,后端导航:a_nav_admin.php');
    INSERT INTO `cms_mod` VALUES (8, 3, 'cactool', 'CAC', 'a_cactool.php', 1, 97, 0, '', '', 'CAC面板:a_cactool.php,CAC任务:a_cactool_task.php');
    INSERT INTO `cms_mod` VALUES (9, 3, 'admin', '管理', 'a_admin.php', 1, 98, 1, '', '', '角色管理:a_admin_role.php,管理员账号:a_admin.php,登陆日志:a_admin_log.php');
    INSERT INTO `cms_mod` VALUES (10, 3, 'system', '设置', 'a_system.php', 1, 99, 1, '', '', '基础设置:a_system.php,语言设置:a_system_lang_set.php');
	INSERT INTO `cms_search_set` VALUES (1, 'cms_article', 'title,content', 'title like [keyword] or content like [keyword]', 'article');</textarea></div></div>";
    echo"<div class='form-group row'><div class='col-sm-12'><input type='submit' class='btn btn-success' value='创建数据库结构'></div></div>";
    echo"</form>";
//基础设置
elseif($l=="setup"&&$_SESSION["setuporder"]=="3"):
    echo"<form name='form6' action='?t=update' method='post'>";
    echo"<input type='hidden' name='template' value='templete/index'><input type='hidden' name='weblogo' value='https://cms.usualtool.com/assets/logo.png'>";
    echo"<div class='form-group row'><div class='col-sm-12'>全局设置</div></div>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'>授权码: </label><div class='col-sm-9'><input type='text' name='authcode' value='0' class='form-control'> 免费授权请填写0；商业授权请填写授权码</div></div>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'>网站名称: </label><div class='col-sm-9'><input type='text' name='webname' value='web' class='form-control'> 填写web则按照语言包解析</div></div>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'>绑定域名: </label><div class='col-sm-9'><input type='text' name='weburl' value='http://cms.usualtool.com' class='form-control'> 必须填写</div></div>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'>SESSION前缀: </label><div class='col-sm-9'><input type='text' name='usercookname' value='usualtool_' class='form-control'></div></div>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'>初始密码盐值: </label><div class='col-sm-9'><input type='text' name='salts' value='usualtoolcms' class='form-control'> 创建密码时使用</div></div>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'>关闭网站: </label><div class='col-sm-9'><input type='text' name='webisclose' value='0' class='form-control'></div></div>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'>开发模式: </label><div class='col-sm-9'><input type='text' name='develop' value='1' class='form-control'></div></div>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'>余额账户默认货币: </label><div class='col-sm-9'><input type='text' name='indexunit' value='CNY' class='form-control'> 用户余额支付账户默认货币单位。</div></div>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'></label><div class='col-sm-9'><input type='submit' class='btn btn-success' value='保存设置'></div></div>";
    echo"</form>";
//设置管理员
elseif($l=="admin"&&$_SESSION["setuporder"]=="4"):
    echo"<form name='form7' action='?t=admin' method='post'>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'>账户: </label><div class='col-sm-9'><input type='text' name='username' class='form-control'></div></div>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'>密码: </label><div class='col-sm-9'><input type='text' name='password' class='form-control'></div></div>";
    echo"<div class='form-group row'><label class='col-sm-3 control-label form-inline'></label><div class='col-sm-9'><input type='submit' class='btn btn-success' value='创建管理'></div></div>";
    echo"</form>";
//安装完成
elseif($l=="welcome"&&$_SESSION["setuporder"]=="5"):
    echo"<div class='form-group row'><div class='col-sm-12'>恭喜!已完成网站初始设置,您还需进一步完善,以便网站能够正常访问!</div></div>";
    echo"<div class='form-group row'><div class='col-sm-12'>特别注意:登录后端后请按系统提示删除安装文件夹!</div></div>";
    echo"<div class='form-group row'><div class='col-sm-12'>请尽快登录开发者平台进行其他设置!地址: <a href='../dev/'>打开</div></div>";
else:
    echo"<div class='form-group row'><div class='col-sm-12'>设置错误！上一步骤尚未完成!</div></div>";
endif;
?>
            </div>
        </div>

        <div class="col-md-3 mb-2">
            <div class="border p-4">
                <p class="border-bottom"><span style='font-size:30px;'>Hi!</span> UT框架</p>
                <p class="text-center <?php echo$liid0;?>" style="line-height: 40px;"><a class="<?php echo$liid0x;?>" href="#">使用协议</a></p>
                <p class="text-center <?php echo$liid1;?>" style="line-height: 40px;"><a class="<?php echo$liid1x;?>" href="#">环境检测</a></p>
                <p class="text-center <?php echo$liid2;?>" style="line-height: 40px;"><a class="<?php echo$liid2x;?>" href="#">数据连接</a></p>
                <p class="text-center <?php echo$liid3;?>" style="line-height: 40px;"><a class="<?php echo$liid3x;?>" href="#">规划框架</a></p>
                <p class="text-center <?php echo$liid4;?>" style="line-height: 40px;"><a class="<?php echo$liid4x;?>" href="#">系统设置</a></p>
                <p class="text-center <?php echo$liid5;?>" style="line-height: 40px;"><a class="<?php echo$liid5x;?>" href="#">管理设置</a></p>
                <p class="text-center <?php echo$liid6;?>" style="line-height: 40px;"><a class="<?php echo$liid6x;?>" href="#">安装完成</a></p>
             </div>
        </div>
 
    </div>
</div>
<script>
$(function(){
    var regBtn = $("#regBtn");
    $("#regText").change(function(){
        var that = $(this);
        that.prop("checked",that.prop("checked"));
        if(that.prop("checked")){
            regBtn.prop("disabled",false)
        }else{
            regBtn.prop("disabled",true)
        }
    });
});
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-3 mb-3">
			<p>
			2018 - <?php echo date('Y');?> Prowed by <a target="_blank" href="http://cms.usualtool.com">UsualToolCMS</a>
			</p>
        </div>
    </div>
</div>
</body>
</html>