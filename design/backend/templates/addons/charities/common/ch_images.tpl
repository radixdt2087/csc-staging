<!-- charities images -->
{if $affiliate.affiliate_id} {* Don't show for new affiliates *}
{assign var="id" value=$affiliate.affiliate_id}
{assign var="image" value=$affiliate.image|default:[]}
{assign var="affiliate_name" value=$affiliate.short_name}
{assign var="name" value=$name|default:"affiliate"}
<input type="hidden" name="{$name}_image_data[0][type]" value="M" />
<input type="hidden" name="{$name}_image_data[0][object_id]" value="{$affiliate.affiliate_id}" />
<!-- TB: image:{$affiliate.image|print_r:true} -->
<div class="attach-images">
	<div class="upload-box clearfix">
		<h5>{__("image")|ucwords}</h5>
		<div class="image-wrap pull-left">
			<div class="image">
				{if $image}
					<img class="solid-border" src="{$image.detailed.image_path}" width="152">
				{else}
					<div class="no-image"><i class="glyph-image" title="{__("no_image")}"></i></div>
				{/if}
			</div>
			<div class="image_alt">
				<div class="input-prepend">
					<span class="add-on cm-tooltip" title="{__("alt_text")}"><i class="icon-comment"></i></span>
					<input type="text" class="input-text cm-image-field" id="alt_text" name="affilate_image_data[0][image_alt]" value="{$image.alt|default:$affiliate.short_name}">
				</div>
			</div>
		</div>
		
		<div class="image-upload">
			{include file="common/fileuploader.tpl" var_name="`$name`_image_detailed[0]"}
		</div>
	</div>
</div>
{else}
<!-- NO affiliate.affiliate_id set -->
{/if}
