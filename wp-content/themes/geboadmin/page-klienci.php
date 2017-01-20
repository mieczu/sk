<?php
/*
 * Template Name: Klienci
 *
 */

if (!is_user_logged_in()) {
    auth_redirect();
}

global $wpdb, $current_user;

$_GET     = array_map('stripslashes_deep', $_GET);
$_POST    = array_map('stripslashes_deep', $_POST);
$_REQUEST = array_map('stripslashes_deep', $_REQUEST);


switch ($_REQUEST['action']) {
    case 'post':
        echo '<pre>';
        var_dump($_POST);
        echo '</pre>';
        break;
    case 'get':
        if (isset($_REQUEST['id'])) {
            $results = $wpdb->get_results('SELECT id_platnik FROM wp_czm_orders WHERE id='.$_REQUEST['id'], OBJECT);
            if ($results[0]->id_platnik > 0) {
                $results_c = $wpdb->get_results('SELECT id, short_name FROM wp_czm_clients WHERE id='.$results[0]->id_platnik, ARRAY_A);

                die(json_encode($results_c[0]));
            }
        }
        die(json_encode(array(
            "id"         => "0",
            "short_name" => ""
        )));
        break;
    case 'get_limit':
        if (isset($_REQUEST['id'])) {
            $sql_invoices = 'SELECT *,DATEDIFF(now(),date_paid) as \'is_paid\'
                    FROM c1skymar.wp_czm_invoices
                    WHERE DATEDIFF(now(),date_paid) is null
                    AND id_client='.$_REQUEST['id'];

            $results_invoices = $wpdb->get_results($sql_invoices, OBJECT);

            $invoice_sum = array();
            $nota_sum    = array();
            $return      = array();

            foreach($results_invoices as $invoice) {
                $return['invoice'] = $invoice;
                switch ($invoice->currency) {
                    case 'pln':
                        $invoice_sum[$invoice->numer]['brutto'] = $invoice->value / 10000;
                        break;
                    case 'eur':
                        $invoice_sum[$invoice->numer]['brutto'] = ($invoice->value / 10000) * $invoice->eur / 10000;
                        break;
                    case 'usd':
                        $invoice_sum[$invoice->numer]['brutto'] = ($invoice->value / 10000) * $invoice->usd / 10000;
                        break;
                }
                if ($invoice->vat > 0) {
                    switch ($invoice->vat_currency) {
                        case 'pln':
                            $invoice_sum[$invoice->numer]['vat'] = $invoice->vat / 10000;
                            break;
                        case 'eur':
                            $invoice_sum[$invoice->numer]['vat'] = ($invoice->vat / 10000) * $invoice->eur / 10000;
                            break;
                        case 'usd':
                            $invoice_sum[$invoice->numer]['vat'] = ($invoice->vat / 10000) * $invoice->usd / 10000;
                            break;
                    }
                } else{
                    $invoice_sum[$invoice->numer]['vat'] = 0;

                }
            }

            //number_format($sub->value / 10000, 2, ',', ' ');

            $sql_notes = 'SELECT *,DATEDIFF(now(),date_paid) as \'is_paid\'
                    FROM c1skymar.wp_czm_noty
                    WHERE DATEDIFF(now(),date_paid) is null
                    AND id_client='.$_REQUEST['id'];

            $results_notes = $wpdb->get_results($sql_notes, OBJECT);

            foreach($results_notes as $nota) {
                $return['nota'] = $nota;
                switch ($nota->currency) {
                    case 'pln':
                        $nota_sum['brutto'][$nota->number] = $nota->value / 10000;
                        break;
                    case 'eur':
                        $nota_sum['brutto'][$nota->number] = ($nota->value / 10000) * $nota->usd / 10000;
                        break;
                    case 'usd':
                        $nota_sum['brutto'][$nota->number] = ($nota->value / 10000) * $nota->usd / 10000;
                        break;
                }
            }

            //            echo $invoice_brutto = array_sum($invoice_sum);

            foreach($invoice_sum as $inv_nr => $val) {
                $suma                    = array_sum($val);
                $invoice_brutto[$inv_nr] = $suma;//number_format($suma, 2, ',', '');
            }

            $return['invoices'] = $invoice_brutto = array_sum($invoice_brutto);


            $return['notas'] = $nota_brutto = array_sum($nota_sum);
            $return['suma']  = number_format($return['invoices'] + $return['notas'], 2, ',', '');

            $sql_client      = 'SELECT limity FROM wp_czm_clients WHERE id='.$_REQUEST['id'];
            $results_client  = $wpdb->get_results($sql_client, OBJECT);
            $return['limit'] = number_format($results_client[0]->limity, 2, ',', '');


            die(json_encode($return));
        }
        break;
    case 'delete':
        if (isset($_REQUEST['id'])) {
            $del_result = $wpdb->delete('wp_czm_clients', array('id' => $_REQUEST['id']));

            if ($del_result) {
                if ($del_result == 1) {
                    die(json_encode(array(
                        "status" => 'success',
                        "msg"    => "Klient został usunięty.",
                        "id"     => $_REQUEST['id']
                    )));
                } else{
                    die(json_encode(array(
                        "status" => 'error',
                        "msg"    => "Usunięto więcej niż jedną pozycję. Liczba usuniętych wierszy: ".$del_result,
                        "id"     => $_REQUEST['id']
                    )));
                }
            } else{
                die(json_encode(array(
                    "status" => 'error',
                    "msg"    => "Klient nie został usunięty..",
                    "id"     => $_REQUEST['id']
                )));
            }

        } else{
            die(json_encode(array(
                "status" => 'bad_id',
                "msg"    => "Niepoprawne id klienta.",
                "id"     => $_REQUEST['id']
            )));
        }
        break;
    case 'autocomplete':
        $results = $wpdb->get_results('SELECT id, short_name FROM wp_czm_clients WHERE name like \'%'.$_REQUEST['term'].'%\' OR short_name like \'%'.$_REQUEST['term'].'%\'', ARRAY_A);
        die(json_encode($results));
        break;
    case 'by_name':
        $results = $wpdb->get_results('SELECT id FROM wp_czm_clients WHERE name like \''.strtoupper($_REQUEST['term']).'\' OR short_name like \''.$_REQUEST['term'].'\'', ARRAY_A);
        die(json_encode($results[0]));
        break;
    case 'list_names':
        $results = $wpdb->get_results('SELECT short_name FROM wp_czm_clients', OBJECT_K);
        $results = array_keys($results);

        die(json_encode($results));
        break;
    case 'new':

        //            die(json_encode($results));
        // die ('<pre>'.print_r($_REQUEST, true).'</pre>');
        //        $wpdb->show_errors();
        $userdata = array(
            'name'       => htmlentities(strtoupper($_POST['fname'])),
            'short_name' => htmlentities(strtoupper($_POST['fshort_name'])),
            'address'    => $_POST['adres'],
            'post_code'  => $_POST['post_code'],
            'kraj'       => $_POST['kraj'],
            'city'       => $_POST['city'],
            'nip'        => (!isset($_POST['fnip']) || empty($_POST['fnip']) ? null : $_POST['fnip']),
            'email'      => $_POST['email'],
            'note'       => $_POST['message'],
            'ok'         => $_POST['ok'],
            'klient'     => 1,
            'date_add'   => date('Y-m-d H:i:s'),
            'date_mod'   => date('Y-m-d H:i:s')
        );

        if ($userdata['kraj'] == 'PL') {
            $userdata['limity'] = get_option("limitPl");
        } else{
            $userdata['limity'] = get_option("limitAll");
        }

        if (!$wpdb->insert('wp_czm_clients', $userdata)) {
            $_REQUEST['action'] = 'add';
            $message            = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Błąd dodawania: '.$wpdb->last_error.'", {autoclose: false, position: "top-center", type: "st-error"});
                });
            </script>';
        } else{
            $message = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Dodano klienta: '.$wpdb->insert_id.'", {autoclose: false, position: "top-center", type: "st-success"});
                });
            </script>';
        }
        //        $wpdb->show_errors();
        break;
    case 'update':
        // die ('<pre>'.print_r($_REQUEST, true).'</pre>');

        $userdata = array(
            'name'       => htmlentities(strtoupper($_POST['fname'])),
            'short_name' => htmlentities(strtoupper($_POST['fshort_name'])),
            'address'    => $_POST['adres'],
            'post_code'  => $_POST['post_code'],
            'kraj'       => $_POST['kraj'],
            'city'       => $_POST['city'],
            'nip'        => (!isset($_POST['fnip']) || empty($_POST['fnip']) ? null : $_POST['fnip']),
            'email'      => $_POST['email'],
            'note'       => $_POST['message'],
            'ok'         => $_POST['ok'],
            'disabled'   => $_POST['zablokowany'],
            'date_mod'   => date('Y-m-d H:i:s')
        );

        if (is_numeric($_POST['limity'])) {
            $userdata['limity'] = $_POST['limity'];
        }

        if (!$wpdb->update('wp_czm_clients', $userdata, array('ID' => $_POST['id']))) {
            $_REQUEST['action'] = 'add';
            $message            = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Błąd zapisywania: '.$wpdb->last_error.'", {autoclose: false, position: "top-center", type: "st-error"});
                });
            </script>';
        } else{
            $message = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Zmiany zapisano: '.$_POST['id'].'", {autoclose: false, position: "top-center", type: "st-success"});
                });
            </script>';
        }
        break;
    default:
        if (isset($_REQUEST['xhr']) && $_REQUEST['xhr'] == 1) {
            $results = $wpdb->get_results('SELECT id, name, short_name, email, nip, date_add FROM wp_czm_clients', ARRAY_N);
            //echo '<pre>'.print_r($results,true).'</pre>';

            foreach($results as $key => $row) {
                //    echo '<pre>'.print_r($row,true).'</pre>';
                //    $response[$key][]='<button>aaaa</button>';
                $buttons = '<a href="'.get_bloginfo('url').'/klienci?action=edit&id='.$results[$key]['0'].'" class="sepV_a" title="Edytuj"><i class="splashy-contact_blue_edit"></i></a>
                   <!-- <a href="#" class="sepV_a" title="Zablokuj"><i class="splashy-contact_blue_remove"></i></a>
                    <a href="http://'.get_bloginfo('url').'/sgfghfg" class="sepV_a ajax-link" title="Odblokuj"><i class="splashy-contact_grey_remove"></i></a>-->';
                if (is_app_admin($current_user)) {
                    $buttons .= '<a href="'.get_bloginfo('url').'/klienci?action=delete&id='.$results[$key]['0'].'"class="ajax-link" title="Usuń"><i class="splashy-remove"></i></a>';
                }
                $results[$key][] = $buttons;

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
        //        echo '<br><br><br><br><pre>'.print_r($_POST, true).'</pre>';
        switch ($_REQUEST['action']) {
            case 'limit':
                get_template_part('klienci', 'limit');
                break;
            case 'edit':
                get_template_part('klienci', 'edit');
                break;
            case 'add':
                get_template_part('klienci', 'add');

                break;
            case 'delete':
                ob_flush();
                echo 'deleteeeeeeeeeee';
                break;
            default:
                ?>
                <table id="dt_klienci" class="table table-bordered table-striped table_vam" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pełna nazwa</th>
                        <th>Nazwa</th>
                        <th>Email</th>
                        <th>NIP</th>
                        <th>Data dodania</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
                <?php
        }


        //echo '<pre>'.print_r(json_encode(array('aaData'=>$results)),true).'</pre>';
        //        echo '<pre>'.print_r(json_decode($test),true).'</pre>';
        //        print_r(json_decode($test));

        ?>


    </div>
    <div class="sticky-queue top-center" style="">
        <div id="loading_animation" class="sticky border-top-right " style="height: 18px; display: none;">
            <div rel="loading_animation" class="sticky-note" style="text-align: center">
                <img alt="" src="<?php echo get_bloginfo('url'); ?>/wp-content/themes/geboadmin/img/ajax_loader.gif"/>
            </div>
        </div>
    </div>
    <div id="testdialog">

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