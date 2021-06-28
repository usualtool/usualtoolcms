<link rel="stylesheet" type="text/css" href="../assets/css/jquery.smarticker.css">
<div class="apimessage">&nbsp;</div>
<div class="smarticker5">
<ul>
    <?php
    if(UsualToolCMSDB::modTable("cms_order")):
        $noorders=UsualToolCMSDB::queryData("cms_order","","state=1","","","0")["querynum"];
    else:
        $noorders=0;
    endif;
    if(UsualToolCMSDB::modTable("cms_book")):
        $nobooks=UsualToolCMSDB::queryData("cms_book","","aname is null","","","0")["querynum"];;
    else:
        $nobooks=0;
    endif;
    if($noorders>0){
    ?>
    <li data-subcategory="订单动态" data-category="订单"><a href='ut-view-module.php?m=member&u=a_users_order.php'>您有 <?php echo$noorders;?> 个订单待发货！</a></li>
    <?php }if($nobooks>0){?>
        <li data-subcategory="咨询留言" data-category="咨询"><a href='ut-view-module.php?m=feedback&u=a_book.php'>您有 <?php echo$nobooks;?> 个咨询待处理！</a></li>
    <?php 
    }
    $apimessages=UsualToolCMS::auth($authcode,$authapiurl,"message");
    $apimessage=explode("|",$apimessages);
    for($i=0;$i<count($apimessage);$i++):
    ?>
        <li data-subcategory="UsualTool官网" data-category="官网"><a target='_blank' href='http://cms.usualtool.com/'><?php echo $apimessage[$i];?></a></li>
    <?php endfor;?>
</ul>
</div>
<script type="text/javascript" src="../assets/js/smarticker.js"></script>