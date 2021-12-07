<?php
use Tygh\Tools\Url;
use Tygh\Registry;
$orderId = $_GET['order_id'];
$curl = curl_init();
$Link = Registry::get('addons.merchant_affiliation.ich_link');
curl_setopt_array($curl, array(
  CURLOPT_URL => $Link."/api/sendOrderTracking/".$orderId,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_SSL_VERIFYPEER => FALSE,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: f2dddd6b-f3a4-fc9e-2de5-111ba5f12363"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
  echo "cURL Error #:" . $err;
} 
$orderTrackingResponse = json_decode($response);
if($orderTrackingResponse->orderTrackingId != null)
{
    /* Get order details */
    $ordercurl = curl_init();

    curl_setopt_array($ordercurl, array(
    CURLOPT_URL => $Link."/api/getOrder/".$orderTrackingResponse->orderId,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => FALSE,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json",
        "postman-token: f3003bdf-b398-fb87-ede8-ae8f501b5ef3"
    ),
    ));
    $orderresponse = curl_exec($ordercurl);
    $ordererr = curl_error($ordercurl);
    curl_close($ordercurl);
    if ($ordererr) {
    echo "cURL Error #:" . $ordererr;
    }
    $orderresponse = json_decode($orderresponse);
    //print_r($orderResponse);

 
    /* End get order details */

    if(!empty($orderresponse))
    {
        $checkTracid = db_get_array("SELECT * FROM ?:shipments WHERE `tracking_number` = '$orderTrackingResponse->orderTrackingId'");
        if(empty($checkTracid)){
            $insertData = "INSERT INTO `cscart_shipments` (`shipping_id`, `tracking_number`, `carrier`, `timestamp`, `comments`, `status`) VALUES ('$orderresponse->shipping_ids', '$orderTrackingResponse->orderTrackingId', 'ups', '$orderresponse->updated_at' ,null, 'P')";
            db_query($insertData);
            $getlastDetail = db_get_array("SELECT * FROM ?:shipments ORDER BY shipment_id DESC LIMIT 0,1");

            if($getlastDetail)
            {
                $shipment_id = $getlastDetail[0]['shipment_id'];
                $insertData = "INSERT INTO `cscart_shipment_items` (`item_id`, `shipment_id`, `order_id`, `product_id`, `amount`) VALUES ('$orderresponse->item_id', '$shipment_id', '$orderTrackingResponse->orderId', '$orderresponse->product_id', '1')";
                db_query($insertData);

            }
        }
    }
}
?>
