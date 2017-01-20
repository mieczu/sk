<?php

global $wpdb, $current_user;
//$sql    = 'SELECT id, number, id_nadawca, id_odbiorca, id_platnik, id_user, transport, typ, date_add, nadawca, odbiorca, platnik, disabled FROM wp_czm_orders ORDER BY id DESC';
$where = '';
$types = [
    'Drogowe',
    'Morskie',
    'Lotnicze'
];

if(!is_user_logged_in()) {
    auth_redirect();
}

if(!is_app_admin($current_user)) {
    $_REQUEST['user'] = $current_user->ID;
}

if(isset($_POST['submit'])) {
    $where .= '`date_add` BETWEEN \''.$_REQUEST['date_start'].'\' AND \''.$_REQUEST['date_end'].'\' ';

    if (is_array($_POST['excluded'])){
        $_POST['excluded2'] = implode(',',$_POST['excluded']);
        $where .= 'AND id not in('.$_POST['excluded2'].') ';
    }

    if(!empty($_REQUEST['user'])) {
        $where .= 'AND id_user ='.$_REQUEST['user'].' ';
    }
    $sql          = 'SELECT * FROM wp_czm_orders WHERE '.$where.'ORDER BY id DESC';
    $orders       = $wpdb->get_results($sql, OBJECT);
    $sql          = 'SELECT count(*) FROM wp_czm_orders WHERE '.$where.'ORDER BY id DESC';
    $orders_count = $wpdb->get_var($sql);

    $sql            = 'SELECT * FROM wp_czm_orders WHERE '.$where.'AND transport=\'Drogowy\' ORDER BY id DESC';
    $orders_d       = $wpdb->get_results($sql, OBJECT);
    $sql            = 'SELECT count(*) FROM wp_czm_orders WHERE '.$where.'AND transport =\'Drogowy\' ORDER BY id DESC';
    $orders_d_count = $wpdb->get_var($sql);

    $sql            = 'SELECT * FROM wp_czm_orders WHERE '.$where.'AND transport =\'Morski\' ORDER BY id DESC';
    $orders_m       = $wpdb->get_results($sql, OBJECT);
    $sql            = 'SELECT count(*) FROM wp_czm_orders WHERE '.$where.'AND transport =\'Morski\' ORDER BY id DESC';
    $orders_m_count = $wpdb->get_var($sql);

    $sql            = 'SELECT * FROM wp_czm_orders WHERE '.$where.'AND transport=\'Lotniczy\' ORDER BY id DESC';
    $orders_l       = $wpdb->get_results($sql, OBJECT);
    $sql            = 'SELECT count(*) FROM wp_czm_orders WHERE '.$where.'AND transport=\'Lotniczy\' ORDER BY id DESC';
    $orders_l_count = $wpdb->get_var($sql);


    $koszt_zlecenia    = [];
    $przychod_zlecenia = [];
    $vat_zlecenia      = [];

    foreach($orders_d as $drogowy) {
        $results_sub = $wpdb->get_results('SELECT id, subcontractor, f_number, value, currency, exchange FROM wp_czm_subcontractor_to_order  WHERE id_order='.$drogowy->id, OBJECT);

        $koszt = [];
        foreach($results_sub as $sub) {
            $faktury_zk = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id_subcontractor_to_order='.$sub->id.' AND typ=\'Zakup\'', OBJECT);
            if(isset($faktury_zk[0])) {
                switch ($faktury_zk[0]->currency) {
                    case 'pln':
                        $koszt[] = ($faktury_zk[0]->value / 10000);
                        break;
                    case 'usd':
                        $koszt[] = ($faktury_zk[0]->value / 10000) * ($faktury_zk[0]->usd / 10000);
                        break;
                    case 'eur':
                        $koszt[] = ($faktury_zk[0]->value / 10000) * ($faktury_zk[0]->eur / 10000);
                        break;
                    default:
                        $koszt[] = ($sub->value / 10000) * ($sub->exchange / 10000);
                }
            }else{
                $koszt[] = ($sub->value / 10000) * ($sub->exchange / 10000);
            }
        }
        $koszt_zlecenia[] = array_sum($koszt);

        $faktury_sp = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id_order='.$drogowy->id.' AND typ=\'Sprzedaż\'', OBJECT);
        $noty       = $wpdb->get_results('SELECT * FROM wp_czm_noty WHERE id_order='.$drogowy->id.' AND typ=\'Transportowa\'', OBJECT);

        $przy = [];
        $vat  = [];

        foreach($faktury_sp as $sp) {
            switch ($sp->currency) {
                case 'pln':
                    $exchnge = 1;
                    break;
                case 'usd':
                    $exchnge = $sp->usd / 10000;
                    break;
                case 'eur':
                    $exchnge = $sp->eur / 10000;
                    break;
            }
            $przy[] = ($sp->value / 10000) * $exchnge;

            switch ($sp->vat_currency) {
                case 'pln':
                    $vat_exchnge = 1;
                    break;
                case 'usd':
                    $vat_exchnge = $sp->usd / 10000;
                    break;
                case 'eur':
                    $vat_exchnge = $sp->eur / 10000;
                    break;
            }
            $vat[] = ($sp->vat / 10000) * $vat_exchnge;
        }

        foreach($noty as $nota) {
            switch (strtolower($nota->currency)) {
                case 'pln':
                    $exchnge = 1;
                    break;
                case 'usd':
                    $exchnge = $nota->usd / 10000;
                    break;
                case 'eur':
                    $exchnge = $nota->eur / 10000;
                    break;
            }
            $przy[] = ($nota->value / 10000) * $exchnge;
            $vat[]  = ($nota->tax / 10000) * $exchnge;
        }
        $vat_zlecenie[]      = array_sum($vat);
        $przychod_zlecenia[] = array_sum($przy);
    }

    $koszt_drogowy    = array_sum($koszt_zlecenia);
    $przychod_drogowy = array_sum($przychod_zlecenia);
    $vat_drogowy      = array_sum($vat_zlecenie);

    $koszt_zlecenia    = [];
    $przychod_zlecenia = [];
    $vat_zlecenia      = [];

    foreach($orders_m as $morski) {
        $results_sub = $wpdb->get_results('SELECT id, subcontractor, f_number, value, currency, exchange FROM wp_czm_subcontractor_to_order  WHERE id_order='.$morski->id, OBJECT);

        $koszt = [];
        foreach($results_sub as $sub) {
            $faktury_zk = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id_subcontractor_to_order='.$sub->id.' AND typ=\'Zakup\'', OBJECT);
            if(isset($faktury_zk[0])) {
                switch ($faktury_zk[0]->currency) {
                    case 'pln':
                        $koszt[] = ($faktury_zk[0]->value / 10000);
                        break;
                    case 'usd':
                        $koszt[] = ($faktury_zk[0]->value / 10000) * ($faktury_zk[0]->usd / 10000);
                        break;
                    case 'eur':
                        $koszt[] = ($faktury_zk[0]->value / 10000) * ($faktury_zk[0]->eur / 10000);
                        break;
                    default:
                        $koszt[] = ($sub->value / 10000) * ($sub->exchange / 10000);
                }
            }else{
                $koszt[] = ($sub->value / 10000) * ($sub->exchange / 10000);
            }
        }
        $koszt_zlecenia[] = array_sum($koszt);

        $faktury_sp = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id_order='.$morski->id.' AND typ=\'Sprzedaż\'', OBJECT);
        $noty       = $wpdb->get_results('SELECT * FROM wp_czm_noty WHERE id_order='.$morski->id.' AND typ=\'Transportowa\'', OBJECT);

        $przy = [];
        $vat  = [];
        foreach($faktury_sp as $sp) {
            switch ($sp->currency) {
                case 'pln':
                    $exchnge = 1;
                    break;
                case 'usd':
                    $exchnge = $sp->usd / 10000;
                    break;
                case 'eur':
                    $exchnge = $sp->eur / 10000;
                    break;
            }
            $przy[] = ($sp->value / 10000) * $exchnge;

            switch ($sp->vat_currency) {
                case 'pln':
                    $vat_exchnge = 1;
                    break;
                case 'usd':
                    $vat_exchnge = $sp->usd / 10000;
                    break;
                case 'eur':
                    $vat_exchnge = $sp->eur / 10000;
                    break;
            }
            $vat[] = ($sp->vat / 10000) * $vat_exchnge;
        }

        foreach($noty as $nota) {
            switch (strtolower($nota->currency)) {
                case 'pln':
                    $exchnge = 1;
                    break;
                case 'usd':
                    $exchnge = $nota->usd / 10000;
                    break;
                case 'eur':
                    $exchnge = $nota->eur / 10000;
                    break;
            }
            $przy[] = ($nota->value / 10000) * $exchnge;
            $vat[]  = ($nota->tax / 10000) * $exchnge;
        }

        $przychod_zlecenia[] = array_sum($przy);
        $vat_zlecenia[]      = array_sum($vat);
    }
    $koszt_morski    = array_sum($koszt_zlecenia);
    $przychod_morski = array_sum($przychod_zlecenia);
    $vat_morski      = array_sum($vat_zlecenia);

    //////////////////////////////////////////////////////
    $koszt_zlecenia    = [];
    $przychod_zlecenia = [];
    $vat_zlecenia      = [];

    foreach($orders_l as $lotniczy) {
        $results_sub = $wpdb->get_results('SELECT id, subcontractor, f_number, value, currency, exchange FROM wp_czm_subcontractor_to_order  WHERE id_order='.$lotniczy->id, OBJECT);

        $koszt = [];
        foreach($results_sub as $sub) {
            $faktury_zk = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id_subcontractor_to_order='.$sub->id.' AND typ=\'Zakup\'', OBJECT);
            if(isset($faktury_zk[0])) {
                switch ($faktury_zk[0]->currency) {
                    case 'pln':
                        $koszt[] = ($faktury_zk[0]->value / 10000);
                        break;
                    case 'usd':
                        $koszt[] = ($faktury_zk[0]->value / 10000) * ($faktury_zk[0]->usd / 10000);
                        break;
                    case 'eur':
                        $koszt[] = ($faktury_zk[0]->value / 10000) * ($faktury_zk[0]->eur / 10000);
                        break;
                    default:
                        $koszt[] = ($sub->value / 10000) * ($sub->exchange / 10000);
                }
            }else{
                $koszt[] = ($sub->value / 10000) * ($sub->exchange / 10000);
            }
        }
        $koszt_zlecenia[] = array_sum($koszt);

        $faktury_sp = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id_order='.$lotniczy->id.' AND typ=\'Sprzedaż\'', OBJECT);
        $noty       = $wpdb->get_results('SELECT * FROM wp_czm_noty WHERE id_order='.$lotniczy->id.' AND typ=\'Transportowa\'', OBJECT);

        $przy = [];
        $vat  = [];
        foreach($faktury_sp as $sp) {
            switch ($sp->currency) {
                case 'pln':
                    $exchnge = 1;
                    break;
                case 'usd':
                    $exchnge = $sp->usd / 10000;
                    break;
                case 'eur':
                    $exchnge = $sp->eur / 10000;
                    break;
            }
            $przy[] = ($sp->value / 10000) * $exchnge;

            switch ($sp->vat_currency) {
                case 'pln':
                    $vat_exchnge = 1;
                    break;
                case 'usd':
                    $vat_exchnge = $sp->usd / 10000;
                    break;
                case 'eur':
                    $vat_exchnge = $sp->eur / 10000;
                    break;
            }
            $vat[] = ($sp->vat / 10000) * $vat_exchnge;
        }

        foreach($noty as $nota) {
            switch (strtolower($nota->currency)) {
                case 'pln':
                    $exchnge = 1;
                    break;
                case 'usd':
                    $exchnge = $nota->usd / 10000;
                    break;
                case 'eur':
                    $exchnge = $nota->eur / 10000;
                    break;
            }
            $przy[] = ($nota->value / 10000) * $exchnge;
            $vat[]  = ($nota->tax / 10000) * $exchnge;
        }

        $przychod_zlecenia[] = array_sum($przy);
        $vat_zlecenia[]      = array_sum($vat);
    }
    $koszt_lotniczy    = array_sum($koszt_zlecenia);
    $przychod_lotniczy = array_sum($przychod_zlecenia);
    $vat_lotniczy      = array_sum($vat_zlecenia);

}
else {

}

