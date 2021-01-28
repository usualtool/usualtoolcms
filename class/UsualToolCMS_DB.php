<?php
//API接口方法
class UsualToolCMSDB{
    //判断模块是否存在
    function modTable($table){
        include(ROOT_PATH.'/'.'sql_db.php');
        if(mysqli_num_rows($mysqli->query("SHOW TABLES LIKE '". $table."'"))==1) {
            return true;
        }else{
            return false;
        }
    }
    //获取所有数据或单个数据详情的方法
    /*
    *table:表名
    *field:输出字段
    *where:查询条件
    *order:排序方式
    *limit:数据量
    *lang:是否自动开启语言，默认1开启。
    */
    function queryData($table,$field='',$where='',$order='',$limit='',$lang='1',$cache='0'){
        include(ROOT_PATH.'/'.'sql_db.php');
        global$language;
        if(!empty($field)):
            $fields=$field;
        else:
            $fields="*";
        endif;
        if($lang=="1"):
        if(!empty($where)):
            $wheres="where lang='$language' and ".$where."";
        else:
            $wheres="where lang='$language'";
        endif;
        else:
        if(!empty($where)):
            $wheres="where ".$where."";
        else:
            $wheres="";
        endif;
        endif;
        if(!empty($order)):
            $orders="order by ".$order."";
        else:
            $orders="";
        endif;
        if(!empty($limit)):$limits="limit ".$limit."";else:$limits="";endif;
        if(UsualToolCMSDB::modTable($table)):
            if(UTREDIS=="1" && $cache!="0"):
                if(!$redis->exists("UT-".$cache."")):
                    $sql="select * from `".$table."` ".$wheres."";
                    $list=$mysqli->query($sql);
                    $listnum=mysqli_num_rows($list);
                    $sqls="select ".$fields." from `".$table."` ".$wheres." ".$orders." ".$limits."";
                    $querys=$mysqli->query($sqls);
                    $querydata=array(); 
                    $xu=0;
                    while($rows=mysqli_fetch_array($querys,MYSQLI_ASSOC)):
                        $xu=$xu+1;
                        $count=count($rows);
                        for($i=0;$i<$count;$i++):
                            unset($rows[$i]);
                        endfor;
                        $rows['xu']=$xu;
                        array_push($querydata,$rows);
                    endwhile;
                    $redis->set("UT-".$cache."",json_encode(array("querydata"=>$querydata,"querynum"=>$listnum)),UTREDIS_TIME);
                    return array("querydata"=>$querydata,"querynum"=>$listnum);
                else:
                    return json_decode($redis->get("UT-".$cache.""),true);
                endif;
            else:
                $sql="select * from `".$table."` ".$wheres."";
                $list=$mysqli->query($sql);
                $listnum=mysqli_num_rows($list);
                $sqls="select ".$fields." from `".$table."` ".$wheres." ".$orders." ".$limits."";
                $querys=$mysqli->query($sqls);
                $querydata=array(); 
                $xu=0;
                while($rows=mysqli_fetch_array($querys,MYSQLI_ASSOC)):
                    $xu=$xu+1;
                    $count=count($rows);
                    for($i=0;$i<$count;$i++):
                        unset($rows[$i]);
                    endfor;
                    $rows['xu']=$xu;
                    array_push($querydata,$rows);
                endwhile;
                return array("querydata"=>$querydata,"querynum"=>$listnum);
            endif;
        else:
            return array();
        endif;
    }
    //增加数据
    /*
    *table:表名
    *data:字段及值的数组
    */
    function insertData($table,$data){
        include(ROOT_PATH.'/'.'sql_db.php');
        $sql="insert into `".$table."` (".implode(',',array_keys($data)).") values ('".implode("','",array_values($data))."')";
        $query=$mysqli->query($sql);
        if($query):
            return mysqli_insert_id($mysqli);
        else:
            return false;
        endif;
    }
    //编辑数据
    /*
    *table:表名
    *data:字段及值的数组
    *where:条件
    */
    function updateData($table,$data,$where){
        include(ROOT_PATH.'/'.'sql_db.php');
        $updatestr='';
        if(!empty($data)):
            foreach($data as $k=>$v):
			    if(preg_match('/\+\d/is',$v)):
			        $updatestr.=$k."=".$v.",";
			    else:
                    $updatestr.=$k."='".$v."',";
		        endif;
            endforeach;
            $updatestr=rtrim($updatestr,',');
        endif;
        $sql="update `".$table."` set ".$updatestr." where ".$where."";
        $query=$mysqli->query($sql);
        if($query):
            return true;
        else:
            return false;
        endif;
    }
    //删除数据
    /*
    *table:表名
    *where:条件
    */
    function delData($table,$where){
    include(ROOT_PATH.'/'.'sql_db.php');
    $sql="delete from `".$table."` where ".$where."";
    $query=$mysqli->query($sql);
    if($query):return true;else:return false;endif;
    }
    //获取标签数据
    /*
    *table:表名
    *field:输出字段
    *where:查询条件
    *order:排序方式
    *lang:是否自动开启语言，默认1开启。
    */
    function tagData($table,$field='',$where='',$order='',$lang='1'){
        include(ROOT_PATH.'/'.'sql_db.php');
        global$language;
        if(!empty($field)):
            $fields=$field;
        else:
            $fields="*";
        endif;
        if($lang=="1"):
        if(!empty($where)):
            $wheres="where lang='$language' and ".$where."";
        else:
            $wheres="where lang='$language'";
        endif;
        else:
        if(!empty($where)):
            $wheres="where ".$where."";
        else:
            $wheres="";
        endif;
        endif;
        if(!empty($order)):
            $orders="order by ".$order."";
        else:
            $orders="";
        endif;
        if(UsualToolCMSDB::modTable($table)):
            $sql="select ".$fields." from `".$table."` ".$wheres." ".$orders."";
            $tag=$mysqli->query($sql);
            while($tagrow=$tag->fetch_row()):
                $tags="".$tags.",".$tagrow[0]."";
            endwhile;
            $taglist=join(',',array_unique(array_diff(explode(",",$tags),array(""))));
            $taglists[]=array('tags'=>$taglist);
            return $taglists;
        else:
            return array();
        endif;
    }
    //获取数据内容中的首图及正文
    /*
    *table:表名
    *field:内容字段名
    *where:查询条件
    */
    function figureData($table,$field,$where=''){
        include(ROOT_PATH.'/'.'sql_db.php');
        if(!empty($where)):
            $wheres="where ".$where."";
        else:
            $wheres="";
        endif;
        if(UsualToolCMSDB::modTable($table)):
            $sql="SELECT * from `".$table."` ".$wheres."";
            $querys=$mysqli->query($sql);  
            $figuredata=array(); 
            while($rows=mysqli_fetch_array($querys,MYSQLI_ASSOC)):
                $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.bmp|\.png]))[\'|\"].*?[\/]?>/";
                preg_match_all($pattern,$rows[$field],$matchcontent);
                if(isset($matchcontent[1][0])):
                    $rows['indexpic']=$matchcontent[1][0];
                else:
                    $rows['indexpic']='images/custom.png';
                endif;
                $count=count($rows);
                for($i=0;$i<$count;$i++):
                    unset($rows[$i]);
                endfor;
                array_push($figuredata,$rows);
            endwhile;
            return $figuredata;
        else:
        return array();
        endif;
    }
    //搜索方法
    /*
    *tables:表名，多个表名以半角逗号分隔
    *keyword:关键词
    */
    function searchData($keyword){
        include(ROOT_PATH.'/'.'sql_db.php');
        global$language;
		if(!empty($keyword)):
			$ssql="SELECT * FROM `cms_search` WHERE keyword ='$keyword'";
			$sdata=mysqli_query($mysqli,$ssql);
			if(mysqli_num_rows($sdata)>0):
			    UsualToolCMSDB::updateData("cms_search",array("hit"=>"hit+1"),"keyword ='$keyword' and lang='$language'");
			else:
			    UsualToolCMSDB::insertData("cms_search",array("lang"=>$language,"keyword"=>$keyword));
			endif;
		endif;
		$data=array();
		$result=$mysqli->query("select * from `cms_search_set`");
		while($row=mysqli_fetch_array($result)){
		    $data[]=array("db"=>$row["dbs"],"field"=>$row["fields"],"where"=>$row["wheres"],"page"=>$row["pages"]);
		}
		$table="select 'search' as thepage,id,'0' as title,'0' as content from `cms_search` where id<0";
		foreach($data as $key=>$val){
			if(UsualToolCMSDB::modTable($val["db"])){
				$table.=" union select '".$val["page"]."' as thepage,id,".$val["field"]." from ".$val["db"]." where ".str_replace("[keyword]","'%".$keyword."%'",$val["where"])."";
			}
		}
        $search=$mysqli->query($table);
        $searchnum=mysqli_num_rows($search);
        $searchdata=array(); 
        $xu=0;
        while($rows=mysqli_fetch_array($search,MYSQLI_ASSOC)):
            $xu=$xu+1;
            $count=count($rows);
            for($i=0;$i<$count;$i++):
                unset($rows[$i]);
            endfor;
            $rows['xu']=$xu;
            array_push($searchdata,$rows);
        endwhile;
        return array("searchdata"=>$searchdata,"searchnum"=>$searchnum);	
    }
    //公众号//小程序结构
    function wechatNavJosn(){
        include(ROOT_PATH.'/'.'sql_db.php');
        $sql="select *from `cms_wechat_nav` where bigid='0' order by ordernum asc";
        $onenav=$mysqli->query($sql); 
        while($onenavrow=mysqli_fetch_array($onenav)):
            $id=$onenavrow["id"];
            $type=$onenavrow["type"];
            if($type=="click"||$type=="scancode_waitmsg"||$type=="scancode_push"||$type=="pic_sysphoto"||$type=="pic_photo_or_album"||$type=="pic_weixin"||$type=="location_select"):
                $navlist[]=array_merge_recursive(array('name'=>$onenavrow["navname"],'type'=>$onenavrow["type"],'key'=>$onenavrow["tkey"]),array('sub_button'=>UsualToolCMSDB::wechatNav($id)));
            elseif($type=="view"):
                $navlist[]=array_merge_recursive(array('name'=>$onenavrow["navname"],'type'=>$onenavrow["type"],'url'=>$onenavrow["url"]),array('sub_button'=>UsualToolCMSDB::wechatNav($id)));
            elseif($type=="miniprogram"):
                $navlist[]=array_merge_recursive(array('name'=>$onenavrow["navname"],'type'=>$onenavrow["type"],'url'=>$onenavrow["url"],'appid'=>$onenavrow["appid"],'pagepath'=>$onenavrow["pagepath"]),array('sub_button'=>UsualToolCMSDB::wechatNav($id)));
            elseif($type=="media_id"||$type=="view_limited"):
                $navlist[]=array_merge_recursive(array('name'=>$onenavrow["navname"],'type'=>$onenavrow["type"],'media_id'=>$onenavrow["media_id"]),array('sub_button'=>UsualToolCMSDB::wechatNav($id)));
            endif;
        endwhile;
        $navlists=array('button'=>$navlist);
        return json_encode($navlists,JSON_UNESCAPED_UNICODE);
    }
    function wechatNav($id){
        include(ROOT_PATH.'/'.'sql_db.php');
        $sql="SELECT * FROM `cms_wechat_nav` WHERE bigid='$id' order by ordernum asc";
        $twonav=$mysqli->query($sql); 
        while($twonavrow=mysqli_fetch_array($twonav)):
            $types=$twonavrow["type"];
            if($types=="scancode_waitmsg"||$types=="scancode_push"||$types=="pic_sysphoto"||$types=="pic_photo_or_album"||$types=="pic_weixin"||$types=="location_select"):
                $nav[]=array('name'=>$twonavrow["navname"],'type'=>$twonavrow["type"],'key'=>$twonavrow["tkey"]);
            elseif($types=="view"):
                $nav[]=array('name'=>$twonavrow["navname"],'type'=>$twonavrow["type"],'url'=>$twonavrow["url"]);
            elseif($types=="miniprogram"):
                $nav[]=array('name'=>$twonavrow["navname"],'type'=>$twonavrow["type"],'url'=>$twonavrow["url"],'appid'=>$twonavrow["appid"],'pagepath'=>$twonavrow["pagepath"]);
            elseif($types=="media_id"||$types=="view_limited"):
                $nav[]=array('name'=>$twonavrow["navname"],'type'=>$twonavrow["type"],'media_id'=>$twonavrow["media_id"]);
            endif;
        endwhile;
        return $nav;
    }
    //获取第三方登录数据
    function authLogin(){
        include(ROOT_PATH.'/'.'sql_db.php');
        $sql="select qq_appid,qq_appkey,qq_reurl,wb_appid,wb_appkey,wb_reurl,ww_appid,ww_appkey,ww_reurl from `cms_connect` limit 1";
        $auth=$mysqli->query($sql); 
        while($setuprow=mysqli_fetch_array($auth)):
            $authlist=array(
            'qq_appid'=>$setuprow["qq_appid"],'qq_appkey'=>$setuprow["qq_appkey"],'qq_reurl'=>$setuprow["qq_reurl"],
            'wb_appid'=>$setuprow["wb_appid"],'wb_appkey'=>$setuprow["wb_appkey"],'wb_reurl'=>$setuprow["wb_reurl"],
            'ww_appid'=>$setuprow["ww_appid"],'ww_appkey'=>$setuprow["ww_appkey"],'ww_reurl'=>$setuprow["ww_reurl"]
            );
        endwhile;
        return $authlist;
    }
}