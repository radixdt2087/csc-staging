{capture name="mainbox"}
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
{include file="addons/infolink/infomenu.tpl"}
<h4>Vendors</h4>
<div id="tabs">
    <ul>
        <li><a href="#general">General</a></li>
        <li><a href="#addons">Add-ons</a></li>
        <li><a href="#description">Description</a></li>
        <li><a href="#logos">Logos</a></li>
        <li><a href="#plan">Plan</a></li>
        <li><a href="#terms_and_conditions">Terms & conditions</a></li>
        <li><a href="#ab__motivation_block">AB: Motivation block</a></li>
        <li><a href="#discussion">Reviews</a></li>
        <li><a href="#digitzs_connect">Digitzs</a></li>
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
            <input type="hidden" name="parentTab" value = "vendor">
            <input type="hidden" name="tabName" value = "general">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
       
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_details_function:'general':'video'}
        {assign var="documentLink" value='vendor'|fn_my_changes_get_upload_details_function:'general':'document'}
        
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
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "vendor">
            <input type="hidden" name="tabName" value = "addons">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_details_function:'addons':'video'}
        {assign var="documentLink" value='vendor'|fn_my_changes_get_upload_details_function:'addons':'document'}
        
        {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}
    </div>
    <div id="description">
        <h4>Description</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "vendor">
            <input type="hidden" name="tabName" va666666lue = "description">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_details_function:'description':'video'}
        {assign var="documentLink" value='vendor'|fn_my_changes_get_upload_details_function:'description':'document'}
        
        {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}
    </div>
    <div id="logos">
        <h4>Logos</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "vendor">
            <input type="hidden" name="tabName" value = "logos">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_details_function:'logos':'video'}
        {assign var="documentLink" value='vendor'|fn_my_changes_get_upload_details_function:'logos':'document'}
        
        {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}
    </div>
    <div id="plan">
        <h4>Plan</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "vendor">
            <input type="hidden" name="tabName" value = "plan">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_details_function:'plan':'video'}
        {assign var="documentLink" value='vendor'|fn_my_changes_get_upload_details_function:'plan':'document'}
        
        {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}
    </div>
    <div id="terms_and_conditions">
        <h4>Terms & conditions</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "vendor">
            <input type="hidden" name="tabName" value = "terms_and_conditions">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_details_function:'terms_and_conditions':'video'}
        {assign var="documentLink" value='vendor'|fn_my_changes_get_upload_details_function:'terms_and_conditions':'document'}
        
        {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}
    </div>

    <div id="ab__motivation_block">
        <h4>AB: Motivation block</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "vendor">
            <input type="hidden" name="tabName" value = "ab__motivation_block">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_details_function:'ab__motivation_block':'video'}
        {assign var="documentLink" value='vendor'|fn_my_changes_get_upload_details_function:'ab__motivation_block':'document'}
        
        {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}
    </div>
    <div id="discussion">
        <h4>Reviews</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select><br/>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "vendor">
            <input type="hidden" name="tabName" value = "discussion">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_details_function:'discussion':'video'}
        {assign var="documentLink" value='vendor'|fn_my_changes_get_upload_details_function:'discussion':'document'}
        
        {foreach $videoLink as $vlink}
            <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}

        {foreach $documentLink as $dlink}
            <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
        {/foreach}
    </div>
    <div id="digitzs_connect">
        <h4>Digitzs</h4>
        <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
            <label>Select Upload Type:</label>
            <select name="uploadType">
                <option selected="true" disabled="disabled">Select Type</option>
                <option value="video">Video</option>
                <option value="document">Document</option>
            </select><br/>
            <input type="hidden" name="upload_data['check']" value = "uploadCheck">
            <input type="hidden" name="parentTab" value = "vendor">
            <input type="hidden" name="tabName" value = "digitzs_connect">
            <label>Click to choose file button to upload a file.</label>
            <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
            <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
        </form>
        <h5>Currently Use: </h5>

        {assign var='videoLink' value='vendor'|fn_my_changes_get_upload_details_function:'digitzs_connect':'video'}
        {assign var="documentLink" value='vendor'|fn_my_changes_get_upload_details_function:'digitzs_connect':'document'}
        
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