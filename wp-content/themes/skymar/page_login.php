<?php

/* Template Name: Login Page */

$template_url = get_template_directory_uri();
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
        <title>Crown - premium responsive admin template for backend systems</title>
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>

        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/spinner/ui.spinner.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/spinner/jquery.mousewheel.js"></script>

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/charts/excanvas.min.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/charts/jquery.sparkline.min.js"></script>

        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/forms/uniform.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/forms/jquery.cleditor.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/forms/jquery.validationEngine-en.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/forms/jquery.validationEngine.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/forms/jquery.tagsinput.min.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/forms/autogrowtextarea.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/forms/jquery.maskedinput.min.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/forms/jquery.dualListBox.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/forms/jquery.inputlimiter.min.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/forms/chosen.jquery.min.js"></script>

        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/wizard/jquery.form.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/wizard/jquery.validate.min.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/wizard/jquery.form.wizard.js"></script>

        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/uploader/plupload.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/uploader/plupload.html5.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/uploader/plupload.html4.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/uploader/jquery.plupload.queue.js"></script>

        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/tables/datatable.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/tables/tablesort.min.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/tables/resizable.min.js"></script>

        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/ui/jquery.tipsy.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/ui/jquery.collapsible.min.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/ui/jquery.prettyPhoto.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/ui/jquery.progress.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/ui/jquery.timeentry.min.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/ui/jquery.colorpicker.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/ui/jquery.jgrowl.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/ui/jquery.breadcrumbs.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/ui/jquery.sourcerer.js"></script>

        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/calendar.min.js"></script>
        <script type="text/javascript" src="<?php echo $template_url; ?>/js/plugins/elfinder.min.js"></script>

        <script type="text/javascript" src="<?php echo $template_url; ?>/js/custom.js"></script>

        <?php wp_head(); ?>
    </head>

    <body class="nobg loginPage">

<!--     Top fixed navigation-->
<!--    <div class="topNav">-->
<!--    <div class="wrapper">-->
<!--    <div class="userNav">-->
<!--    <ul>-->
<!--    <li><a href="#" title=""><img src="images/icons/topnav/mainWebsite.png" alt="" /><span>Main website</span></a></li>-->
<!--    <li><a href="#" title=""><img src="images/icons/topnav/profile.png" alt="" /><span>Contact admin</span></a></li>-->
<!--    <li><a href="#" title=""><img src="images/icons/topnav/messages.png" alt="" /><span>Support</span></a></li>-->
<!--    <li><a href="login.html" title=""><img src="images/icons/topnav/settings.png" alt="" /><span>Settings</span></a></li>-->
<!--    </ul>-->
<!--    </div>-->
<!--    <div class="clear"></div>-->
<!--    </div>-->
<!--    </div>-->


    <!-- Main content wrapper -->
    <div class="loginWrapper">
        <div class="loginLogo"><img src="<?php echo $template_url; ?>/images/logo.png" alt="" /></div>
        <div class="widget">
            <div class="title"><img src="<?php echo $template_url; ?>/images/icons/dark/files.png" alt="" class="titleIcon" /><h6>Login panel</h6></div>
            <form action="<?php bloginfo('url');?>/wp-login.php" id="validate" class="form" method="post">
                <fieldset>
                    <div class="formRow">
                        <label for="login">Username:</label>
                        <div class="loginInput"><input type="text" name="log" class="validate[required]" id="login" /></div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <label for="pass">Password:</label>
                        <div class="loginInput"><input type="password" name="pwd" class="validate[required]" id="pass" /></div>
                        <div class="clear"></div>
                    </div>

                    <div class="loginControl">
                        <div class="rememberMe"><input type="checkbox" id="remMe" name="remMe" /><label for="remMe">Remember me</label></div>
                        <input type="submit" value="Log me in" class="dredB logMeIn" />
                        <div class="clear"></div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

<!--     Footer line-->
<!--    <div id="footer">-->
<!--    <div class="wrapper">As usually all rights reserved. And as usually brought to you by <a href="http://themeforest.net/user/Kopyov?ref=kopyov" title="">Eugene Kopyov</a></div>-->
<!--    </div>-->


    </body>
    </html>
<?php
 // If logged in:
//    wp_loginout(get_permalink()); // Display "Log Out" link.

$args = array(
    'echo'           => true,
    'remember'       => true,
    'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
    'form_id'        => 'loginform',
    'id_username'    => 'user_login',
    'id_password'    => 'user_pass',
    'id_remember'    => 'rememberme',
    'id_submit'      => 'wp-submit',
    'label_username' => __( 'Username' ),
    'label_password' => __( 'Password' ),
    'label_remember' => __( 'Remember Me' ),
    'label_log_in'   => __( 'Log In' ),
    'value_username' => '',
    'value_remember' => false
);

wp_login_form( $args );