<?php
/*****************************************************************************
*                                                                            *
*          All rights reserved! CS-Commerce Software Solutions               *
* 			http://www.cs-commerce.com/license-agreement.html 				 *
*                                                                            *
*****************************************************************************/
if (!defined('BOOTSTRAP')) { die('Access denied'); }
class ClsYandexSpeller{
	public static function _get($text, $lang_code=''){
		$arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false));
		$result = @file_get_contents("https://speller.yandex.net/services/spellservice.json/checkText?text=".urlencode($text), false, stream_context_create($arrContextOptions));		
		$result = json_decode($result, true);		
		if (!empty($result[0]['s'])){
			$corrections = [];
			foreach ($result[0]['s'] as $corr){
				$corrections[]=[
					'q'=>$corr
				];	
			}
			return $corrections;
		}
		return [];
	}
}