$results_users = $wpdb->get_results('SELECT display_name,id FROM wp_users', OBJECT);
?>


<?php

$tabela = '<table style="vertical-align: top;">';
$tabela .= '<tr>
<th>L.p.</th><th></th><th>Numer</th><th>Nadawca</th><th>Odbiorca</th><th>Płatnik</th><th>Pracownik</th><th>Zysk</th>
</tr>';
$lp = 0;
foreach($orders as $order) {
    $users          = $wpdb->get_results('SELECT * FROM wp_users WHERE id='.$order->id_user, OBJECT);
    $subcontractors = $wpdb->get_results('SELECT * FROM wp_czm_subcontractor_to_order WHERE id_order='.$order->id, OBJECT);
    $koszt          = [];
    foreach($subcontractors as $sub) {
        $faktury_zk = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id_subcontractor_to_order='.$sub->id.' AND typ=\'Zakup\'', OBJECT);

        if(isset($faktury_zk[0])) {
            switch ($faktury_zk[0]->currency) {
                case 'pln':
                    $koszt[] = ($faktury_zk[0]->value / 10000);
                    break;
                case 'usd':
                    $koszt[] = ($faktury_zk[0]->value / 10000) * ($faktury_zk[0]->usd / 10000);
                    break;
                case 'eur':
                    $koszt[] = ($faktury_zk[0]->value / 10000) * ($faktury_zk[0]->eur / 10000);
                    break;
                default:
                    $koszt[] = ($sub->value / 10000) * ($sub->exchange / 10000);
            }
        }else{
            $koszt[] = ($sub->value / 10000) * ($sub->exchange / 10000);
        }
    }
    $koszt_zlecenia = array_sum($koszt);

    $tabela .= '<tr><td>'.++$lp.'</td><td><input type="checkbox" name="excluded[]" value="'.$order->id.'" /></td><td>'.$order->number.inicialy($users[0]->display_name).'</td><td>'.$order->nadawca.'</td><td>'.$order->odbiorca.'</td><td>'.$order->platnik.'</td><td>'.$users[0]->display_name.'</td><td>';



    $faktury_sp = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id_order='.$order->id.' AND typ=\'Sprzedaż\'', OBJECT);
    $noty       = $wpdb->get_results('SELECT * FROM wp_czm_noty WHERE id_order='.$order->id.' AND typ=\'Transportowa\'', OBJECT);

    $przy = [];
    $vat  = [];

    $tabela2 = '<table>';
    if(isset($faktury_sp)) {
        foreach($faktury_sp as $sp) {
            switch ($sp->currency) {
                case 'pln':
                    $exchnge = 1;
                    break;
                case 'usd':
                    $exchnge = $sp->usd / 10000;
                    break;
                case 'eur':
                    $exchnge = $sp->eur / 10000;
                    break;
            }
            $przy[] = ($sp->value / 10000) * $exchnge;
            switch ($sp->vat_currency) {
                case 'pln':
                    $vat_exchnge = 1;
                    break;
                case 'usd':
                    $vat_exchnge = $sp->usd / 10000;
                    break;
                case 'eur':
                    $vat_exchnge = $sp->eur / 10000;
                    break;
            }
            $vat[] = ($sp->vat / 10000) * $vat_exchnge;
            $tabela2 .= '
        <tr>
            <td colspan="2">Faktura '.$sp->numer.'</td>
            <td style="white-space: nowrap;">'.number_format(($sp->value / 10000) * $exchnge, 2, ",", " ").' PLN</td>
            <td style="white-space: nowrap;">'.number_format(($sp->vat / 10000) * $vat_exchnge, 2, ",", " ").' PLN</td>
            <td style="white-space: nowrap;">'.number_format((($sp->vat / 10000) * $vat_exchnge) + (($sp->value / 10000) * $exchnge), 2, ",", " ").' PLN</td>
            <td>'.(validateDate($sp->date_paid) ? "Zapłacona" : "Nie zapłacona").'</td>
        </tr>';
        }
    }
    if(isset($noty)) {
        foreach($noty as $nota) {
            switch (strtolower($nota->currency)) {
                case 'pln':
                    $exchnge = 1;
                    break;
                case 'usd':
                    $exchnge = $nota->usd / 10000;
                    break;
                case 'eur':
                    $exchnge = $nota->eur / 10000;
                    break;
            }
            $przy[] = ($nota->value / 10000) * $exchnge;
            $vat[]  = ($nota->tax / 10000) * $exchnge;

            $tabela2 .= '
        <tr>
            <td colspan="2">Nota '.$nota->typ.' '.$nota->number.'</td>
            <td style="white-space: nowrap;">'.number_format(($nota->value / 10000) * $exchnge, 2, ',', ' ').'</td>
            <td>PLN</td>
        </tr>';
        }
        $tabela2 .= '<tr><td></td></tr></table>';

        $tabela .= number_format(array_sum($przy) - $koszt_zlecenia, 2, ',', ' ');

        $tabela .= ' PLN</td></tr><tr><td colspan="3"</td><td colspan="4" style="text-align:right;background-color:#eee;">';

        $tabela .= $tabela2;

        $tabela .= '</td></tr>';
    }
}
$tabela .= '<table>';


