<?php  require_once('Connections/ppmawards2011.php'); ?>
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
<?php require_once('ScriptLibrary/incPureUpload.php'); ?>
<?php
// Pure PHP Upload 2.1.10
$ppu = new pureFileUpload();
$ppu->path = "../uploads";
$ppu->extensions = "";
$ppu->formName = "theform";
$ppu->storeType = "file";
$ppu->sizeLimit = "";
$ppu->nameConflict = "uniq";
$ppu->requireUpload = "true";
$ppu->minWidth = "";
$ppu->minHeight = "";
$ppu->maxWidth = "";
$ppu->maxHeight = "";
$ppu->saveWidth = "";
$ppu->saveHeight = "";
$ppu->timeout = "600";
$ppu->progressBar = "";
$ppu->progressWidth = "300";
$ppu->progressHeight = "100";
$ppu->redirectURL = "thankyou_pop.php";
$ppu->checkVersion("2.1.10");
$ppu->doUpload();

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO submits (firstName, surName, logoFile) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['crn'], "text"),
                       GetSQLValueString($_POST['date'], "text"),
                       GetSQLValueString($_GET['id'], "text"));

  mysql_select_db($database_ppmawards2011, $ppmawards2011);
  $Result1 = mysql_query($insertSQL, $ppmawards2011) or die(mysql_error());

  $insertGoTo = "index_secure.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE submits SET firstName=%s, logoFile=%s WHERE id=%s",
                       GetSQLValueString($_POST['crn'], "text"),
                       GetSQLValueString($_POST['crnscan'], "text"),
                       GetSQLValueString($_POST['crn'], "int"));

  mysql_select_db($database_ppmawards2011, $ppmawards2011);
  $Result1 = mysql_query($updateSQL, $ppmawards2011) or die(mysql_error());

  $updateGoTo = "index_secure.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Recordset1 = "-1";
if (isset($_GET['id'])) {
  $colname_Recordset1 = $_GET['id'];
}
mysql_select_db($database_ppmawards2011, $ppmawards2011);
$query_Recordset1 = sprintf("SELECT * FROM submits WHERE id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $ppmawards2011) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data" >
  <table width="500" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td><h1>Edit CR Number</h1></td>
      <td><h4><?php echo $row_Recordset1['firstName']; ?></h4></td>
    </tr>
    <tr>
      <td width="250">CR number:</td>
      <td width="250"><label for="crn"></label>
      <input name="crn" type="text" id="crn" value="<?php echo $row_Recordset1['firstName']; ?>" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Upload Scanned doc:</td>
      <td><input name="crnscan" type="file" class="logo_upload" id="crnscan" onchange="checkOneFileUpload(this,'',true,'','','','','','','')" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><input name="date" type="hidden" id="date" value="<? echo date("F j, Y, g:i a"); ?>" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><input type="submit" name="button" id="button" value="Add Document" /></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
  <input type="hidden" name="MM_update" value="form1" />
</form>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
