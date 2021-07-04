<?php
$key=UsualToolCMS::sqlcheck($_GET["key"]);
$navname="搜索";
require_once(UTF_PATH.'/'.'top.php');
UsualToolCMS::plugins("nsfw");
$data=UsualToolCMSDB::searchData($key);
if(!empty($_GET["page"])&&is_numeric($_GET["page"])):$page=$_GET["page"];else:$page=1;endif;
$pagenum=10;
$pagelink="".$listlink."key=$key";
$minid=$pagenum*($page-1);
$querynum=$data["searchnum"];
if($querynum==0):
    echo'<script>$(function(){$("title").html("无效搜索")})</script>';
    echo"<script>alert('没有搜索到有价值的信息!');history.go(-1);</script>";
    exit();
else:
    echo'<script>$(function(){$("title").html("搜索:'.$key.'")})</script>';
endif;
$querydata=$data["searchdata"];
$totalpage=ceil($querynum/$pagenum);
?>
<div class="container">
    <div class="row">
        <div class="col-md-8 mb-2">
            <div class="border p-4">
            <p><b><i class="fa fa-search" aria-hidden="true"></i> <?php echo LangData("search");?>:<?php echo$key;?></b></p>
            <?php
                foreach($querydata as $querydatas){
                    echo"<div class='border-bottom pb-2'>".$xu." ";
                    echo"<a target='_blank' href='index.php?ut=".$querydatas["thepage"]."&id=".$querydatas["id"]."'>";
                    echo"".str_ireplace($key,"<font color=red><b>".$key."</b></font>",$querydatas["title"])."</a><br><span style='color:#999999;font-size:12px;'>".UsualToolCMS::subkey(UsualToolCMS::deletehtml($querydatas["content"]),$key)."...</span></div>";
                }
            ?>
            <div class="pt-2"><?php $subPages=new pager($totalpage,$page,$pagenum,$pagelink);echo $subPages->showpager();?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ut-background p-4">
            <form form="search" action="index.php" method="get">
            <input type="hidden" name="ut" value="search">
                <div class="form-group row">
                    <div class="col-sm-8 pb-2">
                        <input type=text name="key" class="form-control">
                    </div>
                    <div class="col-sm-4 pb-2">
                        <input type=submit value="<?php echo LangData("search");?>" class="btn btn-info">
                    </div>
                </div>
            </form>
            <div class="bg-info text-center text-light"><?php echo LangData("ranking");?></div>
            <?php
            $tags=UsualToolCMSDB::queryData("cms_search","hit,keyword","","hit desc","20")["querydata"];
            foreach($tags as $tag):
            echo"<div class='pt-2'>".$tag['xu']." <a href='index.php?ut=search&key=".$tag['keyword']."'>".$tag['keyword']."</a></div>";
            endforeach;
            ?>
            </div>
         </div>
      </div>
   </div>
</div>
<?php
require_once(UTF_PATH.'/'.'bot.php');?>