<?php
/*
 * Template Name: Raporty
 *
 */

if (!is_user_logged_in()) {
    auth_redirect();
}

global $wpdb, $current_user, $wp_roles;

if (!isset($_REQUEST['action']))
    $_REQUEST['action'] = '';

switch ($_REQUEST['action']) {
    case 'print':
        if (isset($_REQUEST['id'])) {
            get_template_part('zlecenia', 'print');
        }
        die('');
        break;
    case 'post':
        var_dump($_POST);
        die();
        break;
    case 'zlecenia_pdf':
        get_template_part('raporty', 'zlecenia-pdf');
        die('');
        break;
    case 'klienci_pdf':
        get_template_part('raporty', 'klienci-pdf');
        die('');
        break;
    default:
        if (isset($_REQUEST['xhr']) && $_REQUEST['xhr'] == 1) {
            if (is_app_admin($current_user)) {
                $sql     = 'SELECT id, number, id_nadawca, id_odbiorca, id_platnik, id_user, transport, typ, date_add, nadawca, odbiorca, platnik, disabled FROM wp_czm_orders ORDER BY id DESC';
                $results = $wpdb->get_results($sql, ARRAY_N);
            } else{
                $results = $wpdb->get_results('SELECT id, number, id_nadawca, id_odbiorca, id_platnik, id_user, transport, typ, date_add, nadawca, odbiorca, platnik, disabled FROM wp_czm_orders WHERE id_user='.$current_user->ID.' ORDER BY id DESC', ARRAY_N);
            }

            //            var_dump( $results);

            $return = array();
            foreach($results as $key => $result) {

            }
            die(json_encode(array('data' => $results)));
        }
}


get_header();

echo $message;

$template_url = get_template_directory_uri();


?>
<div id="contentwrapper">
    <div class="main_content">

        <?php

        switch ($_REQUEST['action']) {
            case 'zlecenia':
                get_template_part('raporty', 'zlecenia');
                break;
            case 'pracownicy':
                get_template_part('raporty', 'pracownicy');
                break;
            case 'klienci':
                get_template_part('raporty', 'klienci');
                break;
            case 'koszty':
                get_template_part('raporty', 'koszty');
                break;
            default:


                ?>
                <div class="row-fluid">
                    <div class="span12">
                        <h3 class="heading">Raporty</h3>

                        <div>Musisz wybrać rodzaj raportu.</div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">


                    </div>
                </div>

                <?php
        }


        $error = array();

        //echo '<pre>'.print_r($current_user, true).'</pre>';
        //echo '<pre>'.print_r($results, true).'</pre>';

        ?>
        <div class="sticky-queue top-center" style="">
            <div id="loading_animation" class="sticky border-top-right " style="height: 18px; display: none;">
                <div rel="loading_animation" class="sticky-note" style="text-align: center">
                    <img alt=""
                         src="<?php echo get_bloginfo('url'); ?>/wp-content/themes/geboadmin/img/ajax_loader.gif"/>
                </div>
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