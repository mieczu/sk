<?php
/*
 * Template Name: Faktury grupowe
 *
 */

//if(!is_user_logged_in()) {
//    auth_redirect();
//}

global $wpdb, $current_user, $wp_roles;

$template_url = get_template_directory_uri();

if(!isset($_REQUEST['action'])) $_REQUEST['action'] = '';

switch ($_REQUEST['action']) {
    case 'get':
        //            $sql     = 'SELECT id, numer, typ, id_order, id_subcontractor, id_client, value, date_payment, date_received  FROM wp_czm_invoices WHERE id_order='.$_REQUEST['id_order'].' AND typ=\'Grupowe\' ORDER BY id DESC';
        $sql     = 'SELECT *  FROM wp_czm_invoices WHERE id='.$_REQUEST['id'].' ORDER BY id DESC';
        $results = $wpdb->get_results($sql, OBJECT);
        //        }
        //        else {
        //            $sql     = 'SELECT * FROM wp_czm_invoices WHERE id='.$_REQUEST['id'].' AND id_user='.$current_user->ID.' ORDER BY id DESC';
        //            $results = $wpdb->get_results($sql, OBJECT);
        //        }
        $results = $results[0];
        die(json_encode($results));
        break;

    case 'delete':
        if(isset($_REQUEST['id'])) {
            $del_result = $wpdb->delete('wp_czm_invoices', array('ID' => $_REQUEST['id']));

            if($del_result) {
                if($del_result == 1) {
                    die(json_encode(array(
                        "status" => 'success',
                        "msg"    => "Faktura została usunięta.",
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
                    "msg"    => "Faktura nie została usunięta.",
                    "id"     => $_REQUEST['id']
                )));
            }

        }
        else {
            die(json_encode(array(
                "status" => 'bad_id',
                "msg"    => "Niepoprawne id faktury",
                "id"     => $_REQUEST['id']
            )));
        }
        break;
    case 'post':

        var_dump($_POST);
        die();
        break;
    case 'new':
        $data = array(
            'typ'              => 'Grupowa',
            'id_subcontractor' => $_POST['idsubcontractor'],
            'subcontractor'    => $_POST['subcontractor'],
            'id_user'          => $current_user->ID,
            'id_order'         => $_POST['order'],
            'hnumer'           => null,
            'numer'            => $_POST['fnumer'],
            'value'            => tofloat($_POST['value']) * 10000,
            'vat'              => tofloat($_POST['vat']) * 10000,
            'eur'              => tofloat($_POST['eur']) * 10000,
            'usd'              => tofloat($_POST['usd']) * 10000,
            'paid'             => tofloat($_POST['paid']) * 10000,
            'currency'         => $_POST['currency'],
            'vat_currency'     => $_POST['vat_currency'],
            'note'             => $_POST['message'],
            'date_add'         => date('Y-m-d H:i:s'),
            'date_mod'         => date('Y-m-d H:i:s'),
            'date_payment'     => $_POST['date_payment'],
            'date_paid'        => $_POST['date_paid'],
            'date_received'    => $_POST['date_received']
        );

        if(!$wpdb->insert('wp_czm_invoices', $data)) {
            $wpdb->show_errors(true);
            $_REQUEST['action'] = 'add';
            $message            = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Błąd dodawania faktury: '.$wpdb->last_error.'", {autoclose: false, position: "top-center", type: "st-error"});
                });
            </script>';
        }
        else {
            $_REQUEST['action'] = 'zakup';
            $message            = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Dodano fakturę: '.$wpdb->insert_id.'", {autoclose: false, position: "top-center", type: "st-success"});
                });
            </script>';
        }
        break;
    case 'update':
        //        echo '<pre>'.print_r($_POST, true).'</pre>'
        $data = array(
            'id_subcontractor' => $_POST['idsubcontractor'],
            'subcontractor'    => $_POST['subcontractor'],
            //            'id_subcontractor_to_order' => $_POST['id_subcontractor_to_order'],
            //            'id_order'                  => $_POST['order'],
            'edit_id_user'     => $current_user->ID,
            'numer'            => $_POST['fnumer'],
            'value'            => tofloat($_POST['value']) * 10000,
            'vat'              => tofloat($_POST['vat']) * 10000,
            'eur'              => tofloat($_POST['eur']) * 10000,
            'usd'              => tofloat($_POST['usd']) * 10000,
            'currency'         => $_POST['currency'],
            'vat_currency'     => $_POST['vat_currency'],
            'paid'             => tofloat($_POST['paid']) * 10000,
            'note'             => $_POST['message'],
            'date_mod'         => date('Y-m-d H:i:s'),
            'date_payment'     => $_POST['date_payment'],
            'date_paid'        => $_POST['date_paid'],
            'date_received'    => $_POST['date_received']
        );
        if(!$wpdb->update('wp_czm_invoices', $data, array('ID' => $_POST['id']))) {
            $wpdb->show_errors();
            $_REQUEST['action'] = 'edit';
            $message            = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Błąd zapisywania: '.$wpdb->last_error.'", {autoclose: false, position: "top-center", type: "st-error"});
                });
            </script>';
        }
        else {

            $results = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id_parent='.$_REQUEST['id'], OBJECT);

            foreach($results as $invoice) {
                echo '<br><br><br><br>';
               var_dump($invoice);
                $data = array(
                    'date_payment'  => $_POST['date_payment'],
                    'date_paid'     => $_POST['date_paid'],
                    'date_received' => $_POST['date_received'],
                    'numer'         => $_POST['fnumer']
                );
                if(!$wpdb->update('wp_czm_invoices', $data, array('ID' => $invoice->id))) {
                    $wpdb->show_errors();
                    $message = '<script>
                                    jQuery(document).ready(function() {
                                        $.sticky("Jeden z elementów faktury grupowej nie został zaktualizowany", {autoclose: false, position: "top-center", type: "st-success"});
                                    });
                                </script>';
                    echo '<h1>NIE</h1>';
                    var_dump( $wpdb->last_query );
                }
                else {
                    echo '<h1>TAK</h1>';
                    var_dump($invoice);
                }

            }

            $_REQUEST['action'] = 'zakup';
            $message            = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Zmiany zapisano", {autoclose: false, position: "top-center", type: "st-success"});
                });
            </script>';
        }
        break;
    default:
        if(isset($_REQUEST['xhr']) && $_REQUEST['xhr'] == 1) {
            //            if(is_app_admin($current_user)) {
            $sql     = 'SELECT id, numer, typ, id_order, id_subcontractor, id_client, value, date_payment,  date_paid, subcontractor, id_user, currency, vat_currency, vat, usd, eur  FROM wp_czm_invoices WHERE typ=\'Grupowa\' ORDER BY id DESC';
            $results = $wpdb->get_results($sql, ARRAY_N);
            //            }
            //            else {
            //                $results = $wpdb->get_results('SELECT id, numer, typ, id_order, id_subcontractor, id_client, value, date_payment,  date_paid, subcontractor, id_user, currency, vat_currency, vat, usd, eur FROM wp_czm_invoices WHERE typ=\'Grupowa\' AND id_user='.$current_user->ID.' ORDER BY id DESC', ARRAY_N);
            //            }


            $return = array();
            foreach($results as $key => $result) {
                if(is_numeric($result[3])) $return[$result[0]]['order'] = $wpdb->get_results('SELECT number FROM wp_czm_orders WHERE id='.$result[3], ARRAY_N);
                if(is_numeric($result[4])) $return[$result[0]]['sub'] = $wpdb->get_results('SELECT name FROM wp_czm_subcontractor WHERE id='.$result[4], ARRAY_N);


                $results[$key][3] = isset($return[$result[0]]['order'][0][0]) ? $return[$result[0]]['order'][0][0] : '';
                $results[$key][4] = (empty($return[$result[0]]['sub'][0][0]) ? $result[9] : $return[$result[0]]['sub'][0][0]);
                unset($results[$key][9]);

                $results[$key][5] = number_format($results[$key][6] / 10000, 2, ',', ' ').' '.$results[$key][11];;

                $buttons = '<a href="'.get_bloginfo('url').'/grupowe?action=edit&id='.$results[$key]['0'].'" class="sepV_a" title="Edytuj"><i class="splashy-contact_blue_edit"></i></a>';
                if(is_app_admin($current_user)) {
                    $buttons .= '<a href="'.get_bloginfo('url').'/grupowe/?action=delete&id='.$results[$key]['0'].'" class="ajax-link sepV_a" title="Usuń"><i class="splashy-remove"></i></a>';
                }

                $vat = tofloat($results[$key][13] / 10000);

                $results[$key][6] = number_format($vat, 2, ',', ' ').' '.$results[$key][12];

                $exchange = 0;
                switch ($results[$key][11]) {//waluta faktury
                    case 'pln':
                        switch ($results[$key][12]) {//waluta vat-u
                            case 'pln':
                                $exchange = 1;
                                break;
                            case 'eur':
                                $exchange = tofloat($results[$key][15] / 10000);//euro
                                break;
                            case 'usd':
                                $exchange = tofloat($results[$key][14] / 10000);//usd
                                break;
                        }
                        break;
                    case 'eur':
                        switch ($results[$key][12]) {//waluta vat-u
                            case 'pln':
                                $exchange = tofloat(1 / ($results[$key][15] / 10000));
                                break;
                            case 'eur':
                                $exchange = 1;
                                break;
                            case 'usd':
                                $exchange = tofloat(($results[$key][14] / 10000) / ($results[$key][15] / 10000));
                                break;
                        }
                        break;
                    case 'usd':
                        switch ($results[$key][12]) {//waluta vat-u
                            case 'pln':
                                $exchange = tofloat(1 / ($results[$key][14] / 10000));
                                break;
                            case 'eur':
                                $exchange = tofloat(($results[$key][15] / 10000) / ($results[$key][14] / 10000));
                                break;
                            case 'usd':
                                $exchange = 1;
                                break;
                        }
                        break;
                }

                $results[$key][9] = $results[$key][8];
                $results[$key][8] = $results[$key][7];

                $results[$key][7]  = number_format(($vat * tofloat($exchange)) + tofloat($results[$key][5]), 2, ',', ' ').' '.$results[$key][11];
                $results[$key][10] = $buttons;
                //unset($results[$key][10]);
                unset($results[$key][11]);
                unset($results[$key][12]);
                unset($results[$key][13]);
            }
            //            var_dump( $results);
            die(json_encode(array('data' => $results)));
        }

}


get_header();
echo $message;

?>
<div id="contentwrapper">
    <div class="main_content">
        <style>
            #dt_pozycje2_filter {
                display: none;
            }
        </style>
        <?php

        switch ($_REQUEST['action']) {
            case 'edit':
                get_template_part('grupowe', 'edit');
                break;
            case 'add':
                get_template_part('grupowe', 'add');
                break;
            default:
                ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <h3 class="heading">Faktury grupowe</h3>

                            <table class="table table-bordered table-striped table_vam" id="dt_grupowe"
                                   style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Numer</th>
                                    <th>Typ</th>
                                    <th>Numer zlecenia</th>
                                    <th>Podwykonawca</th>
                                    <th>Wartość</th>
                                    <th>Vat</th>
                                    <th>Brutto</th>
                                    <th>Termin płatności</th>
                                    <th>Zapłacono</th>
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