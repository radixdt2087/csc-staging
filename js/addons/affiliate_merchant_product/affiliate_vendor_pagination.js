$(".ty-pagination__item").removeClass("cm-ajax");
$(".ty-sort-container").css("display","none");
$(".ty-quick-view-button").css("display","none");
$(document).on('click','.ty-pagination__item',function(e){
  e.preventDefault();
  $(".ty-pagination__item").removeClass("cm-ajax");
  var clicked_page = $(this).attr("data-ca-page");
  var url = $("input[name=redirect_url]").val();
  
  $.ceAjax('request', fn_url(url), {
    result_ids: 'pagination_contents',
    method: 'POST',
    caching: false,
    hidden:true,
    force_exec: true,
    data: { 'page': clicked_page},
    callback: function (data) {
      $(".ty-quick-view-button").css("display","none");
    }
  });
});
$(document).on('click', '.product-affiliate-redirect', function(e) {
  e.preventDefault();
  $('.ty-account-info__buttons').children('a').trigger("click");
});
