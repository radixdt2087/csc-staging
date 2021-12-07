<div id="content_plan" class="hidden">
    <input type="hidden" name="payment_page" id="payment_page" value="{$smarty.get.pay}"/>
    <input type="hidden" name="selected_section" id="selected_section" value="{$smarty.get.selected_section}"/>
    <input type="hidden" name="payment_status" id="payment_status" value="{$payment_details.status}"/>
    {* <input type="hidden" name="purchase_plan_id" id="purchase_plan_id" value="{$payment_details.plan_id}"/> *}
    <input type="hidden" name="ptype" id="ptype" value="plan"/>
    {if $runtime.company_id}
    <div class="cm-j-tabs cm-track tabs">
    <ul class="nav nav-tabs"><li id="chooseplan" class="cm-js"><a>Choose your plan</a></li><li id="upgrade" class="cm-js"><a>Upgrade your features</a></li><li id="current_plan_addons" class="cm-js"><a>Plan/Add-on Billing</a></li></ul>
    </div>
    <div class="cm-tabs-content">
        <div id="content_chooseplan" class="hidden">{include file="addons/vendor_plans/views/vendor_plans/components/plans_selector.tpl" plans=$vendor_plans current_plan_id=$company_data.plan_id name="company_data[plan_id]"}</div>
        <div id="content_upgrade" class="hidden">{include file="addons/vendor_enrollment/views/addons.tpl" addon_data=$addon_data}</div>
        <div id="content_current_plan_addons" class="hidden">{include file="addons/vendor_enrollment/views/current_plan_addons.tpl"}</div>
    </div>
    <div id="ccdetails" class="hidden">
        {include file="addons/vendor_enrollment/views/payment.tpl"}
    </div>
    {else}
        {$allow_add_plan = fn_check_permissions("vendor_plans", "quick_add", "admin", "POST")}
        {$company_plan_id = $company_data.plan_id|default:$default_vendor_plan.plan_id}
        <div class="control-group">
            <label class="control-label" for="elm_company_plan">{__("vendor_plans.plan")}:</label>
            <div class="controls">
                {include file="addons/vendor_plans/views/vendor_plans/components/picker/picker.tpl"
                    item_ids=[$company_plan_id]
                    input_name="company_data[plan_id]"
                    picker_id="vendor_plans_picker"
                    allow_add=$allow_add_plan
                    current_plan_id=$company_plan_id
                }
            </div>
        </div>
        {if $allow_add_plan}
            {script src="js/addons/vendor_plans/backend/companies_update_vendor_plan.js"}
            <div class="control-toolbar__panel">
                <div id="companies_quick_add_vendor_plan"
                        data-ca-inline-dialog-action-context="vendor_update"
                        data-ca-inline-dialog-url="{"vendor_plans.quick_add"|fn_url}">
                </div>
            </div>
        {/if}
    {/if}

    {if $id}
        {include file="addons/vendor_plans/views/vendor_plans/components/storefronts_update_for_vendor_dialog.tpl"
            company_id = $id
        }
    {/if}
</div>