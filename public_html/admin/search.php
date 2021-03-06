<?php require_once('Connections/ppmawards2011.php'); ?>
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

$colname_pod = "-1";
if (isset($_POST['crn'])) {
  $colname_pod = $_POST['crn'];
}
mysql_select_db($database_ppmawards2011, $ppmawards2011);
$query_pod = sprintf("SELECT * FROM submits WHERE firstName = %s ORDER BY id DESC", GetSQLValueString($colname_pod, "text"));
$pod = mysql_query($query_pod, $ppmawards2011) or die(mysql_error());
$row_pod = mysql_fetch_assoc($pod);
$totalRows_pod = mysql_num_rows($pod);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link href="css/lightbox.css" rel="stylesheet" />
    
    <!-- Page Specific CSS -->
    <link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.4.3.min.css">
    <script src="js/jquery-1.10.2.min.js"></script>
<script src="js/lightbox-2.6.min.js"></script>

    <script language="javascript">
	function makesure() {
  if (confirm('Are you sure you want to delete this Proof of Delivery?')) {
     dosomething();
    //or return true;
  }
  else {
    return false;
  }
}
	</script>
  </head>

  <body>

    <div id="wrapper">

      <!-- Sidebar -->
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <img src="assets/img/logo-final.png" width="250" height="41"><a class="navbar-brand" href="dashboard.php">Damco POD Portal</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <?php require_once('includes/nav.php'); ?>
 <?php require_once('includes/top_nav.php'); ?>

        </div><!-- /.navbar-collapse -->
      </nav>

      <div id="page-wrapper">

        <div class="row">
          <div class="col-lg-12">
            <h1>Dashboard <small>Search</small></h1>
            <ol class="breadcrumb">
              <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
            </ol>
           
          </div>
        </div><!-- /.row -->

        <div class="row"></div><!-- /.row -->

        <div class="row">
          <div class="col-lg-12">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i>Proof of Deliveries</h3>
              </div>
              <? if ($row_pod['firstName'] == true ) {?>
              <table class="table table-bordered table-hover table-striped tablesorter">
                <thead>
                  <tr>
                    <th width="16%">CR # <i class="fa fa-sort"></i></th>
                    <th width="36%"> Date <i class="fa fa-sort"></i></th>
                    <th width="33%">Download<i class="fa fa-sort"></i></th>
                    <th width="8%">Edit<i class="fa fa-sort"></i></th>
                    <th width="7%">Delete</th>
                  </tr>
                </thead>
                <tbody>
                  <?php do { ?>
  <tr>
    <td><?php echo $row_pod['firstName']; ?></td>
    <td><?php echo $row_pod['surName']; ?></td>
    <td><a href="uploads/<?php echo $row_pod['logoFile']; ?>" target="_blank">Download</a> | <a href="uploads/<?php echo $row_pod['logoFile']; ?>" data-lightbox="<?php echo $row_pod['firstName']; ?>" title="<?php echo $row_pod['firstName']; ?>">Preview</a></td>
    <td><a href="edit-crn.php?id=<?php echo $row_pod['id']; ?>"><span class="label label-success">EDIT</span></a></td>
    <td><a href="delete.php?id=<?php echo $row_pod['id']; ?>" onclick="return makesure();"><span class="label label-danger">DELETE</span></a></td>
  </tr>
  <?php } while ($row_pod = mysql_fetch_assoc($pod)); ?>
                </tbody>
              </table>
              <? } else {
	echo "<span style=color:red>No results were found<span>";	
	
}?>
            </div>
          </div>
        </div><!-- /.row -->

        <div class="row"></div><!-- /.row -->

      </div><!-- /#page-wrapper -->

    </div><!-- /#wrapper -->

    <!-- Bootstrap core JavaScript -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <!-- Page Specific Plugins -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="http://cdn.oesmith.co.uk/morris-0.4.3.min.js"></script>
    <script src="js/morris/chart-data-morris.js"></script>
    <script src="js/tablesorter/jquery.tablesorter.js"></script>
    <script src="js/tablesorter/tables.js"></script>
  </body>
</html>
<?php
mysql_free_result($pod);
?>
