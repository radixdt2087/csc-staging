<?php 

if ( !defined('AREA') ) { die('Access denied'); }

function fn_rde_point_get_rde_points()
{
   return "504";
}

// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => "https://staging.wesave.com/index.php/api/sendOrderTrackingApi",
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 30,
//   CURLOPT_SSL_VERIFYPEER => FALSE,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "POST",
//   CURLOPT_HTTPHEADER => array(
//     "cache-control: no-cache",
//     "content-type: application/json",
//     "postman-token: 58cb0eef-5f51-cd47-0a30-ed3af73cb700"
//   ),
// ));

// $response = curl_exec($curl);
// $err = curl_error($curl);

// curl_close($curl);

// if ($err) {
//   echo "cURL Error #:" . $err;
// } 

// $orderTrackingResponse = json_decode($response);
//$getUserDetils = db_get_array("SELECT * FROM ?:orders WHERE order_id = ");


// print_r($orderresponse);
// $data = json_decode($orderresponse);
// print_r($data->shipping_ids);
//die();
// if(!empty($getUserDetils))
// {


   


//    $shippingId = $getUserDetils[0]['shipping_ids'];
//    //$insertData = "INSERT INTO `cscart_shipments` (`shipment_id`, `shipping_id`, `tracking_number`, `carrier`, `timestamp`, `comments`, `status`) VALUES ('', '$shippingId', '$orderResponse->orderId', 'ups', '', 'P')";
//    //db_query($insertData);

//    //cscart_shipments
// }
//die();


?>