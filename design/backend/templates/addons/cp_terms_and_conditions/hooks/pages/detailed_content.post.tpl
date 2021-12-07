{include file="common/subheader.tpl" title=__("cp_terms_and_conditions_section") target="#cp_terms_and_conditions"}
<div id="cp_terms_and_conditions" class="in collapse">
    <fieldset>
        <div class="control-group">
            <label for="cp_use_terms_and_conditions" class="control-label">{__("cp_use_terms_and_conditions_enable")}:</label>
            <div class="controls">
                    <input type="hidden" name="page_data[cp_use_terms_and_conditions]" value="N">
                    <span class="checkbox">
                        <input type="checkbox" id="cp_use_terms_and_conditions" value="Y" {if (!$page_data.page_id && $page_data.page_type == "F") || $page_data.cp_use_terms_and_conditions == "Y" }checked="checked"{/if} name="page_data[cp_use_terms_and_conditions]">
                    </span>
            </div>
        </div>
    </fieldset>
</div>
