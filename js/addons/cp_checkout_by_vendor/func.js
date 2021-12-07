(function(_, $) {

    $(document).ready(function() {
        fn_cp_append_company_to_payment();
    });

    $.ceEvent('on', 'ce.ajaxdone', function(){
        fn_cp_append_company_to_payment();
    });
})(Tygh, Tygh.$);

function fn_cp_append_company_to_payment() {
    var $cp_payment_names = $('span[id^="cp_cbv_payment_name_"]');
    if ($cp_payment_names) {
        $cp_payment_names.each(function(){
            var span_id = $(this).attr('id');
            var payment_id = span_id.match(/(\d*)$/)[0];

            var $payment_label = $('.ty-payments-list__item-group label[for="payment_' + payment_id + '"]');
            $payment_label.append($(this).text());
        });
    }
}