<?php
if(strpos($currentpage,"my-")!==false){
require_once(dirname(__FILE__).'/'.'../member/home/my-session.php');}?>
<!doctype>
<html>
<head>
<title><?php echo LangData($navname);?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo$webname;?>" />
<meta name="description" content="<?php echo$webname;?>" />
<link href="<?php echo$weburl;?>/assets/css/font-awesome.css" rel="stylesheet">
<link href="<?php echo$weburl;?>/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo$weburl;?>/assets/css/style.css" rel="stylesheet">
<script src="<?php echo$weburl;?>/assets/js/jquery.min.js"></script>
<script type="text/javascript">window.ROOTPATH='<?php echo$weburl;?>';</script>
<script src="<?php echo$weburl;?>/assets/js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo$weburl;?>/assets/js/jquery.localize.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo$weburl;?>/assets/js/language.cookie.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo$weburl;?>/assets/js/formcheck.js" type="text/javascript"></script>
</head>
<body>
<div class="container mt-2 mb-2">
    <div class="row">
            <div class="col-sm-6 col-md-nobr">
            <a target="_blank" href="<?php echo$weburl;?>" title="<?php echo LangData('index');?>"><img src="<?php echo$weblogo;?>" alt=""></a>
            </div>
            <div class="col-md-6 text-right m-auto col-md-nobr">
                <?php
                if(is_dir(WEB_PATH."/modules/member")):
                    if(empty($uid)&&empty($user)&&empty($usermail)):?>
                            <i class="fa fa-user-circle-o" aria-hidden="true"></i> <a target="_blank" href="<?php echo$weburl;?>/home/index.php?ut=my-home" class="navactive"><?php echo LangData('account');?></a>	
                    <?php
                    else:?>
                            <i class="fa fa-sign-out" aria-hidden="true"></i> 
                            <a href="<?php echo$weburl;?>/index.php?ut=login&t=out" class="navactive"><?php echo LangData('out');?></a>
                    <?php
                    endif;
                endif;
                ?>
            </div>
    </div>
</div>
<div class="clear"></div>
<?php if(strpos($currentpage,"my-")!==false){
require_once(dirname(__FILE__).'/'.'../member/home/my-nav.php');}?>