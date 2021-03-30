<?php
require_once(dirname(dirname(dirname(__FILE__))).'/'.'sql_db.php');
$socket=new Sockets(UTSOCKETS_HOST,UTSOCKETS_PORT);