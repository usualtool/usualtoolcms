<?php 
class pager{ 
    var $each_disNums;//每页显示数 
    var $nums;//总数 
    var $current_page;//当前页 
    var $sub_pages;//每次显示的页数 
    var $pageNums;//总页数 
    var $page_array = array();//用来构造分页的数组 
    var $subPage_link;//每个分页的链接 
    var $subPage_type;//显示分页的类型 
    var $_lang = array( 
    'index_page' => '<utcms data-localize="l.firstpage">首页</utcms>', 
    'pre_page' => '<utcms data-localize="l.previouspage">上一页</utcms>', 
    'next_page' => '<utcms data-localize="l.nextpage">下一页</utcms>', 
    'last_page' => '<utcms data-localize="l.lastpage">尾页</utcms>', 
    'current_page' => '', 
    'total_page' => '<utcms data-localize="l.totalpage">总页数</utcms>:', 
    'current_show' => '<utcms data-localize="l.currentpage">当前显示</utcms>:', 
    'total_record' => '<utcms data-localize="l.totalnum">总记录数</utcms>:'
    ); 
    function __construct($total_page,$current_page,$sub_pages=10,$subPage_link='',$subPage_type=2){ 
        $this->pager($total_page,$current_page,$sub_pages,$subPage_link,$subPage_type); 
    }   
    function pager($total_page,$current_page,$sub_pages=10,$subPage_link='',$subPage_type=2){ 
        if(!$current_page){ 
            $this->current_page=1; 
        }else{ 
            $this->current_page=intval($current_page); 
        } 
        $this->sub_pages=intval($sub_pages); 
        $this->pageNums=ceil($total_page); 
        if($subPage_link){ 
            if(strpos($subPage_link,'?page=') === false AND strpos($subPage_link,'&page=') === false){ 
                if(substr($subPage_link, -1)=="?" || substr($subPage_link, -1)=="&"){
                    $subPage_link .="page=";}
                else{
                    $subPage_link .= (strpos($subPage_link,'?') === false ? '?' : '&') . 'page='; 
                }
            } 
        } 
        $this->subPage_link=$subPage_link ? $subPage_link : $_SERVER['PHP_SELF'] . '?page='; 
        $this->subPage_type = $subPage_type; 
    } 
    function showpager(){ 
        if($this->subPage_type == 1){ 
            return $this->pagelist1(); 
        }elseif ($this->subPage_type == 2){ 
            return $this->pagelist2(); 
        } 
    }
    function initArray(){ 
        for($i=0;$i<$this->sub_pages;$i++){ 
            $this->page_array[$i]=$i; 
        } 
        return $this->page_array; 
    } 
    function construct_num_Page(){ 
        if($this->pageNums < $this->sub_pages){ 
            $current_array=array(); 
            for($i=0;$i<$this->pageNums;$i++){ 
                $current_array[$i]=$i+1; 
            } 
        }else{ 
            $current_array=$this->initArray(); 
            if($this->current_page <= 3){ 
                for($i=0;$i<count($current_array);$i++){ 
                    $current_array[$i]=$i+1; 
                } 
            }elseif ($this->current_page <= $this->pageNums && $this->current_page > $this->pageNums - $this->sub_pages + 1 ){ 
                for($i=0;$i<count($current_array);$i++){ 
                    $current_array[$i]=($this->pageNums)-($this->sub_pages)+1+$i; 
                } 
            }else{ 
                for($i=0;$i<count($current_array);$i++){ 
                    $current_array[$i]=$this->current_page-2+$i; 
                } 
            } 
        } 
        return $current_array; 
    } 
    function pagelist1(){ 
        $subPageCss1Str=""; 
        $subPageCss1Str.= $this->_lang['current_page'] . $this->current_page." / " .$this->pageNums."   "; 
        if($this->current_page > 1){ 
            $firstPageUrl=$this->subPage_link."1"; 
            $prewPageUrl=$this->subPage_link.($this->current_page-1); 
            $subPageCss1Str.="<a href='$firstPageUrl'>{$this->_lang['index_page']}</a> "; 
            $subPageCss1Str.="<a href='$prewPageUrl'>{$this->_lang['pre_page']}</a> "; 
        }else { 
            $subPageCss1Str.="{$this->_lang['index_page']} "; 
            $subPageCss1Str.="{$this->_lang['pre_page']} "; 
        }   
        if($this->current_page < $this->pageNums){ 
            $lastPageUrl=$this->subPage_link.$this->pageNums; 
            $nextPageUrl=$this->subPage_link.($this->current_page+1); 
            $subPageCss1Str.=" <a href='$nextPageUrl'>{$this->_lang['next_page']}</a> "; 
            $subPageCss1Str.="<a href='$lastPageUrl'>{$this->_lang['last_page']}</a> "; 
        }else { 
            $subPageCss1Str.="{$this->_lang['next_page']} "; 
            $subPageCss1Str.="{$this->_lang['last_page']} "; 
        } 
        return $subPageCss1Str; 
    } 
    function pagelist2(){ 
    $subPageCss2Str=""; 
    $subPageCss2Str.=$this->_lang['current_page'] . $this->current_page."/" . $this->pageNums." "; 
    if($this->current_page > 1){ 
        $firstPageUrl=$this->subPage_link."1"; 
        $prewPageUrl=$this->subPage_link.($this->current_page-1); 
        $subPageCss2Str.="<a href='$firstPageUrl'>{$this->_lang['index_page']}</a> "; 
        $subPageCss2Str.="<a href='$prewPageUrl'>{$this->_lang['pre_page']}</a> "; 
    }else { 
        $subPageCss2Str.="{$this->_lang['index_page']} "; 
        $subPageCss2Str.="{$this->_lang['pre_page']} "; 
    } 
    $a=$this->construct_num_Page(); 
    for($i=0;$i<count($a);$i++){ 
        $s=$a[$i]; 
        if($s == $this->current_page){ 
            $subPageCss2Str.="[<span style='color:red;font-weight:bold;'>".$s."</span>]"; 
        }else{ 
            $url=$this->subPage_link.$s; 
            $subPageCss2Str.="[<a href='$url'>".$s."</a>]"; 
        } 
    } 
    if($this->current_page < $this->pageNums){ 
        $lastPageUrl=$this->subPage_link.$this->pageNums; 
        $nextPageUrl=$this->subPage_link.($this->current_page+1); 
        $subPageCss2Str.=" <a href='$nextPageUrl'>{$this->_lang['next_page']}</a> "; 
        $subPageCss2Str.="<a href='$lastPageUrl'>{$this->_lang['last_page']}</a> "; 
    }else { 
        $subPageCss2Str.="{$this->_lang['next_page']} "; 
        $subPageCss2Str.="{$this->_lang['last_page']} "; 
    } 
    return $subPageCss2Str; 
    } 
    function __destruct(){ 
    unset($each_disNums); 
    unset($nums); 
    unset($current_page); 
    unset($sub_pages); 
    unset($pageNums); 
    unset($page_array); 
    unset($subPage_link); 
    unset($subPage_type); 
    } 
}