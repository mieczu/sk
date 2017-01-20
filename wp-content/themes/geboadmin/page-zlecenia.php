<?php
/*
 * Template Name: Zlecenia
 *
 */

if(!is_user_logged_in()) {
    auth_redirect();
}

global $wpdb, $current_user, $wp_roles;

if(!isset($_REQUEST['action'])) $_REQUEST['action'] = '';

switch ($_REQUEST['action']) {
    case 'print':
        if(isset($_REQUEST['id'])) {
            get_template_part('zlecenia', 'print');
        }
        die('');
        break;
    case 'delete':
        if(is_app_admin($current_user)) {
            if(isset($_REQUEST['id'])) {
                $del_result = $wpdb->delete('wp_czm_orders', array('ID' => $_REQUEST['id']));

                if($del_result) {
                    if($del_result == 1) {
                        die(json_encode(array(
                            "status" => 'success',
                            "msg"    => "Zlecenie zostało usunięte.",
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
                        "msg"    => "Zlecenie nie zostało usunięte.",
                        "id"     => $_REQUEST['id']
                    )));
                }

            }
            else {
                die(json_encode(array(
                    "status" => 'bad_id',
                    "msg"    => "Niepoprawne id zlecenia",
                    "id"     => $_REQUEST['id']
                )));
            }
        }
        else {
            $_REQUEST['action'] = '';
        }
        break;
    case 'enable':
        $data = array(
            'disabled' => 0,
        );

        if(is_app_admin($current_user)) {
            if($wpdb->update('wp_czm_orders', $data, array('ID' => $_REQUEST['id']))) {
                die(json_encode(array(
                    "status" => 'success',
                    "msg"    => "Zlecenie zostało odblokowane.",
                    "id"     => $_REQUEST['id']
                )));
            }
            else {
                die(json_encode(array(
                    "status" => 'error',
                    "msg"    => "Zlecenie nie zostało odblokowane.",
                    "id"     => $_REQUEST['id']
                )));
            }
        }
        else {
            die(json_encode(array(
                "status" => 'error',
                "msg"    => "Tylko Administrator może odblokować zlecenie.",
                "id"     => $_REQUEST['id']
            )));
        }
        break;
    case 'disable':
        $data       = array(
            'disabled' => 1,
        );
        $faktury_zk = null;
        $faktury_sp = null;

        $results_sub = $wpdb->get_results('SELECT id, subcontractor, f_number, value, currency, exchange FROM wp_czm_subcontractor_to_order  WHERE id_order='.$_REQUEST['id'], OBJECT);
        foreach($results_sub as $sub) {
            $faktury_zk = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id_subcontractor_to_order='.$sub->id.' AND typ=\'Zakup\'', OBJECT);

            if(!$faktury_zk) {
                die(json_encode(array(
                    "status" => 'error',
                    "msg"    => "Brakuje faktur zakupowych.",
                    "id"     => $_REQUEST['id']
                )));
            }
        }

        $faktury_sp = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id_order='.$_REQUEST['id'].' AND typ=\'Sprzedaż\'', OBJECT);

        if(!$faktury_sp) {
            die(json_encode(array(
                "status" => 'error',
                "msg"    => "Musisz dodać conajmniej jedną fakturę sprzedaży.",
                "id"     => $_REQUEST['id']
            )));
        }

        if($wpdb->update('wp_czm_orders', $data, array('ID' => $_REQUEST['id']))) {
            die(json_encode(array(
                "status" => 'success',
                "msg"    => "Zlecenie zostało zablokowane.",
                "id"     => $_REQUEST['id']
            )));
        }
        else {
            die(json_encode(array(
                "status" => 'error',
                "msg"    => "Zlecenie nie zostało zablokowane.",
                "id"     => $_REQUEST['id']
            )));
        }
        break;
    case 'post':
        var_dump($_POST);
        die();
        break;
    case 'new':
        if(!empty($_POST)) {
            $sql     = "SELECT MAX(id) as 'id', hnumber  FROM wp_czm_orders WHERE date_add BETWEEN '".date('Y-m')."-01 00:00:00' AND '".date('Y-m-t')." 23:59:59'";
            $results = $wpdb->get_results($sql, ARRAY_A);
            $sql     = "SELECT hnumber  FROM wp_czm_orders WHERE id=".$results[0]['id'];
            $results = $wpdb->get_results($sql, ARRAY_A);

            if(!is_numeric($results[0][hnumber])) {
                $number = 1;
            }
            else {
                $number = ++$results[0][hnumber];
            }

            $number2 = $number;
            if($number < 10) $number2 = '0'.$number;

            $results_nadawca = $wpdb->get_results('SELECT id FROM wp_czm_clients WHERE name like \''.strtoupper($_POST['nadawca']).'\' OR short_name like \''.$_POST['nadawca'].'\'', object);
            $results_odbiorca = $wpdb->get_results('SELECT id FROM wp_czm_clients WHERE name like \''.strtoupper($_POST['odbiorca']).'\' OR short_name like \''.$_POST['odbiorca'].'\'', object);

            if(count($results_nadawca)>1){
            }else{
                $results_nadawca = $results_nadawca[0];
                if(is_numeric($results_nadawca->id)){
                    $_POST['idnadawca']=$results_nadawca->id;
                }
            }

            if(count($results_odbiorca)>1){
            }else{
                $results_odbiorca = $results_odbiorca[0];

                if(is_numeric($results_odbiorca->id)){
                    $_POST['idodbiorca']=$results_odbiorca->id;
                }
            }


            $data = array(
                'transport'    => $_POST['transport'],
                'typ'          => $_POST['typ'],
                'id_nadawca'   => $_POST['idnadawca'],
                'id_odbiorca'  => $_POST['idodbiorca'],
                'id_platnik'   => $_POST['idplatnik'],
                'nadawca'      => $_POST['nadawca'],
                'odbiorca'     => $_POST['odbiorca'],
                'platnik'      => $_POST['platnik'],
                'id_user'      => $current_user->ID,
                'hnumber'      => $number,
                'number'       => 'SM'.date('ym').$number2,
                'hawb'         => $_POST['hawb'],
                'awb'          => $_POST['awb'],
                'hbl'          => $_POST['hbl'],
                'bl'           => $_POST['bl'],
                'orgin'        => $_POST['orgin'],
                'destination'  => $_POST['destination'],
                'date_execute' => $_POST['date_execute'],
                'eta'          => $_POST['eta'],
                'etd'          => $_POST['etd'],
                'waga_b'       => $_POST['waga_b'],
                'waga_p'       => $_POST['waga_p'],
                'quantity'     => $_POST['quantity'],
                'kub'          => $_POST['kub'],
                'cmr'          => $_POST['cmr'],
                'fin'          => $_POST['fin'],
                'fout'         => $_POST['fout'],
                'timocom'      => $_POST['timocom'],
                'wtransnet'    => $_POST['wtransnet'],
                'teleroute'    => $_POST['teleroute'],
                'oc'           => $_POST['oc'],
                'fak'          => $_POST['fak'],
                'ocp'          => $_POST['ocp'],
                'transid'      => $_POST['transid'],
                'date_add'     => date('Y-m-d H:i:s'),
                'date_mod'     => date('Y-m-d H:i:s'),
                'icoterms'     => $_POST['icoterms'],
                'note'         => $_POST['message'],
            );
            $wpdb->show_errors();
            if(!$wpdb->insert('wp_czm_orders', $data)) {
                $_REQUEST['action'] = 'add';
                $message            = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Błąd dodawania zlecenia: '.$wpdb->last_error.'", {autoclose: false, position: "top-center", type: "st-error"});
                });
            </script>';
            }
            else {
                $message = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Dodano zlecenie: '.$wpdb->insert_id.'", {autoclose: false, position: "top-center", type: "st-success"});
                });
            </script>';
            }
            //            $wpdb->show_errors();
            //            echo '<pre>'.print_r($_POST, true).'</pre>';
        }
        break;
    case 'update':
        //                echo '<pre>'.print_r($_POST, true).'</pre>';

        $results_nadawca = $wpdb->get_results('SELECT id FROM wp_czm_clients WHERE name like \''.strtoupper($_POST['nadawca']).'\' OR short_name like \''.$_POST['nadawca'].'\'', object);
        $results_odbiorca = $wpdb->get_results('SELECT id FROM wp_czm_clients WHERE name like \''.strtoupper($_POST['odbiorca']).'\' OR short_name like \''.$_POST['odbiorca'].'\'', object);

        if(count($results_nadawca)>1){
        }else{
            $results_nadawca = $results_nadawca[0];
            if(is_numeric($results_nadawca->id)){
                $_POST['idnadawca']=$results_nadawca->id;
            }
        }

        if(count($results_odbiorca)>1){
        }else{
            $results_odbiorca = $results_odbiorca[0];

            if(is_numeric($results_odbiorca->id)){
                $_POST['idodbiorca']=$results_odbiorca->id;
            }
        }

        $data = array(
            'transport'    => $_POST['transport'],
            'typ'          => $_POST['typ'],
            'id_nadawca'   => $_POST['idnadawca'],
            'id_odbiorca'  => $_POST['idodbiorca'],
            'id_platnik'   => $_POST['idplatnik'],
            'nadawca'      => $_POST['nadawca'],
            'odbiorca'     => $_POST['odbiorca'],
            'platnik'      => $_POST['platnik'],
            'hawb'         => $_POST['hawb'],
            'awb'          => $_POST['awb'],
            'hbl'          => $_POST['hbl'],
            'bl'           => $_POST['bl'],
            'orgin'        => $_POST['orgin'],
            'destination'  => $_POST['destination'],
            'date_execute' => $_POST['date_execute'],
            'eta'          => $_POST['eta'],
            'etd'          => $_POST['etd'],
            'waga_b'       => $_POST['waga_b'],
            'waga_p'       => $_POST['waga_p'],
            'quantity'     => $_POST['quantity'],
            'kub'          => $_POST['kub'],
            'cmr'          => $_POST['cmr'],
            'fout'         => $_POST['fout'],
            'fin'          => $_POST['fin'],
            'timocom'      => $_POST['timocom'],
            'wtransnet'    => $_POST['wtransnet'],
            'teleroute'    => $_POST['teleroute'],
            'oc'           => $_POST['oc'],
            'fak'          => $_POST['fak'],
            'ocp'          => $_POST['ocp'],
            'transid'      => $_POST['transid'],
            'date_mod'     => date('Y-m-d H:i:s'),
            'icoterms'     => $_POST['icoterms'],
            'note'         => $_POST['message'],
        );
        //        die();
        //        $wpdb->show_errors();
        if(!$wpdb->update('wp_czm_orders', $data, array('ID' => $_POST['id']))) {
            $wpdb->show_errors();
            $_REQUEST['action'] = 'add';
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
        //        $wpdb->show_errors();
        break;
    default:
        if(isset($_REQUEST['xhr']) && $_REQUEST['xhr'] == 1) {

            if(is_app_admin($current_user)) {
                $sql     = 'SELECT id, number, id_nadawca, id_odbiorca, id_platnik, id_user, transport, typ, date_add, nadawca, odbiorca, platnik, disabled, awb, hawb, bl, hbl FROM wp_czm_orders ORDER BY id DESC';
                $results = $wpdb->get_results($sql, ARRAY_N);
            }
            else {
                    $orderTypes1['air']  = get_user_meta($current_user->ID, 'air', true);
                    $orderTypes1['sea']  = get_user_meta($current_user->ID, 'sea', true);
                    $orderTypes1['land'] = get_user_meta($current_user->ID, 'land', true);

                    $where   = [];
                    $where[] = ($orderTypes1['air'] == 1 ? '"Lotniczy"' : '""');
                    $where[] = $orderTypes1['sea'] == 1 ? '"Morski"' : '""';
                    $where[] = $orderTypes1['land'] == 1 ? '"Drogowy"' : '""';
                    $where   = implode(',', $where);
//                    file_put_contents('order_query.txt', print_r($orderTypes1, true)."\n\n");
//                    file_put_contents('order_query.txt', $where."\n\n", FILE_APPEND);
//                    file_put_contents('order_query.txt', 'SELECT id, number, id_nadawca, id_odbiorca, id_platnik, id_user, transport, typ, date_add, nadawca, odbiorca, platnik, disabled FROM wp_czm_orders WHERE id_user='.$current_user->ID.' OR transport IN('.$where.') ORDER BY id DESC', FILE_APPEND);

                $results = $wpdb->get_results('SELECT id, number, id_nadawca, id_odbiorca, id_platnik, id_user, transport, typ, date_add, nadawca, odbiorca, platnik, disabled, awb, hawb, bl, hbl FROM wp_czm_orders WHERE id_user='.$current_user->ID.' OR transport IN('.$where.') ORDER BY id DESC', ARRAY_N);

            }

            //            var_dump( $results);

            $return = array();
            foreach($results as $key => $result) {
                $return[$result[0]]['nadawca']  = $wpdb->get_results('SELECT id, short_name FROM wp_czm_clients WHERE id='.$result[2], ARRAY_N);
                $return[$result[0]]['odbiorca'] = $wpdb->get_results('SELECT id, short_name FROM wp_czm_clients WHERE id='.$result[3], ARRAY_N);
                $return[$result[0]]['platnik']  = $wpdb->get_results('SELECT id, short_name FROM wp_czm_clients WHERE id='.$result[4], ARRAY_N);
                $return[$result[0]]['user']     = $wpdb->get_results('SELECT id, display_name FROM wp_users WHERE id='.$result[5], ARRAY_N);

                //                $inicialy = explode(' ',ucwords());
                //                $inicial = '';
                //                foreach($inicialy as $i){
                //                    $inicial.= substr($i,0,1);
                //                }

                $nadawca  = $results[$key][9];
                $odbiorca = $results[$key][10];
                $platnik  = $results[$key][11];
                unset($results[$key][9]);
                unset($results[$key][10]);
                unset($results[$key][11]);

                if(isset($return[$result[0]]['nadawca'][0][1]) && $return[$result[0]]['nadawca'][0][1] == $nadawca) {
                    $results[$key][2] = $return[$result[0]]['nadawca'][0][1];
                }
                else {
                    $results[$key][2] = $nadawca;
                }

                if(isset($return[$result[3]]['odbiorca'][0][1]) && $return[$result[3]]['odbiorca'][0][1] == $odbiorca) {
                    $results[$key][3] = $return[$result[0]]['odbiorca'][0][1];
                }
                else {
                    $results[$key][3] = $odbiorca;
                }

                if(isset($return[$result[4]]['platnik'][0][1]) && $return[$result[4]]['platnik'][0][1] == $platnik) {
                    $results[$key][4] = $return[$result[0]]['platnik'][0][1];
                }
                else {
                    $results[$key][4] = $platnik;
                }

                $results[$key][5] = isset($return[$result[0]]['user'][0][1]) ? $return[$result[0]]['user'][0][1] : 'Usunięty';
                $results[$key][1] = isset($return[$result[0]]['user'][0][1]) ? $results[$key][1].inicialy($return[$result[0]]['user'][0][1]) : $results[$key][1];
                $buttons          = '';
                if($results[$key][12] == 0) {
                    $buttons .= '<a href="'.get_bloginfo('url').'/zlecenia?action=edit&id='.$results[$key]['0'].'" class="sepV_a" title="Edytuj"><i class="splashy-contact_blue_edit"></i></a>';

                    $buttons .= '<a href="'.get_bloginfo('url').'/zlecenia?action=disable&id='.$results[$key]['0'].'" class="sepV_a ajax-disable" title="Zablokuj"><i class="splashy-contact_grey_remove"></i></a>';
                }
                if(is_app_admin($current_user)) {
                    if($results[$key][12] == 1) {
                        $buttons .= '<a href="'.get_bloginfo('url').'/zlecenia?action=enable&id='.$results[$key]['0'].'" class="sepV_a ajax-enable" title="Odblokuj"><i class="splashy-contact_blue_remove"></i></a>';
                    }
                    else {
                        $buttons .= '<a href="'.get_bloginfo('url').'/zlecenia/?action=delete&id='.$results[$key]['0'].'" class="ajax-link sepV_a" title="Usuń"><i class="splashy-remove"></i></a>';
                    }
                }
                $buttons .= '<a href="'.get_bloginfo('url').'/zlecenia/?action=print&id='.$results[$key]['0'].'" class="sepV_a" title="Podgląd/Drukuj"><i class="splashy-document_letter"></i></a>';

                $results[$key][9]  = $buttons;
                $results[$key][10] = $results[$key][12];
                $results[$key][11] = $results[$key][13];//awb 047-6785 7031 1336
                $results[$key][12] = $results[$key][14];//hawb
                $results[$key][13] = $results[$key][15];//bl
                $results[$key][14] = $results[$key][16];//hbl

                unset($results[$key][15]);
                unset($results[$key][16]);
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
                get_template_part('zlecenia', 'edit');
                break;
            case 'add':
                $results_clients = $wpdb->get_results('SELECT DISTINCT short_name,id FROM wp_czm_clients', OBJECT);
                get_template_part('zlecenia', 'add');

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
                            <h3 class="heading">Zlecenia</h3>
                            <label>Data wystawienia:</label>
                            Od: <input type="text" id="minZlec" name="minZlec" class="span2"/><br/>
                            Do: <input type="text" id="maxZlec" name="maxZlec" class="span2"/>
                            <table class="table table-bordered table-striped table_vam" id="dt_zlecenia"
                                   style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Numer</th>
                                    <th>Nadawca</th>
                                    <th>Odbiorca</th>
                                    <th>Płatnik</th>
                                    <th>Użytkownik</th>
                                    <th>Transport</th>
                                    <th>Typ</th>
                                    <th>Data dodania</th>
                                    <th></th>
                                    <th class="none">Disabled</th>
                                    <th>AWB</th>
                                    <th>HAWB</th>
                                    <th>BL</th>
                                    <th>HBL</th>
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