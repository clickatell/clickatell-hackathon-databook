<?php


function sendSMS($mobile,$msg)
{

    $msg=str_replace(" ","+",$msg);
    $api_host ='api.clickatell.com';
    $api_username = 'systems.monitor';
    $api_password  = 'TKrcKSqSTa91amtz';
    $http_api_id ='3442616';

    $url  = "http://$api_host/http/sendmsg?";
    $url .= "api_id=$http_api_id&user=$api_username&password=$api_password";
    $url .= "&to=$mobile&text=$msg";

    $response=file_get_contents($url);

    $res=explode(': ',$response);

    $result='SENT SMS TO '.$mobile.' : '.$res[1];

    return $result;

}

function sendTweet($twitter_handle,$msg)
{
    $tauth=array(
    'consumer_key'               => 'hEwK9bzFQDtOm7Xne9yQ',
    'consumer_secret'            => '6F2PpZgELy3ECyPIK8vwv4l0S6xq48NM8IAX6t8s',
    'user_token'                 => '748870393-mDOdOUFaDQgkjQubRNDReWaxvHPa1IrEtPlkP1Sx',
    'user_secret'                => 'oymElew3bIuG6OeXiuZKJmdcm61atDv0NqfA24sqCttFV',
			);

    require '../lib/tmhOAuth.php';
    require '../lib/tmhUtilities.php';
    $tmhOAuth = new tmhOAuth($tauth);

    $args = array(
      'screen_name' => $twitter_handle,
      'text' => $msg,
    );

    $twts = array();

    $tmhOAuth->request('POST','https://api.twitter.com/1.1/direct_messages/new.json',$args,true);

    if ($tmhOAuth->response['code'] == 200)
    {
        $res='SENT DIRECT TWITTER MESSAGE TO '.$twitter_handle.'<br>';
    }
    else
    {
        $res='FAILED DIRECT TWITTER MESSAGE TO '.$twitter_handle.'<br>';
    }

    return $res;
}


$cleanreq=explode('&',$_POST['rqst']);
$get=array();
    foreach ($cleanreq as $req)
    {
        $req2=explode("=",$req);
        if($req2[0]!='msg' && !empty($req2[1]) )
        {

            $get[]=$req2[0].'='.$req2[1];
        }

    }
    $get=implode("&",$get);

$request = "http://databook-api.co.za/list.php?".$get;
//echo($request);

$response_json = file_get_contents($request);

$response = json_decode($response_json, true);
$message=$_POST['msg'];
foreach($response['data'] as $k=>$v)
{
    if(!empty($v['mobile']))
    {
        $pmes='Hey '.$v['firstname'].', '.$message;

        $out=sendSMS($v['mobile'],$pmes);
        echo($out."<br>");
    }
    else
    {
        if(!empty($v['custom3']))
        {
            $pmes='Hey '.$v['firstname'].', '.$message;

            $out=sendTweet($v['custom3'],$pmes);
            echo($out."<br>");
        }
    }

}