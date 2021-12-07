{capture name="mainbox"}
{include file="common/pagination.tpl" save_current_page=true save_current_url=true}

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<style>
	table{
		width:100%
		margin-top: 20px;
	}
	table tr:nth-child(even){
		background:#f1f0f0;
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

</style>
	<div id="exTab2" class="container">	

		<ul class="nav nav-tabs">
			<li class="active"><a  href="#1" data-toggle="tab">Overview</a>
			</li>
			<li><a href="#2" data-toggle="tab">My Transactions</a>
			</li>
			<li><a href="#4" data-toggle="tab">My Donations</a>
			</li>
			<li><a href="#5" data-toggle="tab">My Affiliates</a>
			</li>
			<li><a href="#3" data-toggle="tab">My Earnings</a>
			</li>
			{if $lpoUserData}<li><a href="#6" data-toggle="tab">LPO Reports</a>
			</li>{/if}
		</ul>

			<div class="tab-content ">
			  	<div class="tab-pane active" id="1">
					{if $getOverview}
						{$totalRewardsPoints = 0}
						{foreach from=$getOverview item=overview}
							{if $overview.action == 'A'} {$totalRewardsPoints = $totalRewardsPoints + $overview.amount} {/if}
						{/foreach}
						<p style="float:right;">Total Earning Points: <b>{$totalRewardsPoints}</b></p>
					{/if}
					<table class="table display">
						<thead>
							<tr>
								<th>Date</th>
								<th>Points Spent</th>
								<th>Points Earned</th>
								{*<th>Remaining Balance</th>*}
							</tr>
						</thead>
						<tbody>
							{if !empty($reports) || !empty($myEarnings)}
								{foreach from=$reports item=rep}
									<tr>
										<td>{date('m-d-Y h:i:sa', strtotime($rep.created_at))}</td>
										<td>{$rep.pointRedemped}</td>
										<td>{$rep.points}</td>
									</tr>
								{/foreach}

								{foreach from=$myEarnings item=myEarn}
									<tr>
										<td>{date('m-d-Y h:i:sa', strtotime($myEarn.created_at))}</td>
										<td>{$myEarn.pointRedemped}</td>
										<td>{$myEarn.points}</td>
									</tr>
								{/foreach}
							{else}
								<tr>
									<td colspan="4">No Transactions Found.</td>
								</tr>
							{/if}
						</tbody>
					</table>
			  	</div>

				<div class="tab-pane" id="2">
					  {if $reports }
						<h1>Transactions Report</h1>
						<table class="table display">
							<thead>
								<tr>
									<th>Date</th>
									<th>Type</th>
									<th>Order ID #</th>
									<th>Vendor</th>
									<th>Points Redeemed</th>
									<th>Points Earned</th>
									<th>Generated Donations</th>
								</tr>
							</thead>
							<tbody>
								{foreach from=$reports item=rep}
									<tr>
										<td>{date('m-d-Y h:i:sa', strtotime($rep.created_at))}</td>
										<td>{$rep.pointsFrom}</td>
										<td><a href="{$orderLink}{$rep.purchaseId}" target="_blank">{$rep.purchaseId}</a></td>
										<td>{if $rep.seller_id != ""} <a href="http://store.wesave.com/index.php?dispatch=companies.products&company_id={$rep.companyId}" target="_blank">{$rep.sellerUserName}</a> {else} -- {/if}</td>
										<td>{$rep.pointRedemped}</td>
										<td>{$rep.points}</td>
										<td>{if $rep.donation != ''}${assign var="donation" value=$rep.donation|fn_my_changes_donation_function} {else}$0{/if}</td>
									</tr>
								{/foreach}
							</tbody>
						</table>
					{else}
						<p>No Transactions Found.</p>
					{/if}
				</div>
				
        		<div class="tab-pane" id="3">
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
								<table class="table display">
									<thead>
										<tr>
											<th>ID</th>
											<th>Type</th>
											<th>Customer Name</th>
											<th>Order ID #</th>
											<th>Transaction Total</th>
											<th>Points Earned</th>
										</tr>
									</thead>
									<tbody>
										{foreach from=$myEarnings item=myEarn}
										{if $myEarn.transactionByUserType == 3}
											<tr>
												<td>{$myEarn.id}</td>
												<td>{$myEarn.pointsFrom}</td>
												<td>{$myEarn.transactionByUserName}</td>
												<td>{$myEarn.purchaseId}</td>
												<td>${assign var="price" value=$myEarn.totalTransaction|fn_my_changes_price_function}</td>
												<td>{$myEarn.points}</td>
											</tr>
										{/if}
										{if $myEarn.transactionByUserType == 4 && $myEarn.memberStatus == 1}
											<tr>
												<td>{$myEarn.id}</td>
												<td>{$myEarn.pointsFrom}</td>
												<td>{$myEarn.transactionByUserName}</td>
												<td>{$myEarn.purchaseId}</td>
												<td>${assign var="price" value=$myEarn.totalTransaction|fn_my_changes_price_function}</td>
												<td>{$myEarn.points}</td>
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
								<table class="table display">
									<thead>
										<tr>
											<th>ID</th>
											<th>Type</th>
											<th>Customer Name</th>
											<th>Order ID #</th>
											<th>Transaction Total</th>
											<th>Points Earned</th>
										</tr>
									</thead>
									<tbody>
										{foreach from=$myEarnings item=myEarn}
										{if $myEarn.transactionByUserType == 4 && $myEarn.memberStatus == 2}
											<tr>
												<td>{$myEarn.id}</td>
												<td>{$myEarn.pointsFrom}</td>
												<td>{$myEarn.transactionByUserName}</td>
												<td>{$myEarn.purchaseId}</td>
												<td>${assign var="price" value=$myEarn.totalTransaction|fn_my_changes_price_function}</td>
												<td>{$myEarn.points}</td>
											</tr>
										{/if}
										{if $myEarn.transactionByUserType == 4 && $myEarn.memberStatus == Null}
											<tr>
												<td>{$myEarn.id}</td>
												<td>{$myEarn.pointsFrom}</td>
												<td>{$myEarn.transactionByUserName}</td>
												<td>{$myEarn.purchaseId}</td>
												<td>${assign var="price" value=$myEarn.totalTransaction|fn_my_changes_price_function}</td>
												<td>{$myEarn.points}</td>
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

				<div class="tab-pane" id="4">
					<h1>Donation Report</h1>
					<table class="table display">
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
									<td><a href="{$orderLink}{$rep.purchaseId}" target="_blank">{$rep.purchaseId}</a></td>
									<td>{if $rep.donation != ''}${assign var="donation" value=$rep.donation|fn_my_changes_donation_function} {else}$0{/if}</td>
									<td>Help Worldwide Foundation</td>
									<td>--</td>
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

				<div class="tab-pane" id="5">
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
							
							<table class="table display">
								<thead>
									<tr>
										<th>Date Enrolled</th>
										<th>Name</th>
										<th>Tier</th>
										<th>Referral ID</th>
										<th>Total Trans (done by this person)</th>
										<th>Total Earnings</th>
									</tr>
								</thead>
								<tbody>
									{if $memberData}
										{foreach from=$memberData item=reportData}
											<tr>
												<td>{date('m-d-Y h:i:sa', strtotime($reportData.created_at))}</td>
												<td>{$reportData.firstName} {$reportData.lastName}</td>
												<td>{$reportData.id}</td>
												<td>{$reportData.createdBy}</td>
												<td>${assign var="price" value=$reportData.totalTransaction|fn_my_changes_donation_function}</td>
												<td>{$reportData.totalPoints}</td>
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
				<div class="tab-pane" id="6">
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
										<th>Customer Name</th>
										<th>Order ID #</th>
										<th>Transaction Total</th>
										<th>Points Earned</th>
									</tr>
								</thead>
								<tbody>
								{if $lpoUserData}
									{foreach from=$lpoUserData item=myEarn}
										{if $myEarn.transactionByUserType == 3}
											<tr>
												<td>{$myEarn.id}</td>
												<td>{$myEarn.pointsFrom}</td>
												<td>{$myEarn.transactionByUserName}</td>
												<td>{$myEarn.purchaseId}</td>
												<td>${assign var="price" value=$myEarn.totalTransaction|fn_my_changes_price_function}</td>
												<td>{$myEarn.points}</td>
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
											<th>Customer Name</th>
											<th>Order ID #</th>
											<th>Transaction Total</th>
											<th>Points Earned</th>
										</tr>
									</thead>
									<tbody>
									{if $lpoUserData}
										{foreach from=$lpoUserData item=myEarn}
										{if $myEarn.transactionByUserType == 4}
											<tr>
												<td>{$myEarn.id}</td>
												<td>{$myEarn.pointsFrom}</td>
												<td>{$myEarn.transactionByUserName}</td>
												<td>{$myEarn.purchaseId}</td>
												<td>${assign var="price" value=$myEarn.totalTransaction|fn_my_changes_price_function}</td>
												<td>{$myEarn.points}</td>
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

			</div>
  	</div>

  	{*<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>*}
	{*<script src="https://code.jquery.com/jquery-migrate-3.3.2.js"></script>*}
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
	</script>


{/capture}
{include file="common/mainbox.tpl" title="Vendor Reports" content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons sidebar=$smarty.capture.sidebar}
