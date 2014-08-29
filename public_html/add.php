<?php
    // include the API Builder Database class
    require_once('lib/class.API.inc.php');

    //set page to output JSON
    header("Content-Type: application/json; charset=utf-8");
    $json_obj = new StdClass();
    //if GET is present...
    if(isset($_GET) && !empty($_GET))
    {

        $api = new API("localhost", "databook_db", "contact", "databook_db", "");
        $columns = "id,firstname,surname,title,birthdate,mobile,email,gender,profession,location,tags,custom1,custom2,custom3";

        $api->setup($columns);
        $api->set_default_order("surname");
        $api->set_pretty_print(false);
        $api->set_key_required(true);

        if(!empty($_GET['key']) && $api->checkUpsert($_GET['key']))
        {
            $aCol=explode(',',$columns);
            foreach($_GET as $k=>$v)
            {
                if(in_array($k,$aCol))
                {
                    $get[$k]=$_GET[$k];
                }

                // add fnma and moble as validation
            }

            if(!empty($get))
            {
                if(empty($get['firstname']))
                {
                    $json_obj->error = "The firstname field is required";
                }
                else
                {
                    $post_array = Database::clean($get);

                    $post_array['user_id']=$api->API_USER_ID;
                    //submit the data to your table.
                    if($id=Database::execute_from_assoc($post_array, Database::$table))
                    {
                        $json_obj->success = "The data was submitted to the database ";
                        $json_obj->id = $id;
                    }else
                    {
                       $json_obj->error = "There was an error submitting the data to the database";
                    }
                }
            }
            else
            {
                $json_obj->error = "No fields provided";
            }
        }
        else
        {
            $json_obj->error = "API key is invalid or was not provided";
        }

    }
    else
    {
        $json_obj->error = "Nothing was added to the database because the http request has no GET values";
    }

    echo(json_encode($json_obj));
?>