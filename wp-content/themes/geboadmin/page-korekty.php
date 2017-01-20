<?php
/*
 * Template Name: Korekty
 *
 */

//if(!is_user_logged_in()) {
//    auth_redirect();
//}

global $wpdb, $current_user, $wp_roles;

$template_url = get_template_directory_uri();

if(!isset($_REQUEST['action'])) $_REQUEST['action'] = '';

switch ($_REQUEST['action']) {
    case 'pdf':
        if(isset($_REQUEST['id'])) {
            get_template_part('korekty', 'pdf');
        }
        die('');
        break;
    case 'print':
        if(isset($_REQUEST['id'])) {
            get_template_part('korekty', 'print');
        }
        die('');
        break;
    case 'get':
//        if(is_app_admin($current_user)) {
            $sql     = 'SELECT id, numer, typ, id_order, id_subcontractor, id_client, value, date_payment, date_received  FROM wp_czm_korekty WHERE id_order='.$_REQUEST['id_order'].' ORDER BY id DESC';
            $results = $wpdb->get_results($sql, ARRAY_N);
//        }
//        else {
//            $sql     = 'SELECT id, numer, typ, id_order, id_subcontractor, id_client, value, date_payment, date_received FROM wp_czm_korekty WHERE id_order='.$_REQUEST['id_order'].' AND id_user='.$current_user->ID.' ORDER BY id DESC';
//            $results = $wpdb->get_results($sql, ARRAY_N);
//        }

        $return = array();
        foreach($results as $key => $result) {
            $return[$result[0]]['order']  = $wpdb->get_results('SELECT number FROM wp_czm_orders WHERE id='.$result[3], ARRAY_N);
            $return[$result[0]]['sub']    = $wpdb->get_results('SELECT name FROM wp_czm_subcontractor WHERE id='.$result[4], ARRAY_N);
            $return[$result[0]]['client'] = $wpdb->get_results('SELECT name FROM wp_czm_clients WHERE id='.$result[5], ARRAY_N);
            //                $return[$result[0]]['user']     = $wpdb->get_results('SELECT id, display_name FROM wp_users WHERE id='.$result[5], ARRAY_N);
            //var_dump('SELECT name FROM wp_czm_subcontractors WHERE id='.$result[4], ARRAY_N);
            $results[$key][3] = $return[$result[0]]['order'][0][0];
            $results[$key][4] = $return[$result[0]]['sub'][0][0];
            $results[$key][5] = $return[$result[0]]['client'][0][0];
            $results[$key][6] = $results[$key][6] / 10000;
            //                $results[$key][5] = $return[$result[0]]['user'][0][1];
            $buttons = '<a href="'.get_bloginfo('url').'/korekty?action=edit'.($results[$key][2] == 'Zakup' ? 'zk' : '').'&id='.$results[$key]['0'].'" class="sepV_a" title="Edytuj"><i class="splashy-contact_blue_edit"></i></a>';
            if(is_app_admin($current_user)) {
                $buttons .= '<a href="'.get_bloginfo('url').'/korekty/?action=delete&id='.$results[$key]['0'].'" class="ajax-link" title="Usuń"><i class="splashy-remove"></i></a>';
            }
            $results[$key][] = $buttons;
        }
        //            var_dump( $results);
        die(json_encode(array('data' => $results)));
        break;
    case 'delete':
        if(isset($_REQUEST['id'])) {
            $del_result = $wpdb->delete('wp_czm_korekty', array('ID' => $_REQUEST['id']));

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
                "msg"    => "Niepoprawne id korekty",
                "id"     => $_REQUEST['id']
            )));
        }
        break;
    case 'post':
