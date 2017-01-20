<?php
/*
 * Template Name: Koszty stałe
 *
 */

if (!is_user_logged_in()) {
    auth_redirect();
}

global $wpdb, $current_user;

switch ($_REQUEST['action']) {
    case 'post':
        echo '<pre>';
        var_dump($_POST);
        echo '</pre>';
        break;
    case 'new':
        $userdata = array(
            'number'       => $_POST['number'],
            'typ'          => $_POST['typ'],
            'date_applies' => $_POST['date_applies'],
            'issuer'       => $_POST['issuer'],
            'name'         => $_POST['fname'],
            'netto'        => tofloat($_POST['netto']) * 10000,
            'vat'          => tofloat($_POST['vat']) * 10000,
            'brutto'       => tofloat($_POST['brutto']) * 10000,
            'note'         => $_POST['note'],
            'date_payment' => $_POST['date_payment'],
            'date_paid'    => $_POST['date_paid'],
            'date_add'     => date('Y-m-d H:i:s'),
            'date_mod'     => date('Y-m-d H:i:s'),
            'id_user'      => $current_user->ID
        );

        if (!$wpdb->insert('wp_czm_costs', $userdata)) {
            $_REQUEST['action'] = 'add';
            $message            = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Błąd koszt: '.$wpdb->last_error.'", {autoclose: false, position: "top-center", type: "st-error"});
                });
            </script>';
        } else{
            $message = '
            <script>
                jQuery(document).ready(function() {
                    $.sticky("Dodano koszt: '.$wpdb->insert_id.'", {autoclose: false, position: "top-center", type: "st-success"});
                });
            </script>';
        }
        break;
    case 'update':
        $userdata = array(
            'number'       => $_POST['number'],
            'typ'          => $_POST['typ'],
            'date_applies' => $_POST['date_applies'],
            'issuer'       => $_POST['issuer'],
            'name'         => $_POST['fname'],
            'netto'        => tofloat($_POST['netto']) * 10000,
            'vat'          => tofloat($_POST['vat']) * 10000,
            'brutto'       => tofloat($_POST['brutto']) * 10000,
            'note'         => $_POST['note'],
            'date_payment' => $_POST['date_payment'],
            'date_paid'    => $_POST['date_paid'],

            'date_mod'     => date('Y-m-d H:i:s'),
            'id_user_edit'      => $current_user->ID
        );
        
        if (!$wpdb->update('wp_czm_costs', $userdata, array('ID' => $_POST['id']))) {
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
    case 'delete':
        if (isset($_REQUEST['id'])) {
            $del_result = $wpdb->delete('wp_czm_costs', array('id' => $_REQUEST['id']));

            if ($del_result) {
                if ($del_result == 1) {
                    die(json_encode(array(
                        "status" => 'success',
                        "msg"    => "Koszt został usunięty.",
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
                    "msg"    => "Koszt nie został usunięty..",
                    "id"     => $_REQUEST['id']
                )));
            }

        } else{
            die(json_encode(array(
                "status" => 'bad_id',
                "msg"    => "Niepoprawne id.",
                "id"     => $_REQUEST['id']
            )));
        }
        break;
    default:
        if (isset($_REQUEST['xhr']) && $_REQUEST['xhr'] == 1) {
            $results = $wpdb->get_results('SELECT `id`, `name`, `issuer`, `number`, `typ`, `netto`, `vat`, `brutto`, `date_applies`, `date_payment`, `date_paid` FROM wp_czm_costs', OBJECT);
//            echo '<pre>'.print_r($results,true).'</pre>';
            foreach($results as $key => $row) {
                $results[$key]->netto = number_format($results[$key]->netto/10000, 2,',',' ').' PLN';
                $results[$key]->vat = number_format($results[$key]->vat/10000, 2,',',' ').' PLN';;
                $results[$key]->brutto = number_format($results[$key]->brutto/10000, 2,',',' ').' PLN';;
                $buttons = "<a href=\"".get_bloginfo('url')."/koszty?action=edit&id=".$results[$key]->id."\" class=\"sepV_a\" title=\"Edytuj\"><i class=\"splashy-contact_blue_edit\"></i></a>";
                if (is_app_admin($current_user)) {
                    $buttons .= "<a href=\"".get_bloginfo('url')."/koszty?action=delete&id=".$results[$key]->id."\" class=\"ajax-link\" title=\"Usuń\"><i class=\"splashy-remove\"></i></a>";
                }
                $results[$key]->buttons = $buttons;

            }
            //echo '<pre>'.print_r($response,true).'</pre>';
//            echo '<pre>'.print_r($results,true).'</pre>';


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
        if (is_app_admin($current_user)) {
            //                echo '<br><br><br><br><pre>'.print_r($_POST, true).'</pre>';
            switch ($_REQUEST['action']) {
                case 'edit':
                    get_template_part('koszty', 'edit');
                    break;
                case 'add':
                    get_template_part('koszty', 'add');
                    break;
                case 'delete':
                    ob_flush();
                    echo 'deleteeeeeeeeeee';
                    break;
                default:
                    ?>
                    <table id="dt_koszty" class="table table-bordered table-striped table_vam nowrap" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tytułem</th>
                            <th>Firma</th>
                            <th>Nr. faktury</th>
                            <th>Rodzaj</th>
                            <th>Netto</th>
                            <th>VAT</th>
                            <th>Brutto</th>
                            <th>Miesiąc</th>
                            <th>Termin płatności</th>
                            <th>Data zapłaty</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                    <?php
            }

        }else{
            echo 'Tą stronę może przeglądać tylko administrator!!';
        }
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