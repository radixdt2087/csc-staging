<th width="10%">
    <a class="cm-ajax" href="{"`$c_url`&sort_by=youtube_link&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>
        {__("youtube_link")}{if $search.sort_by == "youtube_link"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}
    </a>
</th>
