<?php
//首页示例Demo
$mytpl->runin('webplace',LangData("index"));
//文章标签数据
$mytpl->runin("taglists",UsualToolCMSDB::tagData("cms_article","keywords","","addtime desc"));
//读取最新/推荐/点击排行文章
$mytpl->runin("newarticles",UsualToolCMSDB::queryData("cms_article","","","addtime desc","")["querydata"]);
$mytpl->runin("toparticles",UsualToolCMSDB::queryData("cms_article","","recommend=1","addtime desc","")["querydata"]);
$mytpl->runin("hitarticles",UsualToolCMSDB::queryData("cms_article","","","hit desc","")["querydata"]);
//读取最新/推荐/点击排行商品
$mytpl->runin("newgoods",UsualToolCMSDB::queryData("cms_goods","","","addtime desc","")["querydata"]);
$mytpl->runin("topgoods",UsualToolCMSDB::queryData("cms_goods","","recommend=1","addtime desc","")["querydata"]);
$mytpl->runin("hitgoods",UsualToolCMSDB::queryData("cms_goods","","","hit desc","")["querydata"]);
//读取最新/推荐/点击排行图集
$mytpl->runin("newatlas",UsualToolCMSDB::queryData("cms_atlas","","","addtime desc","")["querydata"]);
$mytpl->runin("topatlas",UsualToolCMSDB::queryData("cms_atlas","","recommend=1","addtime desc","")["querydata"]);
$mytpl->runin("hitatlas",UsualToolCMSDB::queryData("cms_atlas","","","hit desc","")["querydata"]);
//读取最新/推荐/点击排行招聘
$mytpl->runin("newjobs",UsualToolCMSDB::queryData("cms_job","","shen=1","addtime desc","")["querydata"]);
$mytpl->runin("topjobs",UsualToolCMSDB::queryData("cms_job","","shen=1 and recommend=1","addtime desc","")["querydata"]);
$mytpl->runin("hitjobs",UsualToolCMSDB::queryData("cms_job","","shen=1","hit desc","")["querydata"]);
//读取最新/推荐/下载量排行下载
$mytpl->runin("newdowns",UsualToolCMSDB::queryData("cms_down","","shen=1","addtime desc","")["querydata"]);
$mytpl->runin("topdowns",UsualToolCMSDB::queryData("cms_down","","shen=1 and recommend=1","addtime desc","")["querydata"]);
$mytpl->runin("hitdowns",UsualToolCMSDB::queryData("cms_down","","shen=1","downnum desc","")["querydata"]);
//读取最新/推荐/点击排行分类信息
$mytpl->runin("newinfos",UsualToolCMSDB::queryData("cms_info","","shen=1","addtime desc","")["querydata"]);
$mytpl->runin("topinfos",UsualToolCMSDB::queryData("cms_info","","shen=1 and recommend=1","addtime desc","")["querydata"]);
$mytpl->runin("hitinfos",UsualToolCMSDB::queryData("cms_info","","shen=1","hit desc","")["querydata"]);
//读取最新音乐/专辑
$mytpl->runin("newmusics",UsualToolCMSDB::queryData("cms_music","","","addtime desc","")["querydata"]);
$mytpl->runin("musicalbums",UsualToolCMSDB::queryData("cms_music_album","","","addtime desc","")["querydata"]);
//读取最新视频
$mytpl->runin("newvideos",UsualToolCMSDB::queryData("cms_video","","","addtime desc","")["querydata"]);
//读取自定义内容首图及正文
$mytpl->runin("customs",UsualToolCMSDB::figureData("cms_page","htmlcontent","id=1"));
//读取文章/商品/图集/信息一级分类
$mytpl->runin("articlecats",UsualToolCMSDB::queryData("cms_article_cat","","bigclassid=0","ordernum desc","")["querydata"]);
$mytpl->runin("goodscats",UsualToolCMSDB::queryData("cms_goods_cat","","bigclassid=0","ordernum desc","")["querydata"]);
$mytpl->runin("atlacats",UsualToolCMSDB::queryData("cms_atlas_cat","","bigclassid=0","ordernum desc","")["querydata"]);
$mytpl->runin("infocats",UsualToolCMSDB::queryData("cms_info_cat","","bigclassid=0","ordernum desc","")["querydata"]);
//第三方登录接口
$mytpl->runin(
array("qq_appid","qq_reurl","wb_appid","wb_reurl","ww_appid","ww_reurl"),
array($auths["qq_appid"],$auths["qq_reurl"],$auths["wb_appid"],$auths["wb_reurl"],$auths["ww_appid"],$auths["ww_reurl"])
);
$mytpl->open('index.cms');
?>