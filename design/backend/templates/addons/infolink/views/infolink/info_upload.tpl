{capture name="mainbox"}
{include file="addons/infolink/infomenu.tpl"}
<h4>Products</h4>
<div id="tabs">
    <ul>
        <li><a href="#general">General</a></li>
        <li><a href="#seo">SEO</a></li>
        {* <li><a href="#options">Options</a></li> *}
        <li><a href="#variations">Variations</a></li>
        <li><a href="#shippings">Shipping properties</a></li>
        <li><a href="#qty_discounts">Quantity discounts</a></li>
        <li><a href="#subscribers">Subscribers</a></li>
        <li><a href="#addons">Add-ons</a></li>
        <li><a href="#features">Features</a></li>
        <li><a href="#buy_together">Buy together</a></li>
        <li><a href="#tags">Tags</a></li>
        <li><a href="#reward_points">Reward points</a></li>
        <li><a href="#ab__video_gallery">AB: Video gallery of the product</a></li>
        <li><a href="#wk_store_pickup">Store Pickup</a></li>
    </ul>

    <br/>
    <h5 style="padding-left: 20px;">Note: Only PDF, WEBM, MP4 extension are allowed.</h5>
    <div id="general">
        <h4>General</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "products">
            <input type="hidden" name="tabName" value = "general">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
       
       <h5>Currently Use: </h5>
        {assign var='videoLink' value='products'|fn_my_changes_get_upload_details_function:'general':'video'}
        {assign var="documentLink" value='products'|fn_my_changes_get_upload_details_function:'general':'document'}
    
        {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}


    </div>
    <div id="seo">
        <h4>SEO</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "products">
            <input type="hidden" name="tabName" value = "seo">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='products'|fn_my_changes_get_upload_details_function:'seo':'video'}
        {assign var="documentLink" value='products'|fn_my_changes_get_upload_details_function:'seo':'document'}
        
       {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

    </div>

    {* <div id="options">
        <h4>Options Remove Option from this functionality</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "products">
            <input type="hidden" name="tabName" va666666lue = "options">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>
        <p>Video: <a href="{assign var='var1' value='products'|fn_my_changes_get_upload_details:'options':'video'}" target="_blank">{assign var='var1' value='products'|fn_my_changes_get_upload_details:'options':'video'}</a></p>
        <p>Document: <a href="{assign var='var1' value='products'|fn_my_changes_get_upload_details:'options':'document'}" target="_blank">{assign var='var1' value='products'|fn_my_changes_get_upload_details:'options':'document'}</a></p>
    </div> *}
    <div id="variations">
        <h4>Variations</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "products">
            <input type="hidden" name="tabName" value = "variations">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>
        {assign var='videoLink' value='products'|fn_my_changes_get_upload_details_function:'variations':'video'}
        {assign var="documentLink" value='products'|fn_my_changes_get_upload_details_function:'variations':'document'}
        
       {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

    </div>
    <div id="shippings">
        <h4>Shipping properties</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "products">
            <input type="hidden" name="tabName" value = "shippings">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='products'|fn_my_changes_get_upload_details_function:'shippings':'video'}
        {assign var="documentLink" value='products'|fn_my_changes_get_upload_details_function:'shippings':'document'}
        
       {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}
    </div>

    <div id="qty_discounts">
        <h4>Quantity discounts</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "products">
            <input type="hidden" name="tabName" value = "qty_discounts">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='products'|fn_my_changes_get_upload_details_function:'qty_discounts':'video'}
        {assign var="documentLink" value='products'|fn_my_changes_get_upload_details_function:'qty_discounts':'document'}
        
       {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}
    </div>
    <div id="subscribers">
        <h4>Subscribers</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "products">
            <input type="hidden" name="tabName" value = "subscribers">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>
        {assign var='videoLink' value='products'|fn_my_changes_get_upload_details_function:'subscribers':'video'}
        {assign var="documentLink" value='products'|fn_my_changes_get_upload_details_function:'subscribers':'document'}
        
       {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}
    </div>
    <div id="addons">
        <h4>Add-ons</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select><br/>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "products">
            <input type="hidden" name="tabName" value = "addons">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='products'|fn_my_changes_get_upload_details_function:'addons':'video'}
        {assign var="documentLink" value='products'|fn_my_changes_get_upload_details_function:'addons':'document'}
        
       {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

    </div>
    <div id="features">
        <h4>Features</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "products">
            <input type="hidden" name="tabName" value = "features">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='products'|fn_my_changes_get_upload_details_function:'features':'video'}
        {assign var="documentLink" value='products'|fn_my_changes_get_upload_details_function:'features':'document'}
        
       {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}
    </div>

    <div id="buy_together">
        <h4>Buy together</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "products">
            <input type="hidden" name="tabName" value = "buy_together">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='products'|fn_my_changes_get_upload_details_function:'buy_together':'video'}
        {assign var="documentLink" value='products'|fn_my_changes_get_upload_details_function:'buy_together':'document'}
        
       {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}
    </div>
    <div id="tags">
        <h4>Tags</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "products">
            <input type="hidden" name="tabName" value = "tags">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='products'|fn_my_changes_get_upload_details_function:'tags':'video'}
        {assign var="documentLink" value='products'|fn_my_changes_get_upload_details_function:'tags':'document'}
        
       {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}
    </div>
    <div id="reward_points">
        <h4>Reward points</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "products">
            <input type="hidden" name="tabName" value = "reward_points">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='products'|fn_my_changes_get_upload_details_function:'reward_points':'video'}
        {assign var="documentLink" value='products'|fn_my_changes_get_upload_details_function:'reward_points':'document'}
        
       {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}
    </div>
    <div id="ab__video_gallery">
        <h4>AB: Video gallery of the product</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "products">
            <input type="hidden" name="tabName" value = "ab__video_gallery">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='products'|fn_my_changes_get_upload_details_function:'ab__video_gallery':'video'}
        {assign var="documentLink" value='products'|fn_my_changes_get_upload_details_function:'ab__video_gallery':'document'}
        
       {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

    </div>
    <div id="wk_store_pickup">
        <h4>Store Pickup</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "products">
            <input type="hidden" name="tabName" value = "wk_store_pickup">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='products'|fn_my_changes_get_upload_details_function:'wk_store_pickup':'video'}
        {assign var="documentLink" value='products'|fn_my_changes_get_upload_details_function:'wk_store_pickup':'document'}
        
       {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

    </div>
</div>

{/capture}

{include file="common/mainbox.tpl" title=$page_title content=$smarty.capture.mainbox adv_buttons=$smarty.capture.adv_buttons select_languages=$select_languages sidebar=$smarty.capture.sidebar}

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $( function() {
        $( "#tabs" ).tabs();
    } );
</script>