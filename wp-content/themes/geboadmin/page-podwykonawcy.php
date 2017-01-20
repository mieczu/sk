<?php
/*
 * Template Name: Podwykonawcy
 *
 */

if(!is_user_logged_in()) {
    auth_redirect();
}

global $wpdb, $current_user;

switch ($_REQUEST['action']) {
    case 'autocomplete2':
        $results = $wpdb->get_results('SELECT id, number FROM wp_czm_orders WHERE number like \'%'.$_REQUEST['term'].'%\'', ARRAY_A);
        die(json_encode($results));
        break;
    case 'e':
        $results = $wpdb->get_results('SELECT name FROM wp_czm_subcontractor WHERE id =20', ARRAY_A);
        die(var_dump($results));
        break;
    case 'autocomplete':
        $results = $wpdb->get_results('SELECT id, name FROM wp_czm_subcontractor WHERE name like \'%'.$_REQUEST['term'].'%\'', ARRAY_A);
        die(json_encode($results));
        break;
    case 'exists':
        die(json_encode($_POST));
        $results = $wpdb->get_results('SELECT id, name FROM wp_czm_subcontractor WHERE name like \'%'.$_REQUEST['term'].'%\'', ARRAY_A);
        die(json_encode($results));
        break;
    case 'delete_sub':
        if(isset($_REQUEST['id'])) {
            $del_result = $wpdb->delete('wp_czm_subcontractor_to_order', array('ID' => $_REQUEST['id']));

            if($del_result) {
                if($del_result == 1) {
                    die(json_encode(array(
                        "status" => 'success',
                        "msg"    => "Podwykonawca został usunięty.",
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
                    "msg"    => "Podwykonawca nie został usunięty.",
                    "id"     => $_REQUEST['id']
                )));
            }
        }
        else {
            die(json_encode(array(
                "status" => 'bad_id',
                "msg"    => "Niepoprawne id podwykonawcy",
                "id"     => $_REQUEST['id']
            )));
        }
        break;
    case 'save_sub':
        $message = array();

        foreach($_POST['row'] as $sub_item) {
            if (is_numeric($sub_item['id'])) {
                $results = $wpdb->get_results('SELECT name FROM wp_czm_subcontractor WHERE id ='.$sub_item['id'], ARRAY_A);
                if($results[0]['name']!=$sub_item['subc']){
                    $sub_item['id'] = '';
                }
            }

            $data = array(
                'id_subcontractor' => $sub_item['id'],
                'subcontractor'    => $sub_item['subc'],
                'f_number'         => $sub_item['name'],
                'value'            => (tofloat($sub_item['value'])*10000),
                'currency'         => $sub_item['wal'],
                'exchange'         => (tofloat($sub_item['exch'])*10000),
                'date_mod'         => date('Y-m-d H:i:s')
            );


            if(!$wpdb->update('wp_czm_subcontractor_to_order', $data, array('ID' => $sub_item['id_sub']))) {
                $message = array(
                    'status' => 'error',
                    'id'     => $sub_item['id_sub'],
                    'msg'    => $wpdb->last_error
                );
                die(json_encode($message));
            }
        }
        $message = array(
            'status' => 'success',
            'id'=>'',
        );
//        die('<pre>'.print_r($mes, true).'</pre>');
        die(json_encode($message));
        break;
    case 'new_sub':
        if(!$wpdb->insert('wp_czm_subcontractor_to_order', array(
            'id_order' => $_POST['id_order'],
            'exchange' => 10000,
            'disabled' => 0,
            'date_add' => date('Y-m-d H:i:s'),
            'date_mod' => date('Y-m-d H:i:s')
        ))) {
            $message           = array();
            $message['status'] = 'error';
            die(json_encode($message));
        }else {
            $message           = array();
            $message['status'] = 'success';
            $message['id']     = $wpdb->insert_id;
            die(json_encode($message));
        }
        break;
    case 'new':

        //            die(json_encode($results));
        //         die ('<pre>'.print_r($_REQUEST, true).'</pre>');
        if(!$wpdb->insert('wp_czm_subcontractor', array(
            'name'      => htmlentities(strtoupper($_POST['fname'])),
            'address'   => htmlentities($_POST['adres']),
            'post_code' => $_POST['post_code'],
            'kraj'      => $_POST['kraj'],
            'city'      => $_POST['city'],
            'nip'       => $_POST['fnip'],
            'email'     => $_POST['email'],
            'note'      => $_POST['message'],
            'disabled'  => $_POST['zablokowany'],
            'date_add'  => date('Y-m-d H:i:s')
        ))
        ) {
            $_REQUEST['action'] = 'add';
            $message            = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Błąd dodawania: '.$wpdb->last_error.'", {position: "top-center", type: "st-error"});
                });
            </script>';
        }
        else {
            $message = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Dodano podwykonawcę: '.$wpdb->insert_id.'", {position: "top-center", type: "st-success"});
                });
            </script>';
        }
        //        var_dump($wpdb->last_error);
        break;
    case 'update':
        //         die ('<pre>'.print_r($_REQUEST, true).'</pre>');

        if(!$wpdb->update('wp_czm_subcontractor', array(
            'name'      => htmlentities(strtoupper($_POST['fname'])),
            'address'   => htmlentities($_POST['adres']),
            'post_code' => $_POST['post_code'],
            'kraj'      => $_POST['kraj'],
            'city'      => $_POST['city'],
            'nip'       => $_POST['fnip'],
            'email'     => $_POST['email'],
            'note'      => $_POST['message'],
            'disabled'  => $_POST['zablokowany']
        ), array('ID' => $_POST['id']))
        ) {
            $_REQUEST['action'] = 'edit';
            $message            = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Błąd zapisu: '.$wpdb->last_error.'", {position: "top-center", type: "st-error"});
                });
            </script>';
        }
        else {
            $message = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Zapisano podwykonawcę", {position: "top-center", type: "st-success"});
                });
            </script>';
        }
        break;
    case 'delete':
        if(is_app_admin($current_user)) {
            if(!$wpdb->delete('wp_czm_subcontractor', array('ID' => $_REQUEST['id']))
            ) {

                $message = '
                    <script>
                        jQuery(document).ready(function() {
                            $.sticky("Nie można usunąć podwykonawcy: '.$wpdb->last_error.'", {position: "top-center", type: "st-error"});
                        });
                    </script>';
            }
            else {
                $message = '
                    <script>
                        jQuery(document).ready(function() {
                            $.sticky("Usunięto podwykonawcę", {position: "top-center", type: "st-success"});
                        });
                    </script>';
            }
        }

        $_REQUEST['action'] = '';
        break;
    default:
        if(isset($_REQUEST['xhr']) && $_REQUEST['xhr'] == 1) {
            $results = $wpdb->get_results('SELECT id, name, email, nip, note, date_add FROM wp_czm_subcontractor', ARRAY_N);
            //echo '<pre>'.print_r($results,true).'</pre>';

            foreach($results as $key => $row) {
                $action = '<a href="'.get_bloginfo('url').'/podwykonawcy?action=edit&id='.$results[$key]['0'].'" class="sepV_a" title="Edytuj"><i class="splashy-contact_blue_edit"></i></a>';
                if(is_app_admin($current_user)) {
                    $action .= '<a href="'.get_bloginfo('url').'/podwykonawcy?action=delete&id='.$results[$key]['0'].'"class="ajax-link" title="Usuń"><i class="splashy-remove"></i></a>';
                }
                $results[$key][] = $action;

            }
            //echo '<pre>'.print_r($response,true).'</pre>';
            //echo '<pre>'.print_r($results,true).'</pre>';


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
            case 'edit':
                get_template_part('podwykonawcy', 'edit');
                break;
            case 'add':
                get_template_part('podwykonawcy', 'add');

                break;
            default:?>
                    <table id="dt_podwykonawcy" class="table table-bordered table-striped table_vam" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nazwa</th>
                            <th>Email</th>
                            <th>NIP</th>
                            <th>Notatka</th>
                            <th>Data dodania</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
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