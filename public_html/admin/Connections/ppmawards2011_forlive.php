<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_ppmawards2011 = "mysql.clickatell.webcentric.co.za";
$database_ppmawards2011 = "ppmawards_db1";
$username_ppmawards2011 = "ppmawards_user1";
$password_ppmawards2011 = "arrow786";
$ppmawards2011 = mysql_pconnect($hostname_ppmawards2011, $username_ppmawards2011, $password_ppmawards2011) or trigger_error(mysql_error(),E_USER_ERROR); 
?>