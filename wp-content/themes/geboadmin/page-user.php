<?php
/*
 * Template Name: Users Page
 *
 */

if (!is_user_logged_in()) {
    auth_redirect();
}

//if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
//    /* special ajax here */
//
//
//    die(json_encode(array(
//        "status" => 'success',
//        "id"     => $_REQUEST['id']
//    )));
//}
//echo '<br><br><br><br><pre>'.print_r($_POST, true).'</pre>';

switch ($_REQUEST['action']) {
    case 'new':
        if (isset($_POST['adduser'])) {

            $userdata = array(
                'user_login'   => $_POST['login'],
                'user_pass'    => $_POST['password'],
                'user_email'   => $_POST['email'],
                'first_name'   => $_POST['first-name'],
                'last_name'    => $_POST['last-name'],
                'role'         => $_POST['role'],
                'display_name' => $_POST['first-name'].' '.$_POST['last-name'],
                'description'  => $_POST['message']
                //                ,
                //                'air'          => 1,
                //                'sea'          => 1,
                //                'land'         => true
                //                'air'          => $_POST['air']?0:1,
                //                'sea'          => $_POST['sea']?0:1,
                //                'land'         => $_POST['land']?0:1
            );

            $user_id = wp_insert_user($userdata);

            if (is_numeric($user_id)) {
                $_REQUEST['action'] = '';
                update_user_meta($user_id, 'air', $_POST['air']);
                update_user_meta($user_id, 'sea', $_POST['sea']);
                update_user_meta($user_id, 'land', $_POST['land']);
            } else{
                if (is_wp_error($user_id)) {
                    $message123 = $user_id->get_error_message();
                }
                $_REQUEST['action'] = 'add';
            }
        } else{
            $_REQUEST['action'] = 'add';
        }
        break;
    case 'delete':
        if (isset($_REQUEST['id'])) {
            require(ABSPATH.'/wp-admin/includes/user.php');
            if (wp_delete_user($_REQUEST['id'])) {
                die(json_encode(array(
                    "status" => 'success',
                    "id"     => $_REQUEST['id']
                )));
            } else{
                die(json_encode(array(
                    "status" => 'error',
                    "id"     => $_REQUEST['id'].ABSPATH.'/wp-admin/includes/user.php'
                )));
            }

        } else{
            die(json_encode(array(
                "status" => 'bad_id',
                "id"     => $_REQUEST['id']
            )));
        }
        break;
    case 'update':

        if (isset($_POST['login'])) {
            //                        echo '<br><br><br><br><pre>'.print_r($_POST, true).'</pre>';
            $userdata = get_user_meta($_POST['user_id'], 'wp_capabilities');
            $userdata = get_user_meta($_POST['user_id'], 'wpuf_postlock');
            $userdata = array(
                'ID'           => $_POST['user_id'],
                //                'user_login'   => $_POST['login'],
                'user_pass'    => $_POST['password'],
                'user_email'   => $_POST['email'],
                'first_name'   => $_POST['first-name'],
                'last_name'    => $_POST['last-name'],
                'role'         => $_POST['role'],
                'display_name' => $_POST['first-name'].' '.$_POST['last-name'],
                'description'  => $_POST['message']
            );


            if (!empty($_POST['pass1']) && !empty($_POST['pass2'])) {
                if ($_POST['pass1'] == $_POST['pass2'])
                    $userdata['user_pass'] = esc_attr($_POST['pass1']);

                else
                    $error[] = __('Podane hasła nie pasują do siebie. Hasło nie zosało zmienione', 'profile');
            }

            $user_id = wp_update_user($userdata);

            if (is_numeric($user_id)) {
                $user_id = update_user_meta($_POST['user_id'], 'account_locked', $_POST['lock']);
                update_user_meta($_POST['user_id'], 'air', $_POST['air']);
                update_user_meta($_POST['user_id'], 'sea', $_POST['sea']);
                update_user_meta($_POST['user_id'], 'land', $_POST['land']);
                $_REQUEST['action'] = '';
                $message123         = 'Użytkownik został zaktualizowany';
            } else{

                $message123 = 'Błąd: ';
                if (is_wp_error($user_id)) {
                    $message123 .= $user_id->get_error_message();
                }
                echo '<pre>'.print_r($user_id, true).'</pre>';
                $_REQUEST['action'] = 'edit';
                $_REQUEST['id']     = $_POST['user_id'];
            }


            if (count($error) == 0) {
                do_action('edit_user_profile_update', $_POST['user_id']);


            } else{
                $message123 = '';
                foreach($error as $msg) {
                    $message123 .= $msg.'<br/>';
                }

            }
        } else{
            $_REQUEST['action'] = '';
            $message123         = 'User nie został zmieniony';
        }

        break;
}


get_header();

$template_url = get_template_directory_uri();

global $current_user;

