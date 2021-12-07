{script src="js/addons/cp_checkout_by_vendor/func.js"}

{if $smarty.session.back_to_actual}
    <script type="text/javascript">
        $(document).ready(function() {
            var i = $('i.ty-icon-basket'),
            a = i.closest('div');
            i.addClass('has-actual-items');
            a.hover(function(){
                setTimeout(function(){
                    a.find('.session-popup-warn').fadeIn('fast');
                }, 300);
            }, function(){
                setTimeout(function(){
                    a.find('.session-popup-warn').fadeOut('fast');
                }, 200);
            }).append('<div class="session-popup-warn hidden">{__('products_in_session_warn')}</div>');
        });
        function fn_set_session_warn() {
        
        }
    </script>
{/if}