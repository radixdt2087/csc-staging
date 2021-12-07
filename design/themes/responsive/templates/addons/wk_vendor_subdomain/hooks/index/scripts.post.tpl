<script type="text/javascript">
 (function(_, $) {
  $.ceEvent('on', 'ce.ajaxdone', function(elms, scripts, params, response_data, response_text) {
    var host = '{$config.http_host}';
    {if $smarty.const.HTTPS_HOST}
      host = '{$config.https_host}';
    {/if}
    var location = '{$config.current_location}';
    $('a').each(function(){
      var a_class = $(this).attr('class');
      if(a_class && a_class == 'wk_vendor_subdomain_logo'){
        return true;
      }
      var href = $(this).attr('href');
      var parser = document.createElement('a');
      parser.href = href;
      if(href && parser.href && host.search(parser.hostname) >=0 ){
        $(this).attr('href',href.replace(parser.hostname,host));
      }
    });
    var url_without_subdomain = "{fn_remove_vendor_subdomain($config.current_location)}"+'/';
    $('form[name="call_requests_form"]').attr('action',url_without_subdomain);
  });
  var url_without_subdomain = "{fn_remove_vendor_subdomain($config.current_location)}"+'/';
  $('#new_thread_form_{$smarty.request.company_id}').attr('action',url_without_subdomain);
  $('#new_thread_form_{$smarty.request.product_id}').attr('action',url_without_subdomain);
 })(Tygh,Tygh.$);
</script>

