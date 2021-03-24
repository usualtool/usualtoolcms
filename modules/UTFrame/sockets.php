<?php
require_once(dirname(dirname(dirname(__FILE__))).'/'.'sql_db.php');
if(UTSOCKETS=='1'):
    require_once(ROOT_PATH.'/'.'class/UsualToolCMS_Sockets.php');
    $socket=new Sockets(UTSOCKETS_HOST,UTSOCKETS_PORT);
endif;