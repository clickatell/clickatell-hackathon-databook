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

mysql_select_db($database_ppmawards2011, $ppmawards2011);
$query_rsSubmits = "SELECT * FROM submits ORDER BY id ASC";
$rsSubmits = mysql_query($query_rsSubmits, $ppmawards2011) or die(mysql_error());
$row_rsSubmits = mysql_fetch_assoc($rsSubmits);
$totalRows_rsSubmits = mysql_num_rows($rsSubmits);
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
  <h2>CRN Submissions &gt; <a href="add.php">Add new</a></h2><p><a style="color:#000;" href="<?php echo $logoutAction ?>">Log Out</a></p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <form id="form1" name="form1" method="post" action="search.php">
    <strong>Search: 
    <label for="crn"></label>
    <input name="crn" type="text" id="crn" value="crn" />
    </strong>
    <input type="submit" name="button" id="button" value="Search" />
  </form>
<br /></div>
<div id="table2">
<table width="95%" border="0" cellpadding="5">
  <tr id="header2">
    <td width="16%" id="header2">CR Number</td>
    <td width="17%">Date</td>
    <td width="9%">Download</td>
     <!--<td width="19%">CR SCAN</td>
   <td width="29%">Approve Status</td>-->
    <td width="10%">Edit Status</td>
    <td width="10%">Delete</td>
  </tr>
  <?php do { ?>
  <tr id="header3">
    <td valign="top"><?php echo $row_rsSubmits['firstName']; ?></td>
    <td valign="top"><?php echo $row_rsSubmits['surName']; ?></td>
    <td valign="top"><a href="../uploads/<?php echo $row_rsSubmits['logoFile']; ?>" target="_blank">Download</a></td>
    <!--<td valign="top"><img src="../uploads/<?php echo $row_rsSubmits['logoFile']; ?>" width="150" /></td>
    <td valign="top" style="text-align:center;"><?php if($row_rsSubmits ['approvedStatus'] == "Approved"): ?>
      
      Approved <img src="img/icons/icon_ticklist.png" width="16" height="16" alt="Approved" />
  <?php else: ?>
      NOT Approved <img src="img/icons/icon_cross_sml.png" width="16" height="16" alt="Approved" /><?php endif; ?>
      
      
    </td>-->
 	<td valign="top"><a href="edit.php?id=<?php echo $row_rsSubmits['id']; ?>">Edit Status</a></td>
 	<td valign="top"><a href="delete.php?id=<?php echo $row_rsSubmits['id']; ?>">Delete</a></td>
  </tr>
  <?php } while ($row_rsSubmits = mysql_fetch_assoc($rsSubmits)); ?>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($rsSubmits);
?>
