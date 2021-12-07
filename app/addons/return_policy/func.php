<?php 

// if (!defined('BOOTSTRAP')) { die('Access denied'); }

// use Tygh\Registry;
// use Tygh\Tools\Url;
// $str = $_SERVER['QUERY_STRING'];
// $productId = strstr($str, 'product_id=');
// preg_match_all('!\d+!', $productId, $matches);

// print_r($_SERVER);

// if(isset($_GET['product_id']))
// {   
//     $productId = $_GET['product_id'];
//     $getProduct = db_get_array("SELECT * FROM ?:products WHERE product_id = $productId"); 
   
//     $productCode = $getProduct[0]['product_code'];
    
//     /* Get Company name */

//     $url = "https://staging.wesave.com/index.php/api/productDetails/$productCode";

//     // CURL INITIATE
//     $ch = CURL_INIT();
//     CURL_SETOPT($ch, CURLOPT_URL, $url);
//     CURL_SETOPT($ch, CURLOPT_HTTPHEADER, ARRAY('CONTENT-TYPE: APPLICATION/JSON'));
//     CURL_SETOPT($ch, CURLOPT_POST, 1);
//     CURL_SETOPT($ch, CURLOPT_RETURNTRANSFER, TRUE);
//     CURL_SETOPT($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//     $response  = CURL_EXEC($ch);
//     CURL_CLOSE($ch);

//     $getResult = json_decode($response);

//     /* End Get Company name */

//     $checkReturnPolicy = db_get_array("SELECT * FROM ?:bm_blocks_content WHERE object_id = $productId"); 
//     print_r($checkReturnPolicy);
//     die();
//     if(!empty($checkReturnPolicy))
//     {
//         $stringCount = strlen($getResult->policy);
//         //$returnPolicy = htmlentities($getResult->policy, ENT_QUOTES); 
//         $contentData = 'a:1:{s:7:"content";s:'.$stringCount.':"'.$getResult->policy.'";}';
//         $query = "UPDATE cscart_bm_blocks_content SET `content`= '$contentData' WHERE `object_id` = $productId";
//         $databaseResult = db_query($query);

//     } else {

//         $stringCount = strlen($getResult->policy);
//         $contentData = 'a:1:{s:7:"content";s:'.$stringCount.':"'.$getResult->policy.'";}';
//         $insertData = "INSERT INTO `cscart_bm_blocks_content` (`snapping_id`, `object_id`, `object_type`, `block_id`, `lang_code`, `content`) VALUES (0, '$productId', 'products',  17, 'en', '$contentData')";
//         db_query($insertData);

//     }


// }

?>