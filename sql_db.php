<?php
define('ROOT_PATH',dirname(__FILE__));
define('REWRITE',0);
define('UTREDIS',0);
define('UTREDIS_HOST','127.0.0.1');
define('UTREDIS_PORT',6379);
define('UTREDIS_PASS','UT');
define('UTREDIS_TIME',3600);
$dbhost="localhost";//Host IP
$dbname="";//Database Name
$dbuser="";//Database User
$dbpass="";//Database Password
$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
$mysqli->set_charset('utf8');
if(UTREDIS=='1'):
    $redis=new Redis();
    $redis->connect(UTREDIS_HOST,UTREDIS_PORT);
    if(UTREDIS_PASS!='UT'):
        $redis->auth(UTREDIS_PASS);
    endif;
endif;