echo '<pre>';
        var_dump($_POST);
        echo '</pre>';
        die();
        break;
    case 'new':
        $sql1 = "SELECT MAX(id) as 'id' FROM wp_czm_korekty WHERE hnumer is not null AND date_add BETWEEN '".date('Y-m')."-01 00:00:00' AND '".date('Y-m-t')." 23:59:59'";
        $results1 = $wpdb->get_results($sql1, ARRAY_A);
        $sql2 = "SELECT hnumer FROM wp_czm_korekty WHERE id=".$results1[0]['id'];
        $results2 = $wpdb->get_results($sql2, ARRAY_A);

        if(!is_numeric($results2[0][hnumer])) {
            $number = 1;
        }
        else {
            $number = ++$results2[0][hnumer];
        }

        $number2 = $number;
        if($number < 10) $number2 = '0'.$number;

        $data = array(
            'name'          => $_POST['fname'],
            'address'       => $_POST['adres'],
            'post_code'     => $_POST['post_code'],
            'city'          => $_POST['city'],
            'nip'           => $_POST['fnip'],
            'id_client'     => $_POST['id_client'],
            'id_invoice'    => $_POST['id_invoice'],
            'id_user'       => $current_user->ID,
            'hnumer'        => $number,
            'numer'         => $number2.'/'.date('m').'/'.date('Y'),
            'currency'      => $_POST['currency'],
            'vat_currency'  => $_POST['vat_currency'],
            'eur'           => tofloat($_POST['eur']) * 10000,
            'usd'           => tofloat($_POST['usd']) * 10000,
            'reason'          => $_POST['reason'],
            'note'          => $_POST['message'],
            'date_add'      => date('Y-m-d H:i:s'),
            'date_mod'      => date('Y-m-d H:i:s'),
            'date_payment'  => $_POST['date_payment'],
            'date_paid'     => $_POST['date_paid']
//            'date_received' => $_POST['date_received']
        );

        if(!$wpdb->insert('wp_czm_korekty', $data)) {
            $_REQUEST['action'] = 'add';
            $message            = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Błąd dodawania faktury korygującej: '.$wpdb->last_error.'", {autoclose: false, position: "top-center", type: "st-error"});
                });
            </script>';
        }
        else {
            $message = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Dodano fakturę korygującą: '.$wpdb->insert_id.'", {autoclose: false, position: "top-center", type: "st-success"});
                });
            </script>';

            $korekta_id = $wpdb->insert_id;
            $total_f    = array(
                'total' => 0,
                'vat'   => 0,
            );
            foreach($_POST['row'] as $row) {

                $sub = array(
                    'name'       => $row['subc'],
                    'quantity'   => $row['quantity'],
                    'value'      => tofloat($row['value']) * 10000,
                    'vat'        => (!is_numeric($row['vat']) ? 'NP' : tofloat($row['vat']) * 10000),
                    'currency'   => $row['wal'],
                    'id_invoice' => $korekta_id
                );

                if(!$wpdb->insert('wp_czm_korekty_items_to_invoices', $sub)) {
                    $message .= '
                        <script>
                            jQuery(document).ready(function() {
                                $.sticky("Błąd produktu do korekty: '.$wpdb->last_error.'", {autoclose: false, position: "top-center", type: "st-error"});
                            });
                        </script>';
                }
                //                else {
                //                    $total_f['total'] += tofloat($row['value']) * $row['quantity'];
                //                    $total_f['vat'] += tofloat($row['value']) * ((tofloat($row['vat']) / 1000000));
                //                }
                $unitValue = tofloat($row['value']) * $row['quantity'];
                $unitVAT   = tofloat($row['vat'])/100;
                $eur       = (tofloat($_POST['eur']) > 1 ? tofloat($_POST['eur']) : 1);
                $usd       = (tofloat($_POST['usd']) > 1 ? tofloat($_POST['usd']) : 1);

                if($_POST['currency'] == $row['wal']) {
                    $total_f['total'] += $unitValue;
                    if(is_numeric(tofloat($row['vat']))) {
                        switch ($_POST['currency']) {
                            case 'pln':
                                switch ($_POST['vat_currency']) {
                                    case 'pln':
                                        $total_f['vat'] += $unitValue * $unitVAT;
                                        break;
                                    case 'usd':
                                        $total_f['vat'] += ($unitValue * $unitVAT) / $usd;
                                        break;
                                    case 'eur':
                                        $total_f['vat'] += ($unitValue * $unitVAT) / $eur;
                                        break;
                                }
                                break;
                            case 'usd':
                                switch ($_POST['vat_currency']) {
                                    case 'pln':
                                        $total_f['vat'] += $unitValue * $unitVAT * $usd;
                                        break;
                                    case 'usd':
                                        $total_f['vat'] += $unitValue * $unitVAT;
                                        break;
                                    case 'eur':
                                        $total_f['vat'] += ($unitValue * $unitVAT * $usd) / $eur;
                                        break;
                                }
                                break;
                            case 'eur':
                                switch ($_POST['vat_currency']) {
                                    case 'pln':
                                        $total_f['vat'] += $unitValue * $unitVAT * $eur;
                                        break;
                                    case 'usd':
                                        $total_f['vat'] += ($unitValue * $unitVAT * $eur) / $usd;
                                        break;
                                    case 'eur':
                                        $total_f['vat'] += $unitValue * $unitVAT;
                                        break;
                                }
                                break;
                        }
                    }
                }
                elseif($_POST['currency'] == 'pln' && $row['wal'] == 'usd') {
                    $total_f['total'] += $unitValue * $usd;

                    if(is_numeric(tofloat($row['vat']))) {
                        //                        $total_f['vat'] += $unitValue * $unitVAT * $usd;
                        switch ($_POST['vat_currency']) {
                            case 'pln':
                                $total_f['vat'] += $unitValue * $unitVAT * $usd;
                                break;
                            case 'usd':
                                $total_f['vat'] += ($unitValue * $unitVAT);
                                break;
                            case 'eur':
                                $total_f['vat'] += ($unitValue * $unitVAT * $usd) / $eur;
                                break;
                        }
                    }
                }
                elseif($_POST['currency'] == 'pln' && $row['wal'] == 'eur') {
                    $total_f['total'] += $unitValue * $eur;
                    if(is_numeric(tofloat($row['vat']))) {
                        //                        $total_f['vat'] += $unitValue * $unitVAT * $eur;
                        switch ($_POST['vat_currency']) {
                            case 'pln':
                                $total_f['vat'] += $unitValue * $unitVAT * $eur;
                                break;
                            case 'eur':
                                $total_f['vat'] += ($unitValue * $unitVAT);
                                break;
                            case 'usd':
                                $total_f['vat'] += ($unitValue * $unitVAT * $eur) / $usd;
                                break;
                        }
                    }
                }
                elseif($_POST['currency'] == 'usd' && $row['wal'] == 'pln') {
                    $total_f['total'] += $unitValue / $usd;
                    if(is_numeric(tofloat($row['vat']))) {
                        //                        $total_f['vat'] += $unitValue * $unitVAT / $usd;
                        switch ($_POST['vat_currency']) {
                            case 'pln':
                                $total_f['vat'] += $unitValue * $unitVAT;
                                break;
                            case 'eur':
                                $total_f['vat'] += ($unitValue * $unitVAT) / eur;
                                break;
                            case 'usd':
                                $total_f['vat'] += ($unitValue * $unitVAT) / $usd;
                                break;
                        }
                    }
                }
                elseif($_POST['currency'] == 'usd' && $row['wal'] == 'eur') {
                    $total_f['total'] += ($unitValue * $eur) / $usd;
                    if(is_numeric(tofloat($row['vat']))) {
                        //                        $total_f['vat'] += ($unitValue * $unitVAT * $eur) / $usd;
                        switch ($_POST['vat_currency']) {
                            case 'pln':
                                $total_f['vat'] += ($unitValue * $unitVAT) * $eur;
                                break;
                            case 'eur':
                                $total_f['vat'] += ($unitValue * $unitVAT);
                                break;
                            case 'usd':
                                $total_f['vat'] += ($unitValue * $unitVAT * $eur) / $usd;
                                break;
                        }
                    }

                }
                elseif($_POST['currency'] == 'eur' && $row['wal'] == 'pln') {
                    $total_f['total'] += $unitValue / $eur;
                    if(is_numeric(tofloat($row['vat']))) {
                        //                        $total_f['vat'] += $unitValue * $unitVAT / $eur;
                        switch ($_POST['vat_currency']) {
                            case 'pln':
                                $total_f['vat'] += ($unitValue * $unitVAT);
                                break;
                            case 'eur':
                                $total_f['vat'] += ($unitValue * $unitVAT) / $eur;
                                break;
                            case 'usd':
                                $total_f['vat'] += ($unitValue * $unitVAT) / $usd;
                                break;
                        }
                    }
                }
                elseif($_POST['currency'] == 'eur' && $row['wal'] == 'usd') {
                    $total_f['total'] += ($unitValue * $usd) / $eur;
                    if(is_numeric(tofloat($row['vat']))) {
                        //                        $total_f['vat'] += ($unitValue * $unitVAT * $usd) / $eur;
                        switch ($_POST['vat_currency']) {
                            case 'pln':
                                $total_f['vat'] += ($unitValue * $unitVAT) * usd;
                                break;
                            case 'eur':
                                $total_f['vat'] += ($unitValue * $unitVAT * $usd) / $eur;
                                break;
                            case 'usd':
                                $total_f['vat'] += ($unitValue * $unitVAT);
                                break;
                        }
                    }
                }
            }

            $data = array(
                'value'    => $total_f['total'] * 10000,
                'vat'      => $total_f['vat'] * 10000,
                'date_mod' => date('Y-m-d H:i:s')
            );
            //        die();
            //        $wpdb->show_errors();
            if(!$wpdb->update('wp_czm_korekty', $data, array('id' => $korekta_id))) {
                $_REQUEST['action'] = '';
                $_REQUEST['id']     = $korekta_id;
                $message            = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Błąd zapisywania sumy korekty: '.$wpdb->last_error.'", {autoclose: false, position: "top-center", type: "st-error"});
                });
            </script>';
            }
        }
        break;
    case 'update_sp':
        $data = array(
            'id_client'     => $_POST['client'],
            'id_order'      => $_POST['order'],
            'edit_id_user'  => $current_user->ID,
            'currency'      => $_POST['currency'],
            'vat_currency'  => $_POST['vat_currency'],
            'eur'           => tofloat($_POST['eur']) * 10000,
            'usd'           => tofloat($_POST['usd']) * 10000,
            'paid'          => tofloat($_POST['paid']) * 10000,
            'note'          => $_POST['message'],
            'lang'          => $_POST['lang'],
            'date_mod'      => date('Y-m-d H:i:s'),
            'date_payment'  => $_POST['date_payment'],
            'date_received' => $_POST['date_received'],
            'date_paid'     => $_POST['date_paid']
        );
        //        die('<pre>'.print_r($_POST, true).'</pre>');
        $wpdb->show_errors();
        if(!$wpdb->update('wp_czm_korekty', $data, array('id' => $_REQUEST['id']))) {
            //            $_REQUEST['action'] = 'edit';
            //            $_REQUEST['id']=$_REQUEST['id'];
            $wpdb->show_errors();
            $message = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Błąd zapisywania korekty: '.$wpdb->last_error.'", {autoclose: false, position: "top-center", type: "st-error"});
                });
            </script>';
        }
        else {
            $message = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Zmiany zapisano", {autoclose: false, position: "top-center", type: "st-success"});
                });
            </script>';


            $invoice_id = $_REQUEST['id'];
            $total_f    = array(
                'total' => 0,
                'vat'   => 0,
            );
            foreach($_POST['row'] as $row) {
                $sub = array(
                    'name'       => $row['subc'],
                    'quantity'   => $row['quantity'],
                    'value'      => tofloat($row['value']) * 10000,
                    'vat'        => (!is_numeric($row['vat']) ? 'NP' : tofloat($row['vat']) * 10000),
                    'currency'   => $row['wal'],
                    'id_invoice' => $invoice_id
                );

                if(!$wpdb->update('wp_czm_korekty_items_to_invoices', $sub, array('id' => $row['id_sub'],))) {
                    $wpdb->show_errors(true);
                    //                    $message .= '
                    //                        <script>
                    //                            jQuery(document).ready(function() {
                    //                                $.sticky("Nie można zapisać towarów '.$wpdb->last_error.' '.$wpdb->last_query.'", {autoclose: false, position: "top-center", type: "st-error"});
                    //                            });
                    //                        </script>';
                }

                $unitValue = tofloat($row['value']) * $row['quantity'];
                $unitVAT   = tofloat($row['vat']) / 1000000;
                $eur       = (tofloat($_POST['eur']) > 1 ? tofloat($_POST['eur']) : 1);
                $usd       = (tofloat($_POST['usd']) > 1 ? tofloat($_POST['usd']) : 1);

                if($_POST['currency'] == $row['wal']) {
                    $total_f['total'] += $unitValue;
                    if(is_numeric(tofloat($row['vat']))) {
                        switch ($_POST['currency']) {
                            case 'pln':
                                switch ($_POST['vat_currency']) {
                                    case 'pln':
                                        $total_f['vat'] += $unitValue * $unitVAT;
                                        break;
                                    case 'usd':
                                        $total_f['vat'] += ($unitValue * $unitVAT) / $usd;
                                        break;
                                    case 'eur':
                                        $total_f['vat'] += ($unitValue * $unitVAT) / $eur;
                                        break;
                                }
                                break;
                            case 'usd':
                                switch ($_POST['vat_currency']) {
                                    case 'pln':
                                        $total_f['vat'] += $unitValue * $unitVAT * $usd;
                                        break;
                                    case 'usd':
                                        $total_f['vat'] += $unitValue * $unitVAT;
                                        break;
                                    case 'eur':
                                        $total_f['vat'] += ($unitValue * $unitVAT * $usd) / $eur;
                                        break;
                                }
                                break;
                            case 'eur':
                                switch ($_POST['vat_currency']) {
                                    case 'pln':
                                        $total_f['vat'] += $unitValue * $unitVAT * $eur;
                                        break;
                                    case 'usd':
                                        $total_f['vat'] += ($unitValue * $unitVAT * $eur) / $usd;
                                        break;
                                    case 'eur':
                                        $total_f['vat'] += $unitValue * $unitVAT;
                                        break;
                                }
                                break;
                        }
                    }
                }
                elseif($_POST['currency'] == 'pln' && $row['wal'] == 'usd') {
                    $total_f['total'] += $unitValue * $usd;

                    if(is_numeric(tofloat($row['vat']))) {
                        //                        $total_f['vat'] += $unitValue * $unitVAT * $usd;
                        switch ($_POST['vat_currency']) {
                            case 'pln':
                                $total_f['vat'] += $unitValue * $unitVAT * $usd;
                                break;
                            case 'usd':
                                $total_f['vat'] += ($unitValue * $unitVAT);
                                break;
                            case 'eur':
                                $total_f['vat'] += ($unitValue * $unitVAT * $usd) / $eur;
                                break;
                        }
                    }
                }
                elseif($_POST['currency'] == 'pln' && $row['wal'] == 'eur') {
                    $total_f['total'] += $unitValue * $eur;
                    if(is_numeric(tofloat($row['vat']))) {
                        //                        $total_f['vat'] += $unitValue * $unitVAT * $eur;
                        switch ($_POST['vat_currency']) {
                            case 'pln':
                                $total_f['vat'] += $unitValue * $unitVAT * $eur;
                                break;
                            case 'eur':
                                $total_f['vat'] += ($unitValue * $unitVAT);
                                break;
                            case 'usd':
                                $total_f['vat'] += ($unitValue * $unitVAT * $eur) / $usd;
                                break;
                        }
                    }
                }
                elseif($_POST['currency'] == 'usd' && $row['wal'] == 'pln') {
                    $total_f['total'] += $unitValue / $usd;
                    if(is_numeric(tofloat($row['vat']))) {
                        //                        $total_f['vat'] += $unitValue * $unitVAT / $usd;
                        switch ($_POST['vat_currency']) {
                            case 'pln':
                                $total_f['vat'] += $unitValue * $unitVAT;
                                break;
                            case 'eur':
                                $total_f['vat'] += ($unitValue * $unitVAT) / eur;
                                break;
                            case 'usd':
                                $total_f['vat'] += ($unitValue * $unitVAT) / $usd;
                                break;
                        }
                    }
                }
                elseif($_POST['currency'] == 'usd' && $row['wal'] == 'eur') {
                    $total_f['total'] += ($unitValue * $eur) / $usd;
                    if(is_numeric(tofloat($row['vat']))) {
                        //                        $total_f['vat'] += ($unitValue * $unitVAT * $eur) / $usd;
                        switch ($_POST['vat_currency']) {
                            case 'pln':
                                $total_f['vat'] += ($unitValue * $unitVAT) * $eur;
                                break;
                            case 'eur':
                                $total_f['vat'] += ($unitValue * $unitVAT);
                                break;
                            case 'usd':
                                $total_f['vat'] += ($unitValue * $unitVAT * $eur) / $usd;
                                break;
                        }
                    }

                }
                elseif($_POST['currency'] == 'eur' && $row['wal'] == 'pln') {
                    $total_f['total'] += $unitValue / $eur;
                    if(is_numeric(tofloat($row['vat']))) {
                        //                        $total_f['vat'] += $unitValue * $unitVAT / $eur;
                        switch ($_POST['vat_currency']) {
                            case 'pln':
                                $total_f['vat'] += ($unitValue * $unitVAT);
                                break;
                            case 'eur':
                                $total_f['vat'] += ($unitValue * $unitVAT) / $eur;
                                break;
                            case 'usd':
                                $total_f['vat'] += ($unitValue * $unitVAT) / $usd;
                                break;
                        }
                    }
                }
                elseif($_POST['currency'] == 'eur' && $row['wal'] == 'usd') {
                    $total_f['total'] += ($unitValue * $usd) / $eur;
                    if(is_numeric(tofloat($row['vat']))) {
                        //                        $total_f['vat'] += ($unitValue * $unitVAT * $usd) / $eur;
                        switch ($_POST['vat_currency']) {
                            case 'pln':
                                $total_f['vat'] += ($unitValue * $unitVAT) * usd;
                                break;
                            case 'eur':
                                $total_f['vat'] += ($unitValue * $unitVAT * $usd) / $eur;
                                break;
                            case 'usd':
                                $total_f['vat'] += ($unitValue * $unitVAT);
                                break;
                        }
                    }
                }
            }

            $data = array(
                'value'    => $total_f['total'] * 10000,
                'vat'      => $total_f['vat'] * 10000 * 10000,
                'date_mod' => date('Y-m-d H:i:s')
            );
            //        die();
            //        $wpdb->show_errors();
            if(!$wpdb->update('wp_czm_korekty', $data, array('id' => $invoice_id))) {

                //                $message = '
                //            <script>
                //                jQuery(document).ready(function() {
                //                    $.sticky("Błąd zapisywania sumy faktury: '.$wpdb->last_error.'", {autoclose: false, position: "top-center", type: "st-error"});
                //                });
                //            </script>';
            }
        }
        $_REQUEST['action'] = '';
        $_REQUEST['id']     = $invoice_id;
        //        $wpdb->show_errors();
        break;
    case 'new_sub':
        if(!$wpdb->insert('wp_czm_korekty_items_to_invoices', array(
            'id_invoice' => $_REQUEST['id_invoice'],
        ))
        ) {
            $message           = array();
            $message['status'] = 'error';
            die(json_encode($message));
        }
        else {
            $message           = array();
            $message['status'] = 'success';
            $message['id']     = $wpdb->insert_id;
            die(json_encode($message));
        }
        break;
    case 'save_sub':
        $message = array();
        $ids     = '';
        $error   = false;
        foreach($_POST['row'] as $sub_key => $sub_item) {

            $data = array(
                'name'       => $sub_item['subc'],
                'quantity'   => $sub_item['quantity'],
                'value'      => tofloat($sub_item['value']) * 10000,
                'vat'        => (!is_numeric($sub_item['vat']) ? 'NP' : tofloat($sub_item['vat']) * 10000),
                'currency'   => $sub_item['wal'],
                'id_invoice' => $sub_item['id']
            );


            if(!$wpdb->update('wp_czm_korekty_items_to_invoices', $data, array('id' => $sub_item['id_sub']))) {
                $wpdb->show_errors();

                $error = true;
                $ids .= $sub_item['id_sub'].' ';
            }
        }

        if($error) {
            $message = array(
                'status' => 'error',
                'id'     => $ids,
                'msg'    => $wpdb->last_error.' '.$wpdb->last_query
            );
        }

        $message = array(
            'status' => 'success',
            'id'     => '',
        );
        //        die('<pre>'.print_r($mes, true).'</pre>');
        die(json_encode($message));
        break;
    case 'delete_sub':
        if(isset($_REQUEST['id'])) {
            $del_result = $wpdb->delete('wp_czm_korekty_items_to_invoices', array('id' => $_REQUEST['id']));

            if($del_result) {
                if($del_result == 1) {
                    die(json_encode(array(
                        "status" => 'success',
                        "msg"    => "Towar został usunięty.",
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
                    "msg"    => "Towar nie został usunięty.",
                    "id"     => $_REQUEST['id']
                )));
            }
        }
        else {
            die(json_encode(array(
                "status" => 'bad_id',
                "msg"    => "Niepoprawne id towaru",
                "id"     => $_REQUEST['id']
            )));
        }
        break;
    default:

        if(isset($_REQUEST['xhr']) && $_REQUEST['xhr'] == 1) {

//            if(is_app_admin($current_user)) {
                $sql     = 'SELECT *  FROM wp_czm_korekty ORDER BY id DESC';
                $results = $wpdb->get_results($sql, OBJECT);
//            }
//            else {
//                $results = $wpdb->get_results('SELECT * FROM wp_czm_korekty WHERE id_user='.$current_user->ID.' ORDER BY id DESC', OBJECT);
//            }



            $return = array();

            foreach($results as $key => $result) {

                $results_invoice = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id='.$result->id_invoice.' ORDER BY id DESC', OBJECT);
                $results_invoice = $results_invoice['0'];

                $return[$key][] = $result->id;
                $return[$key][] = $result->numer;
                $return[$key][] = $result->name;
                $return[$key][] = $results_invoice->numer;
                $return[$key][] = number_format($result->value/10000, 2, ',', ' ').' '.$result->currency;
                $return[$key][] = number_format($result->vat/10000, 2, ',', ' ').' '.$result->vat_currency;
                $return[$key][] = $result->date_payment;
                $return[$key][] = $result->date_paid;
                $return[$key][] = $current_user->display_name;

                $buttons = '<div class="btn-group">
										<button class="btn dropdown-toggle" data-toggle="dropdown">Akcje <span class="caret"></span></button>
										<ul class="dropdown-menu">';
                $buttons .= '<li><a href="'.get_bloginfo('url').'/korekty?action=edit&id='.$return[$key]['0'].'" class="sepV_a" title="Edytuj"><i class="splashy-contact_blue_edit"></i>Edytuj</a></li>';
                if(is_app_admin($current_user)) {
                    $buttons .= '<li><a href="'.get_bloginfo('url').'/korekty/?action=delete&id='.$return[$key]['0'].'" class="ajax-link sepV_a" title="Usuń"><i class="splashy-remove"></i>Usuń</a></li>';
                }

                    $buttons .= '<li><a href="'.get_bloginfo('url').'/korekty/?action=print&id='.$return[$key]['0'].'" class="sepV_a" title="Podgląd/Drukuj"><i class="splashy-document_letter"></i>Podgląd</a></li>';
                    $buttons .= '<li><a href="'.get_bloginfo('url').'/korekty/?action=pdf&id='.$return[$key]['0'].'" class="sepV_a" title="Podgląd/Drukuj"><img src="'.$template_url.'/img/pdf.png" style="width:25px;" alt="PDF" />PDF</a></li>';


                $buttons .= '</ul></div>';


                $return[$key][] = $buttons;
            }

//            var_dump( $return);
            die(json_encode(array('data' => $return)));
        }
}

get_header();
echo $message;

?>
<div id="contentwrapper">
    <div class="main_content">
        <?php

        switch ($_REQUEST['action']) {
            case 'edit':
                get_template_part('korekty', 'edit');
                break;
            case 'add':
                get_template_part('korekty', 'add');
                break;
            default:

                ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <h3 class="heading">Faktury korygujące</h3>

                            <table class="table table-bordered table-striped table_vam" id="dt_korekty"
                                   style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Numer</th>
                                    <th>Klient</th>
                                    <th>Nr. faktury</th>

<!--                                    <th>Numer zlecenia</th>-->
                                    <th>Wartość</th>
                                    <th>VAT</th>
                                    <th>Termin płatności</th>
                                    <th>Zapłacono</th>
                                    <th>Wystawił</th>
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