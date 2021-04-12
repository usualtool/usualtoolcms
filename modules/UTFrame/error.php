<?php
if($mode==1):
    echo"UTCMS ERROR : Invalid Module.Access denied.<br>";
    echo"No page was found : ".$ut.".php.<br>";
    echo"Current Path : ".$wwwpath.".<br>";
    echo"Error Manual : <a target='_blank' href='http://b.usualtool.com'>http://b.usualtool.com</a> or <a target='_blank' href='http://cms.usualtool.com/doc'>http://cms.usualtool.com/doc</a>.<br>";
    echo"Back to Homepage : <a href='".$weburl."'>".$weburl."</a>";
else:
    echo"404 Error.<br>";
    echo"Homepage : <a href='".$weburl."'>".$weburl."</a>.";
endif;
?>