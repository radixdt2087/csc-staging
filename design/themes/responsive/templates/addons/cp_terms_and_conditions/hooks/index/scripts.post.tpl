<script type="text/javascript">
    (function(_, $) {
        $.ceFormValidator('registerValidator', {
            class_name: 'cm-cp-check-agreement',
            message: '{__("checkout_terms_n_conditions_alert")|escape:javascript}',
            func: function(id) {
                return $('#' + id).prop('checked');
            }
        });     
    }(Tygh, Tygh.$));
</script>

<script type="text/javascript">
    $(function() {
    
        if($(".cm-cp-agreement").is(":checked")) {
            $(".ty-login-popup .ty-text-center").removeClass("disablesocial")
        }
        else {
            $(".ty-login-popup .ty-text-center").addClass("disablesocial")
        }

        $(".cm-cp-agreement" ).on("click", function() {
            if($(this).is(":checked")) {
                $(".ty-login-popup .ty-text-center").removeClass("disablesocial")
            }
            else {
                $(".ty-login-popup .ty-text-center").addClass("disablesocial")
            }
        })
    });
</script>