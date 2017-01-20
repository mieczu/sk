<?php
/*
 * Template Name: Noty księgowe
 *
 */

if(!is_user_logged_in()) {
    auth_redirect();
}

global $wpdb, $current_user, $wp_roles;

if(!isset($_REQUEST['action'])) $_REQUEST['action'] = '';

switch ($_REQUEST['action']) {
    case 'get':
        if(isset($_REQUEST['id_order']) && is_numeric($_REQUEST['id_order'])) {
//            if(is_app_admin($current_user)) {
                $sql     = 'SELECT id, number, id_client, id_order, id_user, typ, date_add FROM wp_czm_noty WHERE id_order='.$_REQUEST['id_order'].' ORDER BY id DESC';
                $results = $wpdb->get_results($sql, ARRAY_N);
//            }
//            else {
//                $results = $wpdb->get_results('SELECT id, number, id_client, id_order, id_user, typ, date_add FROM wp_czm_noty WHERE id_order='.$_REQUEST['id_order'].' AND id_user='.$current_user->ID.' ORDER BY id DESC', ARRAY_N);
//            }

            //            var_dump( $results);

            $return = array();
            foreach($results as $key => $result) {
                $return[$result[0]]['client'] = $wpdb->get_results('SELECT id, short_name FROM wp_czm_clients WHERE id='.$result[2], ARRAY_N);
                $return[$result[0]]['order']  = $wpdb->get_results('SELECT id, number FROM wp_czm_orders WHERE id='.$result[3], ARRAY_N);
                $return[$result[0]]['user']   = $wpdb->get_results('SELECT id, display_name FROM wp_users WHERE id='.$result[4], ARRAY_N);


                $results[$key][2] = $return[$result[0]]['client'][0][1];
                $results[$key][3] = $return[$result[0]]['order'][0][1].inicialy($return[$result[0]]['user'][0][1]);
                $results[$key][4] = $return[$result[0]]['user'][0][1];


                $buttons = '<a href="'.get_bloginfo('url').'/noty?action=edit&id='.$results[$key]['0'].'" class="sepV_a" title="Edytuj"><i class="splashy-contact_blue_edit"></i></a>
                    <!--<a href="#" class="sepV_a" title="Zablokuj"><i class="splashy-contact_blue_remove"></i></a>
                    <a href="http://'.get_bloginfo('url').'/sgfghfg" class="sepV_a ajax-link" title="Odblokuj"><i class="splashy-contact_grey_remove"></i></a>-->';
                if(is_app_admin($current_user)) {
                    $buttons .= '<a href="'.get_bloginfo('url').'/noty/?action=delete&id='.$results[$key]['0'].'" class="ajax-link sepV_a" title="Usuń"><i class="splashy-remove"></i></a>';
                }
                $buttons .= '<a href="'.get_bloginfo('url').'/noty/?action=print&id='.$results[$key]['0'].'" class="sepV_a" title="Podgląd/Drukuj"><i class="splashy-document_letter"></i></a>';

                $results[$key][] = $buttons;
            }
            die(json_encode(array('data' => $results)));
        }
        break;
    case 'print':
        if(isset($_REQUEST['id'])) {
            get_template_part('noty', 'print');
        }
        die('');
        break;
    case 'delete':
        if(is_app_admin($current_user)) {
            if(isset($_REQUEST['id'])) {
                $del_result = $wpdb->delete('wp_czm_noty', array('ID' => $_REQUEST['id']));

                if($del_result) {
                    if($del_result == 1) {
                        die(json_encode(array(
                            "status" => 'success',
                            "msg"    => "Nota została usunięta.",
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
                        "msg"    => "Nota nie została usunięta.",
                        "id"     => $_REQUEST['id']
                    )));
                }

            }
            else {
                die(json_encode(array(
                    "status" => 'bad_id',
                    "msg"    => "Niepoprawne id noty",
                    "id"     => $_REQUEST['id']
                )));
            }
        }
        else {
            $_REQUEST['action'] = '';
        }
        break;
    case 'post':

        var_dump($_POST);
        die();
        break;
    case 'new':
        if(!empty($_POST)) {
            $sql     = "SELECT MAX(id) as 'id', hnumber  FROM wp_czm_noty WHERE date_add BETWEEN '".date('Y-m')."-01 00:00:00' AND '".date('Y-m-t')." 23:59:59'";
            $results = $wpdb->get_results($sql, ARRAY_A);
            $sql     = "SELECT hnumber FROM wp_czm_noty WHERE id=".$results[0]['id'];
            $results = $wpdb->get_results($sql, ARRAY_A);

            if(!is_numeric($results[0]['hnumber'])) {
                $number = 1;
            }
            else {
                $number = ++$results[0]['hnumber'];
            }

            $number2 = $number;
            if($number < 10) $number2 = '0'.$number;

            $data = array(
                'typ'          => $_POST['typ'],
                'id_client'    => $_POST['klient'],
                'id_order'     => $_POST['zlecenie'],
                'id_user'      => $current_user->ID,
                'hnumber'      => $number,
                'number'       => $number2.'/'.date('m').'/'.date('Y'),
                'duty'         => tofloat($_POST['typ'] == 'Transportowa' ? $_POST['clotr'] : $_POST['clo']) * 10000,
                'tax'          => tofloat($_POST['typ'] == 'Transportowa' ? $_POST['vattr'] : $_POST['vat']) * 10000,
                'value'        => tofloat($_POST['typ'] == 'Transportowa' ? $_POST['valuetr'] : $_POST['value']) * 10000,
                'eur'          => tofloat($_POST['eur']) * 10000,
                'usd'          => tofloat($_POST['usd']) * 10000,
                'content'      => $_POST['content'],
                'lang'         => $_POST['lang'],
                'currency'     => ($_POST['typ'] == 'Transportowa' ? $_POST['currencytr'] : $_POST['currency']),
                'note'         => $_POST['note'],
                'sad'          => $_POST['sad'],
                'sad_date'     => $_POST['sad_date'],
                'date_add'     => date('Y-m-d H:i:s'),
                'date_mod'     => date('Y-m-d H:i:s'),
                'date_paid'    => ($_POST['typ'] == 'Transportowa' ? $_POST['date_paidtr'] : $_POST['date_paid']),
                'date_paid2'   => ($_POST['typ'] == 'Transportowa' ? $_POST['date_paid2tr'] : $_POST['date_paid2']),
                'date_payment' => ($_POST['typ'] == 'Transportowa' ? $_POST['date_paymenttr'] : $_POST['date_payment'])
            );
            $wpdb->show_errors();
            if(!$wpdb->insert('wp_czm_noty', $data)) {
                $_REQUEST['action'] = 'add';
                $message            = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Błąd dodawania noty: '.$wpdb->last_error.'", {autoclose: false, position: "top-center", type: "st-error"});
                });
            </script>';
            }
            else {
                $message = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Dodano notę: '.$wpdb->insert_id.'", {autoclose: false, position: "top-center", type: "st-success"});
                });
            </script>';
            }
            //            $wpdb->show_errors();
            //            echo '<pre>'.print_r($_POST, true).'</pre>';
        }
        break;
    case 'update':
        //                        die( '<pre>'.print_r($_POST, true).'</pre>');
        if(is_numeric($_REQUEST['id'])) {
            $data = array(
                'typ'       => $_POST['typ'],
                'id_client' => $_POST['klient'],
                'id_order'  => $_POST['zlecenie'],
                'duty'      => tofloat($_POST['typ'] == 'Transportowa' ? $_POST['clotr'] : $_POST['clo']) * 10000,
                'tax'       => tofloat($_POST['typ'] == 'Transportowa' ? $_POST['vattr'] : $_POST['vat']) * 10000,
                'value'     => tofloat($_POST['typ'] == 'Transportowa' ? $_POST['valuetr'] : $_POST['value']) * 10000,
                'eur'       => tofloat($_POST['eur']) * 10000,
                'usd'       => tofloat($_POST['usd']) * 10000,
                'content'   => $_POST['content'],
                'lang'      => $_POST['lang'],
                'currency'  => ($_POST['typ'] == 'Transportowa' ? $_POST['currencytr'] : $_POST['currency']),
                'note'      => $_POST['note'],
                'sad'       => $_POST['sad'],
                'sad_date'  => $_POST['sad_date'],
                'date_mod'  => date('Y-m-d H:i:s'),
                'date_paid'    => ($_POST['typ'] == 'Transportowa' ? $_POST['date_paidtr'] : $_POST['date_paid']),
                'date_paid2'   => ($_POST['typ'] == 'Transportowa' ? $_POST['date_paid2tr'] : $_POST['date_paid2']),
                'date_payment' => ($_POST['typ'] == 'Transportowa' ? $_POST['date_paymenttr'] : $_POST['date_payment'])
            );
            //        die();
            //        $wpdb->show_errors();
            if(!$wpdb->update('wp_czm_noty', $data, array('id' => $_REQUEST['id']))) {
                $_REQUEST['action'] = 'edit';
                $message            = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Błąd zapisywania: '.$wpdb->last_error.'", {autoclose: false, position: "top-center", type: "st-error"});
                });
            </script>';
            }
            else {
                $message = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Zmiany zapisano.", {autoclose: false, position: "top-center", type: "st-success"});
                });
            </script>';
            }
        }
        //        $wpdb->show_errors();
        break;
    default:
        if(isset($_REQUEST['xhr']) && $_REQUEST['xhr'] == 1) {
//            if(is_app_admin($current_user)) {
                $sql     = 'SELECT id, number, id_client, id_order, id_user, typ, `value`, date_add, date_payment, date_paid, date_paid2, currency FROM wp_czm_noty ORDER BY id DESC';
                $results = $wpdb->get_results($sql, ARRAY_N);
//            }
//            else {
//                $results = $wpdb->get_results('SELECT id, number, id_client, id_order, id_user, typ, date_add, date_payment, date_paid, date_paid2 FROM wp_czm_noty WHERE id_user='.$current_user->ID.' ORDER BY id DESC', ARRAY_N);
//            }

            $return = array();
            foreach($results as $key => $result) {
                $return[$result[0]]['client']     = $wpdb->get_results('SELECT id, short_name FROM wp_czm_clients WHERE id='.$result[2], ARRAY_N);
                $return[$result[0]]['order']      = $wpdb->get_results('SELECT id, number, id_user FROM wp_czm_orders WHERE id='.$result[3], ARRAY_N);
                $return[$result[0]]['user']       = $wpdb->get_results('SELECT id, display_name FROM wp_users WHERE id='.$result[4], ARRAY_N);
                $return[$result[0]]['order_user'] = $wpdb->get_results('SELECT id, display_name FROM wp_users WHERE id='.$return[$result[0]]['order'][0][2], ARRAY_N);

                $results[$key][2] = $return[$result[0]]['client'][0][1];
                $results[$key][3] = $return[$result[0]]['order'][0][1].inicialy($return[$result[0]]['order_user'][0][1]);
                $results[$key][4] = $return[$result[0]]['user'][0][1];

                $results[$key][6] = number_format($results[$key][6]/10000, 2, ',', ' ').' '.strtoupper($results[$key][11]);

                $buttons = '<a href="'.get_bloginfo('url').'/noty?action=edit&id='.$results[$key]['0'].'" class="sepV_a" title="Edytuj"><i class="splashy-contact_blue_edit"></i></a>
                    <!--<a href="#" class="sepV_a" title="Zablokuj"><i class="splashy-contact_blue_remove"></i></a>
                    <a href="http://'.get_bloginfo('url').'/sgfghfg" class="sepV_a ajax-link" title="Odblokuj"><i class="splashy-contact_grey_remove"></i></a>-->';
                if(is_app_admin($current_user)) {
                    $buttons .= '<a href="'.get_bloginfo('url').'/noty/?action=delete&id='.$results[$key]['0'].'" class="ajax-link sepV_a" title="Usuń"><i class="splashy-remove"></i></a>';
                }
                $buttons .= '<a href="'.get_bloginfo('url').'/noty/?action=print&id='.$results[$key]['0'].'" class="sepV_a" title="Podgląd/Drukuj"><i class="splashy-document_letter"></i></a>';

                $results[$key][11] = $buttons;
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
            case 'edit':
                get_template_part('noty', 'edit');
                break;
            case 'add':
                $results_clients = $wpdb->get_results('SELECT DISTINCT short_name,id FROM wp_czm_clients', OBJECT);
                get_template_part('noty', 'add');

                break;
            default:
                ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <h3 class="heading">Noty</h3>

                            <table class="table table-bordered table-striped table_vam" id="dt_noty"
                                   style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Numer</th>
                                    <th>Klient</th>
                                    <th>Zlecenie</th>
                                    <th>Użytkownik</th>
                                    <th>Typ</th>
                                    <th>Wartość</th>
                                    <th>Data dodania</th>
                                    <th>Termin płatności</th>
                                    <th>Klinet zapłacił</th>
                                    <th>CŁO zapłacono</th>
                                    <th></th>
                                </tr>
                                </thead>
                            </table>
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