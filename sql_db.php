<?php
defined('ROOT_PATH') or define('ROOT_PATH',dirname(__FILE__));
defined('REWRITE') or define('REWRITE',0);
defined('UTREDIS') or define('UTREDIS',0);
defined('UTREDIS_HOST') or define('UTREDIS_HOST','127.0.0.1');
defined('UTREDIS_PORT') or define('UTREDIS_PORT',6379);
defined('UTREDIS_PASS') or define('UTREDIS_PASS','UT');
defined('UTREDIS_TIME') or define('UTREDIS_TIME',3600);
defined('UTSOCKETS') or define('UTSOCKETS',0);
defined('UTSOCKETS_HOST') or define('UTSOCKETS_HOST','127.0.0.1');
defined('UTSOCKETS_WSIP') or define('UTSOCKETS_WSIP','127.0.0.1');
defined('UTSOCKETS_PORT') or define('UTSOCKETS_PORT',8080);
$dbhost="localhost";
$dbuser="";
$dbpass="";
$dbname="";
if(!empty($dbuser) && !empty($dbpass) && !empty($dbname)):
    $mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
    $mysqli->set_charset('utf8');
endif;
if(UTREDIS=='1'):
    $redis=new Redis();
    $redis->connect(UTREDIS_HOST,UTREDIS_PORT);
    if(UTREDIS_PASS!='UT'):
        $redis->auth(UTREDIS_PASS);
    endif;
endif;
if(UTSOCKETS=='1'):
    require_once(ROOT_PATH.'/'.'class/UsualToolCMS_Sockets.php');
endif;