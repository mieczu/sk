<?php
/*
 * Template Name: Pozycje na fakturze
 *
 */

if(!is_user_logged_in()) {
    auth_redirect();
}

global $wpdb, $current_user;

switch ($_REQUEST['action']) {
    case 'autocomplete':
        $results = $wpdb->get_results('SELECT id, name FROM wp_czm_invoices_items WHERE name like \'%'.$_REQUEST['term'].'%\'', ARRAY_A);
        die(json_encode($results));
        break;
    case 'new':
        if(!$wpdb->insert('wp_czm_invoices_items', array(
            'name'     => $_POST['poz_name'],
            'disabled' => 0
        ))
        ) {
            $message           = array();
            $message['status'] = 'error';
            $message['msg'] = 'Pozycja nie została dodana: '.$wpdb->last_error;
            die(json_encode($message));
        }
        else {
            $message           = array();
            $message['status'] = 'success';
            $message['id']     = $wpdb->insert_id;
            die(json_encode($message));
        }
        break;
    case 'delete':
        if(is_app_admin($current_user)) {
            if(isset($_REQUEST['id'])) {
                $del_result = $wpdb->delete('wp_czm_invoices_items', array('ID' => $_REQUEST['id']));

                if($del_result) {
                    if($del_result == 1) {
                        die(json_encode(array(
                            "status" => 'success',
                            "msg"    => "Pozycja została usunięta.",
                            "id"     => $_REQUEST['id']
                        )));
                    }
                    else {
                        die(json_encode(array(
                            "status" => 'error',
                            "msg"    => "Usunięto więcej niż jedną pozycję. Liczba usuniętych wierszy: ".$del_result,
                            "id"     => $_REQUEST['id']
                        )));
                    }
                }
                else {
                    die(json_encode(array(
                        "status" => 'error',
                        "msg"    => "Pozycja nie została usunięta.",
                        "id"     => $_REQUEST['id']
                    )));
                }
            }
            else {
                die(json_encode(array(
                    "status" => 'bad_id',
                    "msg"    => "Niepoprawne id pozycji",
                    "id"     => $_REQUEST['id']
                )));
            }
        }

        $_REQUEST['action'] = '';
        break;
    default:
        if(isset($_REQUEST['xhr']) && $_REQUEST['xhr'] == 1) {
            $results = $wpdb->get_results('SELECT id, name FROM wp_czm_invoices_items', ARRAY_N);

            foreach($results as $key => $row) {
                $action          = '<a href="'.get_bloginfo('url').'/pozycje?action=delete&id='.$results[$key]['0'].'"class="ajax-link" title="Usuń"><i class="splashy-remove"></i></a>';
                $results[$key][] = $action;

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
            //            case 'edit':
            //                get_template_part('pozycje', 'edit');
            //                break;
            //            case 'add':
            //                get_template_part('pozycje', 'add');
            //
            //                break;
            default:
                ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <h3 class="heading">Faktury</h3>
                            <table id="dt_pozycje" class="table table-bordered table-striped table_vam" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nazwa</th>
                                    <th></th>
                                </tr>
                                </thead>
                            </table>
                            <input type="text" name="poz_name" id="poz_name">
                            <button class="btn btn-gebo" type="button" id="addPoz">Dodaj</button>
                        </div>
                    </div>
                <?php
        }
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