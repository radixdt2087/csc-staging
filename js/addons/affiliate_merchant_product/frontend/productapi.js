$(document).ready(function() {
     if((localStorage.getItem('lastclickedurl')) && localStorage.getItem('successFlag') == 1){ 
         var newurl = localStorage.getItem('lastclickedurl'); 
         if (newurl.indexOf("cus_ich_{subid}") >= 0){ 
            var new_url = newurl.replace('cus_ich_{subid}', 'cus_ich_'+user_id);
         }
         if (newurl.indexOf("cus_ich_0") >= 0){
            var new_url = newurl.replace('cus_ich_0', 'cus_ich_'+user_id);
         }
         customDialog({message: 'You are about to be redirected to an external site which will OPEN IN NEW TAB as shown below.',title: 'Confirm Please'},new_url);
         localStorage.removeItem('successFlag');
      }
     $(document).on('click', '.buy_now', function(ev) {
         ev.preventDefault();
         var url = $(this).attr("url"); 
         var url1 = "companies.logs";
         $.ceAjax('request', fn_url(url1), {
             method: 'POST',
             caching: false,
             hidden:true,
             force_exec: true,
             callback: function (data) {
             }
           });
         localStorage.setItem('lastclickedurl', url);
         if(user_id  == 0){
             $('.ty-account-info__buttons').children('a').trigger("click");
             $('.ty-btn__login').on('click',function(){
                  localStorage.setItem('successFlag', 1);
                  
             });
         }else{
             if(localStorage.getItem('flag') == 1){
                 createPopupWin(url,url,1250,1250);
             }else{
                 customDialog({message: 'You are about to be redirected to an external site which will OPEN IN NEW TAB as shown below.',title: 'Confirm Please'},url);
             }
         }
     });
     if($(".vendor-listing ").length > 0){
         $('.sort-by-company-absolute_vendor_rating-desc').css('display','none');
         $('.sort-by-company-absolute_vendor_rating-asc').css('display','none');
         $('.sort-by-company-absolute_vendor_rating-desc').css('display','none');
         $('.sort-by-company-rating-asc').css('display','none');
         $('.sort-by-company-rating-desc').css('display','none');
     }
 });
 $(document).on('click', '.homepage-top-banner a', function(ev) { 
     ev.preventDefault();
      var videourl =  $(this).attr('href');
      var iframhtml = "";
     if (videourl.indexOf("video") >= 0){
         iframhtml = '<div class="video-block"><div class="iframe-wrapper"><span class="close-iframe">x</span><div class="embed-responsive embed-responsive-16by9"><iframe width="700" height="500" src='+videourl+'?autoplay=1 allow="autoplay" frameborder="0"  allowfullscreen class="embed-responsive-item"></iframe></div></div>';
         $('body').append(iframhtml);
     }else{
         window.open($(this).attr('href'),"_self");
     }
 });
 $(document).on('click', '.close-iframe', function(ev) {
     $('.video-block').remove();
 });
 function createPopupWin(pageURL, pageTitle,popupWinWidth, popupWinHeight) {
     var left = (screen.width/2);
     var top = 0;
    // var myWindow = window.open(pageURL, pageTitle,'resizable=yes, width=' + popupWinWidth + ', height=' + popupWinHeight + ', top=' + top + ', left=' + left);
    var myWindow = window.open(pageURL,'_blank');
 }
 var customDialog = function (options,url) {
   //  localStorage.removeItem('successFlag');
     $('<div class"dialog-box"></div>').appendTo('body')
                     .html('<div class="dialog-box-text" style="margin-top: 15px; margin-left: 15px; font-weight: bold;font-size:16px; font-weight: normal;">You are about to be redirected to an external site which will<span style="font-weight: bold; font-weight: bold;"> OPEN IN NEW TAB </span> as shown below.<span style="font-weight: normal;">Please come back to our site by selecting your previous tab.</span></div><div style="text-align:center; padding: 20px 0px"><img src="images/newtab.png" width="600"/></div><div><input class= "dialog-box-checkbox" style="margin: 15px 10px 16px 15px;" type="checkbox" id="myCheckBox" />Don\'t show this message again.</div>')
                     .dialog({
                         modal: true,
                         title: options.title || ''+options.message+'', zIndex: 10000, autoOpen: true,
                         width: 'auto', resizable: false,
                         buttons: {
                             Ok: function () {
                                     createPopupWin(url,url,1250,1250);
                                     $(this).remove();
                                
                             },
                             Cancel: function () {
                                 $(this).dialog("close");
                             },
                         },
                         close: function (event, ui) {
                             $(this).remove();
                         }
                     });
                     $("#myCheckBox").click( function(){
                         if($(this).is(':checked')){ 
                             localStorage.setItem('flag', 1);
                         }else{ 
                             localStorage.setItem('flag', 0);
                         }
                      });
 };
 $("div.homepage-banner-custom  a").click(function(ev) {
     ev.preventDefault();
     var hrefurl =decodeURIComponent($(this).attr('href'));
     var hrefvalue = "";
     if (hrefurl.indexOf("cus_ich_{subid}") == -1 || hrefurl.indexOf("cus_ich_0") == -1){ 
        hrefvalue = hrefurl;
     }
     if (hrefurl.indexOf("cus_ich_{subid}") >= 0 || hrefurl.indexOf("cus_ich_0") >= 0){ 
        hrefvalue = hrefurl.replace('cus_ich_{subid}', 'cus_ich_'+user_id);
     }
     if (hrefvalue.indexOf("cus_ich_0") >= 0){
         hrefvalue = hrefvalue.replace('cus_ich_0', 'cus_ich_'+user_id);
     }
     localStorage.setItem('lastclickedurl', hrefvalue);
     if(user_id  == 0){
         $('.ty-account-info__buttons').children('a').trigger("click");
         $('.ty-btn__login').on('click',function(){
             localStorage.setItem('successFlag', 1);
        });
     }else{
         if(localStorage.getItem('flag') == 1){
             createPopupWin(hrefvalue,hrefvalue,750,850);
         }else{
             customDialog({message: 'You are about to be redirected to an external site which will OPEN IN NEW TAB as shown below.',title: 'Confirm Please'},hrefvalue);
         }
     }
 });