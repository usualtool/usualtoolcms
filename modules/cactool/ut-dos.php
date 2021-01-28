<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
require_once(dirname(dirname(dirname(__FILE__))).'/'.'conn.php');
if(isset($_SESSION['usualtooladmin'])&&isset($_SESSION['usualtooladminid'])&&!empty($_SESSION['usualtooladmin'])&&!empty($_SESSION['usualtooladminid'])):
    $o=str_replace("%26","",str_replace("%7C","",str_replace("&","",str_replace("|","",$_POST["o"]))));
    if(substr($o,0,3)=="php" || substr($o,0,8)=="composer"):
        $results = shell_exec($o);
        echo"<p>[".$_SESSION['usualtooladmin']."@localhost ~]#".$o." run complete.</p><p>".$results."</p>";
    else:
        echo"<p>[".$_SESSION['usualtooladmin']."@localhost ~]#Command not supported.</p>";
    endif;
else:
    echo"<p>[".$_SESSION['usualtooladmin']."@localhost ~]#UT administrator account not logged in.</p>";
endif;