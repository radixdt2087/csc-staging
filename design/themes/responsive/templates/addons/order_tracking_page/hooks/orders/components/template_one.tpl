<div class="otp-t1-main-box">
    <div class="otp-t1-section" style="width:{$section_width}px;">
        {assign var=bar_flag value=false}
        {foreach from=$labels_data item=label}
        {if $bar_flag}
            <span class="otp-t1-bar {if $label.label_status == 'D'}otp-t1-bar-done{/if} {if $label.label_status == 'A'}otp-t1-bar-activ{/if}"></span>
        {/if}
        <a title="{$label.description}" class="otp-t1-a-cusror">
        <div class="otp-t1-circle {if $label.label_status == 'D'}otp-t1-circle_done{/if} {if $label.label_status == 'A'}otp-t1-circle_activ{/if}" id="label_{$label.id}">
			<span class="otp-t1-label {if $label.label_status == 'D'}otp-t1-label-done{/if} {if $label.label_status == 'A'}otp-t1-label-activ{/if}">{if $label.label_status == 'D'}<i class="ty-icon-ok"></i>{/if}</span>
			<span class="otp-t1-title">{$label.title}</span>
		</div>
        </a>
        {assign var=bar_flag value=true}
        {/foreach}
    </div>
</div>
