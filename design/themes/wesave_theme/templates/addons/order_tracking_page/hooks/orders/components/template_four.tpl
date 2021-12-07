{* linear template two *}
<style>
    @media screen and (max-width: 500px) {
        .otp-t4-section{
                height: {$css.section_height}px;
                width:150px!important;
        }
    }
    {if $css.section_media_query_start_width > 500 && $css.section_media_query_end_width> 500}
    @media (min-width:{$css.section_media_query_start_width}px) and (max-width: {$css.section_media_query_end_width}px) {
        .otp-t4-section{
                width:{$css.media_width_one}px!important;
        }
        .otp-t4-circle{
        width: 60px;
        height: 60px;
        }
        .otp-t4-bar{
            width: 60px;
            height: 6px;
            top: 30px;
        }
        .otp-t4-img{
            width: 30px;
        }
        .otp-t4-text{
            top: 65px;
            left: -25px;
            font-size: 12px;
        }
    }
    {/if}
    {if $css.section_media_query_start_width > 500}
    @media (min-width:500px) and (max-width:{$css.section_media_query_start_width}px){
        .otp-t4-section{
            width:{$css.media_width_two}px!important;            
        }
        .otp-t4-circle{
            width: 40px;
            height: 40px;
        }
        .otp-t4-bar{
            width: 40px;
            height: 4px;
            top: 19px;
        }
        .otp-t4-img{

        }
        .otp-t4-text{
            top: 45px;
            left: -27px;
            font-size: 9px;
            width: 90px;
            min-width:unset;
        }
    }
    {/if}
	.otp-t4-bar-not{
		background-color: #f10b0b;
	}
	.otp-t4-circle_not{
		border-color: #f10b0b;
	}
</style>
{assign var=count value=0}
<div class="otp-t4-main-box">
    <div class="otp-t4-section" style="width:{$css.section_width}px">
        {foreach from=$labels_data item=label name=labels}
        {assign var=count value=$count+1}
        <div class="otp-t4-circle {if $label.label_status == 'D'}otp-t4-circle_done{elseif $label.label_status == 'I'}otp-t4-circle_not{/if} {if $label.label_status == 'A'}otp-t4-circle_activ{/if}" id="label_{$label.id}">
			<span class="otp-t4-label"><img src="{if $label.label_status == 'D'}{$images_dir}/addons/order_tracking_page/template_four/green-tick.png{elseif $label.label_status == 'I'}{$images_dir}/addons/order_tracking_page/template_four/red_cross.png{/if}" class="otp-t4-img"/></span>
            <span class="otp-t4-text">{$label.title}</span>
		</div>
        {if $count != $css['labels_count']}
        <span class="otp-t4-bar {if $label.bar_status == 'D'}otp-t4-bar-done{/if} {if $label.bar_status == 'A'}otp-t4-bar-activ{/if}" id="bar{$count}"></span>
	{else}
	{if $label.bar_status == 'I'}
	<script>
	var bar_count = {$count}-1;
	$('#bar'+bar_count).removeClass('otp-t4-bar-done');
	$('#bar'+bar_count).addClass('otp-t4-bar-not');
	</script>
	{/if}
        {/if}
        {/foreach}
    </div>
</div>
