<style>
  input{
    width:70%;
    background:#fff !important;
    color:#000 !important;
    padding: 17px 11px;
  }
  .aff_link_content{
  	background: rgb(246, 246, 246);
    padding: 15px;
    border-radius: 0px;
  }
  .aff_header{
  	margin-bottom: 20px;
  }
  h1{
  	font-size: 27px;
  }
  input[type=text] {
    cursor: auto !important;
  }
</style>
<script>
  document.querySelector('.copyLink').onclick = function() {
    this.select();
    document.execCommand('copy');
    alert('Copied');
  }
  document.querySelector('.copyCompanyLink').onclick = function() {
    this.select();
    document.execCommand('copy');
    alert('Copied');
  }
</script>
{capture name="mainbox"}
{include file="common/pagination.tpl" save_current_page=true save_current_url=true}
    <div class="aff_header">
        {*<h1>Affiliates / Affiliate plan</h1>*}
        <h1>Affiliate Links</h1>
    	<p><b>Take part in our partnership program and get reward points to pay for products in the store. The amount of reward points depends on the commission rates of your plan.</b></p>
    	<p>Refer your friends and family to shop and earn rewards when they buy!</p>
    </div>


    <div class="reward_points tabcontent">

      {assign var='videoLink' value='affiliate_links'|fn_my_changes_get_upload_product_affiliate_details:'affiliate_links':'video'}
      {assign var='documentLink' value='affiliate_links'|fn_my_changes_get_upload_product_affiliate_details:'affiliate_links':'document'}
      
      {if $videoLink != null || $documentLink != null}
        <h4>For guidance on how to copy affiliate link please select a link below</h4>
      {/if}

      {assign var="videoCount" value=1}
      {foreach $videoLink as $vlink}
          {*{if $videoLink != null} <a data-ca-target-id="popup-{$vlink['id']}" class="cm-dialog-opener cm-dialog-auto-size">Video {$videoCount++}</a>,{/if} *}

            <video width="200" height="120" controls>
                <source src="{$vlink['url']}">
            </video>

            <div class="product-options hidden" id="popup-{$vlink['id']}" title="Video Information">
                <div style="width: 1000px; height: 500px; overflow: hidden;">
                    <video controls="" style="width: 100%; height: 500px;"> 
                        <source src="{$vlink['url']}" type="video/mp4">
                    </video>
                </div>
            </div>
        {/foreach}

        {if $videoLink != null && $documentLink != null} & {/if}

        {assign var="docCount" value=1}
        {foreach $documentLink as $dlink}
            {if $documentLink != null}<a href="#" onclick="window.open('{$dlink['url']}', '_blank', 'resizable=yes, scrollbars=yes, titlebar=yes, width=800, height=900, top=10, left=10'); return false;"><i class="icon-file-text" style="font-size:40px;"></i></a>{/if}
        {/foreach}
    </div>
   <br/>
	<div class="aff_link_content">
	  <p>Your affiliate ID is {$link[0]}. To receive commission according to the plan you can add <b>?aff_id={$link[0]}</b> (or <b>&aff_id={$link[0]}</b> if the page link already contains "?") to any link to our store. For example, here is the link for registration of a new affiliate:</p>
    <p>Click on the link to copy</p>
	  <div class="affiliate_link">
    <label><b>Member enrollment page Link</b></label>
    <input type="text" name="affiliate_link" class="copyLink" value="http://{$config.http_host}/index.php?dispatch=profiles.add&aff_id={$link[0]}" readonly>
    </div>
	  <div class="affiliate_link_with_company_id">
    <label><b>Vendor Store</b></label>
    <input type="text" name="affiliate_link_with_company_id" class="copyCompanyLink" value="http://{$config.http_host}/index.php?dispatch=companies.products&company_id={$link[1]}" readonly>
    </div>

	</div>
{/capture}
{include file="common/mainbox.tpl" title=__("vendor_plans.vendor_plans") content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons sidebar=$smarty.capture.sidebar}