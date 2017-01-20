<?php
if(!is_user_logged_in()) {
    auth_redirect();
}

wp_footer();

global $post;
$template_url = get_template_directory_uri();
?>

<script src="<?php echo $template_url; ?>/js/jquery.min.js"></script>
<!-- smart resize event -->
<script src="<?php echo $template_url; ?>/js/jquery.debouncedresize.min.js"></script>
<!-- hidden elements width/height -->
<script src="<?php echo $template_url; ?>/js/jquery.actual.min.js"></script>
<!-- js cookie plugin -->
<script src="<?php echo $template_url; ?>/js/jquery.cookie.min.js"></script>
<!-- main bootstrap js -->
<script src="<?php echo $template_url; ?>/bootstrap/js/bootstrap.min.js"></script>
<!-- bootstrap plugins -->
<script src="<?php echo $template_url; ?>/js/bootstrap.plugins.min.js"></script>
<!-- tooltips -->
<script src="<?php echo $template_url; ?>/lib/qtip2/jquery.qtip.min.js"></script>
<!-- jBreadcrumbs -->
<script src="<?php echo $template_url; ?>/lib/jBreadcrumbs/js/jquery.jBreadCrumb.1.1.min.js"></script>
<!-- sticky messages -->
<script src="<?php echo $template_url; ?>/lib/sticky/sticky.min.js"></script>


<!-- fix for ios orientation change -->
<script src="<?php echo $template_url; ?>/js/ios-orientationchange-fix.js"></script>
<!-- scrollbar -->
<script src="<?php echo $template_url; ?>/lib/antiscroll/antiscroll.js"></script>
<script src="<?php echo $template_url; ?>/lib/antiscroll/jquery-mousewheel.js"></script>
<!-- common functions -->
<script src="<?php echo $template_url; ?>/js/gebo_common.js"></script>

<!-- colorbox -->
<script src="<?php echo $template_url; ?>/lib/colorbox/jquery.colorbox.min.js"></script>
<!-- datatable -->
<script src="<?php echo $template_url; ?>/lib/datatables/jquery.dataTables.min.js"></script>
<!-- additional sorting for datatables -->
<script src="<?php echo $template_url; ?>/lib/datatables/jquery.dataTables.sorting.js"></script>

<script src="<?php echo $template_url; ?>/lib/validation/jquery.validate.min.js"></script>
<!-- tables functions -->
<script src="<?php echo $template_url; ?>/js/gebo_tables.js"></script>

<script src="<?php echo $template_url; ?>/lib/datetimepicker/build/jquery.datetimepicker.full.min.js"></script>

<script src="<?php echo $template_url; ?>/js/common.js"></script>

<script src="<?php echo $template_url; ?>/lib/smoke/smoke.min.js"></script>
<?php
if (is_url_exist($template_url.'/js/'.$post->post_name.'.js')){?>
    <script src="<?php echo $template_url.'/js/'.$post->post_name.'.js' ?>"></script>
<?php }?>

<script src="<?php echo $template_url; ?>/lib/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>

<script src="<?php echo $template_url; ?>/js/jquery.imagesloaded.min.js"></script>
<script src="<?php echo $template_url; ?>/js/jquery.wookmark.js"></script>

<!-- sortable/filterable list -->
<script src="<?php echo $template_url; ?>/lib/list_js/list.min.js"></script>
<script src="<?php echo $template_url; ?>/lib/list_js/plugins/paging/list.paging.min.js"></script>

<!--<script src="--><?php //echo $template_url; ?><!--/js/noty.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        //* show all elements & remove preloader
        setTimeout('$("html").removeClass("js")',1000);
    });
</script>

</div>
</body>
</html>