//echo '<pre>'.print_r($user_query, true).'</pre>';
if (isset($message123)) {
    if (!empty($message123)) {
        ?>
        <script>
            jQuery(document).ready(function () {
                jQuery.sticky('<?php echo $message123;?>', {autoclose: false, position: "top-center", type: "st-info"});
            });
        </script>
        <?php
    }
}
?>
<div id="contentwrapper">
    <div class="main_content">

        <?php

        if (is_app_admin($current_user)) {
            switch ($_REQUEST['action']) {
                case 'edit':
                    get_template_part('user', 'edit');
                    break;
                case 'add':
                    get_template_part('user', 'add');
                    break;
                default:

                    $user_query = new WP_User_Query(array(
                        'meta_query' => array(
                            'relation' => 'OR',
                            array(
                                'key'     => $wpdb->get_blog_prefix($blog_id).'capabilities',
                                'value'   => 'client_user',
                                'compare' => 'like'
                            ),
                            array(
                                'key'     => $wpdb->get_blog_prefix($blog_id).'capabilities',
                                'value'   => 'client_admin',
                                'compare' => 'like'
                            )
                        )
                    ));
                    ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <h3 class="heading">Użytkownicy</h3>
<!--                            --><?php //echo '<pre>';var_dump($user_query);echo '</pre>';?>

                            <table class="table table-bordered table-striped table_vam" id="dt_gal">
                                <thead>
                                <tr>
                                    <th class="table_checkbox"><input type="checkbox" name="select_rows"
                                                                      class="select_rows"
                                                                      data-tableid="dt_gal"/></th>
                                    <th>Name</th>
                                    <th>Login</th>
                                    <th>Data dodania</th>
                                    <th>Email</th>
                                    <th>Uprawnienia</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                foreach($user_query->results as $user) {
                                    $user_status = get_user_meta( $user->ID,'account_locked');
                                    $user_status = $user_status['0'];

                                    ?>
                                    <tr<?php echo ($user_status?' class="important"':'');?>>
                                        <td><input type="checkbox" name="row_sel" class="row_sel"/></td>
                                        <td><?php echo $user->data->display_name; ?></td>
                                        <td><?php echo $user->data->user_login; ?></td>
                                        <td><?php echo $user->data->user_registered; ?></td>
                                        <td><?php echo $user->data->user_email; ?></td>
                                        <td>
                                            <?php
                                            if (in_array('client_admin', $user->roles)) {
                                                echo 'Administrator';
                                            }

                                            if (in_array('client_user', $user->roles)) {
                                                echo 'Użytkownik';
                                            }

                                            if (in_array('client_manage', $user->roles)) {
                                                echo 'Manager';
                                            }

                                            ?>
                                        </td>
                                        <td><?php echo ($user_status?'Zablokowany':'Aktywny');?></td>
                                        <td>
                                            <a href="?action=edit&id=<?php echo $user->ID; ?>" title="Edytuj"><i
                                                    class="splashy-contact_blue_edit"></i></a>
                                            <!--                                        <a href="#" class="sepV_a" title="Zablokuj"><i-->
                                            <!--                                                class="splashy-contact_blue_remove"></i></a>-->
                                            <!--                                        <a href="/skymar/sgfghfg" class="sepV_a ajax-link" title="Odblokuj"><i-->
                                            <!--                                                class="splashy-contact_grey_remove"></i></a>-->
                                            <a href="<?php echo get_bloginfo('url').'/uzytkownicy/?action=delete&id='.$user->ID ?>"
                                               class="ajax-delete" title="Usuń"><i class="splashy-remove"></i></a>
                                        </td>
                                    </tr>


                                    <?php
                                }

                                ?>

                                </tbody>
                            </table>

                        </div>
                    </div>

                    <!-- hide elements (for later use) -->
                    <div class="hide">
                        <!-- actions for datatables -->
                        <div class="dt_gal_actions">
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn dropdown-toggle">Action <span
                                        class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#" class="delete_rows_dt" data-tableid="dt_gal"><i
                                                class="icon-trash"></i>Delete</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- confirmation box -->
                        <div id="confirm_dialog" class="cbox_content">
                            <div class="sepH_c tac"><strong>Are you sure you want to delete this row(s)?</strong>
                            </div>
                            <div class="tac">
                                <a href="#" class="btn btn-gebo confirm_yes">Yes</a>
                                <a href="#" class="btn confirm_no">No</a>
                            </div>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span12 ">


                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
            }
        } else{
            ?>
            <p class="warning">
                Ta strona jest dostępna tylko dla administratora!
            </p>
            <?php

        } ?>

    </div>

    <div class="sticky-queue top-center" style="">
        <div id="loading_animation" class="sticky border-top-right " style="height: 18px; display: none;">
            <div rel="loading_animation" class="sticky-note" style="text-align: center">
                <img alt="" src="<?php echo get_bloginfo('url'); ?>/wp-content/themes/geboadmin/img/ajax_loader.gif"/>
            </div>
        </div>
    </div>

</div>
<?php get_sidebar(); ?>

<?php get_footer(); ?>

<script>
    $(document).ready(function () {
        //* show all elements & remove preloader
        setTimeout('$("html").removeClass("js")', 1000);
    });
</script>

</div>
</body>
</html>