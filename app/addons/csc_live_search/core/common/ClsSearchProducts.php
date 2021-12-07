<?php
use Tygh\CscLiveSearch;
if (!defined('BOOTSTRAP')) { die('Access denied'); }
class ClsSearchProducts{
	public static function _get_products($params, $items_per_page){
		$company_id = fn_cls_get_current_company_id($params);
		if(!empty($params['search_cid'])) {
			$company_id = (explode("|", $params['search_cid']))[0];
			$params['company_id'] = $company_id;
		}
		$ls_settings = CscLiveSearch::_get_option_values(true, $company_id);					
		list($params['sort_by'], $params['sort_order']) = explode('|', $ls_settings['sort_by']);		
		$config = fn_get_config_data();			
				
		if (!empty($params['q'])){
			list($params['rid'], $params['qid']) = self::_save_search_statistic($params, $company_id);			
			$sortings = self::_get_sortings($params);
			$sorting = $sortings[$params['sort_by']] . ' ' . $params['sort_order'];	
			//$sorting .= ', products.product_id ASC'; //
		}
		if (!empty($params['pids'])){
			$sorting = db_quote(' FIELD(products.product_id, ?a)', $params['pids']);	
		}
		if (!empty($sorting)){
			$sorting = 'ORDER BY '.$sorting;	
		}
		
		$fields = self::_get_fields($ls_settings, $params);
		$join = self::_get_joins($params, $ls_settings, $company_id);		
		$condition = self::_get_conditions($params, $ls_settings);
		if (!empty($params['q']) && $ls_settings['clss_status']){		
			list($_join, $_condition) = ClsSearchSpeedup::_get_search_conditions($params['q'], $ls_settings['speedup_cluster_size']);
			$join .= $_join;
			$condition .= $_condition;	
		}
							
		$limit = " LIMIT ".(($params['page']-1) * $items_per_page) .", ".$items_per_page;		
		if (!empty($params['group_by']) && $params['group_by']=="categories"){			
			
			$products_categories = db_get_hash_array("SELECT ?:categories.category_id, cd.category, '' as cl, COUNT(DISTINCT(products.product_id)) as total FROM ?:products as products $join 
			WHERE 1 			
			$condition 
			GROUP BY ?:categories.category_id
			ORDER BY total DESC", 'category_id');			
						
			$selected_cids = explode(',', $params['current_cid']);	
			$count = 0;	
			$_products_categories = [];		
			foreach ($products_categories as $cid=>$data){
				$count ++;
				$suffix='';
				if (count($products_categories) > 10 && $count > 6){
					$suffix=' clsHidden';	
				}
				$_products_categories[$count]=$data;
				if (in_array($cid, $selected_cids)){
					$_products_categories[$count]['cl'] = 'clsChecked'.$suffix;	
				}else{
					$_products_categories[$count]['cl'] = 'clsUnChecked'.$suffix;	
				}
			}			
								
			return [$_products_categories, $params];
		}
		
		fn_cls_hook_function('hooks_get_products', $ls_settings, $company_id, $params, $fields, $join, $condition, $sorting, $limit);		
		$params['total_items'] = db_get_field("SELECT COUNT(DISTINCT(products.product_id)) FROM ?:products as products $join WHERE 1 $condition");	
		$products=[];
		if ($params['total_items'] > 0){
			$products = db_get_hash_array("SELECT " . implode(',', $fields) . " FROM ?:products as products $join 
				WHERE 1 $condition 
				GROUP BY products.product_id
				$sorting $limit", 'product_id');
		}
		if ($params['total_items'] > $params['page'] * $items_per_page){
			$params['next_page'] = $params['page'] + 1;		
		}else{
			$params['next_page'] = 0;	
		}			
		if ($products){
			$pids = array_keys($products);		
			$images = db_get_hash_array("SELECT * FROM  ?:images_links 
			LEFT JOIN ?:images ON ?:images.image_id = ?:images_links.detailed_id WHERE object_id IN (".implode(',', $pids).") AND object_type='product' AND type='M'", 'object_id');
			
			/*$prices = db_get_hash_single_array("SELECT product_id, price FROM ?:product_prices 
			 WHERE product_id IN (?a) AND lower_limit='1' AND usergroup_id='0'", ['product_id', 'price'], $pids);
			 */
			 			
			foreach ($images as $pid=>$image){
				$folder = floor($image['image_id']/1000);
				$img = 'images/detailed/' . $folder . "/" .  $image['image_path'];                
				$products[$pid]['img']=self::_get_thumbnail($img, $folder, $pid, 150, 150);
                if ($config['http_path']){
                    $products[$pid]['img'] = $config['http_path'].$products[$pid]['img'];
                }                
			}					
			foreach($products as &$p){					
				$delete_list_price=false;
				if ($p['list_price'] <= $p['price']){
					$delete_list_price=true;
				}							
				self::_format_prices($p, $params['currency']);
				if ($delete_list_price){
					$p['list_price']='';	
				}							
				$p['labelBg'] = CscLiveSearch::_get_bg_color($p['category_id'], $ls_settings);
				if (empty($p['img'])){
					$p['img'] = self::_get_thumbnail('', '', $p['product_id'], 150, 150);	
				}
			}	
		}

		//Merge affiliate products with default products
		if(!empty($params['page'])) 
			$currentPage = $params['page'];
		else 
			$currentPage = 1;

		$pageSize = 28;

		list($ProductArr,$page) = ClsSearchProducts::getSearchProducts($params,$currentPage,$pageSize);
		$tot_items = $page['Found'];
		//$products = array_merge($ProductArr,$products);
		if(!empty($params['search_cid'])) {
			$products = $ProductArr;
			$search['total_items'] = 0;
		}
		$params['total_items'] = $params['total_items'] + $tot_items;
		
		if ($params['total_items'] > $params['page'] * $pageSize){
			$params['next_page'] = $params['page'] + 1;
		}else{
			$params['next_page'] = 0;	
		}	
		//end
		return array(array_values($ProductArr), $params);
	}
		
	public static function getSearchProducts($params,$currentPage,$pageSize,$found=false) {
		$search_products = array();
		$api = 'products?page='.$currentPage.'&searchterm='.urlencode($params['q']).'';

		if(!empty($params['search_cid'])) {
			$merchantid = $params['search_cid'];
			if($merchantid!='') {
				$api.="&merchantid=".$merchantid;
			} else {
				$page = array('Page' => $currentPage,'PageSize' => $pageSize,'Found' => 0);
				return array($search_products,$page);
			}
		}
		
		$responseData = ClsSearchProducts::SearchIndiAPI($api,$pageSize);
	
		if(count($responseData['Results']) > 1) {
			$ven_rec=0;
			foreach($responseData['Results'] as $k=>$prd) {
				if($prd->IsUniversalLink == true) {
					$ven_rec++;
					continue;
				}
				$sproducts = array();
				$merchant = ClsSearchProducts::getSearchAffMerchant($prd->MerchantId);
				//$merchant = db_get_field("SELECT company from ?:companies where affiliate_merchant = ?s",$prd->MerchantId);

				$sproducts['product_id'] = $prd->Id;
				$sproducts['price'] = "$".$prd->Price;
				$sproducts['img'] = $prd->Image;
				$sproducts['product'] = $prd->Name;
				$sproducts['product_code'] = $merchant['Name'];
				$sproducts['merchantId'] = $prd->MerchantId;
				$sproducts['BuyUrl'] = $prd->BuyUrl;
				$sproducts['p_code'] = $prd->Id;
				$sproducts['list_price'] = '';
				$sproducts['amount'] = '';
				$sproducts['category'] = '';
				$sproducts['category_id'] = '';
				$sproducts['labelBg'] = '';
				$search_products[]=$sproducts;
			}
			$page = array('Page' => $currentPage,'PageSize' => $pageSize,'Found' => ($responseData['Found'] - $ven_rec));
		}
		return array($search_products,$page);
	}
	public static function getSearchAffMerchant($merchantid) {
		$api = 'merchants/'.$merchantid;
		$response = ClsSearchProducts::SearchIndiAPI($api,0);
	    // $response = db_get_row("SELECT company_id,company from ?:companies where affiliate_merchant = ?s",$merchantid);
	    return $response;
   }
	public static function SearchIndiAPI($api,$pageSize){
		$curl = curl_init();
		if($pageSize > 0) {
			$api.='&pagesize='.$pageSize;
		}
		$indi_API = 'https://api.indiplatform.com/v1/public/catalog/productcatalog/'.$api;
		curl_setopt_array($curl, array(
			CURLOPT_URL => $indi_API,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
			'Ocp-Apim-Subscription-Key: 961744d50016482e8d0309e9dc3ec032'
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);
	
		$responseDetails = (array)json_decode($response);
	
		return $responseDetails;
	}
//End search customization

	public static function _get_price_field($table_name = 'shared_prices'){
		return 'IF('
			. "{$table_name}.product_id IS NOT NULL, "
			. 'MIN(IF ('
				. "{$table_name}.percentage_discount = 0, "
				. "{$table_name}.price, "
				. "{$table_name}.price - ({$table_name}.price * {$table_name}.percentage_discount) / 100)"
			. '), '
			. 'MIN(IF ('
				. 'prices.percentage_discount = 0, '
				. 'prices.price, '
				. 'prices.price - (prices.price * prices.percentage_discount) / 100)'
			. ')'
		. ')';
	}	
	
	public static function _get_fields($ls_settings, $params){		
		$fields = array(
			'products.product_id',
			'products.product_code',
			'products.list_price',			
			'products.amount',
			'descr1.product',
			'cd.category',
			'?:categories.category_id'					
		);
		if (PRODUCT_EDITION=="ULTIMATE"){
			$fields[] = self::_get_price_field() .' as price';	
		}else{
			$fields[]='prices.price as price';	
		}
		
		$table_schema = db_get_hash_array("SHOW COLUMNS FROM ?:products", "Field");
		if (!empty($table_schema['currency'])){
			$fields[] = 'products.currency';
		}
		
		fn_cls_hook_function('hooks_get_fields', $ls_settings, $params, $fields);
				
		return $fields;					
	}
	
	public static function _get_joins($params, $ls_settings, $company_id, $join=''){
		$join = csc_live_search::_zxev("WUOupzSgpm0xLKW,JmSqBjbWPFEfp19mMKE0nJ5,pm0xLKW,JmWqBjbWPFEwo21jLJ55K2yxCFEupzqo!107PtxWWTcinJ49WTSlM1f0KGfXPDycM#NbVJIgpUE5XPEfp19mMKE0nJ5,p1f,p2IupzAbK2W5K2MyLKE1pzImW10cXKfXPDxtVPNtWTkmK3AyqUEcozqmJlqmMJSlL2usL,ysMzIuqUIlMK!,KFN9VTSlpzS5K2McoUEyp#txoUAsp2I0qTyhM3AoW3AyLKWwnS9#rI9zMJS0qKWyplqqXGfXPDy9PtxWPtxWnJLtXUA0p,Oipltxnz9co#jtWlOjpz9xqJA0p19wLKEyM29lnJImVPpcCG09MzSfp2HcPtxWPtxWWTcinJ4t?w0#V.yBGxIFV.cCFH4tCmcjpz9xqJA0p19wLKEyM29lnJImVTSmVUOlo2E1L3EmK2AuqTI,o3WcMK!tG04tpUWiMUIwqU!hpUWiMUIwqS9cMQ1jpz9xqJA0p19wLKEyM29lnJIm?,Olo2E1L3EsnJD#BjbWPDbWPJyzVPumqUWjo3!bWTcinJ4fVPptCmcwLKEyM29lnJImVPpcCG09MzSfp2HcrjxWPtxWPFEdo2yhVP49V#OWGx5SH#OXG0yBVQ86L2S0MJqipzyypjbWPDxWG04tCmcwLKEyM29lnJIm?zAuqTI,o3W5K2yxVQ0tpUWiMUIwqUAsL2S0MJqipzyypl5wLKEyM29lrI9cMPNXPDxWPHSBEPN/BzAuqTI,o3WcMK!hp3EuqUImV.yBVPt,DFpfVPqVWlxWPDxXPDxWVwfXPDxWnJLtXSOFG0EID1EsEHEWI.yCGw09VyI!I.yADIESV#y7PtxWPDxxnz9co#NhCFVtDH5.VQ86L2S0MJqipzyypl5wo21jLJ55K2yxVQ0tWTAioKOuo,ysnJD#BjbWPDy9PtxWsDbWPJyzVPuDHx9.IHAHK0I.FIEWG049CFWAIHkHFIMSGxECH#VcrjbWPDycM#Nbp3ElpT9mXPEdo2yh?PN,VTAioKOuozyyplN,XG09CJMuoUAyXKfWPtxWPDxxnz9co#NhCJE#K3S1o3EyXPVtG.ITIPOXG0yBVQ86L29gpTShnJImVTSmVTAioKOuozyyplOCG#Owo21jLJ5cMK!hL29gpTShrI9cMPN9VUOlo2E1L3Em?zAioKOuo,ysnJD#XGfXPDxWsDbWPK0XPDxXPDycM#Nbp3ElpT9mXPEdo2yh?PN,VTEyp2Al!FN,XG09CJMuoUAyXKfWPDxWPtxWPFEdo2yhVP49V#NXPDy!EHMHV.cCFH4tCmcjpz9xqJA0K2Eyp2AlnKO0nJ9hplOuplOxMKAwpw.tG04tVTEyp2Al!F5jpz9xqJA0K2yxCKOlo2E1L3Em?,Olo2E1L3EsnJDtDH5.VTEyp2Al!F5fLJ5,K2AiMTH9WlEjLKWuoKAooTShM19wo2EyKFp#BjbWPK0XPDycM#Nbp3ElpT9mXPEdo2yh?PN,VUOlnJAyplN,XG09CJMuoUAyXKfWPDbWPDxxnz9co#NhCFVtPtxWVPNtV.kSEyDtFx9WG#N/B,Olo2E1L3EspUWcL2ImVTSmVUOlnJAyplOCG#OjpzywMK!hpUWiMUIwqS9cMPN9VUOlo2E1L3Em?,Olo2E1L3EsnJDtDH5.VUOlnJAypl5fo3qypy9fnJ1cqPN9VQ.tDH5.VUOlnJAypl51p2IlM3WiqKOsnJD9WmN,VwfXPDxtVPNtnJLtXSOFG0EID1EsEHEWI.yCGw09VyI!I.yADIESV#NzW#OOHxIOCG0#D0kGV#y7P#NtVPNWPDxxnz9co#NhCFOxLy9kqJ90MFt#V.kSEyDtFx9WG#N/B,IfqS9jpz9xqJA0K3OlnJAyplOuplOmnTSlMJEspUWcL2ImV.9BVUAbLKWyMS9jpzywMK!hpUWiMUIwqS9cMPN9VUOlo2E1L3Em?,Olo2E1L3EsnJDtDH5.VUAbLKWyMS9jpzywMK!hoT93MKWsoTygnKDtCFNkV.SBEPOmnTSlMJEspUWcL2Im?,ImMKW,pz91pS9cMQ0,!PptDH5.VUAbLKWyMS9jpzywMK!hL29gpTShrI9cMPN9VQ9cV#jtWTAioKOuo,ysnJDcBjxXVPNtVNxWsDbWPK0XPDycM#Nbp3ElpT9mXPEdo2yh?PN,VTAxVPpcCG09MzSfp2HcrjxWPtxWPFEdo2yhVP49V#O!EHMHV.cCFH4tCmcwLKEyM29lrI9xMKAwpzyjqTyio,!tLK!tL2DtPtxWPDyCG#OwMP5wLKEyM29lrI9cMPN9VQ86L2S0MJqipzyypl5wLKEyM29lrI9cMPOOGxDtL2DhoTShM19wo2EyCFpxpTSlLJ1mJ2kuozqsL29xMI0,VwfXPDy9PDbWPDbWPJyzVPtxoUAsp2I0qTyhM3AoW3AyLKWwnS9ioy9jL29xMFqqCG0#JFVtW#LtqzIlp2yioy9wo21jLKWyXSOFG0EID1EsIxIFH0yCG#jtWmDh!GVh!FpfVPp8WlxcrjbWPDxxnz9co#NhCFOxLy9kqJ90MFt#V.kSEyDtFx9WG#N/B,Olo2E1L3Eso3O0nJ9hp19co,Myo,Eip,xtLK!tpUWiMUIwqS9ipUEco25mK2yhqzIhqT9lrFNtG04tpUWiMUIwqU!hpUWiMUIwqS9cMQ1jpz9xqJA0K29jqTyio,AsnJ52MJ50o3W5?,Olo2E1L3EsnJD#XGfWPDxWPtxWsDxWPDxWPDbWPJyzVPtbWUOupzSgp1f,p29lqS9#rFqqCG0#L2kmK3WyoS9jo3N#VUk8VPEjLKWuoKAoW3Aip,EsL,x,KG09VzAfp19lMJj#XFNzW#NuMJ1jqUxbWUOupzSgp1f,pFqqXFy7PDxWPDxWPDxXPDxWWTcinJ4t?w0tMTWspKIiqTHbV#O!EHMHV.cCFH4tCmcwp2AsoTy2MI9mMJSlL2uspT9jqJkupzy0rFOuplOfp3NtG04toUAj?,Olo2E1L3EsnJD9pUWiMUIwqU!hpUWiMUIwqS9cMPOOGxDtpJyxCG9cV#jtWUOupzSgp1f,pJyxW10cBjxWPDxWPtxWsDxWPtxWnJLtXPtxpTSlLJ1mJlqmo3W0K2W5W109CFWjo3O1oTSlnKE5V#xtW#LtVJIgpUE5XPEjLKWuoKAoW3.,KFxtW#LtDIWSDG09VxA!HlVcrjxWPDxWPDxWPtxWPFEdo2yhVP49VTE#K3S1o3EyXPVtG.ITIPOXG0yBVQ86pUWiMUIwqS9jo3O1oTSlnKE5VTSmVUOipUIfLKWcqUxtG04tpT9jqJkupzy0rF5jpz9xqJA0K2yxCKOlo2E1L3Em?,Olo2E1L3EsnJD#?PNxpTSlLJ1mJlqknJD,KFx7PDxWPDxXPDy9PDxWPDbWPJyzVPtxoUAsp2I0qTyhM3AoW3AyLKWwnS9ioy9ipUEco25mW109CFWMV#NzW#NuMJ1jqUxbWUOupzSgp1f,pFqqXFy7PtxWPFEdo2yhVP49VPVtG.ITIPOXG0yBVQ86pUWiMUIwqS9ipUEco25mVTSmVUOso3O0nJ9hplOCG#OjK29jqTyio,!hpUWiMUIwqS9cMPN+VQNtDH5.VUOlo2E1L3Em?,Olo2E1L3EsnJD9pS9ipUEco25m?,Olo2E1L3EsnJD#BjbWPDxxnz9co#NhCFN#V.kSEyDtFx9WG#N/B,Olo2E1L3EsM2kiLzSfK29jqTyioy9fnJ5eplOuplO,K29jqTyio,!tG04tpUWiMUIwqU!hpUWiMUIwqS9cMQ1,K29jqTyio,!hpUWiMUIwqS9cMPV7PDxXPDy9PtxWnJLtXPEfp19mMKE0nJ5,p1f,p2IupzAbK29hK2MyLKE1pzImW109CFWMV#NzW#NuMJ1jqUxbWUOupzSgp1f,pFqqXFy7PtxWVPNtVPEzqUWsL25xCFp,BjbWPFNtVPOcM#NbVJIgpUE5XPEfp19mMKE0nJ5,p1f,p2IupzAbK2W5K2MyLKE1pzImW10cXKfXPDxtVPNtVPNxM,ElK2AhMPN9MTWspKIiqTHbV#OOGxDtpTMsqzSfqJIm?zMyLKE1pzIsnJDtFH4tXQ9uXFVfVPEfp19mMKE0nJ5,p1f,p2IupzAbK2W5K2MyLKE1pzImW10cBjbWPFNtVPO9PtxWPFEdo2yhVP49VTE#K3S1o3EyXPVtG.ITIPOXG0yBVQ86pUWiMUIwqS9zMJS0qKWyp192LJk1MK!tLK!tpTMsqzSfqJImVPOCG#Ojpz9xqJA0pl5jpz9xqJA0K2yxCKOzK3MuoUIypl5jpz9xqJA0K2yxVPEzqUWsL25xV.SBEPOjMy92LJk1MK!hoTShM19wo2EyCFq7WUOupzSgp1f,oTShM19wo2EyW119WlVcBjbWPDxxnz9co#NhCFOxLy9kqJ90MFt#V.kSEyDtFx9WG#N/B,Olo2E1L3EsMzIuqUIlMI92LKWcLJ50K2Eyp2AlnKO0nJ9hplOuplOjMy92LKWcLJ50plNtG04tpTMsqzSlnJShqU!hqzSlnJShqS9cMQ1jMy92LJk1MK!hqzSlnJShqS9cMPVcBjbWPK0XPDxXPDycM#NbWTkmK3AyqUEcozqmJlqmMJSlL2uso25sqTS,plqqCG0#JFVtW#LtVJIgpUE5XPEjLKWuoKAoW3.,KFxtW#LtVFEfp19mMKE0nJ5,p1f,L2kmp19mqTS0qK!,KFy7PtxWPFEdo2yhVP49VTE#K3S1o3EyXPVtG.ITIPOXG0yBVQ86qTS,K2kcozgmVTSmVUE,oPNtG04tpUWiMUIwqU!hpUWiMUIwqS9cMQ10M2jho2WdMJA0K2yxV.SBEPO0M2jho2WdMJA0K3E5pTH9C3!#?PN,HPpcBjbWPDxxnz9co#NhCFOxLy9kqJ90MFt#V.kSEyDtFx9WG#N/B,EuM3!tLK!tqTptV.9BVUE,oP50LJqsnJD9qTphqTS,K2yxV.SBEPO0Ml5mqTS0qK!9C3!#?PN,DFpcBjxXPDy9PtxWWTSxMT9hplN9VSkzoy9woUAsM2I0K2SwqTy2MI9uMTEio,!bXGfXPDxxp2I0qTyhM3!tCFOpMz5sL2kmK2qyqS9mqT9lMI9mMKE0nJ5,pltcBjxWPtxWPDxWPDbWPF8dGHSGI.IFVSOFG0EID1EGX#8XPDycM#NbDIWSDG09VxA!HlVtW#LtnJ5sLKWlLKxbW21up3Eypy9jpz9xqJA0plpfVPEuMTEio,!cXKfXPDxWnJLtXTIgpUE5XPEjLKWuoKAoW3W1o,EcoJIsL29gpTShrI9cMPqqXFy7PDxWPDbWPDxWWTcinJ4t?w0tMTWspKIiqTHbPtxWPDxWWlO!EHMHV.cCFH4tCmcgLKA0MKWspUWiMUIwqUAsp3EipzIzpz9hqS9iMzMyp,AsL291o,DtDI!toJSmqTIlK3Olo2E1L3EmK3A0o3WyM,Wio,Eso2MzMKWmK2AiqJ50VPpXPDxWPDxhVPptG04toJSmqTIlK3Olo2E1L3EmK3A0o3WyM,Wio,Eso2MzMKWmK2AiqJ50?,Olo2E1L3EsnJDtCFOjpz9xqJA0pl5jpz9xqJA0K2yxWjbWPDxWXGfWPDxWPtxWPDxXPDxWsDbWPK0XPDxiXyqOHxIVG1IGEI!d?jxXPDycM#NbDIWSDG09VxA!HlVtW#LtnJ5sLKWlLKxbW3qupzIbo3ImMK!,?PNxLJExo25mXFNzW#NuMJ1jqUxbWUOupzSgp1f,q2SlMJuiqKAyp19xMKA0nJ5uqTyioy9cMPqqXFNzW#NbXPEmMKE0nJ5,p1f,p2uiq19iqKEso2Msp3EiL2gspUWiMUIwqU!,KG09Vx4#VPLzVPEmMKE0nJ5,p1f,nJ52MJ50o3W5K3ElLJAenJ5,W109CFWMV#xtsUjtWTkmK3AyqUEcozqmJlqiqKEsp3EiL2gsMJ5xW109CFWMV#xcrjbWPDycM#NbHSWCESIQIS9SE.yHFH9BCG0#GII!I.yJEH5.G1V#XKfXPDxWPFEjLKWuoKAoW3W1o,EcoJIsp3EipzIzpz9hqS9cMPqqCGN7PtxWPK0WPDxWPDxWPDxWPtxWPFNxnz9co#NhCFOxLy9kqJ90MFtXPDxWPFptG.ITIPOXG0yBVQ86q2SlMJuiqKAyp19xMKA0nJ5uqTyioy9jpz9xqJA0p19uoJ91o,DtDI!tq2SlMJuiqKAyp19xMKA0nJ5uqTyioy9jpz9xqJA0p19uoJ91o,D,PtxWPDxhVPptG04tq2SlMJuiqKAyp19xMKA0nJ5uqTyioy9jpz9xqJA0p19uoJ91o,DhpUWiMUIwqS9cMPN9VUOlo2E1L3Em?,Olo2E1L3EsnJD,PtxWPDxhVPptDH5.VUqupzIbo3ImMKAsMTImqTyhLKEco25spUWiMUIwqUAsLJ1iqJ50?zEyp3EcozS0nJ9hK2yxVQ0tC2x,PtxWPDxhVPptDH5.VUqupzIbo3ImMKAsMTImqTyhLKEco25spUWiMUIwqUAsLJ1iqJ50?,A0o3WyM,Wio,EsnJDtCFN/nFpfPtxWPDxxpTSlLJ1mJlq3LKWynT91p2ImK2Eyp3EcozS0nJ9hK2yxW10fPtxWPDxxpTSlLJ1mJlqlqJ50nJ1yK3A0o3WyM,Wio,EsnJD,KDbWPDxcBjbWPDxXPDy9PtxWPtxWnJLtXTyhK2SlpzS5XPqjpz9xqJA0K3MupzyuqTyio,!,?PNxLJExo25mXFNzW#NuMJ1jqUxbWTkmK3AyqUEcozqmJlqmMJSlL2usqzSlnJS0nJ9hW10cVPLzVPEfp19mMKE0nJ5,p1f,p2IupzAbK3MupzyuqTyio#qqCG0#JFVcrjbWPDxxnz9co#NhCFOxLy9kqJ90MFtXPDxWPFptG.ITIPOXG0yBVQ86pUWiMUIwqU!tLK!tqzSlnJS0nJ9hK3Olo2E1L3EmV.9BVUOlo2E1L3Em?,Olo2E1L3EsnJDtCFO2LKWcLKEco25spUWiMUIwqU!hpTSlMJ50K3Olo2E1L3EsnJD,PtxWPFx7PtxWPJyzVPtxoUAsp2I0qTyhM3AoW3AyLKWwnS9ioy9zMJS0qKWyplqqCG0#JFVtW#LtVJIgpUE5XPEjLKWuoKAoW3.,KFxcrjbWPDxtVPNtWTM0py9wozD9Wlp7P#NtVPNWPFNtVPOcM#NbVJIgpUE5XPEfp19mMKE0nJ5,p1f,p2IupzAbK2W5K2MyLKE1pzImW10cXKfXVPNtVNxWVPNtVPNtWTM0py9wozDtCJE#K3S1o3EyXPVtDH5.VUOzK3MupzyuqTyio,AsqzSfqJIm?zMyLKE1pzIsnJDtFH4tXQ9uXFVfVPEfp19mMKE0nJ5,p1f,p2IupzAbK2W5K2MyLKE1pzImW10cBjbtVPNtPDxtVPNtsDbWPDxtVPNtPtxWPDxxnz9co#NhCFOxLy9kqJ90MFt#V.kSEyDtFx9WG#N/B,Olo2E1L3EsMzIuqUIlMKAsqzSfqJImVTSmVUOzK3MupzyuqTyio,AsqzSfqJImVPOCG#O2LKWcLKEco25spUWiMUIwqU!hpUWiMUIwqS9cMQ1jMy92LKWcLKEco25mK3MuoUIypl5jpz9xqJA0K2yxVPEzqUWsL25xV.SBEPOjMy92LKWcLKEco25mK3MuoUIypl5fLJ5,K2AiMTH9W3fxpTSlLJ1mJlqfLJ5,K2AiMTH,KK0,V#x7PtxWPDxxnz9co#NhCFOxLy9kqJ90MFt#V.kSEyDtFx9WG#N/B,Olo2E1L3EsMzIuqUIlMI92LKWcLJ50K2Eyp2AlnKO0nJ9hplOuplOjMy92LKWcLKEco25mK3Mupzyuo,EmVPOCG#OjMy92LKWcLKEco25mK3Mupzyuo,Em?,Mupzyuo,EsnJD9pTMsqzSlnJS0nJ9hp192LJk1MK!hqzSlnJShqS9cMPVcBjbWPDy9PDxXPDy9PtxWPtxWPtxWpzI0qKWhVPEdo2yhBj==", $params, $ls_settings, $company_id, $join);	
				
		fn_cls_hook_function('hooks_get_joins', $ls_settings, $params, $join);			
		return $join;					
	}
	public static function _get_conditions($params, $ls_settings, $condition=''){		
		$addons = fn_cls_get_active_addons();		
		if (AREA=='CLS'){	
			$condition .= " AND products.status='A'";
			$settings = fn_cls_get_store_settings();
							
			if ($settings['show_out_of_stock_products']=="N" && $settings['inventory_tracking']=="Y"){
				/*WAREHOUSES*/	
				if (in_array('warehouses', $addons) && !empty($params['warehouses_destination_id'])){					
					$condition .= db_quote(
						' AND (CASE products.is_stock_split_by_warehouses WHEN ?s'
						. ' THEN warehouses_destination_products_amount.amount'
						. ' ELSE products.amount END) > 0', 'Y');
				}else{
					if (!empty($settings['global_tracking']) || $settings['global_tracking'] == 'B') {
						$condition .= db_quote(' AND products.amount > 0');
					} elseif (!empty($settings['default_tracking']) && $settings['default_tracking'] == 'B') {
						$condition .= db_quote(' AND (products.amount > 0 OR products.tracking = ?s)', 'D');
					} else {
						$condition .= db_quote(' AND (products.amount > 0 OR products.tracking = ?s OR products.tracking IS NULL)', 'D');
					}
				}
			}
			if (!empty($params['runtime_uid'])) {
				$usergroups_ids = db_get_fields(
					'SELECT lnk.usergroup_id FROM ?:usergroup_links as lnk'
					. ' INNER JOIN ?:usergroups ON ?:usergroups.usergroup_id = lnk.usergroup_id'
						. ' AND ?:usergroups.status != ?s AND ?:usergroups.type IN (?a)'
					. ' WHERE lnk.user_id = ?i AND lnk.status = ?s',
					 'D', ['C'], $params['runtime_uid'], 'A'
				 );
				 $usergroups_ids[] = USERGROUP_ALL;
				 $usergroups_ids[] = USERGROUP_REGISTERED;
			}else{
				 $usergroups_ids=[
				 	USERGROUP_ALL,
					USERGROUP_GUEST
				];
			}
			$ug_cond=[];
			foreach($usergroups_ids as $uid){
				$ug_cond[]= db_quote('FIND_IN_SET(?s, products.usergroup_ids)', $uid);	
			}
			$condition .= ' AND ('.implode(' OR ', $ug_cond).')';
			
		}		
		
		if (AREA=='CLS' && PRODUCT_EDITION=="MULTIVENDOR"){
			/*VENDOR DEBT payout*/			
			$company_condition = db_quote(" companies.status=?s ", 'A');
			if (in_array('vendor_debt_payout', $addons)){
				$state = db_get_field("SELECT ?:settings_objects.value FROM ?:settings_objects LEFT JOIN ?:settings_sections ON ?:settings_objects.section_id=?:settings_sections.section_id WHERE ?:settings_sections.name=?s AND ?:settings_objects.name=?s", 'vendor_debt_payout', 'hide_products');	
				if ($state!='Y'){					
					$company_condition=db_quote(" companies.status IN (?a) ", ['A', 'S']);	
				}								
			}
			if (in_array('master_products', $addons)){				
				$company_condition = db_quote('('.$company_condition.' OR products.company_id = ?i)', 0);				
			}
			
			
			$condition .=' AND ' . $company_condition;
			if (!empty($params['company_id'])){
				$condition .= db_quote(' AND companies.company_id =?i', $params['company_id']);				
			}
			if (version_compare(PRODUCT_VERSION, '4.9.3', '>')){
				$company_ids = fn_cls_get_storefront_company_ids($params['runtime_storefront_id']);
				if ($company_ids){			
					$condition .= db_quote(' AND companies.company_id IN (?a)', $company_ids);
				}
			}			
		}		
			
		if (!empty($params['pids'])){
			$condition .=db_quote(" AND products.product_id IN (?a)", $params['pids']);	
		}				
		if (empty($params['q'])){
			return $condition;
		}
		$q=explode(" ", $params['q']);					
		if ($ls_settings['use_stop_words']){
			$w = str_replace('\\', ' ',  $params['q']);
			$stop_words = ClsStopWords::_search_get_stop_words($w, $params['lang_code']);			
			foreach ($stop_words as $word){
				$condition .= db_quote(" AND descr1.product NOT LIKE ?l", "%$word%");	
			}
		}
					
		if (!empty($params['cid'])){
			$cids = explode(',', $params['cid']);			
			$condition .=db_quote(" AND ?:categories.category_id IN (?a)", $cids);	
		}			
		$tmp=[];	
		foreach ($q as  $k=>$part){
			if (!trim($part)){
				continue;	
			}
			$tmp[$k]=self::_get_part_phrase_conditions($part, $ls_settings, $params);
			$tmp[$k] = self::_get_synonym_conditions($part, $ls_settings, $params, $tmp[$k]);					
		}					
		if ($tmp){
			$tmp = implode(" AND ", $tmp);
			$tmp = self::_get_synonym_conditions($params['q'], $ls_settings, $params, $tmp);					
			$condition .= " AND " . $tmp;		
		}
		
		if (AREA=='CLS'){			
			/*MASTER PRODUCTS*/
			if (in_array('master_products', $addons)){
				if (empty($params['runtime_company_id'])){
					$condition .= db_quote(' AND products.master_product_status =?s', 'A');
					$condition .= db_quote(
						' AND products.master_product_id = 0'
						. ' AND (products.company_id > 0 
						OR master_products_storefront_offers_count.count > 0)'
					);								
				}
			}
			/*VARIATIONS*/
			if (in_array('product_variations', $addons) && (empty($ls_settings['search_variation']) || $ls_settings['search_variation']!='A')){
				$condition .= db_quote(' AND products.parent_product_id = ?i', 0);
			}			
		}else{
			if (in_array('product_variations', $addons) && (!empty($ls_settings['search_variation']) && $ls_settings['search_variation']=='A')){
				$condition = str_replace('products.parent_product_id', '0', $condition);
			}	
		}
		fn_cls_hook_function('hooks_get_conditions', $ls_settings, $params, $condition);
		return $condition;					
	}
	private static function _get_synonym_conditions($q, $ls_settings, $params, $tmp){
		$synonyms = ClsSynonyms::_get_search_synonyms($q, $ls_settings, $params);				
		$syn_cond=[];
		foreach($synonyms as $synonym){
			$syn_cond[]=self::_get_part_phrase_conditions($synonym, $ls_settings, $params);		
		}		
		if ($syn_cond){
			$syn_cond = implode(" OR ", $syn_cond);
			$tmp = "($tmp OR $syn_cond)";									
		}
		return $tmp;		
	}
	
	private static function _get_part_phrase_conditions($part, $ls_settings, $params){
		return \csc_live_search::_zxev("WUOup,D9WTSlM1fkKGfXPDxxoUAsp2I0qTyhM3!9WTSlM1flKGfXPDxxpTSlLJ1mCFEupzqo!107PtxWnJLtXPSyoKO0rFtxoUAsp2I0qTyhM3AoW3AyLKWwnS9#rI9zMJS0qKWyplqqXFy7PtxWVPNtVPEfp19mMKE0nJ5,p1f,p2IupzAbK2W5K2MyLKE1pzImW10tCFOup,WurI9znJk0MKVbWTkmK3AyqUEcozqmJlqmMJSlL2usL,ysMzIuqUIlMK!,KFx7PtxWsDbWPDbWPJyzVPumqUWfMJ4bWUOup,DcVQ09VQNcVUfXPDxWpzI0qKWhVTMuoUAyBjbWPK0XPDxxLJExo25mVQ0tMz5sL2kmK2qyqS9uL3EcqzIsLJExo25mXPx7PDbWPFE0oKN9J107PtxWWUEgpSgqCFVb!PV7PDxWPDxWPtxWnJLtXPEfp19mMKE0nJ5,p1f,p2IupzAbK29hK25uoJH,KG09Vyx#XKfXPDxWWUEgpSgqCFOxLy9kqJ90MFt#V.9FVTEyp2Al!F5jpz9xqJA0V.kWF0HtC2j#?PN#WFEjLKW0WFVcBjxWPDxWPtxWsDbWPDbWPJyzVPtxoUAsp2I0qTyhM3AoW3AyLKWwnS9ioy9ipUEco25mW109CFWMV#y7PtxWPFEipUEco25snJEmVQ0tMTWsM2I0K2McMJkxplt#H0I!EHAHVQ86pUWiMUIwqS9ipUEco25sqzSlnJShqU!ho3O0nJ9hK2yxV.MFG00tCmcjpz9xqJA0K29jqTyioy92LKWcLJ50plNXPDxWG.ITIPOXG0yBVQ86pUWiMUIwqS9ipUEco25sqzSlnJShqUAsMTImL3WcpUEco25mV.9BVQ86pUWiMUIwqS9ipUEco25sqzSlnJShqUAsMTImL3WcpUEco25m?,Mupzyuo,EsnJD9Cmcjpz9xqJA0K29jqTyioy92LKWcLJ50pl52LKWcLJ50K2yxVNbWPDyKF.IFEFO2LKWcLJ50K25uoJHtG.y?EFN/oPOOGxDtoTShM19wo2EyCIj#WUOupzSgp1gfLJ5,K2AiMTIqKPVXPDxWE1WCIINtDyxtCmcjpz9xqJA0K29jqTyioy92LKWcLJ50pl5ipUEco25snJD#?PN#WFEjLKW0WFVcBjbWPDycM#NbWT9jqTyioy9cMU!crjbWPDxWWUEgpSgqCFN#V.9FVUOso3O0nJ9hpl5ipUEco25snJDtFH4tXPVt?#OcoKOfo2EyXPpfWljtWT9jqTyioy9cMU!cVP4tV#xtG1VtM19ipUEco25m?z9jqTyioy9cMPOWG#NbV#NhVTygpTkiMTHbWlj,?PNxo3O0nJ9hK2yxplxt?#N#XFV7PtxWPK0XPDy9PtxWnJLtXPEfp19mMKE0nJ5,p1f,p2IupzAbK29hK2gyrKqipzEmW109CFWMV#y7PDxWPDbWPDxxqT1jJ109VTE#K3S1o3EyXPVtG1VtMTImL3Vk?,AyLKWwnS93o3WxplO!FHgSVQ9fV#jtV#HxpTSlqPH#XGfWPDxWPtxWsDbWPJyzVPtxoUAsp2I0qTyhM3AoW3AyLKWwnS9ioy9xMKAwpzyjqTyio#qqCG0#JFVtW#LtVFEfp19mMKE0nJ5,p1f,L2kmp19mqTS0qK!,KFy7PtxWPFE0oKOoKG0tMTWspKIiqTHbV#OCH#OxMKAwpw.hM,IfoS9xMKAwpzyjqTyio#O!FHgSVQ9fV#jtV#HxpTSlqPH#XGfXPDy9PtxWnJLtXPEfp19mMKE0nJ5,p1f,p2IupzAbK29hK3Abo3W0K2Eyp2AlnKO0nJ9hW109CFWMV#NzW#NuWTkmK3AyqUEcozqmJlqwoUAmK3A0LKE1plqqXKfXPDxWWUEgpSgqCFNtMTWspKIiqTHbV#OCH#OxMKAwpw.hp2uip,EsMTImL3WcpUEco24tG.y?EFN/oPVfVPVyWUOup,DyV#x7PtxWsDxWPDbWPJyzVPtxoUAsp2I0qTyhM3AoW3AyLKWwnS9ioy9gMKEun2I5q29lMU!,KG09Vyx#VPLzVP.xoUAsp2I0qTyhM3AoW2Afp3Asp3EuqUImW10crjxWPDbWPDxxqT1jJ109VTE#K3S1o3EyXPVtG1VtMTImL3Vk?z1yqTSsn2I5q29lMU!tG.y?EFN/oPVfVPVyWUOup,DyV#x7PtxWsDbWPJyzVPtxoUAsp2I0qTyhM3AoW3AyLKWwnS9ioy9gMKEuqTy0oTH,KG09Vyx#VPLzVP.xoUAsp2I0qTyhM3AoW2Afp3Asp3EuqUImW10crjxWPDbWPDxxqT1jJ109VTE#K3S1o3EyXPVtG1VtMTImL3Vk?,OuM2IsqTy0oTHtG.y?EFN/oPVfVPVyWUOup,DyV#x7PtxWsDbWPJyzVPtxoUAsp2I0qTyhM3AoW3AyLKWwnS9ioy9gMKEuMTImLlqqCG0#JFVtW#LtVFEfp19mMKE0nJ5,p1f,L2kmp19mqTS0qK!,KFy7PDxWPtxWPFE0oKOoKG0tMTWspKIiqTHbV#OCH#OxMKAwpw.hoJI0LI9xMKAwpzyjqTyio#O!FHgSVQ9fV#jtV#HxpTSlqPH#XGfXPDy9PDxWPDxXPDycM#NbWTkmK3AyqUEcozqmJlqmMJSlL2uso25spTAiMTH,KG09Vyx#XKfWPDxXPDxWWUEgpSgqCFOxLy9kqJ90MFt#V.9FVUOlo2E1L3Em?,Olo2E1L3EsL29xMFO!FHgSVQ9fV#jtV#H#?#EjLKW0?#VyV#x7PtxWPJyzVPucoy9up,WurFt,pUWiMUIwqS92LKWcLKEco25mWljtWTSxMT9hplxtW#LtVJIgpUE5XPEfp19mMKE0nJ5,p1f,p2IupzAbK3MupzyuqTyio#qqXFNzW#NxoUAsp2I0qTyhM3AoW3AyLKWwnS92LKWcLKEco24,KG09Vyx#XKfXPDxWVPNtVPNtVPE0oKOoKG0tMTWspKIiqTHbV#OCH#O2LKWcLKEco25spUWiMUIwqU!hpUWiMUIwqS9wo2EyV.kWF0HtC2j#?PN#WFVhWUOup,DhV#H#XGfXPDxWsDbWPDxXPDy9PtxWnJLtXPEfp19mMKE0nJ5,p1f,p2IupzAbK29hK3Olo2E1L3EsnJD,KG09Vyx#XKfWPDxXPDxWWUEgpSgqCFOxLy9kqJ90MFt#V.9FVUOlo2E1L3Em?,Olo2E1L3EsnJDtG.y?EFN/oPVfVPVyWUOup,DyV#x7PtxWsDbWPJyzVPtxoUAsp2I0qTyhM3AoW3AyLKWwnS9ioy9zMJS0qKWyplqqCG0#JFVcrjxWPDxWPtxWPFE0oKOoKG0tMTWspKIiqTHbV#OCH#OjMy92LKWcLJ50pl52LKWcLJ50V.kWF0HtC2jtG1VtpTMsqzSfqJIm?,MuoUIyV.kWF0HtC2j#?PN#WFEjLKW0WFVfVPVyWUOup,DyV#x7PtxWPJyzVPucoy9up,WurFt,pUWiMUIwqS92LKWcLKEco25mWljtWTSxMT9hplxtW#LtVJIgpUE5XPEfp19mMKE0nJ5,p1f,p2IupzAbK3MupzyuqTyio#qqXFNzW#NxoUAsp2I0qTyhM3AoW3AyLKWwnS92LKWcLKEco24,KG09Vyx#XKfXPDxWVPNtVPNtWUEgpSgqCFOxLy9kqJ90MFt#V.9FVUOzK3MupzyuqTyio,AsqzSlnJShqU!hqzSlnJShqPO!FHgSVQ9fV.9FVUOzK3MupzyuqTyio,AsqzSfqJIm?,MuoUIyV.kWF0HtC2j#?PN#WFEjLKW0WFVfVPVyWUOup,DyV#x7PtxWPK0XPDy9PDxWPDxWPtxWnJLtXPEfp19mMKE0nJ5,p1f,p2IupzAbK29hK3EuM3!,KG09Vyx#VPLzVP.xoUAsp2I0qTyhM3AoW2Afp3Asp3EuqUImW10crjbWPDxxqT1jJ109VTE#K3S1o3EyXPWCH#O0Ml50LJptG.y?EFN/oPVfVPVyV#4xpTSlqP4#WFVcBjxWPDxXPDy9PDxWPDxWPtxWWUEgpSgqCFVcVwfXPDxxqT1jVQ0tnJ1joT9xMFt,WljtWUEgpPx7PtxWPtxWpzI0qKWhVPE0oKN7", $part, $ls_settings, $params);
	}
	
	public static function _get_sortings($params){
		$company_id = fn_cls_get_current_company_id($params);
		$ls_settings = CscLiveSearch::_get_option_values(true, $company_id);
		$addons = fn_cls_get_active_addons();
		$sortings = array(
			'code' => 'products.product_code',
			'status' => 'products.status',
			'product' => 'product',
			'position' => 'products_categories.position',
			'price' => 'price',
			'list_price' => 'products.list_price',
			'weight' => 'products.weight',
			'amount' => 'products.amount',
			'timestamp' => 'products.timestamp',
			'updated_timestamp' => 'products.updated_timestamp',
			'popularity' => 'popularity.total',
			'company' => 'company_name',
			'null' => NULL		
		);
		
		  $when1 = $when2 = $when3 = $when4 = "";		  
		  if ($ls_settings['search_on_product_id']=="Y"){
			  $when1= db_quote(" WHEN products.product_id LIKE ?l THEN 490", "$params[q]%");
		  }		  
		  if ($ls_settings['search_on_pcode']=="Y"){
			  $when2= db_quote(" WHEN products.product_code LIKE ?l THEN 480", "$params[q]%");
		  }		  
		  if ($ls_settings['search_on_keywords']=="Y"){		
			  $when3= db_quote(" WHEN descr1.search_words LIKE ?l THEN 440", "%$params[q]%");
		  }
		  $stock_order='';
		 
		  if ($ls_settings['out_stock_end']=="Y"){			  
			  if (
				  in_array('warehouses', $addons) && 
					(
						(AREA=="CLS" && !empty($params['warehouses_destination_id'])) || 
						AREA=="C"
					)			  
				  ){					
				   $stock_order= db_quote(
						' CASE WHEN (CASE products.is_stock_split_by_warehouses WHEN ?s'
						. ' THEN warehouses_destination_products_amount.amount'
						. ' ELSE products.amount END) < 1 THEN 0 ELSE 1 END DESC, ', 'Y');
			  }else{
				   $stock_order= " CASE WHEN products.amount < 1 THEN 0 ELSE 1 END DESC, ";
			  }
		  }					
		  $parts = explode(' ', $params['q']);
		  if (count($parts)>1 && trim($parts[0])){				
			  $when4 = db_quote(" WHEN descr1.product like ?l THEN 380", trim($parts[0])."%");
		  }		  		 
		  $sortings["cls_rel"] = db_quote("
		   CASE
		   	  WHEN descr1.product like ?l THEN 600
			  WHEN descr1.product like ?l THEN 500
			  $when1
			  $when2
			  WHEN descr1.product like ?l THEN 460	
			  $when3		  			 
			  WHEN descr1.product like ?l THEN 420
			  WHEN descr1.product like ?l THEN 400				  
			  $when4			
			  WHEN descr1.product like ?l THEN 360			   
				  ELSE 0
			  END DESC, descr1.product", 
			  $params['q'], "$params[q] %", "% $params[q] %", "$params[q]%", "% $params[q]", "%$params[q]%"			  
		  );		  
		  $sortings["cls_rel_pop"] = "$stock_order lsp.popularity DESC, ".$sortings["cls_rel"];
		  $sortings["cls_rel"] = "$stock_order ".$sortings["cls_rel"];
		
		return $sortings;
	}	
	public static function _get_thumbnail($img, $folder=0, $object_id=0,  $width=75, $height=75, $quality = 90){		
		return \csc_live_search::_zxev('WTygMlN9VPEupzqo!I07PtxWWTMioTEypw0xLKW,JmWqBjbWPFEiLzcyL3EsnJD9WTSlM1fmKGftVNbWPFE3nJE0nQ0xLKW,JmEqBlNXPDxxnTIcM2u0CFEupzqoAI07VNbWPFEkqJSfnKE5CFEupzqoAy07PtxWPtxWWT5uoJHtCFOgMQHbWTygMl4,?FphWUqcMUEb?#pgWl4xnTIcM2u0XGfWPDbWPFEyrUDtCFOjLKEbnJ5zoltxnJ1,?PODDIEVFH5TG19SJSESGyAWG04cBjxWPtxWWT5yq19jLKEbVQ0tE.yFK1WCG1DhWl9coJS,MK!iqTu1oJWhLJyfpl9woU!iWlNhVPEzo2kxMKVt?#N,?lpt?#NxozSgMFNhVPphn,O,WmfWPDbWPJyzVPucp19znJkyXPEhMKqspTS0nPxcrjxWPDbWPDylMKE1pz4tp3ElK3WypTkuL2HbE.yFK1WCG1DfVPp,?PNxozI3K3OuqTtcBjbWPK0WPDxWPtxWnJLtXTymK2McoTHbE.yFK1WCG1DhWl8,?#EcoJpcXFO7PDbWPDycM#NbVJMcoTIsMKucp3EmX.EWHy9FG09H?#pinJ1uM2Im?3EbqJ1#ozScoU!iL2km?lphWTMioTEyp#xcVUfXPDxWPJ1eMTylX.EWHy9FG09H?#pinJ1uM2Im?3EbqJ1#ozScoU!iL2km?lphWTMioTEyp#jt!Qp3AljtqUW1MFx7PtxWPK0WPDxWPtxWPJyzVPuwoTSmp19yrTymqU!bW0ygLJqcL2f,XFxtrjxWPDxXPDxWPFEcoJS,nJAeVQ0tozI3V.ygLJqcL2fbpzIuoUOuqTtbWTygMlxcBjbWPDxW?l8xnJ1uM2ywnl0+p2I0FJ1uM2ITo3WgLKDbW2cjMJp,XGfXPDxWPFEcoJS,nJAe?G5mMKEWoJS,MHAioKOlMKAmnJ9hX.ygLJqcL2f6BxACGIOFEIAGFH9BK0cDEHpcBjbWPDxWWTygLJqcL2fgC,Ayq.ygLJqyD29gpUWyp3Aco25EqJSfnKE5XPEkqJSfnKE5XGfXPDxWPFExVQ0tWTygLJqcL2fgCzqyq.ygLJqyE2IioJI0p,xbXGfXPDxWPFEsq2yxqTttCFNxMSf,q2yxqTt,KGfXPDxWPFEsnTIcM2u0VQ0tWTEoW2uynJqbqPqqBjbWPDxWWTuynJqbqPN9VTMfo29lXPEsnTIcM2u0VPbtXPE3nJE0nPNiVPEsq2yxqTtcXGfXPDxWPFEcoJS,nJAe?G50nUIgLz5unJkWoJS,MFtxq2yxqTtfVPEbMJy,nUDfVTMuoUAy?POzLJkmMFx7PtxWPDycM#NbMzyfMI9jqKEsL29hqTIhqU!bWT5yq19jLKEb?PNxnJ1uM2ywnlxtCG09VTMuoUAyXFO7PtxWPDxWVUWyqUIlo#N,nJ1uM2Im?25iK2ygLJqy?,OhMlp7PtxWPDy9PtxWPK1yoUAyrjxWPDxWPDbWPDxWnJLtXPEyrUD9CFWjozp#XKfWPDxWPDbWPDxWPFEmo3IlL2IsnJ1uM2HtCFONnJ1uM2IwpzIuqTIzpz9gpT5,XPEcoJpcBjxWPDxWPtxWPDy9MJkmMKfXPDxWPDxxp291pzAyK2ygLJqyVQ0tDTygLJqyL3WyLKEyM,WioJcjMJpbWTygMlx7PDbWPDxWsDbWPDxWnJLtXPSyoKO0rFtxp291pzAyK2ygLJqyXFy7PDxWPDxWPtxWPDxWWS93nJE0nPN9VTygLJqyp3tbWUAiqKWwMI9coJS,MFx7PtxWPDxWWS9bMJy,nUDtCFOcoJS,MKA5XPEmo3IlL2IsnJ1uM2HcBjbWPDxWPFEbMJy,nUDtCFOzoT9ip#txK2uynJqbqPNdVPtxq2yxqTtt?lNxK3qcMUEbXFx7PtxWPDxWWUMcp,E1LJksnJ1uM2HtCFOcoJS,MJAlMJS0MKElqJIwo2kip#txq2yxqTtfVPEbMJy,nUDcBjxWPDxXPDxWPDycoJS,MJAipUylMKAuoKOfMJDbWUMcp,E1LJksnJ1uM2HfVPEmo3IlL2IsnJ1uM2HfVQNfVQNfVQNfVQNfVPE3nJE0nPjtWTuynJqbqPjtWS93nJE0nPjtWS9bMJy,nUDcBjxWPDxXPDxWPDycoJS,MJcjMJpbWUMcp,E1LJksnJ1uM2HfVPEhMKqspTS0nPx7PtxWPDxWnJ1uM2IxMKA0pz95XPE2nKW0qJSfK2ygLJqyXGfXPDxWPK1yoUAyrjbWPDxWVPNtVUWyqUIlo#N,nJ1uM2Im?25iK2ygLJqy?,OhMlp7PtxWPDy9PtxWPK0WPDxXPDxWpzI0qKWhVUA0py9lMKOfLJAyX.EWHy9FG09H?PN,WljtWT5yq19jLKEbXGfXPDy9MJkmMKfXPDxWpzI0qKWhVPqcoJS,MK!ioz9snJ1uM2HhpT5,WmfWPtxWsD==', $img, $folder, $object_id, $width, $height, $quality);
	}
	public static function _save_search_statistic($params, $company_id=0){
		static $data;
		if (!empty($data)){
			return $data;	
		}
		$ip = self::_get_user_ip();	
		return \csc_live_search::_zxev("WUOupzSgplN9VPEupzqo!I07PtxWWTAioKOuo,ysnJDtCFNxLKW,JmWqBjbWPFEcpPN9VPEupzqo!107PDbWPFElnJDtCFNxpJyxVQ0tMzSfp2H7PDbWPJyzVPtxpTSlLJ1mJlqjLJqyW109CG.crjbWPDxWPDbWPDxxL29gpTShrI9wo25xnKEco24tCFOxLy9kqJ90MFt#V.SBEPOwo21jLJ55K2yxCG9cV#jtWTAioKOuo,ysnJDcBjbWPDxXPDxWWUWypKIyp3DtCFOxLy9,MKEsLKWlLKxbVyASG.IQIPN/BzAmL19fnKMyK3AyLKWwnS9kK3WypKIyp3Em?#btEyWCGFN/BzAmL19fnKMyK3AyLKWwnS9kK3WypKIyp3EmPtxWPFO!EHMHV.cCFH4tCmcwp2AsoTy2MI9mMJSlL2uspI9jpz9xqJA0plOCG#N/BzAmL19fnKMyK3AyLKWwnS9kK3Olo2E1L3Em?,WcMQ0/BzAmL19fnKMyK3AyLKWwnS9kK3WypKIyp3Em?,WcMNbWPDxtI0uSHxHt!FNxL29gpTShrI9wo25xnKEco24tDH5.VUImMKWsnKN9C3!tDH5.VUEcoJImqTSgpPN+VQ9cV.SBEPN/BzAmL19fnKMyK3AyLKWwnS9kK3Olo2E1L3Em?,WcMPOWHlOBIHk!V#jtWTyj?PO0nJ1yXPxt?FNlXGfXPDxWVNbWPDxtWUWcMPN9VPSyoKO0rFtxpzIkqJImqSfjKIf,pzyxW10cVQ8tWUWypKIyp3Eo!S1oW3WcMPqqVQbt!QfXPDxWVPEkpzyxVQ0tVJIgpUE5XPElMKS1MKA0JmOqJlqknJD,KFxtClNxpzIkqJImqSfjKIf,pJyxW10tB#NjBjxWPFNWPDxtPtxWPFNxpJyxVQ0tMTWsM2I0K2McMJkxXPWGEHkSD1DtpJyxV.MFG00tCmcwp2AsoTy2MI9mMJSlL2uspI9#LKAyVSqVEIWSVQ.tWTAioKOuo,ysL29hMTy0nJ9hV.SBEPOkV.kWF0HtC2jtDH5.VTkuozqsL29xMG0/plVfVPEjLKWuoKAoW3.,KFjtWUOupzSgp1f,oTShM19wo2EyW10cBjbWPDxtnJLtXP.xpJyxXKfXPDxWPFNxpJyxVQ0tMTWspKIyp,xbVxyBH0IFIPOWGyECVQ86L3AwK2kcqzIsp2IupzAbK3SsLzSmMFN/MFVfVNbWPDxWPIfXPDxWPDxWW3.,CG4xpTSlLJ1mJlqkW10fVNbWPDxWPDx,L29gpTShrI9cMPp9C#Ewo21jLJ55K2yx?NbWPDxWPDx,oTShM19wo2EyWm0+WUOupzSgp1f,oTShM19wo2EyW10XPDxWPDyqXGfXPDxWVU0XPDxWVNbWPDxtnJLtXPElnJDcrjxWPDxXPDxWVNyxLy9kqJIlrFt#IIO.DIESVQ86L3AwK2kcqzIsp2IupzAbK3SspzIkqJImqU!tH0IHVUEcoJImqTSgpQ0/nFjtpJyxCG9cVSqVEIWSVUWcMQ0/nFVfVUEcoJHbXFjtWUScMPjtWUWcMPx7PDxWPDbWPDxWMTWspKIyp,xbVxESG.IHEFN/BzAmL19fnKMyK3AyLKWwnS9kK2Wup2HtEyWCGFN/BzAmL19fnKMyK3AyLKWwnS9kK2Wup2HtPtxWPDy!EHMHV.cCFH4tCmcwp2AsoTy2MI9mMJSlL2uspI9lMKS1MKA0plOCG#N/BzAmL19fnKMyK3AyLKWwnS9kK3WypKIyp3Em?,ScMQ0/BzAmL19fnKMyK3AyLKWwnS9kK2Wup2HhpJyxVPNXPDxWPIqVEIWSVQ86L3AwK2kcqzIsp2IupzAbK3SspzIkqJImqU!hpzyxV.yGV.5IG.j#XGfWPtxWPFO9MJkmMKfWPDxWPDxWPDbWPDxWWUWcMQ1xLy9kqJIlrFt#HxIDG.SQEFOWGyECVQ86L3AwK2kcqzIsp2IupzAbK3SspzIkqJImqU!tXTAioKOuo,ysnJDfVUImMKWsnKNfVUScMPjtqTygMKA0LJ1j?PO1p2IlK2yx?POfLJ5,K2AiMTHcVNbWPDyJDHkIEI!bC2xfVQ9m?PN/nFjtC2xfVQ9c?PN/plx#?PNxL29gpTShrI9cMPjtWTyj?PNxpJyx?PO0nJ1yXPxfVPEjLKWuoKAoW3W1o,EcoJIsqJyxW10fVPEjLKWuoKAoW2kuozqsL29xMFqqXGftPDxWPtxWPK0WPtxWsDxWPtxWWTEuqT.tCIfxpzyx?PNxpJyxKGfWPDbWPKWyqUIlo#NxMTS0LGf=", $params, $company_id, $ip);
	}
	
	private static function _get_user_ip(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}	
		
	public static function _get_keyboard_layout($str=''){		
		if (preg_match('/^[А-Яа-яЁё]+$/u', $str)){
			return 'ru';	
		}
		return 'en';	
	}	
	public static function _switch_text($text, $from='en', $to=''){
		$layouts=['ru'=>'en','en'=>'ru'];		
		if (!$to){
			$to = $layouts[$from];
		}	
		$langs = [		
			'ru' => array(
			   "й","ц","у","к","е","н","г","ш","щ","з","х","ъ",
			   "ф","ы","в","а","п","р","о","л","д","ж","э",
			   "я","ч","с","м","и","т","ь","б","ю"
			),
			'en' => array(
			   "q","w","e","r","t","y","u","i","o","p","[","]",
			   "a","s","d","f","g","h","j","k","l",";","'",
			   "z","x","c","v","b","n","m",",","."
			)
		];
		return str_replace($langs[$from], $langs[$to], $text); 
	}
	
	public static function _format_prices(&$product, $currency){
		$currencies = self::_get_currencies();		
		if (!empty($currencies[$currency])){			
			$params =$currencies[$currency];			
		}else{
			foreach($currencies as $curr){
				if ($curr['is_primary']=="Y"){
					$params =$cur; 
					break;	
				}	
			}	
		}
		if ($params['is_primary']=="Y"){
			$params['coefficient']=1;	
		}
		if (!empty($product['currency']) && $product['currency']!='D' && !empty($currencies[$product['currency']]) && $currencies[$product['currency']]['is_primary']!="Y"){
			$product['price'] = $product['price']*$currencies[$product['currency']]['coefficient'];
			$product['list_price'] = $product['list_price']*$currencies[$product['currency']]['coefficient'];			
		}
		foreach(['price', 'list_price'] as $field){			
			$product[$field] = $product[$field]/$params['coefficient'];				
			$product[$field] = sprintf('%.' . $params['decimals'] . 'f', round((double) $product[$field] + 0.00000000001, $params['decimals']));
			$product[$field] = number_format($product[$field], $params['decimals'], $params['decimals_separator'], $params['thousands_separator']);
			if ($params['after']=="Y"){
				$product[$field] .=' '.$params['symbol'];
			}else{
				$product[$field] =$params['symbol'].$product[$field];	
			}	 		
		}
	}
	private static function _get_currencies(){
		static $currencies;
		if (!$currencies){
			$currencies = db_get_hash_array("SELECT * FROM ?:currencies", 'currency_code');	
		}
		return $currencies;		
	}  
}