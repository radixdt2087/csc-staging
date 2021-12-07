{if !$suffix}
    {assign var="suffix" value=""|uniqid}
{/if}

{hook name="terms_and_conditions:forms"}
    <div class="cm-field-container">
        {strip}
        <label for="id_cp_accept_terms{$suffix}" class="cm-cp-check-agreement">
            <input type="checkbox" id="id_cp_accept_terms{$suffix}" name="{$field_name|default:"cp_accept_terms"}" value="Y" class="cm-cp-agreement checkbox" {if $addons.cp_terms_and_conditions.terms_checked_all == "Y"}checked="checked"{/if}/>
            {capture name="terms_link"}
                {assign var="link_meta" value="cm-ajax cm-dialog-opener cm-dialog-auto-size ty-dashed-link"}
                <a id="sw_cp_terms_and_conditions_{$suffix}" class="{$link_meta}" href="{"cp_terms_and_conditions.get_content"|fn_url}" data-ca-target-id="content_cp_terms_and_conditions_page" >
                    {__("cp_terms_n_conditions_name")}
                </a>
            {/capture}
            {__("cp_terms_n_conditions", ["[terms_href]" => $smarty.capture.terms_link])}
        </label>
        {/strip}
    </div>
{/hook}
