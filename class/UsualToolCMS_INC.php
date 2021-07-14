<?php
class UsualToolCMS{
    /*过滤操作函数*/
    static function sqlcheck($StrPost){
        $StrPost=UsualToolCMS::sqlchecks($StrPost);
        if(PHP_VERSION>=6 || !get_magic_quotes_gpc()):
            $StrPost=addslashes($StrPost);
        endif;
        $StrPost=nl2br($StrPost);
        $StrPost=htmlspecialchars($StrPost,ENT_QUOTES);
        return $StrPost;
    }
    static function sqlchecks($StrPost){
        $StrPost=str_replace("'","’",$StrPost);
        $StrPost=str_replace('"','“',$StrPost);
        $StrPost=str_replace("(","（",$StrPost);
        $StrPost=str_replace(")","）",$StrPost);
        $StrPost=str_replace("@","#",$StrPost);
        $StrPost=str_replace("/*","",$StrPost);
        $StrPost=str_replace("*/","",$StrPost);
        return $StrPost;
    }
    static function sqlcheckv($StrPost){
        $StrPost=str_ireplace("eval","",$StrPost);
        $StrPost=str_ireplace("assert","",$StrPost);
        $StrPost=str_ireplace("create_function","",$StrPost);
        $StrPost=str_ireplace("call_user_func","",$StrPost);
        $StrPost=str_ireplace("call_user_func_array","",$StrPost);
        $StrPost=str_ireplace("array_map","",$StrPost);
        $StrPost=str_ireplace("system","",$StrPost);
        $StrPost=str_ireplace("shell_exec","",$StrPost);
        $StrPost=str_ireplace("passthru","",$StrPost);
        $StrPost=str_ireplace("exec","",$StrPost);
        $StrPost=str_ireplace("popen","",$StrPost);
        $StrPost=str_ireplace("proc_open","",$StrPost);
        $StrPost=str_ireplace("ob_start","",$StrPost);
        $StrPost=str_ireplace("putenv","",$StrPost);
        $StrPost=str_ireplace("ini_set","",$StrPost);
        $StrPost=str_ireplace("preg_match","",$StrPost);
        return $StrPost;
    }
    static function sqlcheckx($StrPost){
        $result = false;
        if($StrPost !== '' && !is_null($StrPost)){
        $var = UsualToolCMS::sqlchecks($StrPost);
        $var=str_replace("+","",$var);
        $var=str_replace("-","",$var);
        $var=str_replace("%","",$var);
        $var=str_replace("*","",$var);
        if($var !== ''&& !is_null($var) && (is_numeric($var)||is_float($var))){
            $result = $var;
        }else{
            $result = false;
        }
        }
        return $result;
    }
    static function clearnum($StrPost){
        $StrPost=str_replace("s.php","",$StrPost);
        $StrPost=str_replace("s.html","",$StrPost);
        $StrPost=str_replace("-","",$StrPost);
        $StrPost=str_replace("0","",$StrPost);
        $StrPost=str_replace("1","",$StrPost);
        $StrPost=str_replace("2","",$StrPost);
        $StrPost=str_replace("3","",$StrPost);
        $StrPost=str_replace("4","",$StrPost);
        $StrPost=str_replace("5","",$StrPost);
        $StrPost=str_replace("6","",$StrPost);
        $StrPost=str_replace("7","",$StrPost);
        $StrPost=str_replace("8","",$StrPost);
        $StrPost=str_replace("9","",$StrPost);
        $StrPost=str_replace(".php","",$StrPost);
        $StrPost=str_replace(".html","",$StrPost);
        return $StrPost;
    }
    static function deletehtml($str){
        global$language;
        $str = strip_tags($str,"");
        $str = str_replace(array("\r\n", "\r", "\n"), "", $str);   
        $str = str_replace("　","",$str);
        $str = str_replace("&nbsp;","",$str);
        if($language=="zh"):
            $str = str_replace(" ","",$str);
        endif;
        return ltrim(trim($str));
    }
    static function contain($str,$target){
        $tmpArr = explode($str,$target);
        if(count($tmpArr)>1)return true;
        else return false;
    }
    static function arraymerge(&$a,$b){
        foreach($a as $key=>&$val){
            if(is_array($val) && array_key_exists($key, $b) && is_array($b[$key])){
                UsualToolCMS::arraymerge($val,$b[$key]);
                $val = $val + $b[$key];
            }else if(is_array($val) || (array_key_exists($key, $b) && is_array($b[$key]))){
                $val = is_array($val)?$val:$b[$key];
            }
        }
        $a = $a + $b;
    }
    /*模块操作函数*/
    static function modsearch($module,$array){
        $error="error";
        $json=json_encode($array,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        if(strpos($json,$module)!==false){
            foreach($array as $key=>$value){
                if(strpos($value,$module)!==false){
                    return $key;
                }
            }
        }else{
        return $error;
        }
    }
    static function delmodarray($arr,$key){
        if(!is_array($arr)){
            return $arr;
        }
        foreach($arr as $k=>$v){
            if($k == $key){
                unset($arr[$k]);
            }
        }
        return $arr;
    }
    /*互联操作函数*/
    static function isetup(){
        if(is_dir('setup')){
            if(file_exists('setup/usualtoolcms.lock')){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }
    static function plugins($pluginname,$pluginroot='index.php'){
        if(is_dir("".ROOT_PATH."/plugins/".$pluginname."")):
            if(empty($pluginroot)||UsualToolCMS::contain(".php",$pluginroot)):
                include_once("".ROOT_PATH."/plugins/".$pluginname."/".$pluginroot."");
            else:
                $getpost="<iframe src=plugins/".$pluginname."/".$pluginroot." frameborder=0 id=external-frame></iframe><style>iframe{width:100%;margin:0 0 1em;border:0;}</style><script src=images/js/autoheight.js></script>";
                echo$getpost;
            endif;
        endif;
    }
    static function auth($str,$url,$api){
        $FromUrl=UsualToolCMS::curPageURL();
        $urls="".$url."?AuthCode=".$str."&FromUrl=".$FromUrl."&type=".$api."";
        $content=UsualToolCMS::httpget($urls);
        return str_replace("#","$",str_replace("&","=",UsualToolCMS::str_substr("<php>","</php>",$content)));
    }
    /*其他操作函数*/
    static function getrandomstring($len, $chars=null){
        if (is_null($chars)){
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        }  
        mt_srand(10000000*(double)microtime());
        for($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }
    static function getip(){
        $unknown = 'unknown';
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown) ){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown) ) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if (false !== strpos($ip, ','))
            $ip = reset(explode(',', $ip));
            return $ip;
    }
    static function isapp(){
        if(isset($_SERVER['HTTP_X_WAP_PROFILE'])){
            return true;
        }
        if(isset($_SERVER['HTTP_VIA'])){
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        if(isset($_SERVER['HTTP_USER_AGENT'])){
            $clientkeywords = array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile','MicroMessenger'); 
            if(preg_match("/(".implode('|', $clientkeywords).")/i",strtolower($_SERVER['HTTP_USER_AGENT']))){
                return true;
            } 
        }
        if(isset ($_SERVER['HTTP_ACCEPT'])) {
            if((strpos($_SERVER['HTTP_ACCEPT'],'vnd.wap.wml')!== false) && (strpos($_SERVER['HTTP_ACCEPT'],'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))){
            return true;
            } 
        } 
        return false;
    }
    static function forbytes($size) { 
        $units = array('B','KB','MB','GB','TB'); 
        for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024; 
        return round($size, 0).$units[$i]; 
    } 
    static function cutsubstr($str,$start=0,$length=0,$charset="utf-8",$suffix=true){
        if(function_exists("mb_substr"))
            return mb_substr($str, $start, $length, $charset);
        elseif(function_exists('iconv_substr')) {
            return iconv_substr($str,$start,$length,$charset);
        }
        $re['utf-8']   = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
        $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
        $re['gbk']    = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
        $re['big5']   = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
        if($suffix) return $slice."…";
        return $slice;
    }
    static function opendate($dates,$type){
        if($type==1):
            return date('y',$dates);
        elseif($type==2):
            return date('m',$dates);
        elseif($type==3):
            return date('d',$dates);
        endif;
    }
    static function preg_substr($start,$end,$str){
        $temp = preg_split($start, $str);
        $content = preg_split($end, $temp[1]);
        return $content[0];
    }
    static function str_substr($start,$end,$str){
        $temp = explode($start, $str, 2);
        $content = explode($end, $temp[1], 2);
        return $content[0];
    }
    static function subkey($str,$key,$len=100,$enc='utf-8'){
        $strlen = mb_strlen($str,$enc);
        $keylen = mb_strlen($key,$enc);
        $keypos = mb_strpos($str,$key,0,$enc);
        $leftpos = $keypos - 1;
        $rightpos = $keypos + $keylen;
        if($keylen > $len){
            return "<font color=red>".mb_substr($key,0,$len,$enc)."</font>...";
        }
        $result = "<font color=red>".$key."</font>";
        for($i = 0;$i<$len - $keylen;$i++){
            if($leftpos >= 0){
                $result = mb_substr($str,$leftpos--,1,$enc).$result;
            }else{
                $result .= mb_substr($str,$rightpos++,1,$enc);
            }
        }
        if($leftpos >= 0){
            $result = "...".$result;
        }
        if($rightpos < $strlen){
            $result .= "...";
        }
        return $result;
    }
    /*CURL操作函数*/
    static function httppost($url,$data){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if(!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    static function httpget($url,$timeout='10',$chart='1'){
        if(function_exists($curl_init)){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $info = curl_exec($ch);
            curl_close($ch);
            return $info;
        }
        else{
            $info=file_get_contents($url);
            if($chart==1):
                return $info;
            else:
                return mb_convert_encoding($info, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
            endif;
        }
    }
    static function httpcode($url){
        $ch = curl_init();
        $timeout =10;
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_exec($ch);
        return curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
    }
    /*文件地址操作函数*/
    static function curpageurl(){
        $pageURL = 'http';
        if(isset($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"])=="on"){
            $pageURL .= "s";
        }
        $pageURL .= "://";
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        return $pageURL;
    }
    static function getfile($url,$save_dir='',$filename='',$type=0){  
        if(trim($url) == ''){
            return false;
        }  
        if(trim($save_dir) == ''){
            $save_dir = './';
        }  
        if(0 !== strrpos($save_dir, '/')){
            $save_dir.= '/';
        }   
        if(!file_exists($save_dir) && !mkdir($save_dir, 0777, true)){
            return false;
        }    
        if($type){  
            $ch = curl_init();  
            $timeout = 5;  
            curl_setopt($ch, CURLOPT_URL, $url);  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);  
            $content = curl_exec($ch);  
            curl_close($ch);  
        } else {  
            ob_start();  
            readfile($url);  
            $content = ob_get_contents();  
            ob_end_clean();  
        }   
        $size = strlen($content);
        $fp2 = @fopen($save_dir . $filename, 'a');  
        fwrite($fp2, $content);  
        fclose($fp2);  
        unset($content, $url);  
        return array(  
        'file_name' => $filename,  
        'save_path' => $save_dir . $filename,  
        'file_size' => $size  
        );  
    }
    static function unlinkfile($aimUrl){
        if (file_exists($aimUrl)){
            unlink($aimUrl);
            return true;
        }else{
            return false;
        }
    }
    static function deldir($directory){
        if(file_exists($directory)){
            if($dir_handle = @opendir($directory)){
                while($filename = readdir($dir_handle)){
                    if($filename != "."&& $filename != ".."){
                        $subFile = $directory."/".$filename;
                        if(is_dir($subFile))
                        UsualToolCMS::deldir($subFile);
                        if(is_file($subFile)) 
                        unlink($subFile);
                    }
                }
                closedir($dir_handle);
                rmdir($directory);
                return 1;
            }
        }
    }
    static function searchdir($dir){
        if(is_dir($dir)){
            return 1;
        }
    }
    static function getsysteminfo(){
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
    function detect_encoding($file) {
        $list = array('GBK', 'UTF-8', 'UTF-16LE', 'UTF-16BE', 'ISO-8859-1');
        $str = file_get_contents($file);
        foreach ($list as $item) {
            $tmp = mb_convert_encoding($str, $item, $item);
            if (md5($tmp) == md5($str)) {
                return $item;
            }
        }
        return null;
    }
    static function makedir($dir,$mode = 0777){
        if(is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
        if(!mkdirs(dirname($dir), $mode)) return FALSE;
        return @mkdir($dir, $mode);
    }
    static function movedir($rootFrom,$rootTo){
        $handle=opendir($rootFrom);
        while(false!==($file = readdir($handle)) && $file!=="usualtoolcms.config"){
            $fileFrom=$rootFrom.DIRECTORY_SEPARATOR.$file;
            $fileTo=$rootTo.DIRECTORY_SEPARATOR.$file;
            if(strpos($fileFrom,"cmsadmin")===false){
                if($file=='.' || $file=='..'){continue;}
                if(is_dir($fileFrom)){
                    mkdir($fileTo,0777);
                    UsualToolCMS::movedir($fileFrom,$fileTo);
                }else{
                    @copy($fileFrom,$fileTo);
                }
            }
        }
    }
    static function editdir($oldpath,$newpath){
        $_path = iconv('utf-8', 'gb2312', $oldpath);
        $__path = iconv('utf-8', 'gb2312',$newpath);
        if(is_dir($_path)){
            if(file_exists($__path)==false){
                if (rename($_path, $__path)){
                    return true;
                }else{
                 return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    static function getdir($path){
        if(!is_dir($path)){
              return false;
        }
        $arr = array();
        $data = scandir($path);
        foreach ($data as $value){
              if($value != '.' && $value != '..'){
                  $arr[] = $value;
              }
        }
        return $arr;
    }
    /*图片数流操作函数*/
    function imgtobase64($img=''){
        $imageInfo = getimagesize($img);
        $base64 = "" . chunk_split(base64_encode(file_get_contents($img)));
        return 'data:' . $imageInfo['mime'] . ';base64,' . chunk_split(base64_encode(file_get_contents($img)));
    }
    function base64toimg($base64,$path){
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)){
            $type = $result[2];
            $newfile = $path."/".time().".{$type}";
            if (file_put_contents($newfile, base64_decode(str_replace($result[1], '', $base64)))){
                return str_replace("../","",$newfile);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
/*语言操作函数*/
function LangData($word,$type=''){
    global$language;
    if(!empty($type)):
        $langdata=json_decode(file_get_contents(WEB_PATH."/lang/lg-".$type.".json"),true);
    else:
        $langdata=json_decode(file_get_contents(WEB_PATH."/lang/lg-".$language.".json"),true);
    endif;
    if(array_key_exists($word,$langdata["l"])){
    $langword=$langdata["l"]["".$word.""];
        return $langword;
    }else{
        return $word;
    }
}
function ModLangData($word){
	global$language;
	global$modpath;
    $langdata=json_decode(file_get_contents(WEB_PATH."/".$modpath."/lang/lg-".$language.".json"),true);
    if(array_key_exists($word,$langdata["l"])){
        $langword=$langdata["l"]["".$word.""];
        return $langword;
    }else{
        return $word;
    }
}
function LangSet($word,$type=''){
    global$language;
    if(!empty($type)):
        $langdata=json_decode(file_get_contents(WEB_PATH."/lang/lg-".$type.".json"),true);
    else:
        $langdata=json_decode(file_get_contents(WEB_PATH."/lang/lg-".$language.".json"),true);
    endif;
    $langword=$langdata["s"]["".$word.""];
    return $langword;
}
function Lang($path = '../lang/'){
    $current_dir = opendir($path);
    while(($file = readdir($current_dir)) !== false) {
        if(UsualToolCMS::contain(".json",$file)!==false):
            $filename=explode(".json",$file);
            $lgfilename=str_replace("lg-","",str_replace($path,"",$filename[0]));
            $lgfile[]=array("speak"=>LangSet("speak",$lgfilename),"lgname"=>$lgfilename);
        endif;
    }
    return $lgfile;
}