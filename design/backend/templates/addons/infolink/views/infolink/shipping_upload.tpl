{capture name="mainbox"}
{include file="addons/infolink/infomenu.tpl"}
    <h4> Shipping methods</h4>
    <h5>Note: Only PDF, WEBM, MP4 extension are allowed.</h5>
    <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
        <label>Select Upload Type:</label>
        <select name="uploadType">
            <option selected="true" disabled="disabled">Select Type</option>
            <option value="video">Video</option>
            <option value="document">Document</option>
        </select>
        <label>Click to choose file button to upload a file.</label>
        <input type="hidden" name="upload_data['check']" value = "uploadCheck">
        <input type="hidden" name="parentTab" value = "shipping_links">
        <input type="hidden" name="tabName" value = "shipping_links">
        <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
        <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
    </form>
    
    <h5>Currently Use: </h5>

    {assign var='videoLink' value='shipping_links'|fn_my_changes_get_upload_details_function:'shipping_links':'video'}
    {assign var="documentLink" value='shipping_links'|fn_my_changes_get_upload_details_function:'shipping_links':'document'}
    
    {foreach $videoLink as $vlink}
        <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
    {/foreach}

    {foreach $documentLink as $dlink}
        <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
    {/foreach}
    
{/capture}

{include file="common/mainbox.tpl" title=$page_title content=$smarty.capture.mainbox adv_buttons=$smarty.capture.adv_buttons select_languages=$select_languages sidebar=$smarty.capture.sidebar}

