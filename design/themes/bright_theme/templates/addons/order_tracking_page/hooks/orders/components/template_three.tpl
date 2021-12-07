<style>
    @media screen and (max-width: 500px) {
            .otp-t3-section{
                height: {$css.section_media_height}px;
                width:150px!important;
            }
    }
    {if $css.section_media_query_start_width > 500 && $css.section_media_query_end_width> 500}
    @media (min-width:{$css.section_media_query_start_width}px) and (max-width: {$css.section_media_query_end_width}px) {
        .otp-t3-section{
            width:{$css.section_media_width}px!important;
        }
        .otp-t3-circle{
            width: 35px;
            height: 35px;
        }
        .otp-t3-icon{
            top: 4px;
            font-size: 17px;
        }
        .otp-t3-bar{
            width: 80px;
            top: 17px;
        }
        .otp-t3-label{
            top: 47px;
        }
        .otp-t3-img{
            width: 45px;
        }
        .otp-t3-text{
            font-size: 13px;
            width: 91px;
            text-align: center;
            position: relative;
            left: 10px;
        }
    }
    {/if}
    {if $css.section_media_query_start_width > 500}
     @media (min-width:500px) and (max-width: {$css.section_media_query_start_width}px){
         .otp-t3-section{
            width:{$css.section_media_two_width}px!important;
        }
        .otp-t3-circle{
            width: 30px;
            height: 30px;
        }
        .otp-t3-icon{
            top: 4px;
            font-size: 15px;
        }
        .otp-t3-bar{
            width: 50px;
            top: 15px;
        }
        .otp-t3-text{
            font-size: 10px;
            width: 80px;
            position: relative;
            left: 15px;
        }
        .otp-t3-label{
            top: 40px;
            left: -41px;
            width: 105px;
        }
        .otp-t3-img{
            width: 40px;
        }
     }
     {/if}
	.otp-t3-bar-not,.otp-t3-circle_not{
		background-color: #f10b0b;
	}
</style>
{assign var=count value=0}
<div class="otp-t3-main-box">
    <div class="otp-t3-section" style="width:{$css.section_width}px;"> 
        {foreach from=$labels_data item=label key=key name=labels}
        {assign var=count value=$count+1}

        <div class="otp-t3-circle {if $label.label_status == 'D' }otp-t3-circle_done{elseif $label.label_status == 'I'}otp-t3-circle_not{/if} {if $label.label_status == 'A'}otp-t3-circle_activ{/if}" id="label_{$label.id}">
            {if $label.label_status == 'D'}<span class="otp-t3-icon"><i class="ty-icon-ok"></i></span>{/if}
            <div class="otp-t3-label">
              {if $label.act_img neq '' || $label.deact_img neq ''}  <div class="otp-t3-image"><img src="{if $label.label_status == 'D'}{$label.act_img}{else}{$label.deact_img}{/if}" class="otp-t3-img"/></div>{/if}
                <div class="otp-t3-text"><span>{$label.title}</span></div>
            </div>
        </div>
        {if $count != $css['labels_count']}
        <span class="otp-t3-bar {if $label.bar_status == 'D'}otp-t3-bar-done{/if} {if $label.bar_status == 'A'}otp-t3-bar-activ{/if}" id="bar{$count}"></span>
	{else}
	{if $label.bar_status == 'I'}
	<script>
	var bar_count = {$count}-1;
	$('#bar'+bar_count).removeClass('otp-t3-bar-done');
	$('#bar'+bar_count).addClass('otp-t3-bar-not');
	</script>
	{/if}
        {/if}
        {/foreach}        
    </div>
</div>
