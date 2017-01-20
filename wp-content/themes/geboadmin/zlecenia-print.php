<?php if(!is_user_logged_in()) {
    auth_redirect();
}

$template_url = get_template_directory_uri();

global $wpdb, $current_user;

//$results_clients = $wpdb->get_results('SELECT short_name,id FROM wp_czm_clients ORDER BY 2 DESC', OBJECT);
//$results_orders = $wpdb->get_results('SELECT number,id FROM wp_czm_orders ORDER BY 2 DESC', OBJECT);
//$results_sub = $wpdb->get_results('SELECT name,id FROM wp_czm_subcontractor ORDER BY 2 DESC', OBJECT);

if(is_app_admin($current_user)) {
    $results = $wpdb->get_results('SELECT * FROM wp_czm_orders WHERE id='.$_REQUEST['id'], OBJECT);
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

    $results = $wpdb->get_results('SELECT * FROM wp_czm_orders WHERE id='.$_REQUEST['id'].' AND (id_user ='.$current_user->ID.' OR transport IN('.$where.'))', OBJECT);
}
//die('<pre>'.print_r($results,true).'</pre>');
if($results) {
$results = $results[0];

$results_nadawca = $wpdb->get_results('SELECT * FROM wp_czm_clients WHERE id='.$results->id_nadawca, OBJECT);
$results_odbiorca = $wpdb->get_results('SELECT * FROM wp_czm_clients WHERE id='.$results->id_odbiorca, OBJECT);
$results_platnik = $wpdb->get_results('SELECT * FROM wp_czm_clients WHERE id='.$results->id_platnik, OBJECT);

$results_user = $wpdb->get_results('SELECT * FROM wp_users  WHERE id='.$results->id_user, OBJECT);

$results_sub = $wpdb->get_results('SELECT id, subcontractor, f_number, value, currency, exchange FROM wp_czm_subcontractor_to_order  WHERE id_order='.$_REQUEST['id'], OBJECT);

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="<?php echo $template_url; ?>/css/print.css" id="link_theme"/>
    <title></title>
</head>
<body style="font-size: 14px">
<?php
//var_dump($results_order)
?>
<div id="page-container">
<table style="width: 740px; margin: 0 auto">
<tr>
    <td class="l noprint"><a href="javascript:window.print()">drukuj</a> | <a
            href="<?php echo bloginfo('url').'/zlecenia'; ?>">anuluj</a></td>
</tr>
<tr>
    <td style="padding: 15px 0 25px; font-size: 16px;" class="b c" colspan="2">
        Karta sprawy <?php echo $results->number.inicialy($results_user[0]->display_name); ?><br/>
    </td>
</tr>
<tr>
    <td style="font-size: 16px;" class="b c" colspan="2">
        <?php echo $results->transport.' / '.$results->typ; ?><br/>
    </td>
</tr>
<tr>
    <td colspan="2">
        <table style="width: 100%;">
            <tr>
                <td>
                    <span><b>Nadawca/Załadunek</b></span><br/>
                    <?php if($results_nadawca) { ?>
                        <span><?php echo $results_nadawca[0]->name; ?></span><br/>
                        <span><?php echo $results_nadawca[0]->address; ?></span><br/>
                        <span><?php echo $results_nadawca[0]->post_code.' '.$results_nadawca[0]->city.' '.$results_nadawca[0]->kraj; ?></span>
                        <span><?php echo $results_nadawca[0]->nip; ?></span><br/>
                        <br/>
                    <?php
                    }
                    else {
                        ?>
                        <span><?php echo $results->nadawca; ?></span><br/>
                    <?php } ?>
                </td>
                <td>
                    <span><b>Odbiorca/Rozładunek</b></span><br/>
                    <?php if($results_odbiorca) { ?>
                        <span><?php echo $results_odbiorca[0]->name; ?></span><br/>
                        <span><?php echo $results_odbiorca[0]->address; ?></span><br/>
                        <span><?php echo $results_odbiorca[0]->post_code.' '.$results_odbiorca[0]->city.' '.$results_odbiorca[0]->kraj; ?></span>
                        <br/>
                        <span><?php echo $results_odbiorca[0]->nip; ?></span><br/>
                        <br/>
                    <?php
                    }
                    else {
                        ?>
                        <span><?php echo $results->odbiorca; ?></span><br/>
                    <?php } ?>
                </td>
                <td>
                    <span><b>Płatnik</b></span><br/>
                    <?php if($results_platnik) { ?>
                        <span><?php echo $results_platnik[0]->name; ?></span><br/>
                        <span><?php echo $results_platnik[0]->address; ?></span><br/>
                        <span><?php echo $results_platnik[0]->post_code.' '.$results_platnik[0]->city.' '.$results_platnik[0]->kraj; ?></span>
                        <br/>
                        <span><?php echo $results_platnik[0]->nip; ?></span><br/>
                        <br/>
                    <?php
                    }
                    else {
                        ?>
                        <span><?php echo $results->platnik; ?></span><br/>
                    <?php } ?>
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td colspan="2">

        <div class="option">
            <span class="bl b">Icoterms:</span>
            <span class="bl"><?php echo $results->icoterms; ?></span>
        </div>
        <div class="option">
            <span class="bl b">Orgin:</span>
            <span class="bl"><?php echo $results->orgin; ?></span>
        </div>
        <div class="option">
            <span class="bl b">Destination:</span>
            <span class="bl"><?php echo $results->destination; ?></span>
        </div>
        <?php if($results->transport == 'Lotniczy') { ?>
            <div class="option">
                <span class="bl b">AWB:</span>
                <span class="bl"><?php echo $results->awb; ?></span>
            </div>
            <div class="option">
                <span class="bl b">HAWB:</span>
                <span class="bl"><?php echo $results->hawb; ?></span>
            </div>
        <?php } ?>
        <?php if($results->transport == 'Morski') { ?>
            <div class="option">
                <span class="bl b">BL:</span>
                <span class="bl"><?php echo $results->bl; ?></span>
            </div>
            <div class="option">
                <span class="bl b">HBL:</span>
                <span class="bl"><?php echo $results->hbl; ?></span>
            </div>
        <?php } ?>
        <div class="option">
            <span class="bl b">Ilość opakowań:</span>
            <span class="bl"><?php echo $results->quantity; ?></span>
        </div>
        <div class="option">
            <span class="bl b">Waga:</span>
            <span class="bl"><?php echo $results->waga_b; ?></span>
        </div>
        <div class="option">
            <span class="bl b">Waga płatna:</span>
            <span class="bl"><?php echo $results->waga_p; ?></span>
        </div>
        <div class="option">
            <span class="bl b">Kubatura:</span>
            <span class="bl"><?php echo $results->kub; ?></span>
        </div>
    </td>
</tr>
<tr>
    <td colspan="2">
        <table style="width: 100%;" cellspacing="0">
            <tr>
                <th>L.p.</th>
                <th>Podwykonawca</th>
                <th>Opis</th>
                <th>Wartość</th>
                <th>Waluta</th>
                <!--                <th>Kurs waluty</th>-->
                <th>Faktura</th>
            </tr>
            <?php
            $count = 0;
            $price = array();
            foreach($results_sub as $sub) {
                $faktury_zk = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id_subcontractor_to_order='.$sub->id.' AND typ=\'Zakup\'', OBJECT);


                switch ($faktury_zk[0]->currency) {
                    case 'pln':
                        $price[] = ($faktury_zk[0]->value / 10000);
                        break;
                    case 'usd':
                        $price[] = ($faktury_zk[0]->value / 10000) * ($faktury_zk[0]->usd / 10000);
                        break;
                    case 'eur':
                        $price[] = ($faktury_zk[0]->value / 10000) * ($faktury_zk[0]->eur / 10000);
                        break;
                    default:
                        $price[] = ($sub->value / 10000) * ($sub->exchange / 10000);
                }


                switch ($sub->currency) {
                    case 'pln':
                        ?>
                        <tr class="c">
                            <td><?php echo ++$count; ?></td>
                            <td><?php echo $sub->subcontractor; ?></td>
                            <td><?php echo $sub->f_number; ?></td>
                            <td><?php echo number_format($sub->value / 10000, 2, ',', ' '); ?></td>
                            <td><?php echo $sub->currency; ?></td>
                            <td><?php echo number_format(($faktury_zk[0]->value / 10000), 2, ',', ' ').' '.$faktury_zk[0]->currency.' ('.$faktury_zk[0]->numer.')'; ?></td>
                        </tr>
                        <?php
                        break;
                    case 'usd':
                        ?>
                        <tr class="c">
                            <td><?php echo ++$count; ?></td>
                            <td><?php echo $sub->subcontractor; ?></td>
                            <td><?php echo $sub->f_number; ?></td>
                            <td><?php echo number_format($sub->value / 10000, 2, ',', ' '); ?></td>
                            <td><?php echo $sub->currency; ?></td>
                            <td><?php echo number_format(($faktury_zk[0]->value / 10000), 2, ',', ' ').' '.$faktury_zk[0]->currency.' ('.$faktury_zk[0]->numer.')'; ?></td>
                        </tr>
                        <?php
                        break;
                    case 'eur':
                        ?>
                        <tr class="c">
                            <td><?php echo ++$count; ?></td>
                            <td><?php echo $sub->subcontractor; ?></td>
                            <td><?php echo $sub->f_number; ?></td>
                            <td><?php echo number_format($sub->value / 10000, 2, ',', ' '); ?></td>
                            <td><?php echo $sub->currency; ?></td>
                            <td><?php echo number_format(($faktury_zk[0]->value / 10000), 2, ',', ' ').' '.$faktury_zk[0]->currency.' ('.$faktury_zk[0]->numer.')'; ?></td>
                        </tr>
                        <?php
                        break;
                }?>

            <?php } ?>
            <tr class="c">
                <td><?php echo ''; ?></td>
                <td><?php echo ''; ?></td>
                <td class="bor"><?php echo 'Suma kosztów:'; ?></td>
                <td class="bor" colspan="2">
                    <?php
                    $koszt = array_sum($price);
                    echo number_format($koszt, 2, ',', ' '); ?></td>
                <td class="bor">pln</td>
            </tr>
            <?php
            $faktury_sp = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id_order='.$_REQUEST['id'].' AND typ=\'Sprzedaż\'', OBJECT);
            $noty       = $wpdb->get_results('SELECT * FROM wp_czm_noty WHERE id_order='.$_REQUEST['id'].' AND typ=\'Transportowa\'', OBJECT);
            $przy = array();
            foreach($faktury_sp as $sp) {
                switch ($sp->currency) {
                    case 'pln':
                        $exchnge  = 1;
                        $currency = 'pln';
                        break;
                    case 'usd':
                        $exchnge  = $sp->usd / 10000;
                        $currency = 'pln';
                        break;
                    case 'eur':
                        $exchnge  = $sp->eur / 10000;
                        $currency = 'pln';
                        break;
                }

                $przy[] = ($sp->value / 10000) * $exchnge;
                ?>

                <tr class="c">
                    <td><?php echo ++$count; ?></td>
                    <td colspan="2">Faktura <?php echo $sp->numer ?></td>
                    <td style="white-space: nowrap;"><?php echo number_format(($sp->value / 10000) * $exchnge, 2, ',', ' '); ?></td>
                    <td><?php echo $currency; ?></td>
                    <td></td>
                </tr>

            <?php
            }

            foreach($noty as $nota) {
                switch (strtolower($nota->currency)) {
                    case 'pln':
                        $exchnge  = 1;
                        $currency = 'pln';
                        break;
                    case 'usd':
                        $exchnge  = $nota->usd / 10000;
                        $currency = 'pln';
                        break;
                    case 'eur':
                        $exchnge  = $nota->eur / 10000;
                        $currency = 'pln';
                        break;
                }
                $przy[] = ($nota->value / 10000) * $exchnge;
                ?>

                <tr class="c">
                    <td><?php echo ++$count; ?></td>
                    <td colspan="2">Nota <?php echo $nota->typ.' '.$nota->number ?></td>
                    <td style="white-space: nowrap;"><?php echo number_format(($nota->value / 10000) * $exchnge, 2, ',', ' '); ?></td>
                    <td><?php echo $currency; ?></td>
                    <td></td>
                </tr>
            <?php
            }
            ?>

            <tr class="c">
                <td><?php echo ''; ?></td>
                <td><?php echo ''; ?></td>
                <td class="bor"><?php echo 'Przychód:'; ?></td>
                <td class="bor" colspan="2">
                    <?php
                    $przychod = array_sum($przy);
                    echo number_format($przychod, 2, ',', ' '); ?></td>
                <td class="bor">pln</td>
                <td></td>
            </tr>
            <tr class="c">
                <td><?php echo ''; ?></td>
                <td><?php echo ''; ?></td>
                <td class="bor"><?php echo 'Zysk:'; ?></td>
                <td class="bor b" colspan="2">
                    <?php echo number_format($przychod - $koszt, 2, ',', ' '); ?></td>
                <td class="bor">pln</td>
                <td></td>
            </tr>
        </table>

    </td>
