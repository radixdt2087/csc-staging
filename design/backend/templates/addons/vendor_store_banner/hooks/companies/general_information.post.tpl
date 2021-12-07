{include file="common/subheader.tpl" title=__("store_banner")}
<div class="control-group">
    	<label class="control-label">{__("vendor_store_banner")}:</label>
    		<div class="controls">
            	{include file="common/attach_images.tpl" image_name="vendor_store_banner_main" image_object_type="vendor_store_banner" image_pair=$company_data.vendor_store_banner icon_text=__("text_vendor_thumbnail") detailed_text=__("text_vendor_detailed_image") no_thumbnail=true}
        	</div>
</div>