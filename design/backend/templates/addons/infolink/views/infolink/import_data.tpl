{capture name="mainbox"}
{include file="addons/infolink/infomenu.tpl"}
    <h4>Import Data</h4>
    <div id="tabs">
        <ul>
            <li><a href="#general">General</a></li>
            <li><a href="#fields">Fields mapping</a></li>
            <li><a href="#options">Settings</a></li>
        </ul>
        <br/>
        <h5 style="padding-left: 20px;">Note: Only PDF, WEBM, MP4 extension are allowed.</h5>
        <div id="general">
            <h4>File</h4>
            <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
                <label>Select Upload Type:</label>
                <select name="uploadType">
                    <option selected="true" disabled="disabled">Select Type</option>
                    <option value="video">Video</option>
                    <option value="document">Document</option>
                </select>
                <label>Click to choose file button to upload a file.</label>
                <input type="hidden" name="upload_data['check']" value = "uploadCheck">
                <input type="hidden" name="parentTab" value = "import_data">
                <input type="hidden" name="tabName" value = "general">
                <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
                <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
            </form>
        
            <h5>Currently Use: </h5>

            {assign var='videoLink' value='import_data'|fn_my_changes_get_upload_details_function:'general':'video'}
            {assign var="documentLink" value='import_data'|fn_my_changes_get_upload_details_function:'general':'document'}
            
            {foreach $videoLink as $vlink}
                <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
            {/foreach}

            {foreach $documentLink as $dlink}
                <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
            {/foreach}

        </div>

        <div id="fields">
        <h4>Fields mapping</h4>
            <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
                <label>Select Upload Type:</label>
                <select name="uploadType">
                    <option selected="true" disabled="disabled">Select Type</option>
                    <option value="video">Video</option>
                    <option value="document">Document</option>
                </select>
                <label>Click to choose file button to upload a file.</label>
                <input type="hidden" name="upload_data['check']" value = "uploadCheck">
                <input type="hidden" name="parentTab" value = "import_data">
                <input type="hidden" name="tabName" value = "fields">
                <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
                <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
            </form>
        
            <h5>Currently Use: </h5>

            {assign var='videoLink' value='import_data'|fn_my_changes_get_upload_details_function:'fields':'video'}
            {assign var="documentLink" value='import_data'|fn_my_changes_get_upload_details_function:'fields':'document'}
            
            {foreach $videoLink as $vlink}
                <p>Video: {if $videoLink != null}<a href="{$vlink['url']}" target="_blank">{$vlink['url']}</a> <a href="?dispatch=infolink.delete&id={$vlink['id']}" class="close_data">X</a>{/if}</p>
            {/foreach}

            {foreach $documentLink as $dlink}
                <p>Document:  {if $documentLink != null}<a href="{$dlink['url']}" target="_blank">{$dlink['url']}</a> <a href="?dispatch=infolink.delete&id={$dlink['id']}" class="close_data">X</a>{/if}</p>
            {/foreach}
        </div>

        <div id="options">
            <h4>Settings</h4>
            <form action="su-admincenter.php?dispatch=infolink.info_upload" method="post" enctype="multipart/form-data" class="cm-processed-form">
                <label>Select Upload Type:</label>
                <select name="uploadType">
                    <option selected="true" disabled="disabled">Select Type</option>
                    <option value="video">Video</option>
                    <option value="document">Document</option>
                </select>
                <label>Click to choose file button to upload a file.</label>
                <input type="hidden" name="upload_data['check']" value = "uploadCheck">
                <input type="hidden" name="parentTab" value = "import_data">
                <input type="hidden" name="tabName" value = "options">
                <input type="file" name="uploadVideoDocument" class="btn cm-tooltip">
                <input type="submit" value="Upload" name="dispatch[save]" class="btn cm-tooltip">
            </form>
        
            <h5>Currently Use: </h5>

            {assign var='videoLink' value='import_data'|fn_my_changes_get_upload_details_function:'options':'video'}
            {assign var="documentLink" value='import_data'|fn_my_changes_get_upload_details_function:'options':'document'}
            
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