</tr>
<tr>
    <td colspan="2" class="l">
        <div style="width: 100%;" class="b l option">Do Zrobienia</div>
        <div class="option">
            <span class="bl b">Wykonano:</span>
            <span class="bl"><?php echo $results->date_execute; ?></span>
        </div>
        <div class="option">
            <span class="bl b">TIMO COM:</span>
            <span class="bl"><?php echo(!empty($results->timocom) ? 'TAK' : 'NIE') ?></span>
        </div>
        <div class="option">
            <span class="bl b">WTRANSNET:</span>
            <span class="bl"><?php echo(!empty($results->wtransnet) ? 'TAK' : 'NIE') ?></span>
        </div>
        <div class="option">
            <span class="bl b">TELEROUTE:</span>
            <span class="bl"><?php echo(!empty($results->teleroute) ? 'TAK' : 'NIE') ?></span>
        </div>
        <div class="option">
            <span class="bl b">Odprawa celna:</span>
            <span class="bl"><?php echo(!empty($results->oc) ? 'TAK' : 'NIE') ?></span>
        </div>
        <div class="option">
            <span class="bl b">Faktura za usługę:</span>
            <span class="bl"><?php echo(!empty($results->fak) ? 'TAK' : 'NIE') ?></span>
        </div>
        <div class="option">
            <span class="bl b">ID:</span>
            <span class="bl"><?php echo $results->transid; ?></span>
        </div>
        <div class="option">
            <span class="bl b">Booking/ETA:</span>
            <span class="bl"><?php echo $results->eta; ?></span>
        </div>
        <div class="option">
            <span class="bl b">Booking/ETD:</span>
            <span class="bl"><?php echo $results->etd; ?></span>
        </div>
        <div class="option">
            <span class="bl b">Orginał CMR/<br/>Faktury otrzymano:</span>
            <span class="bl"><?php echo $results->fin; ?></span>
        </div>
        <div class="option">
            <span class="bl b">Orginał CMR/<br/>Faktury wysłano:</span>
            <span class="bl"><?php echo $results->fout; ?></span>
        </div>
        <div class="option">
            <span class="bl b">CMR</span>
            <span class="bl"><?php echo $results->cmr; ?></span>
        </div>
    </td>
</tr>
<tr>
    <td colspan="2" class="l">
        <div class="option">
            <span class="bl b">Uwagi:</span>
            <span class="bl"><?php echo nl2br($results->note); ?></span>
        </div>
    </td>
</tr>
</table>
</div>
</body>
</html>

<?php
} else {
    echo '<h3>Ta faktura została usunięta lub nie należy do Ciebie.</h3>';
}
?>
