<?php
require_once '../conn.php';
if($_GET["do"]=="out"){
    unset($_SESSION['usualtooladmin']);
    unset($_SESSION['usualtooladminid']);
    echo"<script>alert('登出UT Develop成功!');window.location.href='ut-login.php'</script>";
}
if($_GET['do']=="login"){
    $uuser=UsualToolCMS::sqlcheck($_POST["uuser"]);
    $upass=UsualToolCMS::sqlcheck($_POST["upass"]);
    $ucode=UsualToolCMS::sqlcheck($_POST["ucode"]);
    $uip=UsualToolCMS::sqlcheck(UsualToolCMS::getip());
    if($_SESSION['authcode']==$ucode):
            if(!empty($uuser)&&!empty($upass)):
                $datas=UsualToolCMSDB::queryData("cms_admin","id,username,password,salts","username='$uuser'","","","0");
                if($datas["querynum"]==1):
                    $rows=$datas["querydata"][0];
                    $shaupass=sha1($rows['salts'].$upass);
                    if($shaupass==$rows['password']):
                        UsualToolCMSDB::insertData("cms_admin_log",array("adminusername"=>$uuser,"ip"=>$uip,"logintime"=>date('Y-m-d H:i:s',time())));
                        $_SESSION['usualtooladmin']=$rows['username'];
                        $_SESSION['usualtooladminid']=$rows['id'];
                        session_regenerate_id(TRUE);
                        echo"<script>alert('登陆UT Develop成功!');window.location.href='index.php'</script>";
                    else:
                        echo"<script>alert('账户或密码不匹配!');window.location.href='ut-login.php'</script>";
                    endif;
                else:
                    echo"<script>alert('账户不存在!');window.location.href='ut-login.php'</script>";
                endif;
        else:
            echo"<script>alert('账户或密码不能为空!');window.location.href='ut-login.php'</script>";
        endif;
    else:
        echo"<script>alert('验证码不正确!');window.location.href='ut-login.php'</script>";
    endif;
    $mysqli->close();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>UT Develop 8.0</title>
<link rel="stylesheet" type="text/css" href="../assets/css/login.css">
<?php if($setup["cmscolor"]>1):?><link rel="stylesheet" type="text/css" href="../assets/css/cms-<?php echo$setup["cmscolor"];?>css.css"><?php endif;?>
<link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.css">
<script type="text/javascript" src="../assets/js/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(c) {
	$('.alert-close').on('click', function(c){
		$('.message').fadeOut('slow', function(c){
	  		$('.message').remove();
		});
	});	  
});
</script>
</head>
<body>
<form name="login" form="login" method=POST action="?do=login" style="margin:0 0px;">
<div class="message warning">
<div class="inset">
<div class="login-head">
<h1>UT Develop 8.0</h1>
<div class="alert-close"></div> 
</div>
<ul>
<li><input type="text" class="text" name="uuser"><a href="#" class=" icon user"></a></li>
<li><input type="password" name="upass"> <a href="#" class="icon lock"></a></li>
<li><input type="text" class="text" name="ucode" style="background: url('../class/UsualToolCMS_Code.php?r=<?php echo rand();?>') no-repeat right 0px center;"/>
<a href="ut-login.php" class="icon code"></a></li>
<div class="submit">
<button type="submit" class="utlogin" ><i class="fa fa-send" aria-hidden="true"></i> 登录</button>
<div class="clear">
</div>
</div>
</ul>
</div>				
</div>
</form>
</body>
</html>