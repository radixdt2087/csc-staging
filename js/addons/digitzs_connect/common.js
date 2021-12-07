$("#connect").click(function(){
    $("#digitzs_connect").click();
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
});

$(function() {
    $("#day_phone").inputmask("(999) 999-9999",{placeholder: ''});
    $("#evening_phone").inputmask("(999) 999-9999",{placeholder: ''});
    if($("#mdata").val() == '1') {
        window.history.pushState({}, document.title, "/" + "vendor.php?dispatch=companies.update&company_id="+$("input[name=company_id]").val());
    }
    $(".cm-update-company").click(function() {
        if($(".cm-js.active").attr('id')=='digitzs_connect') {
            $("#digitz_data").val(1);
            if(!confirm('This button will save and submit the application. Are you sure you are ready to submit? ')) {
                return false ;
            }
        }
    });
    $("#resendCode").click(function() {
        $("#resendEmail").val(0);
        $("#sendEmail").click();
    });
    $("#sendEmail").click(function() {
        $("#submitEmail").val(1);
    });
});
