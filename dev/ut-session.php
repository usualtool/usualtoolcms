<?php
if(isset($_SESSION['usualtooladmin'])&&isset($_SESSION['usualtooladminid'])&&!empty($_SESSION['usualtooladmin'])&&!empty($_SESSION['usualtooladminid'])):
    $adminuser=$_SESSION['usualtooladmin'];
    $adminuserid=$_SESSION['usualtooladminid'];
    $adminnum=mysqli_num_rows(mysqli_query($mysqli,"SELECT id FROM `cms_admin` where id='$adminuserid' and username='$adminuser'"));
    if($adminnum!==1):
        header("location:ut-login.php");exit();
    endif;
else:
    header("location:ut-login.php");
    exit();
endif;
?>