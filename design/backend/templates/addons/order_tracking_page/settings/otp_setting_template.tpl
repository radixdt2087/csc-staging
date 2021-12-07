<p class="center">
    {if $addons.order_tracking_page.wk_select_template == 'template_three'}
    <img id="otp-image" src="design/backend/media/images/addons/order_tracking_page/template_three.jpg" width="700px"/>
    {elseif $addons.order_tracking_page.wk_select_template == 'template_two'}
    <img id="otp-image" src="design/backend/media/images/addons/order_tracking_page/template_two.jpg" width="700px"/>
    {elseif $addons.order_tracking_page.wk_select_template == 'template_four'}
    <img id="otp-image" src="design/backend/media/images/addons/order_tracking_page/template_four.jpg" width="700px"/>
    {else}
    <img id="otp-image" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" width="700px"/>        
    {/if}
</p>
<script>
    $('#addon_option_order_tracking_page_wk_select_template').on('change',function(){
        var select  = $(this).val();
        if(select == 'template_three'){
            $('#otp-image').attr('src','design/backend/media/images/addons/order_tracking_page/template_three.jpg')
        }
        if(select == 'template_four'){
            $('#otp-image').attr('src','design/backend/media/images/addons/order_tracking_page/template_four.jpg')
        }
        if(select == 'template_two'){
            $('#otp-image').attr('src','design/backend/media/images/addons/order_tracking_page/template_two.jpg')
        }
    });
</script>