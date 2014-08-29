<?php
    //include the API Builder mini lib
     require_once("lib/class.API.inc.php");

      //If API parameters were included in the http request via $_GET...
    if(isset($_GET) && !empty($_GET))
    {

        //specify the columns that will be output by the api
        $columns = "id,firstname,surname,title,birthdate,mobile,email,gender,profession,location,tags,custom1,custom2,custom3";

        //setup the API
        //the API constructor takes parameters in this order: host, database, table, username, password
        $api = new API("localhost", "databook_db", "contact", "databook_db", "");
        $api->setup($columns);
        $api->set_default_order("surname");
        $api->set_pretty_print(true);
        $api->set_key_required(true);
        $api->set_exclude_allowed(true);

        $get_array = Database::clean($_GET);

        //output the results of the http request
        $data=$api->get_json_from_assoc($get_array);
        if(!empty($_GET['output']) && $_GET['output']=='csv')
        {
            $arr = json_decode($data, true);
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=data.csv');
            $output = fopen('php://output', 'w');
            $aCols=explode(',',$columns);
            fputcsv($output, $aCols);
            foreach($arr['data'] as $k=>$v)
            {
                $csv=array();
                foreach($aCols as $s)
                {
                    $csv[$s]='';
                }
                $csv2=array_merge($csv,$v);
                fputcsv($output, $csv2);
            }
        }
        else
        {
            //set page to output JSON
            header("Content-Type: application/json; charset=utf-8");
            echo($data);
        }
    }
    else
    {
        header("Content-Type: application/json; charset=utf-8");
        $response['error']="Nothing was retrieved to the database because the http request has no GET values";
        echo(json_encode($response));
    }