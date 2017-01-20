<?php
/*
 * Template Name: Users Profile
 *
 */

if(!is_user_logged_in()) {
    auth_redirect();
}

get_header();

$template_url = get_template_directory_uri();
?>
<div id="contentwrapper">
<div class="main_content">

<?php

global $current_user, $wp_roles;

$error = array();

if('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) && $_POST['action'] == 'update-user') {

    /* Update user password. */
    if(!empty($_POST['pass1']) && !empty($_POST['pass2'])) {
        if($_POST['pass1'] == $_POST['pass2']) wp_update_user(array(
            'ID'        => $current_user->ID,
            'user_pass' => esc_attr($_POST['pass1'])
        ));
        else
            $error[] = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
    }

    /* Update user information. */
    if(!empty($_POST['url'])) wp_update_user(array(
        'ID'       => $current_user->ID,
        'user_url' => esc_url($_POST['url'])
    ));
    if(!empty($_POST['email'])) {
        if(!is_email(esc_attr($_POST['email']))) $error[] = __('The Email you entered is not valid.  please try again.', 'profile');
        elseif(email_exists(esc_attr($_POST['email'])) != $current_user->id) $error[] = __('This email is already used by another user.  try a different one.', 'profile');
        else {
            wp_update_user(array(
                'ID'         => $current_user->ID,
                'user_email' => esc_attr($_POST['email'])
            ));
        }
    }

    if(!empty($_POST['first-name'])) update_user_meta($current_user->ID, 'first_name', esc_attr($_POST['first-name']));
    if(!empty($_POST['last-name'])) update_user_meta($current_user->ID, 'last_name', esc_attr($_POST['last-name']));
    if(!empty($_POST['description'])) update_user_meta($current_user->ID, 'description', esc_attr($_POST['description']));

    /* Redirect so the page will show updated info.*/
    /*I am not Author of this Code- i dont know why but it worked for me after changing below line to if ( count($error) == 0 ){ */
    if(count($error) == 0) {
        //action hook for plugins and extra fields saving
          do_action('edit_user_profile_update', $current_user->ID);
        // wp_redirect( get_permalink() );
        // exit;
        echo '<script>jQuery(document).ready(function(){
                       jQuery.sticky("Profil został zaktualizowany", {autoclose : false, position: "top-center" })

                    });
                    </script>';
    }
}

//echo '<pre>'.print_r($current_user,true).'</pre>';
      if(have_posts()) : while (have_posts()) : the_post(); ?>
            <div id="post-<?php the_ID(); ?>">
                <div class="entry-content entry">
                    <?php the_content(); ?>
                    <?php if(!is_user_logged_in()) : ?>
                        <p class="warning">
                            <?php _e('You must be logged in to edit your profile.', 'profile'); ?>
                            <?php auth_redirect(); ?>
                        </p><!-- .warning -->
                    <?php else : ?>
                        <?php

                        global $current_user;

                        $user_roles = $current_user->roles;
                        $user_role  = array_shift($user_roles);

//                        echo $user_role;


                        if(count($error) > 1) echo '<p class="error">'.implode("<br />", $error).'</p>';
//                        echo 'aa'.count($error).'zz';
//                        var_dump($current_user);
                        ?>


v
                        <form method="post" id="adduser" action="<?php the_permalink(); ?>">
                            <p class="form-username">
                                <label for="first-name"><?php _e('First Name', 'profile'); ?></label>
                                <input class="text-input" name="first-name" type="text" id="first-name"
                                       value="<?php the_author_meta('first_name', $current_user->ID); ?>"/>
                            </p><!-- .form-username -->
                            <p class="form-username">
                                <label for="last-name"><?php _e('Last Name', 'profile'); ?></label>
                                <input class="text-input" name="last-name" type="text" id="last-name"
                                       value="<?php the_author_meta('last_name', $current_user->ID); ?>"/>
                            </p><!-- .form-username -->
                            <p class="form-email">
                                <label for="email"><?php _e('E-mail *', 'profile'); ?></label>
                                <input class="text-input" name="email" type="text" id="email"
                                       value="<?php the_author_meta('user_email', $current_user->ID); ?>"/>
                            </p><!-- .form-email -->
                            <p class="form-url">
                                <label for="url"><?php _e('Website', 'profile'); ?></label>
                                <input class="text-input" name="url" type="text" id="url"
                                       value="<?php the_author_meta('display_name', $current_user->ID); ?>"/>
                            </p><!-- .form-url -->
                            <p class="form-password">
                                <label for="pass1"><?php _e('Password *', 'profile'); ?> </label>
                                <input class="text-input" name="pass1" type="password" id="pass1"/>
                            </p><!-- .form-password -->
                            <p class="form-password">
                                <label for="pass2"><?php _e('Repeat Password *', 'profile'); ?></label>
                                <input class="text-input" name="pass2" type="password" id="pass2"/>
                            </p><!-- .form-password -->
                            <style>
                                h3{
                                    display: none;
                                }
                            </style>

                            <?php
                            //action hook for plugin and extra fields
                            do_action('edit_user_profile', $current_user);
                            ?>
                            <p class="form-submit">
                                <?php echo $referer; ?>
                                <input name="updateuser" type="submit" id="updateuser" class="submit button"
                                       value="Zapisz"/>
                                <?php wp_nonce_field('update-user') ?>
                                <input name="action" type="hidden" id="action" value="update-user"/>
                            </p><!-- .form-submit -->
                        </form><!-- #adduser -->
                    <?php endif; ?>
                </div>
                <!-- .entry-content -->
            </div><!-- .hentry .post -->
        <?php endwhile; ?>
        <?php else: ?>
            <p class="no-data">
                <?php _e('Sorry, no page matched your criteria.', 'profile'); ?>
            </p><!-- .no-data -->
        <?php endif;?>





</div>
</div>
<?php get_sidebar(); ?>

<?php //get_footer(); ?>
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
<!-- tables functions -->
<script src="<?php echo $template_url; ?>/js/gebo_tables.js"></script>

<script>
    $(document).ready(function () {
        //* show all elements & remove preloader
        setTimeout('$("html").removeClass("js")', 1000);
    });
</script>

</div>
</body>
</html>