?><form id="rep1" name="search_order" id="search_order" action="" method="post">
    <div class="row-fluid">
        <div class="span12">

            <h3 class="heading">Raporty zlecenia</h3>


                <div class="formSep">
                    <div class="row-fluid">
                        <div class="span2">
                            <input class="span10" id="date_start" type="text" name="date_start"
                                   value="<?php echo $_REQUEST['date_start']; ?>"/>
                        </div>
                        <div class="span2">
                            <input class="span10" id="date_end" type="text" name="date_end"
                                   value="<?php echo $_REQUEST['date_end']; ?>"/>
                        </div>
                        <div class="span2">
                            <select id="user" name="user" class="span12">
                                <?php
                                if(is_app_admin($current_user)) {
                                    ?>
                                    <option value="">Wszyscy</option>
                                    <?php
                                    foreach($results_users as $user) {
                                        ?>
                                        <option
                                            value="<?php echo $user->id; ?>"<?php echo(isset($_POST['user']) && $user->id == $_POST['user'] ? ' selected="selected"' : '') ?>><?php echo $user->display_name; ?></option>
                                    <?php
                                    }
                                }
                                else {
                                    ?>
                                    <option
                                        value="<?php echo $current_user->ID; ?>"><?php echo $current_user->display_name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="span1">
                            <input id="pdf" type="checkbox" name="pdf"
                                   value="pdf"<?php echo(isset($_POST['pdf']) ? ' checked="checked"' : '') ?>/>Wygeneruj
                            PDF
                        </div>
                        <div class="span2">
                            <input type="submit" name="submit" value="Pokaż"/>
                        </div>
                    </div>
                </div>

        </div>
    </div>
<?php
if(isset($_POST['submit'])) {
    ?>
    <div class="row-fluid">
        <div class="span12">

<!--            <pre>--><?php
//
//
//
//                var_dump($_POST);
//                ?><!--</pre>-->
            <style>
                td {
                    padding: 5px;
                }
            </style>
            <table>
                <tr>
                    <th></th>
                    <th>Lotniczy</th>
                    <th>Morski</th>
                    <th>Drogowy</th>
                    <th>Razem</th>
                </tr>
                <tr>
                    <td>Liczba zleceń</td>
                    <td><?php echo isset($orders_l_count) ? $orders_l_count : '0'; ?></td>
                    <td><?php echo isset($orders_m_count) ? $orders_m_count : '0'; ?></td>
                    <td><?php echo isset($orders_d_count) ? $orders_d_count : ''; ?></td>
                    <td><?php echo isset($orders_count) ? $orders_count : '0'; ?></td>
                </tr>
                <tr>
                    <td>Sprzedaż brutto</td>
                    <td><?php echo number_format($przychod_lotniczy + $vat_lotniczy, 2, ',', ' '); ?></td>
                    <td><?php echo number_format($przychod_morski + $vat_morski, 2, ',', ' '); ?></td>
                    <td><?php echo number_format($przychod_drogowy + $vat_drogowy, 2, ',', ' '); ?></td>
                    <td><?php echo number_format($przychod_drogowy + $vat_drogowy + $przychod_morski + $vat_morski + $przychod_lotniczy + $vat_lotniczy, 2, ',', ' '); ?></td>
                </tr>
                <tr>
                    <td>Sprzedaż netto</td>
                    <td><?php echo number_format($przychod_lotniczy, 2, ',', ' '); ?></td>
                    <td><?php echo number_format($przychod_morski, 2, ',', ' '); ?></td>
                    <td><?php echo number_format($przychod_drogowy, 2, ',', ' '); ?></td>
                    <td><?php echo number_format($przychod_lotniczy + $przychod_morski + $przychod_drogowy, 2, ',', ' '); ?></td>
                </tr>
                <tr>
                    <td>VAT</td>
                    <td><?php echo number_format($vat_lotniczy, 2, ',', ' '); ?></td>
                    <td><?php echo number_format($vat_morski, 2, ',', ' '); ?></td>
                    <td><?php echo number_format($vat_drogowy, 2, ',', ' '); ?></td>
                    <td><?php echo number_format($vat_lotniczy + $vat_drogowy + $vat_morski, 2, ',', ' '); ?></td>
                </tr>
                <tr>
                    <td>Koszt</td>
                    <td><?php echo number_format($koszt_lotniczy, 2, ',', ' '); ?></td>
                    <td><?php echo number_format($koszt_morski, 2, ',', ' '); ?></td>
                    <td><?php echo number_format($koszt_drogowy, 2, ',', ' '); ?></td>
                    <td><?php echo number_format($koszt_lotniczy + $koszt_morski + $koszt_drogowy, 2, ',', ' '); ?></td>
                </tr>
                <tr>
                    <td>Zysk</td>
                    <td><?php echo number_format($przychod_lotniczy - $koszt_lotniczy, 2, ',', ' '); ?></td>
                    <td><?php echo number_format($przychod_morski - $koszt_morski, 2, ',', ' '); ?></td>
                    <td><?php echo number_format($przychod_drogowy - $koszt_drogowy, 2, ',', ' '); ?></td>
                    <td><?php echo number_format(($przychod_lotniczy - $koszt_lotniczy) + ($przychod_morski - $koszt_morski) + ($przychod_drogowy - $koszt_drogowy), 2, ',', ' '); ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <?php
            echo $tabela;
            ?>
        </div>
    </div>
    </form>
    <?php
}
