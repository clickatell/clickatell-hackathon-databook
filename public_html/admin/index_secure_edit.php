<?php require_once('Connections/ppmawards2011.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index_fail.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE submits SET approvedStatus=%s WHERE id=%s",
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_ppmawards2011, $ppmawards2011);
  $Result1 = mysql_query($updateSQL, $ppmawards2011) or die(mysql_error());

  $updateGoTo = "index_secure.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsGetlist = "-1";
if (isset($_GET['id'])) {
  $colname_rsGetlist = $_GET['id'];
}
mysql_select_db($database_ppmawards2011, $ppmawards2011);
$query_rsGetlist = sprintf("SELECT * FROM submits WHERE id = %s", GetSQLValueString($colname_rsGetlist, "int"));
$rsGetlist = mysql_query($query_rsGetlist, $ppmawards2011) or die(mysql_error());
$row_rsGetlist = mysql_fetch_assoc($rsGetlist);
$totalRows_rsGetlist = mysql_num_rows($rsGetlist);

$colname_rsGetlist = "-1";
if (isset($_GET['id'])) {
  $colname_rsGetlist = $_GET['id'];
}
mysql_select_db($database_ppmawards2011, $ppmawards2011);
$query_rsGetlist = sprintf("SELECT * FROM submits WHERE id = %s", GetSQLValueString($colname_rsGetlist, "int"));
$rsGetlist = mysql_query($query_rsGetlist, $ppmawards2011) or die(mysql_error());
$row_rsGetlist = mysql_fetch_assoc($rsGetlist);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin Template - Login</title>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
<link href="styles/login.css" rel="stylesheet" type="text/css" />
<!-- Theme Start -->
<link href="themes/blue/styles.css" rel="stylesheet" type="text/css" />
<!-- Theme End -->

</head>
<body>
<br />
<br />
<div id="table2">
  <h2>PPM Awards Submit List</h2><p><a href="<?php echo $logoutAction ?>" style="color:#000;">Log Out</a> - <a style="color:#000;" href="index_secure.php">Home</a></p>
  <p>&nbsp;</p><br />
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="500px" border="0" cellpadding="5">
    <tr id="header2">
      <td>Company Name</td>
      <td>Current Status</td>
      <td>Change Status</td>
    </tr>
    <tr id="header3">
      <td><span id="header3"><?php echo $row_rsGetlist['companyName']; ?></span></td>
      <td><span id="header3"><?php echo $row_rsGetlist['approvedStatus']; ?></span></td>
      <td>
        <span id="header3">
        <select name="status" size="1" id="status">
          <option value='<?php echo $row_rsGetlist['approvedStatus']; ?>' selected="selected" rel='<?php echo $row_rsGetlist['approvedStatus']; ?>'>>>Current>> <?php echo $row_rsGetlist['approvedStatus']; ?></option>
          <option value='NOT-Approved' rel='NOT-Approved' style="color:#F00">NOT-Approved</option>
          <option value='Approved' rel='Approved' style="color: #093">Approved</option>
        </select>
        </span></td>
    </tr>
    <tr id="header3">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><span id="header3">
        <input type="submit" name="Update" id="Update" value="UPDATE" />
        <input type="hidden" name="id" value="<?php echo $row_rsGetlist['id']; ?>" id="hiddenField" />
      </span></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
</form>
  <p>&nbsp;</p>
<br /></div>
<div id="table2"></div>
</body>
</html>
<?php
mysql_free_result($rsGetlist);
?>
