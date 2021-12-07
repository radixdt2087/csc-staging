<div class="control-group">
    <label class="control-label" for="elm_allowed_vendor_subdomain_{$id}">{__("allowed_vendor_subdomain")}:</label>
    <div class="controls">
        <input type="hidden" name="plan_data[allowed_vendor_subdomain]" value="0" />
        <input type="checkbox" id="elm_allowed_vendor_subdomain_{$id}" name="plan_data[allowed_vendor_subdomain]" size="10" value="1"{if $plan.allowed_vendor_subdomain} checked="checked"{/if} />
    </div>
</div>