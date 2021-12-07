<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
    select, input[type="file"] {
        height: auto !important;
    }
    .close_data{
        color: red !important;
        font-size: 20px;
        padding: 20px;
    }
</style>

<script>
    setTimeout(function () { $('.alert-wrap').modal('hide'); }, 5000);
</script>

<ul class="nav hover-show nav-pills nav-child">
    <li class="dropdown  ">
        <a href="#" class="dropdown-toggle">
            Products
            <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
                                        
            <li class="products">
                <a class="  " href="su-admincenter.php?dispatch=infolink.info_upload">Products</a>
            </li>  
            <li class="products_manage">
                <a class="  " href="su-admincenter.php?dispatch=infolink.product_manage">Products Manage</a>
            </li>                            
        </ul>
    </li>     

    <li class="dropdown  ">
        <a href="#" class="dropdown-toggle">
            Vendors
            <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
                                        
            <li class="products">
                <a class="  " href="su-admincenter.php?dispatch=infolink.vendor_upload">Vendors</a>
            </li>     

            <li class="products">
                <a class="  " href="su-admincenter.php?dispatch=infolink.affiliate_links">Affiliate Links</a>
            </li>                          
        </ul>
    </li> 

    <li class="dropdown  ">
        <a href="#" class="dropdown-toggle">
            Administration
            <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
            <li class="affiliates_partnership dropdown-submenu">
                <div class="dropdown-submenu__link-overlay"></div>
                <a class=" dropdown-submenu__link is-addon" href="#">
                <span>Shipping & taxes<i class="icon-is-addon"></i></span>
                <span class="hint">Manage your affiliates.</span></a>

                <ul class="dropdown-menu">
                    <li class="  is-addon"><a href="su-admincenter.php?dispatch=infolink.shipping_upload">Shipping methods</a></li> 
                    <li class="  is-addon"><a href="su-admincenter.php?dispatch=infolink.taxes_upload">Taxes</a></li>          
                </ul>
            </li>      

            <li class="affiliates_partnership dropdown-submenu">
                <div class="dropdown-submenu__link-overlay"></div>
                <a class=" dropdown-submenu__link is-addon" href="#">
                <span>Import Data<i class="icon-is-addon"></i></span></a>

                <ul class="dropdown-menu">
                    <li class="  is-addon"><a href="su-admincenter.php?dispatch=infolink.import_data">Products</a></li>        
                </ul>
            </li>                    
        </ul>
    </li>


</ul>