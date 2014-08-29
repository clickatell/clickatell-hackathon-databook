<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
if (!isset($_SESSION))
{
    session_start();
}
$msg='';

$t= new trinity();


class trinity
{
    var $db='';
    var $msg='';
    var $meta='';
    var $context='';
    var $location = '';

    function trinity()
    {
        require_once("../lib/db.php");
        $this->db = new db("mysql:host=localhost;dbname=databook_db", "databook_db", "");

        if(!empty($_SERVER['PHP_SELF']))
        {
            $me = $_SERVER['PHP_SELF'];

            $bootstrap=explode('/',$me);
            if(!empty($bootstrap[2]))
            {
                $notyet=explode('.',$bootstrap[2]);
                $this->meta=$notyet[0];
                if(!empty($this->meta))
                {
                    $func='do'.$this->meta;
                    $this->$func();
                }

            }
            else
            {
                //nope the fuck out of here
            }


        }
    }

    function registerUser($email)
    {
        $sql='select id from users where user="'.$email.'"  limit 1';
        $res=$this->db->run($sql);

        if(empty($res))
        {
            $pass=$this->genPass();
            $mpass=md5($pass);
            $sha=sha1($pass);
            $sql='insert into users set user="'.$email.'",API_key="'.$sha.'",password="'.$pass.'"';
            $res=$this->db->run($sql);

            $message="Token:".$sha." Password:".$pass;
            //sendemail
            mail($email, 'Databook Registration', $message);
        }



    }

    function genPass($length = 9, $add_dashes = false, $available_sets = 'lud')
    {
        $sets = array();
        if(strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if(strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if(strpos($available_sets, 'd') !== false)
            $sets[] = '23456789';
        if(strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%*';

        $all = '';
        $password = '';
        foreach($sets as $set)
        {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }

        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];

        $password = str_shuffle($password);

        if(!$add_dashes)
            return $password;

        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while(strlen($password) > $dash_len)
        {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }

    function doregister()
    {
        if(!empty($_POST))
        {
            if (!empty($_POST['user']))
            {
                $email=$_POST['user'];
                if(filter_var($email, FILTER_VALIDATE_EMAIL))
                {

                    $this->registerUser($email);
                    $msg="Registration Email Sent!";
                    header('Location:index.php?msg='.$msg);
                    exit();
                }
                else
                {
                    $msg='Invalid Email Address';
                    header('Location:register.php?msg='.$msg);
                    exit();
                }


            }
        }
    }

    function doindex()
    {
        if(!empty($_POST))
        {
            if (!empty($_POST['user']) && !empty($_POST['password']))
            {
                $user=$_POST['user'];
                $pass=$_POST['password'];

                $sql='select id,API_key from users where user="'.$user.'" and password="'.$pass.'" limit 1';
                $res=$this->db->run($sql);
                if(!empty($res))
                {
                    $_SESSION['key']=$res[0]['API_key'];
                    header('Location:dashboard.php');
                    exit();
                }
                else
                {
                    header('Location:index.php?msg=Login Failed');
                    exit();
                }
            }
        }
    }


}