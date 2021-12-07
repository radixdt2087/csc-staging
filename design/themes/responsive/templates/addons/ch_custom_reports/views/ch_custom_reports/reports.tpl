{if $auth.user_id}
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<style>
	table{
		width:100%;
		margin-bottom: 0px;
	}
	table thead tr th{
		font-size: 13px;
    	text-align: left;
    	padding:10px 5px !important;
	}

	table thead tr{
	    background: #f1f0f0;
	}

	table tbody tr td{
		font-size: 13px;
    	text-align: left;
    	padding:10px 5px !important;
	}

	h1{
		margin-top: 20px;
		font-size: 20px;
	}

	#exTab2{
	  margin-top: 20;
	}

	/* remove border radius for the tab */

	#exTab2 .nav-pills > li > a {
	  border-radius: 0;
	}

	/* change border radius for the tab , apply corners on top*/

	#exTab2 .nav-pills > li > a {
	  border-radius: 4px 4px 0 0 ;
	}
	ul.tree ul{
	  margin-left: 14px;
	}
	ul.tree li{
	  border: 1px solid #f1f1f1;
	  padding: 7px;
	}
    ul.tree li a {
    	text-decoration: none;
    }
    .fas {
       margin-right:7px;
    }
	/* Overview page design */
	.dashboard-cards {
		vertical-align: top;
	}
	.dashboard-card{
		background: #fff;
		background-color: rgb(255, 255, 255);
		background-color: #fff;
		border: 1px solid #e0e7ef;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
		-webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.05);
		-moz-box-shadow: 0 1px 1px rgba(0,0,0,0.05);
		box-shadow: 0 1px 1px rgba(0,0,0,0.05);
		text-align: center;
		margin-bottom: 20px;
	}
	.dashboard-main-column {
		display: table-cell;
		vertical-align: top;
	}
	.my-rewards .dashboard-row-top {
    display: inline-block;
    width: 100%;
    margin-bottom: 10px;
	padding: 20px;
	border: 1px solid #dddddd;
	border-radius: 5px;
	}
	.my-rewards .tab-content {
		border: 1px solid #ddd;
		padding: 10px 20px;
	}
	.my-rewards .nav-tabs {
		border-bottom: 1px solid transparent;
	}
	.my-rewards .nav-tabs>li {
		float: left;
		margin-bottom: -4px;
	}
	.my-rewards .nav-tabs>li>a {
		margin-right: 2px;
		line-height: 1.42857143;
		border: 1px solid #ddd;
		border-radius: 4px 4px 0 0;
		border-bottom-color: transparent;
	}
	.my-rewards .dashboard-card-title {
		padding: 5px 0px;
		background: #efefef;
	}
	.my-rewards .dashboard-card-content {
		padding: 10px;
	}
	.my-rewards .dashboard-card-content h3 a {
		font-size: 24px;
	}
	.my-rewards .account-btn {
		background: #3b7dd1;
		border: 0px;
		color: #fff;
		width: 100%;
		padding: 5px;
		border-radius: 5px;
	}
	.my-rewards .dashboard-row-top {
		display: inline-block;
		width: 100%;
		margin-bottom: 10px;
		padding: 20px;
		border: 1px solid #ddd;
		border-radius: 5px;
		padding-bottom: 10px;
	}
	.my-rewards .dashboard-vendors-activity h4 {
		margin: 0px;
		padding-bottom: 10px;
	}
	.overview table {
		margin-bottom: 0px ;
	}
	.my-rewards .table>tbody>tr>td {
		border-top: 1px solid #dddddd;
		border-bottom: 1px solid #dddddd;
	}
	.my-rewards .table>tbody>tr:last-child>td {
		border-bottom: 0px;
	}
	.my-transaction table.dataTable.no-footer {
		border-bottom: 1px solid #ddd;
	}
	.my-transaction table {
		border: 1px solid #dddddd;
		border-radius: 5px;
	}
	.my-transaction table thead tr {
		background: #efefef;
	}
	.my-transaction .table>thead>tr>th {
		border-bottom: 1px solid #ddd;
		border-right: 1px solid #ddd;
	}
	.my-transaction .dataTable thead th, .my-transaction .dataTable thead td {
		border-bottom: 1px solid #dddddd;
	}
	.my-transaction .table>tbody>tr>td {
		border-bottom: 1px solid transparent;
	}
	.my-transaction .dataTable.stripe tbody tr.odd, .my-transaction .dataTable.display tbody tr.odd {
		background-color: #ffffff;
	}
	.my-transaction table.dataTable.display tbody tr>.sorting_1 {
		background: none;
	}
	.my-transaction table.dataTable tbody tr.even {
		background-color: #f9f9f9;
	}
	.my-transaction table tbody tr td {
		padding: 10px 15px !important;
		border-right: 1px solid #ddd;
	}
	.my-transaction table tbody tr td:last-child {
		border-right: none;
	}
	.my-transaction .table>thead>tr>th:last-child {
		border-right: none;
	}
	.my-transaction .dataTables_wrapper .dataTables_paginate .paginate_button.current,
	.my-transaction .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
		background: #999;
		color: #fff !important;
		padding: 3px 10px;
	}
	.my-donation table {
		border: 1px solid #dddddd;
		border-radius: 5px;
	}
	.my-donation table thead tr {
		background: #efefef;
	}
	.my-donation .table>thead>tr>th {
		border-bottom: 1px solid #ddd;
		border-right: 1px solid #ddd;
	}
	.my-donation .dataTable thead th, .my-transaction .dataTable thead td {
		border-bottom: 1px solid #dddddd;
	}
	.my-donation .table>tbody>tr>td {
		border-bottom: 1px solid transparent;
	}
	.my-donation .dataTable.stripe tbody tr.odd, .my-transaction .dataTable.display tbody tr.odd {
		background-color: #ffffff;
	}
	.my-donation table.dataTable.display tbody tr>.sorting_1 {
		background: none;
	}
	.my-donation table.dataTable tbody tr.even {
		background-color: #f9f9f9;
	}
	.my-donation table tbody tr td {
		padding: 10px 15px !important;
		border-right: 1px solid #ddd;
	}
	.my-donation table tbody tr td:last-child {
		border-right: none;
	}
	.my-donation .table>thead>tr>th:last-child {
		border-right: none;
	}
	.my-donation .dataTables_wrapper .dataTables_paginate .paginate_button.current,
	.my-donation .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
		background: #999;
		color: #fff !important;
		padding: 3px 10px;
	}
	.my-donation table.dataTable.no-footer {
		border-bottom: 1px solid #ddd;
	}
	.my-rewards .span8 .dashboard-row-top {
    text-align: center;
	padding-bottom: 20px;
}
.my-rewards .dashboard-vendors-activity h4.title-blue {
    color: #3b7dd1;
    font-size: 24px;
    font-weight: bold;
    padding-top: 10px;
}
.blue-button {
    background: #3b7dd1;
    color: #fff;
    border-radius: 5px;
    padding: 10px 50px;
    display: inline-block;
}
.blue-button:hover {
    background: #1d57a1;
    color: #fff;
    text-decoration: none;
}
.my-rewards .span8 .dashboard-row-top img {
    height: 39px;
}
</style>

	<div id="exTab2" class="container my-rewards">	

		<ul class="nav nav-tabs">
			<li class="active"><a  href="#1" data-toggle="tab">Overview</a>
			</li>
			<li><a href="#2" data-toggle="tab">My Transactions</a>
			</li>
			<li><a href="#3" data-toggle="tab">My Earnings</a>
			</li>
			<li><a href="#4" data-toggle="tab">My Donations</a>
			</li>
			<li><a href="#5" data-toggle="tab">My Referrals</a>
			</li>
			{if $lpoUserData}<li><a href="#6" data-toggle="tab">LPO Reports</a>
			</li>{/if}
			<li><a href="#7" data-toggle="tab">My ACH Transactions</a>
			</li>
		</ul>

			<div class="tab-content ">
			  	<div class="tab-pane active overview" id="1">
					
					<h1>Overview</h1>
					<div class="dashboard row-fluid" id="dashboard">
						<div class="dashboard-cards span4">
								<div class="dashboard-card dashboard-card--balance">
									<div class="dashboard-card-title">Available Rewards</div>
									<div class="dashboard-card-content">
										<h3 class="display-on-link">
											<a href="javascript:void(0);" data-id="2">{include file="common/price.tpl" value=$wallet['0']['wallet_amt']}</a>
										</h3>
										&nbsp;
										{if $wallet['0']['wallet_amt']<25}
											<button class="account-btn">Please accumulate $25 to withdraw</button>
										{else}
										<button class="account-btn"><a href="{"ach_payments.transfer"|fn_url}" style="color: #fff;">Transfer Rewards</a></button>
										{/if}										
									</div>
								</div>
							
								<div class="dashboard-card">
									<div class="dashboard-card-title">Linked Accounts</div>
									<div class="dashboard-card-content">
										<h3>
											{$linkedAccount|count}
										</h3>
										&nbsp;
										<button class="account-btn"><a href="{"ach_payments.link_accounts"|fn_url}" style="color: #fff;">Link new Account</a></button>
									</div>
								</div>
							
								<div class="dashboard-card">
									<div class="dashboard-card-title">{__("orders")}</div>
									<div class="dashboard-card-content">
										<h3>
											{if $user_can_view_orders}
												<a href="{"orders.manage?is_search=Y&storefront_id=`$storefront_id`&period=C&time_from=`$time_from`&time_to=`$time_to`"|fn_url}">{$orders_stat.orders|count}</a>
											{else}
												{$reports|count}
											{/if}
										</h3>
									</div>
								</div>
							
								<div class="dashboard-card">
									<div class="dashboard-card-title">Total Rewards Earn to Date</div>
									<div class="dashboard-card-content">
										<h3>{include file="common/price.tpl" value=$totalCashback}</h3>
									</div>
								</div>	

								<div class="dashboard-card">
									<div class="dashboard-card-title">Communication</div>
									<div class="dashboard-card-content">
										&nbsp;
										<button onclick="location.href='/contacts'" class="account-btn">Contact Customer Support</button>
									</div>
								</div>
						</div>
				
						<div class="dashboard-main-column span12">
							
							<div class="dashboard-row-top">
								<div class="dashboard-table dashboard-vendors-activity">
									<h4>Hi {fn_ch_custom_reports_get_username($auth.user_id)}! Welcome to your WeSave Member Account!</h4>
									<p>
										Stay up-to-date with your Cash Back Rewards information, your Refer-A-Friend rewards, recent activity and other WeSave notifications! 
									</p>
									<a href="/shop-and-earn-rewards/">Learn how to earn Cash Back at WeSave</a>
								</div>
							</div>	
							<div class="dashboard-row-top">
									<div class="dashboard-table dashboard-vendors-activity">
										<h4>My activity in the selected period</h4>
										<div id="dashboard_vendors_activity">
											<div class="span8">
												<table class="table">
													<tbody>
													<tr>
														<td class="dashboard-vendors-activity__label">
																Total Rewards Earned
														</td>
														<td class="dashboard-vendors-activity__value">
															${$totalCashback|number_format:2}
														</td>
													</tr>
													<tr>
														<td class="dashboard-vendors-activity__label display-on-link">
															<a href="javascript:void(0);" data-id="2">
																My Earned Rewards from shopping
															</a>
														</td>
														<td class="dashboard-vendors-activity__value">
															${$totalCashback|number_format:2}
														</td>
													</tr>
													<tr>
														<td class="dashboard-vendors-activity__label display-on-link">
															{$url = "companies.manage?extend[]=products&new_products_from={$time_from}&new_products_to={$time_to}&status=A"}
																<a href="javascript:void(0);" data-id="3">
																Earned Rewards from Referrals
															</a>
														</td>
														<td class="dashboard-vendors-activity__value">
															${$totalCashbacker|number_format:2}
														</td>
													</tr>
													</tbody>
												</table>
											</div>
				
											<div class="span8">
												<table class="table">
													<tbody>
														{hook name="index:vendors_activity"}
															<tr>
																<td class="dashboard-vendors-activity__label">
																		Total Rewards Transferred to account
																</td>
																<td class="dashboard-vendors-activity__value">
																	$0.00
																</td>
															</tr>
															<tr>
																<td class="dashboard-vendors-activity__label display-on-link">
																	{hook name="index:dashboard_new_products_link"}
																	{$url = "products.manage?time_from={$time_from}&time_to={$time_to}&period=C&status[]=A&company_status[]=A"}
																	{/hook}
																	<a href="javascript:void(0);" data-id="2">
																		My Completed Orders
																	</a>
																</td>
																<td class="dashboard-vendors-activity__value">
																	{if $user_can_view_orders}
																		<a href="{"orders.manage?is_search=Y&storefront_id=`$storefront_id`&period=C&time_from=`$time_from`&time_to=`$time_to`"|fn_url}">{$orders_stat.orders|count}</a>
																	{else}
																		{$reports|count}
																	{/if}
																</td>
															</tr>
															<tr>
																<td class="dashboard-vendors-activity__label display-on-link">
																	{hook name="index:dashboard_new_products_link"}
																	{$url = "products.manage?time_from={$time_from}&time_to={$time_to}&period=C&status[]=A&company_status[]=A"}
																	{/hook}
																	<a href="javascript:void(0);" data-id="5">
																		Friends I've Enrolled
																	</a>
																</td>
																<td class="dashboard-vendors-activity__value">
																	{if $memberData}
																		{$memberData|count}
																	{else}
																		0
																	{/if}
																</td>
															</tr>
														{/hook}
													</tbody>
												</table>
											</div>
											<!--dashboard_vendors_activity--></div>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span8">
										<div class="dashboard-row-top">
											<div class="dashboard-table dashboard-vendors-activity">
												<img src="images/WeSave-logo-icon-blue.png" height="39">
												<h4 class="title-blue">Refer &amp; Earn </h4>
												<p>
													Stay up-to-date with your Cash Back Rewards information, your Refer-A-Friend rewards, recent activity and other WeSave notifications! 
												</p>
												<a href="/index.php?dispatch=affiliate_plans.list" class="blue-button">Learn More</a>
											</div>
										</div>
									</div>
									<div class="span8">
										<div class="dashboard-row-top">
											<div class="dashboard-table dashboard-vendors-activity">
												<img src="images/WeSave-logo-blue.png">
												<h4 class="title-blue">Shop &amp; Save</h4>
												<p>
													Shop and watch your Cash Back Rewards balance grow! Rewards typically appear in your Account within 2-3 business days. 
												</p>
												<a href="/" class="blue-button">Shop Now</a>
											</div>
										</div>
									</div>
								</div>
							
							<div class="dashboard-row">
								{if !empty($graphs)}
									<div class="dashboard-statistics">
										<h4>
											{__("statistics")}
										</h4>
										{capture name="chart_tabs"}
											<div id="content_sales_chart">
												<div id="dashboard_statistics_sales_chart" class="dashboard-statistics-chart spinner">
												</div>
											</div>
											{hook name="index:chart_statistic"}
											{/hook}
										{/capture}
				
										<div id="statistics_tabs">
											{include file="common/tabsbox.tpl" content=$smarty.capture.chart_tabs}
											<script>
												Tygh.chart_data = {
													{foreach from=$graphs item="graph" key="chart" name="graphs"}
													'{$chart}': [
														{foreach from=$graph item="data" key="date" name="graph"}
														[{if $is_day}[{$date}, 0, 0, 0]{else}new Date({$date}){/if}, {$data.prev}, {$data.cur}]{if !$smarty.foreach.graph.last},{/if}
														{/foreach}
													]{if !$smarty.foreach.graphs.last},{/if}
													{/foreach}
												};
												Tygh.drawChart({$is_day});
											</script>
											<!--statistics_tabs--></div>
									</div>
								{/if}
								{if !empty($order_statuses)}
									<div class="dashboard-recent-orders cm-j-tabs tabs">
										<h4>{__("recent_orders")}</h4>
										<ul class="nav nav-pills">
											<li id="tab_recent_all" class="active cm-js"><a href="#status_all" data-toggle="tab">{__("all")}</a></li>
											{foreach from=$order_statuses item="status"}
												<li id="tab_recent_{$status.status}" class="cm-js"><a href="#status_{$status.status}" data-toggle="tab">{$status.description}</a></li>
											{/foreach}
										</ul>
				
										<div class="cm-tabs-content">
											<div class="tab-pane" id="content_tab_recent_all">
												<div class="table-responsive-wrapper">
													<table class="table table-middle table--relative table-last-td-align-right table-responsive table-responsive-w-titles">
														<tbody>
														{foreach $orders.all as $order}
															<tr>
																<td data-th="&nbsp;">
																	<span class="label btn-info o-status-{$order.status|lower} label--text-wrap">{$order_statuses[$order.status].description}</span>
																</td>
																<td data-th="&nbsp;"><a href="{"orders.details?order_id=`$order.order_id`"|fn_url}">{__("order")} <bdi>#{$order.order_id}</bdi></a> {__("by")} {if $order.user_id}<a href="{"profiles.update?user_id=`$order.user_id`"|fn_url}">{/if}{$order.lastname} {$order.firstname}{if $order.user_id}</a>{/if}</td>
																<td data-th="&nbsp;"><span class="date">{$order.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</span></td>
																<td data-th="&nbsp;"><h4>{include file="common/price.tpl" value=$order.total}</h4></td>
															</tr>
															{foreachelse}
															<tr><td data-th="&nbsp;">{__("no_data")}</td></tr>
														{/foreach}
														</tbody>
													</table>
												</div>
											</div>
											{foreach $order_statuses as $status}
												<div class="tab-pane" id="content_tab_recent_{$status.status}">
													<div class="table-responsive-wrapper">
														<table class="table table-middle table--relative table-last-td-align-right table-responsive table-responsive-w-titles">
															<tbody>
															{foreach $orders[$status.status] as $order}
																<tr>
																	<td data-th="&nbsp;">
																		<span class="label btn-info o-status-{$order.status|lower} label--text-wrap">{$order_statuses[$order.status].description}</span>
																	</td>
																	<td data-th="&nbsp;"><a href="{"orders.details?order_id=`$order.order_id`"|fn_url}">{__("order")} <bdi>#{$order.order_id}</bdi></a> {__("by")} {if $order.user_id}<a href="{"profiles.update?user_id=`$order.user_id`"|fn_url}">{/if}{$order.lastname} {$order.firstname}{if $order.user_id}</a>{/if}</td>
																	<td data-th="&nbsp;"><span class="date">{$order.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</span></td>
																	<td data-th="&nbsp;"><h4>{include file="common/price.tpl" value=$order.total}</h4></td>
																</tr>
																{foreachelse}
																<tr><td data-th="&nbsp;">{__("no_data")}</td></tr>
															{/foreach}
															</tbody>
														</table>
													</div>
												</div>
											{/foreach}
										</div>
									</div>
								{/if}
							</div>
				
							<div class="dashboard-row-bottom">
								<div class="dashboard-tables">
				
									{hook name="index:order_statistic"}
									{/hook}
				
									{hook name="index:order_by_statuses"}
									{if $user_can_view_orders && $order_by_statuses}
										<div class="dashboard-table dashboard-table-order-by-statuses">
											<h4>{__("order_by_status")}</h4>
											<div class="table-wrap" id="dashboard_order_by_status">
												<table class="table">
													<thead>
													<tr>
														<th width="25%">{__("status")}</th>
														<th width="25%">{__("qty")}</th>
														<th width="25%">{__(total)}</th>
														<th width="25%">{__("shipping")}</th>
													</tr>
													</thead>
												</table>
												<div class="scrollable-table">
													<table class="table table-striped table--relative">
														<tbody>
														{foreach from=$order_by_statuses item="order_status"}
															{$url = "orders.manage?is_search=Y&storefront_id=`$storefront_id`&period=C&time_from=`$time_from`&time_to=`$time_to`&status[]=`$order_status.status`"|fn_url}
															<tr>
																<td width="25%"><a class="a--text-wrap" href="{$url}" title="{$order_status.status_name}">{$order_status.status_name}</a></td>
																<td width="25%">{$order_status.count}</td>
																<td width="25%">{include file="common/price.tpl" value=$order_status.total}</td>
																<td width="25%">{include file="common/price.tpl" value=$order_status.shipping}</td>
															</tr>
														{/foreach}
														</tbody>
													</table>
												</div>
												<!--dashboard_order_by_status--></div>
										</div>
									{/if}
									{/hook}
								</div>
				
								{if $logs && "logs.manage"|fn_check_view_permissions:"GET"}
									<div class="dashboard-activity">
										<div class="pull-right"><a href="{"logs.manage"|fn_url}">{__('show_all')}</a></div>
										<h4>{__("recent_activity")}</h4>
										{function name="show_log_row" item=[]}
											{if $item}
												<div class="item">
													{hook name="index:recent_activity"}
													{$_type = "log_type_`$item.type`"}
													{$_action = "log_action_`$item.action`"}
				
													{__($_type)}{if $item.action}&nbsp;({__($_action)}){/if}:
				
													{if $item.type == "users" && "profiles.update?user_id=`$item.content.id`"|fn_url|fn_check_view_permissions:"GET"}
														{if $item.content.id}<a href="{"profiles.update?user_id=`$item.content.id`"|fn_url}">{/if}{$item.content.user}{if $item.content.id}</a>{/if}<br>
				
													{elseif $item.type == "orders" && "orders.details?order_id=`$item.content.id`"|fn_url|fn_check_view_permissions:"GET"}
														{$item.content.status}<br>
														<a href="{"orders.details?order_id=`$item.content.id`"|fn_url}">{__("order")}&nbsp;{$item.content.order}</a><br>
													{elseif $item.type == "products" && "products.update?product_id=`$item.content.id`"|fn_url|fn_check_view_permissions:"GET"}
														<a href="{"products.update?product_id=`$item.content.id`"|fn_url}">{$item.content.product}</a><br>
				
													{elseif $item.type == "categories" && "categories.update?category_id=`$item.content.id`"|fn_url|fn_check_view_permissions:"GET"}
														<a href="{"categories.update?category_id=`$item.content.id`"|fn_url}">{$item.content.category}</a><br>
													{/if}
				
													{hook name="index:recent_activity_item"}{/hook}
				
														<span class="date">{$item.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</span>
													{/hook}
												</div>
											{/if}
										{/function}
				
										<div class="dashboard-activity-list">
											{foreach from=$logs item="item"}
												{show_log_row item=$item}
											{/foreach}
										</div>
									</div>
								{/if}
							</div>
						</div>
						<!--dashboard--></div>
					<!-- {if $getOverview}
						{$totalRewardsPoints = 0}
						{foreach from=$getOverview item=overview}
							{if $overview.action == 'A'} {$totalRewardsPoints = $totalRewardsPoints + $overview.amount} {/if}
						{/foreach}
						<p style="float:right;">Total Earning Points: <b>{$totalRewardsPoints}</b></p>
					{/if}
					<table class="table display" id="overview">
						<thead>
							<tr>
								<th>Date</th>
								<th>Points Spent</th>
								{*<th>Points Earned</th>*}
								<th>Cashback</th>
								{*<th>Remaining Balance</th>*}
							</tr>
						</thead>
						<tbody>
							{if !empty($reports) || !empty($myEarnings)}
								{foreach from=$reports item=rep}
									<tr>
										<td>{date('m-d-Y h:i:sa', strtotime($rep.created_at))}</td>
										<td>{$rep.pointRedemped}</td>
										<td>{$rep.cashback}</td>
									</tr>
								{/foreach}

								{foreach from=$myEarnings item=myEarn}
									<tr>
										<td>{date('m-d-Y h:i:sa', strtotime($myEarn.created_at))}</td>
										<td>{$myEarn.pointRedemped}</td>
										<td>{$myEarn.cashback}</td>
									</tr>
								{/foreach}
							{else}
								<tr>
									<td colspan="4">No Transactions Found.</td>
								</tr>
							{/if}
						</tbody>
					</table> -->
			  	</div>

				<div class="tab-pane my-transaction" id="2">
						<h1>Transactions Report</h1>
				<table class="table display" id="">
							<thead>
								<tr>
									<th>Date</th>
									<th>Type</th>
									<th>Order ID #</th>
									<th>Vendor</th>
									{*<th>Points Redeemed</th>
									<th>Points Earned</th>*}
									<th>Cashback</th>
									<th>Generated Donations</th>
									<th>Order Subtotal</th>
								</tr>
							</thead>
							<tbody>
								{if $reports}
									{foreach from=$reports item=rep}
										<tr>
											<td>{date('m-d-Y h:i:sa', strtotime($rep.created_at))}</td>
											<td>{$rep.pointsFrom}</td>
											{if $rep.from_indi == 1}
											{$companyUrl = fn_ch_custom_reports_init_templater($rep.companyId)}
											<td><a url="{$companyUrl|replace:'{subid}':{$auth.user_id}}" class="buy_now">{$rep.affiliate_order_id}</a></td>
											{else}
											<td><a href="{$orderLink}{$rep.purchaseId}">{$rep.purchaseId}</a></td>
											{/if}
											<td>
											{if $rep.seller_id != ""} 
												<a href="{"companies.products?company_id={$rep.companyId}"|fn_url}" target="_blank">{$rep.company}</a> {else} -- {/if}
											</td>
												{*<td>{$rep.pointRedemped}</td>*}
											<td>${$rep.cashback|number_format:2}</td>
											<td>{if $rep.donation != ''}${assign var="donation" value=$rep.donation|fn_my_changes_donation_function} {else}$0{/if}</td>
											<td>${$rep.totalTransaction}</td>
										</tr>
									{/foreach}
								{else}
									<tr>
										<td colspan="7">No Transactions Found.</td>
									</tr>
								{/if}
							</tbody>
						</table>
					
				</div>
				
        		<div class="tab-pane my-earnings" id="3">
					<ul class="nav nav-tabs">
						<li class="active"><a  href="#customer" data-toggle="tab">Customer</a>
						</li>
						<li><a href="#vendor" data-toggle="tab">Vendor</a>
						</li>
					</ul>

					<div class="tab-content ">
						<div class="tab-pane active" id="customer">
							{if $myEarnings}
								<h1>My Earnings</h1>
								<table class="table display" id="">
									<thead>
										<tr>
											<th>ID</th>
											<th>Type</th>
											<th>Customer #</th>
											<th>Order ID #</th>
											<th>Transaction Total</th>
											{*<th>Points Earned</th>*}
											<th>My Earnings</th>
										</tr>
									</thead>
									<tbody>
										{foreach from=$myEarnings item=myEarn}
										{if $myEarn.transactionByUserType == 3}
											<tr>
												<td>{$myEarn.id}</td>
												<td>{$myEarn.pointsFrom}</td>
												<td>{* $myEarn.transactionBy *}{$myEarn.csc_id}</td>
												{if $myEarn.from_indi == 1 }
												<td>{$myEarn.affiliate_order_id}</td>
												{else}
												<td>{$myEarn.purchaseId}</td>
												{/if}
												<td>${assign var="price" value=$myEarn.totalTransaction|fn_my_changes_price_function}</td>
												{if $myEarn.myEarning == 0 }
												<td data-toggle="tooltip" title="You are not qualified for cashback. Order total is less than the required percentage!">Unqualified</td>
												{else}
												<td>{$myEarn.myEarning}<td>
												{/if}
											</tr>
										{/if}
										{if $myEarn.transactionByUserType == 4 && $myEarn.memberStatus == 1}
											<tr>
												<td>{$myEarn.id}</td>
												<td>{$myEarn.pointsFrom}</td>
												<td>{* $myEarn.transactionBy *}{$myEarn.csc_id}</td>
												{if $myEarn.from_indi == 1 }
												<td>{$myEarn.affiliate_order_id}</td>
												{else}
												<td>{$myEarn.purchaseId}</td>
												{/if}
												<td>${assign var="price" value=$myEarn.totalTransaction|fn_my_changes_price_function}</td>
												{if $myEarn.myEarning == 0 }
												<td data-toggle="tooltip" title="You are not qualified for cashback. Order total is less than the required percentage!">Unqualified</td>
												{else}
												<td>{$myEarn.myEarning}<td>
												{/if}
											</tr>
										{/if}
										{/foreach}
									</tbody>
								</table>
							{else}
								<p>No Transactions Found.</p>
							{/if}
						</div>

						<div class="tab-pane" id="vendor">
							{if $myEarnings}
								<h1>My Earnings</h1>
								<table class="table display" id="myEarningV">
									<thead>
										<tr>
											<th>ID</th>
											<th>Type</th>
											<th>Customer #</th>
											<th>Order ID #</th>
											<th>Transaction Total</th>
											{*<th>Points Earned</th>*}
											<th>My Earnings</th>
										</tr>
									</thead>
									<tbody>
										{foreach from=$myEarnings item=myEarn}
										{if $myEarn.transactionByUserType == 4 && $myEarn.memberStatus == 2}
											<tr>
												<td>{$myEarn.id}</td>
												<td>{$myEarn.pointsFrom}</td>
												<td>{$myEarn.transactionBy}</td>
												<td>{$myEarn.purchaseId}</td>
												<td>${assign var="price" value=$myEarn.totalTransaction|fn_my_changes_price_function}</td>
												<td>{$myEarn.myEarning}</td>
											</tr>
										{/if}
										{if $myEarn.transactionByUserType == 4 && $myEarn.memberStatus == Null}
											<tr>
												<td>{$myEarn.id}</td>
												<td>{$myEarn.pointsFrom}</td>
												<td>{$myEarn.transactionBy}</td>
												<td>{$myEarn.purchaseId}</td>
												<td>${assign var="price" value=$myEarn.totalTransaction|fn_my_changes_price_function}</td>
												<td>{$myEarn.myEarning}</td>
											</tr>
										{/if}
										{/foreach}
									</tbody>
								</table>
							{else}
								<p>No Transactions Found.</p>
							{/if}
						</div>
					</div>
				</div>

				<div class="tab-pane my-donation" id="4">
					<h1>Donation Report</h1>
					<table class="table display compact" id="">
						<thead>
							<tr>
								<th>Date</th>
								<th>Type</th>
								<th>Order ID #</th>
								<th>Donation</th>
								<th>Donated to</th>
								<th>Tax ID </th>
							</tr>
						</thead>
						<tbody>
						{if $reports }
							{foreach from=$reports item=rep}
								<tr>
									<td>{date('m-d-Y h:i:sa', strtotime($rep.created_at))}</td>
									<td>{$rep.pointsFrom}</td>
									{if $rep.from_indi == 1}
										{$companyUrl = fn_ch_custom_reports_init_templater($rep.companyId)}
										<td><a url="{$companyUrl|replace:'{subid}':{$auth.user_id}}" class="buy_now">{$rep.affiliate_order_id}</a></td>
									{else}
										<td><a href="{$orderLink}{$rep.purchaseId}">{$rep.purchaseId}</a></td>
									{/if}
									<td>{if $rep.donation != ''}${assign var="donation" value=$rep.donation|fn_my_changes_donation_function} {else}$0{/if}</td>
									<td>Help Worldwide Foundation</td>
									<td>26-3808748</td>
								</tr>
							{/foreach}
						{else}
							<tr>
								<td colspan="6">No Transactions Found.</td>
							</tr>
						{/if}
						</tbody>
					</table>
				</div>

				<div class="tab-pane" id="5">
					<h1>My Affiliates</h1>
				     <ul class="nav nav-tabs">
						<li class="active"><a  href="#tree" data-toggle="tab">Tree</a>
						</li>
						<li><a href="#table" data-toggle="tab">Table</a>
						</li>
					</ul>

					<div class="tab-content ">
						<br/>
						<div class="tab-pane active" id="tree">
							{if $hierarchyresponse}
								{$hierarchyresponse nofilter}
							{else}
								<p>No Transactions Found.</p>
							{/if}
						</div>

						<div class="tab-pane" id="table">
							
							<table class="table display" id="">
								<thead>
									<tr>
										<th>Date Enrolled</th>
										<th>Customer #</th>
										{* <th>Tier</th> *}
										<th>Referral ID</th>
										<!-- <th>Total Trans (done by this person)</th> -->
										<!-- <th>Reward</th> -->
										<!-- <th>Total Earnings</th> -->
									</tr>
								</thead>
								<tbody>
									{if $memberData}
										{foreach from=$memberData item=reportData}
											<tr>
												<td>{date('m-d-Y h:i:sa', strtotime($reportData.created_at))}</td>
												<td>{$reportData.csc_id}</td>
												{* <td>{$reportData.csc_id}</td> *}
												<td>{$reportData.createdBy}</td>
												<!-- <td>${assign var="price" value=$reportData.totalTransaction|fn_my_changes_donation_function}</td> -->
												<!-- <td>{$reportData.totalPoints}</td> -->
												<!-- <td>rewards</td> -->
											</tr>
										{/foreach}
									{else}
										<tr>
											<td>No Transactions Found.</td>
										</tr>
									{/if}
								</tbody>
							</table>
							
						</div>
					</div>
				</div>


				{if $lpoUserData}
				<div class="tab-pane lpo-reports" id="6">
					<ul class="nav nav-tabs">
						<li class="active"><a  href="#lpoCustomer" data-toggle="tab">Customer</a>
						</li>
						<li><a href="#lpoVendor" data-toggle="tab">Vendor</a>
						</li>
					</ul>

					<div class="tab-content ">
						<div class="tab-pane active" id="lpoCustomer">

							
							<h1>Member Reports</h1>
							<table class="table display" id="myLpoCustomer">
								<thead>
									<tr>
										<th>ID</th>
										<th>Type</th>
										<th>Customer #</th>
										<th>Order ID #</th>
										<th>Transaction Total</th>
										{*<th>Points Earned</th>*}
										<th>My Earnings</th>
									</tr>
								</thead>
								<tbody>
								{if $lpoUserData}
									{foreach from=$lpoUserData item=myEarn}
										{if $myEarn.transactionByUserType == 3}
											<tr>
												<td>{$myEarn.id}</td>
												<td>{$myEarn.pointsFrom}</td>
												<td>{$myEarn.transactionBy}</td>
												<td>{$myEarn.purchaseId}</td>
												<td>${assign var="price" value=$myEarn.totalTransaction|fn_my_changes_price_function}</td>
												<td>{$myEarn.myEarning}</td>
											</tr>
										{/if}
									{/foreach}
								{else}
									<tr>
										<td>No Transactions Found.</td>
									</tr>
								{/if}
								</tbody>
							</table>
							
						</div>

						<div class="tab-pane" id="lpoVendor">
								<h1>My Earnings</h1>
								<table class="table display" id="myLpoVendor">
									<thead>
										<tr>
											<th>ID</th>
											<th>Type</th>
											<th>Customer #</th>
											<th>Order ID #</th>
											<th>Transaction Total</th>
											{*<th>Points Earned</th>*}
											<th>My Earnings</th>
										</tr>
									</thead>
									<tbody>
									{if $lpoUserData}
										{foreach from=$lpoUserData item=myEarn}
										{if $myEarn.transactionByUserType == 4}
											<tr>
												<td>{$myEarn.id}</td>
												<td>{$myEarn.pointsFrom}</td>
												<td>{$myEarn.transactionBy}</td>
												<td>{$myEarn.purchaseId}</td>
												<td>${assign var="price" value=$myEarn.totalTransaction|fn_my_changes_price_function}</td>
												<td>{$myEarn.myEarning}</td>
											</tr>
										{/if}
										{/foreach}
									{else}
										<tr>
											<td>No Transactions Found.</td>
										</tr>
									{/if}
									</tbody>
								</table>
							
						</div>
					</div>
				</div>
				{/if}
				<div class="tab-pane my-donation" id="7">
					<h1>My ACH Transactions</h1>
					<table class="table display compact" id="">
						<thead>
							<tr>
								<th>Date</th>
								<th>Amount</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
						{if $transactionhistory }
							{foreach from=$transactionhistory item=transrep}
								<tr>
									<td>{date('m-d-Y h:i:sa', strtotime($transrep.withdrawl_date))}</td>
									<td>{$transrep.withdrawl_amt}</td>
									<td>{$transrep.status}</td>
								</tr>
							{/foreach}
						{else}
							<tr>
								<td colspan="6">No Transactions Found.</td>
							</tr>
						{/if}
						</tbody>
					</table>
				</div>
			</div>
  	</div>

  	{* <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-migrate-3.3.2.js"></script>*}
  	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  	<script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script>
		$( document ).ready(function() {
			$('.display').DataTable({
				"order": [[ 0, "desc" ]],
			});
		});
		
		$('li').on("click", function(){
			$('.display').DataTable();
		});
		$(".display-on-link").on('click', function() {
			var dataid =  $(this).children("a").attr("data-id");
			var tabid = parseInt(dataid);
			$(".nav-tabs li:nth-child("+tabid+") a").click();
		});
	</script>
  	{else}
		<script>
			location.href = "http://{$config.http_host}";
		</script>
		<style>

			/* The Modal (background) */
			.modal {
				position: fixed; /* Stay in place */
				z-index: 99999; /* Sit on top */
				padding-top: 100px; /* Location of the box */
				left: 0;
				top: 0;
				width: 100%; /* Full width */
				height: 100%; /* Full height */
				overflow: auto; /* Enable scroll if needed */
				background-color: rgb(0,0,0); /* Fallback color */
				background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
			}

			/* Modal Content */
			.modal-content {
				background-color: #fefefe;
				margin: auto;
				padding: 20px;
				border: 1px solid #888;
				width: 385px;
			}

			/* The Close Button */
			.close {
				color: #aaaaaa;
				float: right;
				font-size: 28px;
				font-weight: bold;
			}

			.close:hover,
			.close:focus {
				color: #000;
				text-decoration: none;
				cursor: pointer;
			}

			.modal-header{
			    display: flex;
				align-items: center;
				padding: 0 50px 0 20px;
				height: 50px;
				width: 385px;
				margin: auto;
				background: #007aff;
				border: 0 none;
				z-index: 99999;
			}

			.modal-title{
				padding-right: 0;
				font-family: MuseoSansBlack,'Helvetica Neue',Arial,-apple-system,sans-serif;
				font-weight: normal;
				font-size: 20px;
				color: #fff;
				padding-top: 15px;
			}

		</style>
		{** <div id="myModal" class="modal">
		<div class="modal-header">
			<h4 class="modal-title">Sign in</h4>
		</div>
		<!-- Modal content -->
		<div class="modal-content">
		
			{include file="views/auth/popup_login_form.tpl"}
		</div>

		</div> **}
  	{/if}