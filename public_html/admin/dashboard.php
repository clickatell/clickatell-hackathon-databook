<?php
if (!isset($_SESSION))
{
    session_start();
}

if(empty($_SESSION['key']))
{
    header('Location:index.php');
    exit();
}
else
{
    $key=$_SESSION['key'];
}
$header = "<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>

    <title>Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link href='css/bootstrap.css' rel='stylesheet'>

    <!-- Add custom CSS here -->
    <link href='css/sb-admin.css' rel='stylesheet'>
    <link rel='stylesheet' href='font-awesome/css/font-awesome.min.css'>
    <link href='css/lightbox.css' rel='stylesheet' />

    <!-- Page Specific CSS -->
    <link rel='stylesheet' href='http://cdn.oesmith.co.uk/morris-0.4.3.min.css'>
    <script src='js/jquery-1.10.2.min.js'></script>
<script src='js/lightbox-2.6.min.js'></script>


  </head>

  <body>
<div id='wrapper'>
<nav class='navbar navbar-inverse navbar-fixed-top' role='navigation'>
          <img src='img/logo-final.png' width='250' height='41'><a class='navbar-brand' href='dashboard.php'></a>
        </div>

";

$footer = "  </div>


    <script src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
    <script src='js/bootstrap.js'></script>
    <script src='//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js'></script>
    <script src='http://cdn.oesmith.co.uk/morris-0.4.3.min.js'></script>
    <script src='js/morris/chart-data-morris.js'></script>
    <script src='js/tablesorter/jquery.tablesorter.js'></script>
    <script src='js/tablesorter/tables.js'></script>
  </body>
</html>";

$fieldlist = array( 'id'
                    ,'firstname'
                    ,'surname'
                    ,'title'
                    ,'birthdate'
                    ,'mobile'
                    ,'email'
                    ,'gender'
                    ,'profession'
                    ,'location'
                    ,'tags'
                    ,'custom1'
                    ,'custom2'
                    ,'custom3');

echo $header;
echo
    "<div class='row'>
      <div class='col-md-6 col-md-offset-1'><p>&nbsp;</p>
      <div class='well'>
        <form class='form-horizontal' action='http://databook-api.co.za/admin/list.php'>
        <fieldset>

        <!-- Form Name -->
        <legend>Search for Contacts</legend>

        <!-- Text input-->
        <div class='control-group'>
          <label class='control-label' for='key'>Token</label>
          <div class='controls'>
            <input id='key' name='key' type='text' value='".$key."' class='form-control'>
            <p class='help-block'>Databook Service Token</p>
          </div>
          <div class='controls'>
            <label><input type='checkbox' name='exact'> Exact Match</label>

        </div>
        </div>";

        echo "<div class='control-group'>";

        foreach ($fieldlist as $field) {
            $label=$field;
            if($label=='custom1'){$label='team';}
            if($label=='custom2'){$label='project';}
            if($label=='custom3'){$label='twitter';}
            $label=ucfirst($label);
            echo"<div class='controls'>
                <div class='col-md-3'>
                <label class='control-label' for='search'>$label</label></div>
                <div class='col-md-7'>
                <input id='".$field."' name='".$field."' type='text' placeholder='' class='form-control'></div>
              <p/>
              </div>
              ";
        }

        echo "</div>
        </div>";

        echo "<!-- Button -->
        <div class='control-group'>
          <div class='controls' >
            <button class='btn btn-primary' style='float:right'>Get Contacts</button>
          </div>
        </div>

        </fieldset>
        </form>
        </div>
      </div>
    </div>";

echo $footer;
