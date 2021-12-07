<style>
.otp-t2-circle_not{
		background-color: #f10b0b;
	}
.otp-t2-bar-not{
		stroke: #f10b0b;
	}
</style>
<div id="content_track_order">
    <div class="otp-t2-box-container">
        <div class="otp-t2-section"  style="background-image: url('{$images_dir}/addons/order_tracking_page/template_two/map.png');">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="otp-t2-svg">
                {assign var=count value=0}
                {foreach from=$labels_data item=label key=key}
                    {assign var=count value=$count+1}

                    {if $count == '1' && $count<$labels_count}
                        <path d='M110 385  Q220 190 280 235' class="otp-t2-svg-line-one-1 otp-t2-bar {if $label.bar_status == 'D'}otp-t2-bar-done{/if} {if $label.bar_status == 'A'}otp-t2-bar-activ{/if}" fill="transparent" id="bar{$count}1"/>
                        <path d='M67 385 Q110 290 170 300' class="otp-t2-svg-line-one-2 otp-t2-bar {if $label.bar_status == 'D'}otp-t2-bar-done{/if} {if $label.bar_status == 'A'}otp-t2-bar-activ{/if}" fill="transparent" id="bar{$count}2"/>
                        <path d='M45 129 Q62 110 113 84' class="otp-t2-svg-line-one-3 otp-t2-bar {if $label.bar_status == 'D'}otp-t2-bar-done{/if} {if $label.bar_status == 'A'}otp-t2-bar-activ{/if}" fill="transparent" id="bar{$count}3"/>
                    {elseif $count == '2' && $count<$labels_count}
                        <path d='M280 235 Q340 250 380 280 T480 295' class="otp-t2-svg-line-two-1 otp-t2-bar {if $label.bar_status == 'D'}otp-t2-bar-done{/if} {if $label.bar_status == 'A'}otp-t2-bar-activ{/if}" fill="transparent" id="bar{$count}1"/>
                        <path d='M170 300 Q220 296 245 316 T300 339' class="otp-t2-svg-line-two-2 otp-t2-bar {if $label.bar_status == 'D'}otp-t2-bar-done{/if} {if $label.bar_status == 'A'}otp-t2-bar-activ{/if}" fill="transparent" id="bar{$count}2"/>
                        <path d='M115 86 Q127 75 145 106 T192 108' class="otp-t2-svg-line-two-3 otp-t2-bar {if $label.bar_status == 'D'}otp-t2-bar-done{/if} {if $label.bar_status == 'A'}otp-t2-bar-activ{/if}" fill="transparent" id="bar{$count}3"/>
                    {elseif $count == '3' && $count<$labels_count}
                        <path d='M480 295  Q852 200 680 355' class="otp-t2-svg-line-three-1 otp-t2-bar {if $label.bar_status == 'D'}otp-t2-bar-done{/if} {if $label.bar_status == 'A'}otp-t2-bar-activ{/if}" fill="transparent" id="bar{$count}1"/>
                        <path d='M300 339 Q497 260 430 375' class="otp-t2-svg-line-three-2 otp-t2-bar {if $label.bar_status == 'D'}otp-t2-bar-done{/if} {if $label.bar_status == 'A'}otp-t2-bar-activ{/if}" fill="transparent" id="bar{$count}2"/>
                        <path d='M192 108 Q320 65 275 131' class="otp-t2-svg-line-three-3 otp-t2-bar {if $label.bar_status == 'D'}otp-t2-bar-done{/if} {if $label.bar_status == 'A'}otp-t2-bar-activ{/if}" fill="transparent" id="bar{$count}3"/>
                    {elseif $count == '4' && $count<$labels_count}
                        <path d='M447 450 C520 400 620 380 680 355' class="otp-t2-svg-line-four-1 otp-t2-bar {if $label.bar_status == 'D'}otp-t2-bar-done{/if} {if $label.bar_status == 'A'}otp-t2-bar-activ{/if} " fill="transparent" id="bar{$count}1"/>
                        <path d='M265 439 C320 400 400 422 430 375' class="otp-t2-svg-line-four-2 otp-t2-bar {if $label.bar_status == 'D'}otp-t2-bar-done{/if} {if $label.bar_status == 'A'}otp-t2-bar-activ{/if} " fill="transparent" id="bar{$count}2"/>
                        <path d='M171 177 C196 148 215 201 275 131' class="otp-t2-svg-line-four-3 otp-t2-bar {if $label.bar_status == 'D'}otp-t2-bar-done{/if} {if $label.bar_status == 'A'}otp-t2-bar-activ{/if} " fill="transparent" id="bar{$count}3"/>
                    {elseif $count == '5' && $count<$labels_count}
                        <path d='M218 550 C309 490 398 475 447 450' class="otp-t2-svg-line-five-1 otp-t2-bar {if $label.bar_status == 'D'}otp-t2-bar-done{/if} {if $label.bar_status == 'A'}otp-t2-bar-activ{/if}" fill="transparent" id="bar{$count}1"/>
                        <path d='M126 500 C209 459 208 474 265 439' class="otp-t2-svg-line-five-2 otp-t2-bar {if $label.bar_status == 'D'}otp-t2-bar-done{/if} {if $label.bar_status == 'A'}otp-t2-bar-activ{/if}" fill="transparent" id="bar{$count}2"/>
                        <path d='M85 212 C114 200 108 236 171 177' class="otp-t2-svg-line-five-3 otp-t2-bar {if $label.bar_status == 'D'}otp-t2-bar-done{/if} {if $label.bar_status == 'A'}otp-t2-bar-activ{/if}" fill="transparent" id="bar{$count}3"/>
		    {else}
		{if $label.bar_status == 'I'}
	<script>
	var bar_count = {$count}-1;
	$('#bar'+bar_count + '1').removeClass('otp-t2-bar-done');
	$('#bar'+bar_count + '1').addClass('otp-t2-bar-not');
	$('#bar'+bar_count + '2').removeClass('otp-t2-bar-done');
	$('#bar'+bar_count + '2').addClass('otp-t2-bar-not');
	$('#bar'+bar_count + '3').removeClass('otp-t2-bar-done');
	$('#bar'+bar_count + '3').addClass('otp-t2-bar-not');
	</script>
	{/if}

                    {/if}
                {/foreach}
                <ul class="otp-t2-items">
                    {foreach from=$labels_data item=label key=key name=labels}
                        <li class="otp-t2-item {if $label.label_status == 'D'}otp-t2-item-done{elseif $label.label_status == 'I'}otp-t2-circle_not{/if}">
                            {if $label.label_status == 'D'}<span class="otp-t2-icon"><i class="ty-icon-ok"></i></span>{/if}
                            <div class="otp-t2-content">
                              {if $label.act_img neq '' || $label.deact_img neq ''}  <div class="otp-t2-img"><img src="{if $label.label_status == 'D'}{$label.act_img}{else}{$label.deact_img}{/if}" class="otp-t2-image"/></div>{/if}
                                <div class="otp-t2-text"><span>{$label.title}</span></div>
                            </div>
                        </li>
                    {/foreach}
                <ul>
            </svg>
        </div>
    </div>
</div>
<!--content_track_order-->
