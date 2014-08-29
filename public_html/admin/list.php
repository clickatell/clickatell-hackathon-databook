<?php
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

$footer = "</div>


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

    $request = "http://databook-api.co.za/list.php?key=".htmlspecialchars($_GET['key']);

    foreach ($fieldlist as $field) {
       if (htmlspecialchars($_GET[$field]) != '')
        $request .= "&".$field."=".htmlspecialchars($_GET[$field]);
    }

    if (htmlspecialchars($_GET['exact']) == 'on')
       $request .= "&exact=true";

    $response_json = file_get_contents($request);
    $response = json_decode($response_json, true);
    echo $header;

    if ($response['error'] != '')
        echo $response['error'];
    else {
        echo "<body>
        <div align='center' style='height: 100px;width= 500px;padding-top:40px;'><div class='row'><form method='POST' action='sendsms.php' class='form-inline'><input type='text' name='msg' value=''' height='200px'>
<input type='hidden' name='rqst' value='".$_SERVER['QUERY_STRING']."'>
            <button class='btn btn-primary'>Send Message</button>

        </form></div></div>
              <div class='row'>
                <div class='col-md-10 col-md-offset-1'>
                <div class='well'>
                <table class='table table-striped table-bordered table-condensed'>
                <thead>
                <tr>";
        foreach ($fieldlist as $key => $value) {

            if($value=='custom1'){$value='team';}
            if($value=='custom2'){$value='project';}
            if($value=='custom3'){$value='twitter';}
            echo "<th>".ucfirst($value)."</th>";


        }
        echo "  </tr>
                </thead>
                <tbody>";
        for ($i = 0; $i < count($response['data']); $i++) {
            echo "<tr>";
            foreach ($fieldlist as $field) {
                $found = 0;
                foreach ($response['data'][$i] as $key => $value) {

                    if ($field == $key) {
                        echo "<td>".$value."</td>";
                        $found = 1;
                    }
                }
                if ($found == 0)
                    echo "<td>&nbsp;</td>";
            }
            echo "</tr>";
        }
        "</tbody>
        </table>";
    }
    echo $footer;

?>