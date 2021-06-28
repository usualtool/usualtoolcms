<?php
if(isset($_SESSION['usualtooladmin'])&&isset($_SESSION['usualtooladminid'])&&!empty($_SESSION['usualtooladmin'])&&!empty($_SESSION['usualtooladminid'])):
    $adminuser=$_SESSION['usualtooladmin'];
    $adminuserid=$_SESSION['usualtooladminid'];
    $adminnum=UsualToolCMSDB::queryData("cms_admin","","id='$adminuserid' and username='$adminuser'","","","0")["querynum"];
    if($adminnum!==1):
        header("location:ut-login.php");exit();
    endif;
else:
    header("location:ut-login.php");
    exit();
endif;
?>