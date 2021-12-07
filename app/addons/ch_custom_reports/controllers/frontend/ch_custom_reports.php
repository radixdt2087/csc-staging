<?php 
use Illuminate\Support\Collection;
use Tygh\Tools\Url;
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied');} 

if($mode ==  'reports') 
{
    $user_id = Tygh::$app['session']['auth']['user_id'];
    $reports = array();
    $myEarningsreports = array();
    $getOverview = array();
   if($user_id) {
        $Link = Registry::get('addons.merchant_affiliation.ich_link');
        $email = db_get_field("SELECT email FROM ?:users WHERE user_id = $user_id");
        $linkedAccount = db_get_array("SELECT * FROM ?:ach_details WHERE isDeleted = 0 AND user_id = ?i", $user_id);
        $url = $Link."/api/getMyTransaction/$email";
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $Link."/api/getMyTransaction/$email",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "email=$email",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/x-www-form-urlencoded",
            "Cookie: ARRAffinity=c21174bea1faf39492858f37dabeb4ba4c612b42eecfafbf4420e68963926b67; ARRAffinitySameSite=c21174bea1faf39492858f37dabeb4ba4c612b42eecfafbf4420e68963926b67"
        ),
        ));
        $response = curl_exec($curl);
        $reports =  json_decode($response, true);
        $cashback = array();
        foreach($reports as $rep){
            array_push($cashback,$rep['cashback']);   
        }
        $totalCashback = array_sum($cashback);
        $transaction_history =  db_get_array('SELECT * FROM ?:cashback_transaction_history where user_id = ?i', $user_id);
        
        $myEarningsurl = $Link."/api/getMyEarnings/$email";
        // curl initiate
        $myEarningsch = curl_init();
        curl_setopt($myEarningsch, CURLOPT_URL, $myEarningsurl);
        curl_setopt($myEarningsch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($myEarningsch, CURLOPT_POST, 1);
        curl_setopt($myEarningsch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($myEarningsch, CURLOPT_SSL_VERIFYPEER, false);
        $myEarningsresponse  = curl_exec($myEarningsch);
        curl_close($myEarningsch);
        $myEarningsreports =  json_decode($myEarningsresponse, true);
        $getOverview = db_get_array("SELECT * FROM ?:reward_point_changes WHERE user_id = $user_id"); 

        /// Lpo Report
        $lpourl = $Link."/api/getLpoReport/$email";
        // curl initiate
        $lpoch = curl_init();
        curl_setopt($lpoch, CURLOPT_URL, $lpourl);
        curl_setopt($lpoch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($lpoch, CURLOPT_POST, 1);
        curl_setopt($lpoch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($lpoch, CURLOPT_SSL_VERIFYPEER, false);
        $lporesponse  = curl_exec($lpoch);
        curl_close($lpoch);
        $lporeports =  json_decode($lporesponse, true);
        
        // Get user with Tree report
        $hierarchycurl = curl_init();
        curl_setopt_array($hierarchycurl, array(
        CURLOPT_URL => $Link."/api/getUserWithHierarchy/".$user_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $hierarchyresponse = curl_exec($hierarchycurl);
        $hierarchyerr = curl_error($hierarchycurl);
        curl_close($hierarchycurl);

        //$html = strip_tags($hierarchyresponse, '<u><li><a>');
        $html = html_entity_decode($hierarchyresponse);
        // Get user with table report
        $tablecurl = curl_init();
        curl_setopt_array($tablecurl, array(
        CURLOPT_URL => $Link."/api/getUserWithTable/".$user_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $tableresponse = curl_exec($tablecurl);
        $tableerr = curl_error($tablecurl);
        curl_close($tablecurl);
        $memberData = json_decode($tableresponse, true);
        $cookieName = "lpoData";
        $cookieValue = json_encode($lporeports);
        //print_r($cookieValue);
        setcookie($cookieName, $cookieValue, time() + (86400 * 30), "/");
        $lpoCookieData = json_decode($_COOKIE['lpoData'], TRUE);
        $orderLink = "http://$_SERVER[HTTP_HOST]/index.php?dispatch=orders.details&order_id=";
        // Cashback Wallet
        $wallet = db_get_array("SELECT * FROM ?:cashback_wallet WHERE user_id = $user_id");
        // echo '<pre>';
        // print_r($wallet); die;

        $view = Registry::get('view');
        $view->assign(['reports' => $reports, 'myEarnings' => $myEarningsreports, 'getOverview' => $getOverview, 'orderLink' => $orderLink, 'lpoUserData' => $lporeports, 'hierarchyresponse' => $html, 'memberData' => $memberData, 'linkedAccount' => $linkedAccount, 'totalCashback' => $totalCashback, 'transactionhistory' => $transaction_history, 'wallet' => $wallet]);
    }

}

function fn_my_changes_get_users_details($user_id)
{
        $Link = Registry::get('addons.merchant_affiliation.ich_link');
        $url = $Link."/api/getUsers/$user_id";
        // curl initiate
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response  = curl_exec($ch);
        curl_close($ch);
        $reports =  json_decode($response, true);

        echo $reports['firstName'].' '.$reports['lastName'];
}

function fn_my_changes_get_cscart_users_details($user_id)
{
    
    if($user_id != "")
    {
        $Link = Registry::get('addons.merchant_affiliation.ich_link');
        $url = $Link."/api/getUsers/$user_id";
        // curl initiate
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response  = curl_exec($ch);
        curl_close($ch);
        $reports =  json_decode($response, true);

        $userEmail = $reports['email'];
    
        $csurl = $Link."/api/getCsCartUser/$userEmail";
        // curl initiate
        $csch = curl_init();
        curl_setopt($csch, CURLOPT_URL, $csurl);
        curl_setopt($csch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($csch, CURLOPT_POST, 1);
        curl_setopt($csch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($csch, CURLOPT_SSL_VERIFYPEER, false);
        $csresponse  = curl_exec($csch);
        curl_close($csch);
        $csreports =  json_decode($csresponse, true);
        return $csreports['users'][0];
    
    }


}

function fn_my_changes_price_function($price)
{
    preg_match( "/(-+)?\d+(\.\d{1,2})?/" ,$price ,$pricePoint);
    echo $pricePoint[0];
}

function fn_my_changes_donation_function($donation)
{
    preg_match( "/(-+)?\d+(\.\d{1,2})?/" ,$donation ,$pricePoint);
    echo $pricePoint[0];
}
