<h2>
<a href="?m=admin&u=a_admin.php" class="actionBtn">管理员</a> 
登陆日志
</h2>
<div id="ut-auto">
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tablebasic" id="ut-table">
    <thead>
         <tr>
          <th width="40%" align="left">登陆时间</th>
          <th width="20%" align="left">管理员账号</th>
          <th width="40%" align="center">IP地址</th>
         </tr>
         </thead>
         <tbody>
    <?php
    $pagenum=10;
    $page=empty($_GET["page"]) ? 1 : UsualToolCMS::sqlcheck($_GET["page"]);
    $pagelink="?m=$mod&u=$url";
    $minid=$pagenum*($page-1);
    $list=UsualToolCMSDB::queryData("cms_admin_log","","","logintime desc","$minid,$pagenum","0");
    $total=$list["querynum"];
    $totalpage=ceil($total/$pagenum);
    foreach($list["querydata"] as $rows):
        echo"<tr><td>".$rows["logintime"]."</td><td>".$rows["adminusername"]."</td><td align=center>".$rows["ip"]."</td></tr>";
    endforeach;
    ?>
        </tbody>
    </table>
    </div>
    <div class="pager">
    <?php
    $subPages=new pager($totalpage,$page,$pagenum,$pagelink);
    echo $subPages->showpager();
    ?>
    <p><a href="?m=admin&u=a_admin_log.php&t=del">清除但保留最新50条日志</a></p>
</div>
<?php
if($_GET["t"]=="del"):
    $maxid=UsualToolCMSDB::queryData("cms_admin_log","id","","id desc","1","0")["querydata"][0]["id"];
    $minid=$maxid-50;
    if(UsualToolCMSDB::delData("cms_admin_log","id<='$minid'")):
        echo "<script>alert('日志删除成功!');window.location.href='?m=admin&u=a_admin_log.php'</script>";
    else:
        echo "<script>alert('日志删除失败!');window.location.href='?m=admin&u=a_admin_log.php'</script>";
    endif;
endif;
?>