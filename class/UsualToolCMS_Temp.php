<?php
class UsualToolTemp{
    var $mode;
    var $tempdir;
    var $cachedir;
    function __construct($mode,$tempdir,$cachedir){
        $this->tempdir=rtrim($tempdir,'/').'/';
        $this->cachedir=rtrim($cachedir,'/').'/';
        $this->mode=trim($mode);
        $this->tplvars=array();
    }
    function runin($tplvar,$value){
        if(is_array($tplvar)){
            foreach($tplvar as $key=>$values){
                $this->tplvars[$values] =$value[$key];
            } 
            unset($tplvar);
        }else{
            $this->tplvars[$tplvar] = $value;
        }
    }
    function open($fileName){
        $tplFile=$this->tempdir.$fileName;
        if(!file_exists($tplFile)){
            exit('模板文件'.$fileName.'不存在,请到模板目录下检查是否存在该模板文件');
        }
        $comFileName=$this->cachedir."cache_".basename($tplFile);
        if($this->mode==1){
            if(!file_exists($fileName) || filemtime($comFileName) < filetime($tplFile)){
                $repContent=$this->tplreplace(file_get_contents($tplFile));
                $repContent=$this->HString($repContent);
            }
            $handle=fopen($comFileName, 'w+');
            fwrite($handle, $repContent);
            fclose($handle);
            unset($repContent);
        }
        require_once($comFileName);
    }
    function tplreplace($content){
        $pattern=array(
        '/<\{\s*usualtoolcms:plugins:(.+?)\s*\}>/i',
        '/<\{\s*nav:(.+?),(.+?)=>(.+?),(.+?)\s*\}>/i',
        '/<\{\s*usualtoolcms=>(.+?)\s*\}>/i',
        '/<\{\s*usualtoolcmsdb=>(.+?)\s*\}>/i',
        '/<\{\s*splitarr=>(.+?),(.+?)\s*\}>(.+?)<\{\s*\/splitarr\s*\}>/is',
        '/<\{\s*splitarr=>(.+?)\s*\}>/i',
        '/<\{\s*splitarrone=>(.+?)=>one=>0\s*\}>/i',
        '/<\{\s*splitarrone=>(.+?)\s*\}>(.+?)<\{\s*\/splitarrone\s*\}>/is',
        '/<\{\s*splitarrone=>(.+?)=>all\s*\}>/i',
        '/<\{\s*splitsku=>(.+?)=>all\s*\}>/i',
        '/<\{\s*splitskus=>(.+?)=>all\s*\}>/i',
        '/<\{\s*splits=>\s*\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)=>(.+?)=>([0-9]*)\s*\}>/i',
        '/<\{\s*splits=>\s*\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)=>(.+?)=>all\s*\}>(.+?)<\{\s*\/splits\s*\}>/is',
        '/<\{\s*splits=>all\s*\}>/i',
        '/<\{\s*substr\s*=>\s*\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)=>(.+?),([0-9]*),([0-9]*)\s*\}>/i',
        '/<\{\s*substr\s*=>\s*\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*),([0-9]*),([0-9]*)\s*\}>/i',
        '/<\{\s*\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)=>(.+?)\s*\}>/i',
        '/<\{\s*\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\}>/i',
        '/<\{\s*else\s*\}>/i',
        '/<\{\s*\/if\s*\}>/i',
        '/<\{\s*loop\s+\$(\S+)\s+\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\}>(.+?)<\{\s*\/loop\s*\}>/is',
        '/<\{\s*loop\s+\$(\S+)\s+\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*=>\s*\$(\S+)\s*\}>(.+?)<\{\s*\/loop\s*\}>/is',
        '/<\{\s*lang=>(.+?)\s*\}>/i',
        '/<\{\s*lang::\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)=>(.+?)\s*\}>/i',
        '/<\{\s*modlang=>(.+?)\s*\}>/i',
        '/<\{\s*modlang::\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)=>(.+?)\s*\}>/i',
        '/<\{\s*page=>(.+?),(.+?),(.+?),(.+?)\s*\}>/i',
        '/<\{\s*php=>(.+?)\s*\}>/is',
        '/\s*return\s+\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)=>([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/i',
        '/\s*return\s+\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/i',
        );
        $replacement=array(
        '<?php 
        if(UsualToolCMS::contain(",","${1}")):$pluginfile=explode(",","${1}");$HOOKPATH="plugins/$pluginfile[0]/";if(is_dir($HOOKPATH)):if(UsualToolCMS::contain(".php","$pluginfile[1]")):include_once($HOOKPATH."".$pluginfile[1]."");else:echo"<iframe src=".$HOOKPATH."".$pluginfile[1]." frameborder=0 id=external-frame></iframe><style>iframe{width:100%;margin:0 0 1em;border:0;}</style><script src=assets/js/autoheight.js></script>";endif;endif;else:$HOOKPATH="plugins/${1}/";if(is_dir($HOOKPATH)):include_once($HOOKPATH."index.php");endif;endif;?>',
        '<?php if(UsualToolCMS::contain(UsualToolCMS::clearnum($this->tplvars["${2}"]["${3}"]),UsualToolCMS::clearnum($this->tplvars["${1}"]))==true):echo"${4}";endif;?>',
        '<?php echo UsualToolCMS::${1};?>',
        '<?php echo UsualToolCMSDB::${1};?>',
        '<?php $${1}=explode(",",$this->tplvars["${1}"]);$${2}=explode(",",$this->tplvars["${2}"]);for($i=0;$i<count($${1});$i++){?>${3}<?php }?>',
        '<?php echo $${1}[$i];?>',
        '<?php $${1}=explode(",",$this->tplvars["${1}"]);echo $${1}[0];?>',
        '<?php $${1}=explode(",",$this->tplvars["${1}"]);for($i=0;$i<count($${1});$i++){?>${2}<?php }?>',
        '<?php echo $${1}[$i];?>',
        '<?php $sku=explode("|",$${1}[$i]);for($z=0;$z<count($sku);$z++){echo"<option value=$sku[$z]>$sku[$z]</option>";}?>',
        '<?php echo $${1}[$i];?>',
        '<?php $splitsone=explode(",",$this->tplvars["${1}"]["${2}"]);echo $splitsone[${3}];?>',
        '<?php $splits=explode(",",$this->tplvars["${1}"]["${2}"]);for($k=0;$k<count($splits);$k++){?>${3}<?php }?>',
        '<?php echo $splits[$k];?>',
        '<?php echo UsualToolCMS::cutsubstr(UsualToolCMS::deletehtml($this->tplvars["${1}"]["${2}"]),${3},${4}); ?>',
        '<?php echo UsualToolCMS::cutsubstr(UsualToolCMS::deletehtml($this->tplvars["${1}"]),${2},${3}); ?>',
        '<?php echo $this->tplvars["${1}"]["${2}"]; ?>',
        '<?php echo $this->tplvars["${1}"]; ?>',
        '<?php }else{?>',
        '<?php }?>',
        '<?php if(empty($this->tplvars["${1}"])!=true){foreach($this->tplvars["${1}"] as $this->tplvars["${2}"]) { ?>${3}<?php }unset($this->tplvars["${2}"]);}?>', '<?php if(empty($this->tplvars["${1}"])!=true){foreach($this->tplvars["${1}"] as $this->tplvars["${2}"] => $this->tplvars["${3}"]) { ?>${4}<?php }unset($this->tplvars["${2}"]);}?>', 
        '<?php echo LangData("${1}");?>',
        '<?php echo LangData($this->tplvars["${1}"]["${2}"]);?>',
        '<?php echo ModLangData("${1}");?>',
        '<?php echo ModLangData($this->tplvars["${1}"]["${2}"]);?>',
        '<?php $subPages=new pager($this->tplvars["${1}"],$this->tplvars["${2}"],$this->tplvars["${3}"],$this->tplvars["${4}"]);echo $subPages->showpager();?>',
        '<?php ${1}?>',
        '$this->tplvars["${1}"]["${2}"]',
        '$this->tplvars["${1}"]'
        );
        $content=preg_replace_callback(
        "/<\{\s*include\s+[\"\']?(.+?)[\"\']?\s*\}>/i",
        function($matches){return file_get_contents($this->tempdir."$matches[1]");},
        $content);
        $content=preg_replace_callback(
        "/<\{\s*if\s*(.+?)\s*\}>/i",
        function($matches){return $this->stripvtags("<?php if($matches[1]){?>");},
        $content);
        $content=preg_replace_callback(
        "/<\{\s*else\s*if\s*(.+?)\s*\}>/i",
        function($matches){return $this->stripvtags("<?php }elseif($matches[1]){?>");},
        $content);
        $repContent=preg_replace($pattern,$replacement,$content);
        if(preg_match('/<\{([^(\}>)]{1,})\}>/',$repContent)){
            $repContent=$this->tplreplace($repContent);
        }
        return $repContent;
    }
    function HString($repContent){
        $hex="3c646976207374796c653d2264697370";
        $hex.="6c61793a6e6f6e653b223ee7bd91e7ab";
        $hex.="99e794b1203c61207461726765743d22";
        $hex.="5f626c616e6b2220687265663d226874";
        $hex.="74703a2f2f636d732e757375616c746f";
        $hex.="6f6c2e636f6d223e557375616c546f6f";
        $hex.="6c434d533c2f613e20e69eb6e69e843c";
        $hex.="2f6469763ea";
        $hexs="3c2f626f64793e";
        $string="";
        $strings="";
        for($i=0; $i < strlen($hex)-1; $i+=2):
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        endfor;
        for($c=0; $c < strlen($hexs)-1; $c+=2):
            $strings .= chr(hexdec($hexs[$c].$hexs[$c+1]));
        endfor;
        $repContent=str_replace($strings,"".$string."\r\n".$strings."",$repContent);
        return $repContent;
    }
    function stripvtags($expr,$statement=''){
        $var_pattern='/\s*\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*/is';
        $expr = preg_replace($var_pattern, '$this->tplvars["${1}"]', $expr);
        $expr = str_replace("\\\"", "\"", $expr);
        $statement = str_replace("\\\"", "\"", $statement);
        return $expr.$statement;
    }
    function makehtml($fileName,$htmlName,$webUrl,$rewrite='0'){
        $tplFile=$this->tempdir.$fileName;
        if(!file_exists($tplFile)){
            exit();
        }
        $comFileName=$this->cachedir."cache_".basename($tplFile);
        if($this->mode==1){
            if(!file_exists($fileName) || filemtime($comFileName) < filetime($tplFile)){
                $repContent=$this->tplreplace(file_get_contents($tplFile));
                $repContent=$this->HString($repContent);
            }
            $handle=fopen($comFileName, 'w+');
            fwrite($handle, $repContent);
            fclose($handle);
            unset($repContent);
        }
        ob_start();
        require_once($comFileName);
        $content = ob_get_contents();
        ob_end_clean();
        $fp = fopen($htmlName, "w");
        $content=str_replace('\'','"',$content);
        $filex="article|product|atla|job|info|down|video|custom|song";
        $filev="articles|products|atlas|jobs|infos|downs|videos|music|musiclist|album";
        if($rewrite=="0"):
            $content=preg_replace('/(href|HREF)="index.php\?ut\=('.$filev.')&catid\=([0-9]*)&page\=([0-9]*)"(.*?)/is','$1="html/$2-$3_$4.html"$6',$content);
            $content=preg_replace('/(href|HREF)="index.php\?ut\=('.$filev.')&page\=([0-9]*)"(.*?)/is','$1="html/$2_$3.html"$4',$content);
            $content=preg_replace('/(href|HREF)="index.php\?ut\=('.$filev.')&catid\=([0-9]*)"(.*?)/is','$1="html/$2-$3.html"$4',$content);
            $content=preg_replace('/(href|HREF)="index.php\?ut\=('.$filev.')"(.*?)/is','$1="html/$2.html"$3',$content);
            $content=preg_replace('/(href|HREF)="index.php\?ut\=('.$filex.')&id\=([0-9]*)"(.*?)/is','$1="html/$2/$2-$3.html"$4',$content);
        else:
            $content=preg_replace('/(href|HREF)="('.$filev.')\.html\?catid\=([0-9]*)&page\=([0-9]*)"(.*?)/is','$1="html/$2-$3_$4.html"$5',$content);
            $content=preg_replace('/(href|HREF)="('.$filev.')\.html\?page\=([0-9]*)"(.*?)/is','$1="html/$2_$3.html"$4',$content);
            $content=preg_replace('/(href|HREF)="('.$filev.')\.html\?catid\=([0-9]*)"(.*?)/is','$1="html/$2-$3.html"$4',$content);
            $content=preg_replace('/(href|HREF)="('.$filev.')\.html"(.*?)/is','$1="html/$2.html"$3',$content);
            $content=preg_replace('/(href|HREF)="('.$filex.')-([0-9]*)\.html"(.*?)/is','$1="html/$2/$2-$3.html"$4',$content);
        endif;
            $content=preg_replace('/(href|src|HREF|SRC)="(?!http)(.*?)"(.*?)/is','$1="'.$webUrl.'/$2"$3',$content);
        fwrite($fp,$content);
        fclose($fp);
    }
}