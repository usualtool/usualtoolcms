<?php
define('ROOT_PATH',dirname(__FILE__));
define('REWRITE',0);
define('UTREDIS',0);
define('UTREDIS_HOST','127.0.0.1');
define('UTREDIS_PORT',6379);
define('UTREDIS_PASS','UT');
define('UTREDIS_TIME',3600);
define('UTSOCKETS',0);
define('UTSOCKETS_HOST','127.0.0.1');
define('UTSOCKETS_PORT',8080);
$dbhost="localhost";
$dbname="";
$dbuser="";
$dbpass="";
$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
$mysqli->set_charset('utf8');
if(UTREDIS=='1'):
    $redis=new Redis();
    $redis->connect(UTREDIS_HOST,UTREDIS_PORT);
    if(UTREDIS_PASS!='UT'):
        $redis->auth(UTREDIS_PASS);
    endif;
endif;
if(UTSOCKETS=='1'):
    require_once(ROOT_PATH.'/'.'class/UsualToolCMS_Sockets.php');
    $socket=new Sockets(UTSOCKETS_HOST,UTSOCKETS_PORT);
endif;