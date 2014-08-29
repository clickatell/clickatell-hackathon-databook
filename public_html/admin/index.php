<?php


/*
require_once('Connections/ppmawards2011.php');

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_ppmawards2011, $ppmawards2011);
$query_Recordset1 = "SELECT * FROM adminlist";
$Recordset1 = mysql_query($query_Recordset1, $ppmawards2011) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['user'])) {
  $loginUsername=$_POST['user'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "dashboard.php";
  $MM_redirectLoginFailed = "index_fail.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_ppmawards2011, $ppmawards2011);

  $LoginRS__query=sprintf("SELECT `user`, password FROM adminlist WHERE `user`=%s AND password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text"));

  $LoginRS = mysql_query($LoginRS__query, $ppmawards2011) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";

	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
*/
//short version
require_once("../lib/kontrol.php");
if(!empty($_GET['msg']))
{
    $msg='<p style="color: #F00;">'.$_GET['msg'].'</p>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Databook - Login</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/login.css" rel="stylesheet" type="text/css" />
<!-- Theme Start -->
<link href="themes/blue/styles.css" rel="stylesheet" type="text/css" />
<!-- Theme End -->

</head>
<body>
	<div id="logincontainer">
    	<div id="loginbox">
        	<!--<div id="loginheader">
            	<img src="themes/blue/img/cp_logo_login.png" alt="Control Panel Login" />
            </div>-->
            <div id="innerlogin">
            	<form METHOD="POST" id="loginForm" action="">
                	<p><br />
               	    <img src="../assets/img/logo-final.png" width="250" height="41" /><br />
<br />
               	    Enter your username:</p>
                	<input name="user" type="text" class="logininput" id="user" />
                  <p>Enter your password:</p>
                	<input name="password" type="password" class="logininput" id="password" />

               	  <input type="submit" class="loginbtn" value="Login" /><br />
                    <?php echo($msg); ?>
                </form>
            </div>
        </div>
        <img src="img/login_fade.png" alt="Fade" />
    </div>
</body>
</html>
