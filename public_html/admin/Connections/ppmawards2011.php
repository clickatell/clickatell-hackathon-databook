<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_ppmawards2011 = "localhost";
$database_ppmawards2011 = "linnett1_damco";
$username_ppmawards2011 = "linnett1_damco";
$password_ppmawards2011 = "L3sl3yL2";
$ppmawards2011 = mysql_pconnect($hostname_ppmawards2011, $username_ppmawards2011, $password_ppmawards2011) or trigger_error(mysql_error(),E_USER_ERROR); 
?>