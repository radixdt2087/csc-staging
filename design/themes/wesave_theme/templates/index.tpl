{*  To modify and rearrange content blocks in your storefront pages
    or change the page structure, use the layout editor under Design->Layouts
    in your admin panel.

    There, you can:

    * modify the page layout
    * make it fluid or static
    * set the number of columns
    * add, remove, and move blocks
    * change block templates and types and more.

    You only need to edit a .tpl file to create a new template
    or modify an existing one; often, this is not the case.

    Basic layouting concepts:

    This theme uses the Twitter Bootstrap 2.3 CSS framework.

    A layout consists of four containers (CSS class .container):
    TOP PANEL, HEADER, CONTENT, and FOOTER.

    Containers are partitioned with fixed-width grids (CSS classes .span1, .span2, etc.).

    Content blocks live inside grids. You can drag'n'drop blocks
    from grid to grid in the layout editor.

    A block represents a certain content type (e.g. products)
    and uses a certain template to display it (e.g. list with thumbnails).
*}
<!DOCTYPE html>
<html {hook name="index:html_tag"}{/hook} lang="{$smarty.const.CART_LANGUAGE}" dir="{$language_direction}">
<head>
{capture name="page_title"}
{hook name="index:title"}
{if $page_title}
    {$page_title}
{else}
    {if $language_direction == "rtl"}
        {if $breadcrumbs}
            {foreach from=$breadcrumbs|array_reverse item=i name="bkt"}
                {if !$smarty.foreach.bkt.last}{if !$smarty.foreach.bkt.last && !$smarty.foreach.bkt.first} :: {/if}{$i.title|strip_tags}{/if}
            {/foreach}
        {/if}
    {else}
        {foreach from=$breadcrumbs item=i name="bkt"}
            {if !$smarty.foreach.bkt.first}{$i.title|strip_tags}{if !$smarty.foreach.bkt.last} :: {/if}{/if}
        {/foreach}
    {/if}
    {if !$skip_page_title && $location_data.title}{if $breadcrumbs|count > 1} - {/if}{$location_data.title}{/if}
{/if}
{/hook}
{/capture}
<title>{$smarty.capture.page_title|strip|trim nofilter}</title>
{include file="meta.tpl"}
{hook name="index:links"}
    <link href="{$logos.favicon.image.image_path|fn_query_remove:'t'}" rel="shortcut icon" type="{$logos.favicon.image.absolute_path|fn_get_mime_content_type}" />
{/hook}
{include file="common/styles.tpl" include_dropdown=true}
{if "DEVELOPMENT"|defined && $smarty.const.DEVELOPMENT == true}
<script type="text/javascript" data-no-defer>
window.jsErrors = [];
window.onerror = function(message, source, lineno, colno, error) {
    var verboseMessage = message;
    if (source) {
        verboseMessage = source + '@' + lineno + ':' + colno + "\n\n" + message;
    }

    console.error(verboseMessage);

    if (error && error.stack) {
        console.log(error.stack);
    }

    document.write('<pre data-ca-debug="1" style="border: 2px solid red; margin: 2px;">'
        + verboseMessage + "\n\n"
        + (error && error.stack ? error.stack : '')
        + '</pre>'
    );
};
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-93Q6Q07L7K"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
     
      gtag('config', 'G-93Q6Q07L7K');
    </script>
<!--[if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/es5-shim/4.1.9/es5-shim.min.js"></script><![endif]-->
{/if}
{hook name="index:head_scripts"}{/hook}
</head>

<body>
    {hook name="index:body"}
        {if $runtime.customization_mode.design}
            {include file="common/toolbar.tpl"
                title=__("on_site_template_editing")
                href="customization.disable_mode?type=design&return_url={$config.current_url|urlencode}"
            }
        {elseif $is_bottom_panel_available}
            {include file="backend:components/bottom_panel/bottom_panel.tpl" logo = $logo.theme.image|default:$logos.theme.image}
        {/if}

        <div class="ty-tygh {if $runtime.customization_mode.theme_editor}te-mode{/if} {if $runtime.customization_mode.live_editor || $runtime.customization_mode.design || $runtime.customization_mode.block_manager || $smarty.const.THEMES_PANEL}ty-top-panel-padding{/if} bp-tygh-container" id="tygh_container">

        {include file="common/loading_box.tpl"}
        {include file="common/notification.tpl"}

        <div class="ty-helper-container {if $smarty.cookies.pb_is_bottom_panel_open === "true"}bp-tygh-main-container--padding{/if}" id="tygh_main_container">
            {hook name="index:content"}
                 {* NOTE:
                    render_location - call a Smarty function that builds a page structure according to the layout (see Design->Layouts).
                    The function renders a template and generates blocks depending on 'dispatch'.
                *}
                {render_location}
            {/hook}
        <!--tygh_main_container--></div>

        {hook name="index:footer"}{/hook}
        <!--tygh_container--></div>

        {include file="common/scripts.tpl"}

        {if $runtime.customization_mode.design}
            {include file="backend:common/template_editor.tpl"}
        {/if}
        {if $runtime.customization_mode.theme_editor}
            {include file="backend:common/theme_editor.tpl"}
        {/if}
    {/hook}

<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var user_id =  {$auth.user_id};
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/6169dbcd86aee40a5736ce96/1fi2o56nt';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
</script>
<!--End of Tawk.to Script-->

</body>
</html>

