<?php if(!is_user_logged_in()) {
    auth_redirect();
}
require_once dirname(__FILE__).'/../../../vendor/autoload.php';
use mikehaertl\wkhtmlto\Pdf;

$template_url = get_template_directory_uri();

global $wpdb, $current_user;

if(!is_user_logged_in()) {
    auth_redirect();
}


if(is_app_admin($current_user)) {
    $results = $wpdb->get_results('SELECT * FROM wp_czm_korekty WHERE id='.$_REQUEST['id'], OBJECT);
}
else {
    $results = $wpdb->get_results('SELECT * FROM wp_czm_korekty WHERE id='.$_REQUEST['id'].' AND id_user ='.$current_user->ID, OBJECT);

}

if($results) {
$results = $results[0];

$results_sub_korekta = $wpdb->get_results('SELECT * FROM wp_czm_korekty_items_to_invoices WHERE id_invoice='.$results->id, OBJECT);

$results_invoice = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id='.$results->id_invoice.' ORDER BY id DESC', OBJECT);
$results_invoice = $results_invoice['0'];

$results_sub_invoice = $wpdb->get_results('SELECT * FROM wp_czm_invoices_items_to_invoices WHERE id_invoice='.$results_invoice->id, OBJECT);
//var_dump($results_invoice);

$results_order = $wpdb->get_results('SELECT * FROM wp_czm_orders  WHERE id='.$results_invoice->id_order, OBJECT);
//$results_sub = $wpdb->get_results('SELECT * FROM wp_czm_invoices_items_to_invoices WHERE id_invoice='.$results->id, OBJECT);

$results_user = $wpdb->get_results('SELECT * FROM wp_users  WHERE id='.$results_order[0]->id_user, OBJECT);


$results_country = $wpdb->get_results('SELECT * FROM wp_czm_country WHERE iso_code=\''.$results_client[0]->kraj.'\'', OBJECT);

if(is_numeric($results_order[0]->id_nadawca)) {
    $results_order_nadawca = $wpdb->get_results('SELECT short_name FROM wp_czm_clients WHERE id='.$results_order[0]->id_nadawca, OBJECT);
}
if(is_numeric($results_order[0]->id_odbiorca)) {
    $results_order_odbiorca = $wpdb->get_results('SELECT short_name FROM wp_czm_clients WHERE id='.$results_order[0]->id_odbiorca, OBJECT);
}
ob_start();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="<?php echo $template_url; ?>/css/print.css" id="link_theme"/>
    <title></title>
</head>
<body>
<?php
//var_dump($results_order)
?>
<div id="page-container">
    <table style="width: 740px; margin: 0 auto; min-height: 100%;">
        <tbody>
        <tr>
            <td class="l noprint"><a href="javascript:window.print()">drukuj</a> | <a
                    href="<?php echo bloginfo('url').'/faktury'; ?>">anuluj</a></td>
        </tr>
        <tr>
            <td class="l"><img class="" alt="" src="<?php echo $template_url; ?>/img/logo.png" style="height: 80px;"/></td>
            <td class="r">
                <div class="b">SKY-MAR MAREK ZAWORSKI</div>
                <div class="b">17 Stycznia 39</div>
                <div class="b">02-148 Warszawa</div>
                <div class="b">NIP PL5641054535</div>
            </td>
        </tr>
        <tr>
            <td class="l">
                <div class="b">Konto:</div>
                <div class="b">Bank BZ WBK S.A.</div>
                <div class="">Ul. Rynek 9/11, 50-950 Wrocław</div>
                <div class="">SWIFT / BIG: WBKPPLPP</div>
                <div class="b">PLN: 81 1090 2590 0000 0001 3142 6943</div>
                <div class="b">EUR: PL 45 1090 2590 0000 0001 3142 7300</div>
                <div class="b">USD: PL 48 1090 2590 0000 0001 3142 8022</div>
            </td>
            <td class="l">
                <div class="">&nbsp;</div>
                <div class="b">Bank BGŻ BNP Paribas S.A.</div>
                <div class="">ul. Kasprzaka 10/16, 01-211 Warszawa</div>
                <div class="">SWIFT / BIG: PPABPLPK</div>
                <div class="b">PLN: 83 2030 0045 1110 0000 0219 3430</div>
                <div class="b">EUR: PL 25 2030 0045 3110 0000 0019 9870</div>
                <div class="b">USD: PL 04 2030 0045 3110 0000 0019 9860</div>
            </td>
        </tr>
        <tr>
            <td style="padding: 15px 0 25px; font-size: 14px; border-top: 1px solid #000000" class="b c" colspan="2">
                <?php echo($results->lang == 1 ? 'Invoice No' : 'FAKTURA VAT KOREKTA NR'); ?> <?php echo $results->numer; ?><br/>
                <?php echo($results->lang == 1 ? 'Invoice No' : 'faktura korygująca do FV nr'); ?> <?php echo $results_invoice->numer; ?> z dn. <?php echo substr($results->date_add, 0, 10) ?><br/>
                <?php echo($results->lang == 1 ? 'original / copy' : 'oryginał / kopia'); ?>
            </td>
        </tr>
        <tr>
            <td rowspan="3">
                <span><b><?php echo($results->lang == 1 ? 'Bill to' : 'Nabywca'); ?>:</b></span><br/>
                <span class="b"><?php echo $results->name; ?></span><br/>
                <span><?php echo $results->address; ?></span><br/>
                <span><?php echo $results->post_code.' '.$results->city; ?></span><br/>
                <span><b><?php echo($results->lang == 1 ? 'VAT No: ' : 'NIP: '); ?></b><?php echo $results->nip; ?></span><br/>
            </td>
            <td>
        <span
            class="bl b"><?php echo($results->lang == 1 ? 'Place and date of issue:' : 'Miejsce i data wystawienia:'); ?></span>
                <span class="bl">Warszawa, <?php echo substr($results->date_add, 0, 10) ?></span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="bl b"><?php echo($results->lang == 1 ? 'Date of Service:' : 'Data wykonania usługi:'); ?></span>
                <span class="bl"><?php echo $results_order[0]->date_execute; ?></span>
            </td>
        </tr>
        <!--<tr>-->
        <!--    <td>-->
        <!--        <span class="bl b">--><?php //echo($results->lang == 1 ? 'Reference:' : 'Nr zlecenia:'); ?><!--</span>-->
        <!--        <span class="bl">--><?php //echo $results_order[0]->number.inicialy($results_user[0]->display_name); ?><!--</span>-->
        <!--    </td>-->
        <!--</tr>-->
        <tr>
            <td>
                <p></p>

            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h4>Przed korektą</h4>
                <table class="c" cellspacing="0" style="width:100%;">
                    <thead>
                    <tr>
                        <td><?php echo($results->lang == 1 ? 'No.' : 'Lp.'); ?></td>
                        <td style="min-width: 250px;"><?php echo($results->lang == 1 ? 'Article' : 'Tytułem'); ?></td>
                        <td><?php echo($results->lang == 1 ? 'Quantity' : 'Ilość'); ?></td>
                        <td></td>
                        <td><?php echo($results->lang == 1 ? 'Unit price without tax' : 'Cena jednostkowa'); ?></td>
                        <td><?php echo($results->lang == 1 ? 'Price' : 'Wartość bez podatku'); ?></td>
                        <td><?php echo($results->lang == 1 ? 'Tax rate %' : 'Stawka VAT %'); ?></td>
                        <td><?php echo($results->lang == 1 ? 'Tax amount' : 'Wartość VAT'); ?></td>
                        <td><?php echo($results->lang == 1 ? 'Value' : 'Wartość z podatkiem'); ?></td>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count = 0;

                    $vat      = array();
                    $currency = '';
                    $np       = false;
                    foreach($results_sub_invoice as $sub) {
                        ?>
                        <tr>
                            <td><?php echo ++$count; ?></td>
                            <td class="l"><?php echo $sub->name; ?><?php //echo '<pre>'.print_r($sub,true).'</pre>'; ?></td>
                            <td><?php echo $sub->quantity; ?></td>
                            <td>szt.</td>
                            <td><?php echo number_format($sub->value / 10000, 2, ',', ' '); ?> <?php echo $sub->currency; ?></td>
                            <?php
                            if($results->currency == $sub->currency) {

                                $currency = $sub->currency;
                                ?>
                                <td><?php echo number_format(($sub->value / 10000) * $sub->quantity, 2, ',', ' '); ?> <?php echo $sub->currency; ?></td>
                                <td><?php echo(!is_numeric($sub->vat) ? $np = 'NP' : $sub->vat / 10000); ?></td>
                                <td>
                                    <?php
                                    if($results->currency == $results->vat_currency) {
                                        $vat[$sub->vat]['total'] += ($sub->value / 10000) * $sub->quantity;
                                        echo number_format(($sub->vat / 1000000) * ($sub->value / 10000) * $sub->quantity, 2, ',', ' ').' '.$sub->currency;
                                    }
                                    else {
                                        $exchange=1;
                                        switch ($sub->currency) {
                                            case 'pln':
                                                if($results->vat_currency == 'usd') {
                                                    $exchange = 1 / ($results_invoice->usd / 10000);

                                                }
                                                elseif($results->vat_currency == 'eur') {
                                                    $exchange = 1 / ($results_invoice->eur / 10000);
                                                }
                                                break;
                                            case 'usd':
                                                if($results->vat_currency == 'pln') {
                                                    $exchange =  ($results_invoice->usd / 10000);
                                                }
                                                elseif($results->vat_currency == 'eur') {
                                                    $exchange = ($results_invoice->eur / 10000) / ($results_invoice->usd / 10000);
                                                }
                                                break;
                                            case 'eur':
                                                if($results->vat_currency == 'usd') {
                                                    $exchange = ($results_invoice->usd / 10000) / ($results_invoice->eur / 10000);
                                                }
                                                elseif($results->vat_currency == 'pln') {
                                                    $exchange =  ($results_invoice->eur / 10000);
                                                }
                                                break;
                                        }
                                        $vat[$sub->vat]['total'] += ($sub->value / 10000) * $sub->quantity;

                                        echo number_format(($sub->vat / 1000000) * ($sub->value / 10000) * $sub->quantity * $exchange, 2, ',', ' ').' '.$results->vat_currency;

                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if($results->currency == $results->vat_currency) {
                                        echo number_format((($sub->vat / 1000000) * ($sub->value / 10000) * $sub->quantity) + (($sub->value / 10000) * $sub->quantity), 2, ',', ' ').' '.$sub->currency;
                                    }
                                    else {
                                        echo number_format((($sub->value / 10000) * $sub->quantity), 2, ',', ' ').' '.$sub->currency;

                                    }
                                    ?>
                                </td>
                                <?php
                            }
                            else {
                                $exchange = 1;
                                switch ($results->currency) {
                                    case 'pln':
                                        if($sub->currency == 'usd') {
                                            $exchange = $results_invoice->usd / 10000;
                                        }
                                        else {
                                            $exchange = $results_invoice->eur / 10000;
                                        }


                                        break;
                                    case 'usd':
                                        if($sub->currency == 'pln') {
                                            $exchange = 1 / ($results_invoice->usd / 10000);
                                        }
                                        else {
                                            $exchange = ($results_invoice->eur / 10000) / ($results_invoice->usd / 10000);
                                        }
                                        break;
                                    case 'eur':
                                        if($sub->currency == 'usd') {
                                            $exchange = ($results_invoice->usd / 10000) / ($results_invoice->eur / 10000);
                                        }
                                        else {
                                            $exchange = 1 / ($results_invoice->eur / 10000);
                                        }
                                        break;
                                }

                                //////////////////////////////
                                $exchange_vat = 1;
                                switch ($results->currency) {
                                    case 'pln':
                                        if($results->vat_currency == 'usd') {
                                            $exchange_vat = 1 / ($results_invoice->usd / 10000);
                                        }
                                        elseif($results->vat_currency == 'eur') {
                                            $exchange_vat = 1 / ($results_invoice->eur / 10000);
                                        }
                                        break;
                                    case 'usd':
                                        if($results->vat_currency == 'pln') {
                                            $exchange_vat = $results_invoice->usd / 10000;
                                        }
                                        elseif($results->vat_currency == 'eur') {
                                            $exchange_vat = ($results_invoice->eur / 10000) / ($results_invoice->usd / 10000);
                                        }
                                        break;
                                    case 'eur':
                                        if($results->vat_currency == 'usd') {
                                            $exchange_vat = ($results_invoice->usd / 10000) / ($results_invoice->eur / 10000);
                                        }
                                        elseif($results->vat_currency == 'pln') {
                                            $exchange_vat = $results_invoice->eur / 10000;
                                        }
                                        break;
                                }
                                ////////////////////////////////////////////////////////////////
                                $vat[$sub->vat]['total'] += ($sub->value / 10000) * $sub->quantity * $exchange;
                                $currency = $results->currency;

                                ?>
                                <td><?php echo number_format(($sub->value / 10000) * $sub->quantity * $exchange, 2, ',', ' '); ?> <?php echo $results->currency; ?></td>
                                <td><?php echo (!is_numeric($sub->vat) ? $np = 'NP' : ($sub->vat / 10000)); ?></td>
                                <td><?php echo number_format(($sub->vat / 1000000) * ($sub->value / 10000) * $sub->quantity * $exchange * $exchange_vat, 2, ',', ' ').' '.$results->vat_currency; ?></td>
                                <td>
                                    <?php
                                    if($results->currency == $results->vat_currency) {
                                        echo number_format(((($sub->vat / 1000000) * ($sub->value / 10000) * $sub->quantity) + (($sub->value / 10000) * $sub->quantity)) * $exchange, 2, ',', ' ').' '.$results->currency;
                                    }else{
                                        echo number_format(((($sub->value / 10000) * $sub->quantity)) * $exchange, 2, ',', ' ').' '.$results->currency;

                                    }
                                    ?>
                                </td>
                                <?php

                            }
                            ?>
                        </tr>
                    <?php } ?>

                    <tr>
                        <td class="b r" colspan="5"
                            style="padding: 12px;"><?php echo($results->lang == 1 ? 'According to VAT rate' : 'Ogółem wg stawek VAT:'); ?></td>
                        <td colspan="4"></td>
                    </tr>
                    <?php
                    $total       = 0;
                    $total_netto = 0;
                    $total_vat   = 0;
                    foreach($vat as $s => $v) {
                        $total += $v['total'] * (($s / 1000000) + 1);
                        $total_netto += $v['total'];
                        $total_vat += $v['total'] * ($s / 1000000);



                        ?>
                        <tr>
                            <td class="b r" colspan="5"></td>
                            <td class="vb"><?php echo number_format($v['total'], 2, ',', ' '); ?></td>
                            <td class="vb"><?php echo(!is_numeric($s) ? 'NP' : $s / 10000); ?></td>
                            <td class="vb">
                                <?php

                                $exchange_vat = 1;
                                switch ($results->currency) {
                                    case 'pln':
                                        if($results->vat_currency == 'usd') {
                                            $exchange_vat = 1 / ($results->usd / 10000);
                                        }
                                        elseif($results->vat_currency == 'eur') {
                                            $exchange_vat = 1 / ($results->eur / 10000);
                                        }
                                        break;
                                    case 'usd':
                                        if($results->vat_currency == 'pln') {
                                            $exchange_vat = $results->usd / 10000;
                                        }
                                        elseif($results->vat_currency == 'eur') {
                                            $exchange_vat = ($results->eur / 10000) / ($results->usd / 10000);
                                        }
                                        break;
                                    case 'eur':
                                        if($results->vat_currency == 'usd') {
                                            $exchange_vat = ($results->usd / 10000) / ($results->eur / 10000);
                                        }
                                        elseif($results->vat_currency == 'pln') {
                                            $exchange_vat = $results->eur / 10000;
                                        }
                                        break;
                                }

                                echo number_format($v['total'] * ($s / 1000000)*$exchange_vat, 2, ',', ' ');
                                ?>
                            </td>
                            <td class="vb">
                                <?php
                                if($results->currency == $results->vat_currency) {
                                    echo number_format($v['total'] * (($s / 1000000) + 1), 2, ',', ' ');
                                }else{
                                    echo number_format($v['total'] , 2, ',', ' ');
                                }
                                ?>
                            </td>
                        </tr>

                        <?php
                    }
                    ?>

                    <tr>
                        <td class="b r" colspan="5"><?php echo($results->lang == 1 ? 'TOTAL' : 'RAZEM'); ?></td>
                        <td class="b bt"><?php echo number_format($total_netto, 2, ',', ' '); ?></td>
                        <td class="b bt"></td>
                        <td class="b bt"><?php echo number_format($total_vat*$exchange_vat, 2, ',', ' '); ?></td>
                        <td class="b bt">
                            <?php
                            if($results->currency == $results->vat_currency) {
                                echo number_format($total, 2, ',', ' ');
                                $old_total = $total;
                            }else{
                                echo number_format($total_netto, 2, ',', ' ');
                                $old_total = $total_netto;
                            }
                            ?>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <h4>Po korekcie</h4>

                <table class="c" cellspacing="0" style="width:100%;">
                    <thead>
                    <tr>
                        <td><?php echo($results->lang == 1 ? 'No.' : 'Lp.'); ?></td>
                        <td style="min-width: 250px;"><?php echo($results->lang == 1 ? 'Article' : 'Tytułem'); ?></td>
                        <td><?php echo($results->lang == 1 ? 'Quantity' : 'Ilość'); ?></td>
                        <td></td>
                        <td><?php echo($results->lang == 1 ? 'Unit price without tax' : 'Cena jednostkowa'); ?></td>
                        <td><?php echo($results->lang == 1 ? 'Price' : 'Wartość bez podatku'); ?></td>
                        <td><?php echo($results->lang == 1 ? 'Tax rate %' : 'Stawka VAT %'); ?></td>
                        <td><?php echo($results->lang == 1 ? 'Tax amount' : 'Wartość VAT'); ?></td>
                        <td><?php echo($results->lang == 1 ? 'Value' : 'Wartość z podatkiem'); ?></td>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count = 0;

                    $vat      = array();
                    $currency = '';
                    $np       = false;
                    foreach($results_sub_korekta as $sub) {
                        ?>
                        <tr>
                            <td><?php echo ++$count; ?></td>
                            <td class="l"><?php echo $sub->name; ?><?php //echo '<pre>'.print_r($sub,true).'</pre>'; ?></td>
                            <td><?php echo $sub->quantity; ?></td>
                            <td>szt.</td>
                            <td><?php echo number_format($sub->value / 10000, 2, ',', ' '); ?> <?php echo $sub->currency; ?></td>
                            <?php
                            if($results->currency == $sub->currency) {

                                $currency = $sub->currency;
                                ?>
                                <td><?php echo number_format(($sub->value / 10000) * $sub->quantity, 2, ',', ' '); ?> <?php echo $sub->currency; ?></td>
                                <td><?php echo(!is_numeric($sub->vat) ? $np = 'NP' : $sub->vat / 10000); ?></td>
                                <td>
                                    <?php
                                    if($results->currency == $results->vat_currency) {
                                        $vat[$sub->vat]['total'] += ($sub->value / 10000) * $sub->quantity;
                                        echo number_format(($sub->vat / 1000000) * ($sub->value / 10000) * $sub->quantity, 2, ',', ' ').' '.$sub->currency;
                                    }
                                    else {
                                        $exchange=1;
                                        switch ($sub->currency) {
                                            case 'pln':
                                                if($results->vat_currency == 'usd') {
                                                    $exchange = 1 / ($results->usd / 10000);

                                                }
                                                elseif($results->vat_currency == 'eur') {
                                                    $exchange = 1 / ($results->eur / 10000);
                                                }
                                                break;
                                            case 'usd':
                                                if($results->vat_currency == 'pln') {
                                                    $exchange =  ($results->usd / 10000);
                                                }
                                                elseif($results->vat_currency == 'eur') {
                                                    $exchange = ($results->eur / 10000) / ($results->usd / 10000);
                                                }
                                                break;
                                            case 'eur':
                                                if($results->vat_currency == 'usd') {
                                                    $exchange = ($results->usd / 10000) / ($results->eur / 10000);
                                                }
                                                elseif($results->vat_currency == 'pln') {
                                                    $exchange =  ($results->eur / 10000);
                                                }
                                                break;
                                        }
                                        $vat[$sub->vat]['total'] += ($sub->value / 10000) * $sub->quantity;

                                        echo number_format(($sub->vat / 1000000) * ($sub->value / 10000) * $sub->quantity * $exchange, 2, ',', ' ').' '.$results->vat_currency;

                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if($results->currency == $results->vat_currency) {
                                        echo number_format((($sub->vat / 1000000) * ($sub->value / 10000) * $sub->quantity) + (($sub->value / 10000) * $sub->quantity), 2, ',', ' ').' '.$sub->currency;
                                    }
                                    else {
                                        echo number_format((($sub->value / 10000) * $sub->quantity), 2, ',', ' ').' '.$sub->currency;

                                    }
                                    ?>
                                </td>
                                <?php
                            }
                            else {
                                $exchange = 1;
                                switch ($results->currency) {
                                    case 'pln':
                                        if($sub->currency == 'usd') {
                                            $exchange = $results->usd / 10000;
                                        }
                                        else {
                                            $exchange = $results->eur / 10000;
                                        }


                                        break;
                                    case 'usd':
                                        if($sub->currency == 'pln') {
                                            $exchange = 1 / ($results->usd / 10000);
                                        }
                                        else {
                                            $exchange = ($results->eur / 10000) / ($results->usd / 10000);
                                        }
                                        break;
                                    case 'eur':
                                        if($sub->currency == 'usd') {
                                            $exchange = ($results->usd / 10000) / ($results->eur / 10000);
                                        }
                                        else {
                                            $exchange = 1 / ($results->eur / 10000);
                                        }
                                        break;
                                }

                                //////////////////////////////
                                $exchange_vat = 1;
                                switch ($results->currency) {
                                    case 'pln':
                                        if($results->vat_currency == 'usd') {
                                            $exchange_vat = 1 / ($results->usd / 10000);
                                        }
                                        elseif($results->vat_currency == 'eur') {
                                            $exchange_vat = 1 / ($results->eur / 10000);
                                        }
                                        break;
                                    case 'usd':
                                        if($results->vat_currency == 'pln') {
                                            $exchange_vat = $results->usd / 10000;
                                        }
                                        elseif($results->vat_currency == 'eur') {
                                            $exchange_vat = ($results->eur / 10000) / ($results->usd / 10000);
                                        }
                                        break;
                                    case 'eur':
                                        if($results->vat_currency == 'usd') {
                                            $exchange_vat = ($results->usd / 10000) / ($results->eur / 10000);
                                        }
                                        elseif($results->vat_currency == 'pln') {
                                            $exchange_vat = $results->eur / 10000;
                                        }
                                        break;
                                }
                                ////////////////////////////////////////////////////////////////
                                $vat[$sub->vat]['total'] += ($sub->value / 10000) * $sub->quantity * $exchange;
                                $currency = $results->currency;

                                ?>
                                <td><?php echo number_format(($sub->value / 10000) * $sub->quantity * $exchange, 2, ',', ' '); ?> <?php echo $results->currency; ?></td>
                                <td><?php echo (!is_numeric($sub->vat) ? $np = 'NP' : ($sub->vat / 10000)); ?></td>
                                <td><?php echo number_format(($sub->vat / 1000000) * ($sub->value / 10000) * $sub->quantity * $exchange * $exchange_vat, 2, ',', ' ').' '.$results->vat_currency; ?></td>
                                <td>
                                    <?php
                                    if($results->currency == $results->vat_currency) {
                                        echo number_format(((($sub->vat / 1000000) * ($sub->value / 10000) * $sub->quantity) + (($sub->value / 10000) * $sub->quantity)) * $exchange, 2, ',', ' ').' '.$results->currency;
                                    }else{
                                        echo number_format(((($sub->value / 10000) * $sub->quantity)) * $exchange, 2, ',', ' ').' '.$results->currency;

                                    }
                                    ?>
                                </td>
                                <?php

                            }
                            ?>
                        </tr>
                    <?php } ?>

                    <tr>
                        <td class="b r" colspan="5"
                            style="padding: 12px;"><?php echo($results->lang == 1 ? 'According to VAT rate' : 'Ogółem wg stawek VAT:'); ?></td>
                        <td colspan="4"></td>
                    </tr>
                    <?php
                    $total       = 0;
                    $total_netto = 0;
                    $total_vat   = 0;
                    foreach($vat as $s => $v) {
                        $total += $v['total'] * (($s / 1000000) + 1);
                        $total_netto += $v['total'];
                        $total_vat += $v['total'] * ($s / 1000000);



                        ?>
                        <tr>
                            <td class="b r" colspan="5"></td>
                            <td class="vb"><?php echo number_format($v['total'], 2, ',', ' '); ?></td>
                            <td class="vb"><?php echo(!is_numeric($s) ? 'NP' : $s / 10000); ?></td>
                            <td class="vb">
                                <?php

                                $exchange_vat = 1;
                                switch ($results->currency) {
                                    case 'pln':
                                        if($results->vat_currency == 'usd') {
                                            $exchange_vat = 1 / ($results->usd / 10000);
                                        }
                                        elseif($results->vat_currency == 'eur') {
                                            $exchange_vat = 1 / ($results->eur / 10000);
                                        }
                                        break;
                                    case 'usd':
                                        if($results->vat_currency == 'pln') {
                                            $exchange_vat = $results->usd / 10000;
                                        }
                                        elseif($results->vat_currency == 'eur') {
                                            $exchange_vat = ($results->eur / 10000) / ($results->usd / 10000);
                                        }
                                        break;
                                    case 'eur':
                                        if($results->vat_currency == 'usd') {
                                            $exchange_vat = ($results->usd / 10000) / ($results->eur / 10000);
                                        }
                                        elseif($results->vat_currency == 'pln') {
                                            $exchange_vat = $results->eur / 10000;
                                        }
                                        break;
                                }

                                echo number_format($v['total'] * ($s / 1000000)*$exchange_vat, 2, ',', ' ');
                                ?>
                            </td>
                            <td class="vb">
                                <?php
                                if($results->currency == $results->vat_currency) {
                                    echo number_format($v['total'] * (($s / 1000000) + 1), 2, ',', ' ');
                                }else{
                                    echo number_format($v['total'] , 2, ',', ' ');
                                }
                                ?>
                            </td>
                        </tr>

                        <?php
                    }
                    ?>

                    <tr>
                        <td class="b r" colspan="5"><?php echo($results->lang == 1 ? 'TOTAL' : 'RAZEM'); ?></td>
                        <td class="b bt"><?php echo number_format($total_netto, 2, ',', ' '); ?></td>
                        <td class="b bt"></td>
                        <td class="b bt"><?php echo number_format($total_vat*$exchange_vat, 2, ',', ' '); ?></td>
                        <td class="b bt">
                            <?php
                            if($results->currency == $results->vat_currency) {
                                echo number_format($total, 2, ',', ' ');
                                $new_total = $total;
                            }else{
                                echo number_format($total_netto, 2, ',', ' ');
                                $new_total = $total_netto;
                            }
                            ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <h4>Przyczyna korekty</h4>
                <p><?php echo $results->reason;?></p>
            </td>
        </tr>
        <tr style="page-break-inside: avoid;">
            <td style="page-break-inside: avoid;" colspan="2">
                <table>
                    <tr>
                        <td class="r"><?php echo($results->lang == 1 ? 'Total to pay' : 'Do zapłaty'); ?>:</td>
                        <td class="l b">
                            <?php
                            echo number_format($new_total, 2, ',', ' ').' '.$currency;
                            //                    echo number_format($new_total-$old_total, 2, ',', ' ').' '.$currency;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="r"><?php echo($results->lang == 1 ? 'Say' : 'Słownie'); ?>:</td>
                        <td class="l b"><?php echo ($results->lang == 1 ? slownie_en((int)$total, 'en') : slownie((int)$total)).' '.$currency.' '.number_format(($total - (int)$total) * 100, 0, '', ''); ?>
                            /100
                        </td>
                    </tr>
                    <tr>
                        <td class="r"><?php echo($results->lang == 1 ? 'Payment till' : 'Termin płatności'); ?>:</td>
                        <td class="l b"><?php echo $results->date_payment; ?></td>
                    </tr>
                    <tr>
                        <td class="r"><?php echo($results->lang == 1 ? 'Number of pcs' : 'Ilość opakowań'); ?>:</td>
                        <td class="l b"><?php echo $results_order[0]->quantity; ?></td>
                    </tr>
                    <tr>
                        <td class="r"><?php echo($results->lang == 1 ? 'Sender/Receiver' : 'Nadawca/Odbiorca'); ?>:</td>
                        <td class="l b"><?php echo(!empty($results_order_nadawca) ? $results_order_nadawca[0]->short_name : $results_order[0]->nadawca) ?>
                            / <?php echo(!empty($results_order_odbiorca) ? $results_order_odbiorca[0]->short_name : $results_order[0]->odbiorca); ?></td>
                    </tr>
                    <tr>
                        <td class="r"><?php echo($results->lang == 1 ? 'Port of destination' : 'Port wyładunku'); ?></td>
                        <td class="l b"><?php echo $results_order[0]->destination; ?></td>
                    </tr>
                    <tr>
                        <td class="r"><?php echo($results->lang == 1 ? 'Port of loading' : 'Port nadania'); ?></td>
                        <td class="l b"><?php echo $results_order[0]->orgin; ?></td>
                    </tr>
                    <?php if(!empty($results_order[0]->icoterms)) { ?>
                        <tr>
                            <td class="r">Icoterms</td>
                            <td class="l b"><?php echo $results_order[0]->icoterms; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if(!empty($results_order[0]->awb)) { ?>
                        <tr>
                            <td class="r">AWB</td>
                            <td class="l b"><?php echo $results_order[0]->awb; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if(!empty($results_order[0]->hawb)) { ?>
                        <tr>
                            <td class="r">HAWB</td>
                            <td class="l b"><?php echo $results_order[0]->hawb; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if(!empty($results_order[0]->bl)) { ?>
                        <tr>
                            <td class="r">B/L</td>
                            <td class="l b"><?php echo $results_order[0]->bl; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if(!empty($results_order[0]->hbl)) { ?>
                        <tr>
                            <td class="r">HB/L</td>
                            <td class="l b"><?php echo $results_order[0]->hbl; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if(!empty($results_order[0]->eta)) { ?>
                        <tr>
                            <td class="r">ETA</td>
                            <td class="l b"><?php echo $results_order[0]->eta; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if(!empty($results_order[0]->etd)) { ?>
                        <tr>
                            <td class="r">ETD</td>
                            <td class="l b"><?php echo $results_order[0]->etd; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if(!empty($results_order[0]->waga_b)) { ?>
                        <tr>
                            <td class="r"><?php echo($results->lang == 1 ? 'Gross weight' : 'Waga brutto'); ?></td>
                            <td class="l b"><?php echo $results_order[0]->waga_b; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if(!empty($results_order[0]->waga_p)) { ?>
                        <tr>
                            <td class="r"><?php echo($results->lang == 1 ? 'Chargable weight' : 'Waga płatna'); ?></td>
                            <td class="l b"><?php echo $results_order[0]->waga_p; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if(!empty($results_order[0]->kub)) { ?>
                        <tr>
                            <td class="r"><?php echo($results->lang == 1 ? 'CBM' : 'Kubatura'); ?></td>
                            <td class="l b"><?php echo $results_order[0]->kub; ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="r"><?php echo($results->lang == 1 ? 'Notes' : 'Uwagi'); ?></td>
                        <td class="l b">
                            <?php
                            if($np == 'NP') {
                                echo 'NP - Reversal Charge <br/>';
                            }
                            echo nl2br($results->note); ?>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%">
                    <tr>
                        <td style="padding: 35px 0 0" class="c f8"><?php echo $current_user->display_name; ?></td>
                        <td class="c f8"></td>
                    </tr>
                    <tr>
                        <td class="c f8">...............................................................................</td>
                        <td class="c f8">...............................................................................</td>
                    </tr>
                    <tr>
                        <td class="c f8"><?php echo($results->lang == 1 ? 'Signature' : 'Osoba upoważniona do wystawienia faktury'); ?></td>
                        <td class="c f8"><?php echo($results->lang == 1 ? 'Received by' : 'Osoba upoważniona do odbioru faktury'); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 0 0 20px;"></td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td class="c bt f8 print" colspan="2">Działamy w oparciu o Ogólne Polskie Warunki Spedycyjne dostępne na stronie
                www.pisil.pl
            </td>
        </tr>
        </tfoot>
    </table>
</body>
</html>

<?php

$html = ob_get_clean();
/////////////////////////
//echo $html;
//file_put_contents('strona.html',$html);
    // You can pass a filename, a HTML string or an URL to the constructor
    $pdf = new Pdf($html);
    $pdf->binary='wkhtmltox/bin/wkhtmltopdf';

    $pdf->send();
//////////////////////////////
} else {
    echo '<h3>Ta faktura została usunięta lub nie należy do Ciebie.</h3>';
}
?>



