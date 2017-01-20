<?php
require_once dirname(__FILE__).'/../../../vendor/autoload.php';
use mikehaertl\wkhtmlto\Pdf;

global $wpdb, $current_user;

$template_url = get_template_directory_uri();

if (!is_user_logged_in()) {
    auth_redirect();
}

//if (!is_app_admin($current_user)) {
//    $_REQUEST['user'] = $current_user->ID;
//}
$results_clients = $wpdb->get_results('SELECT name,id FROM wp_czm_clients', OBJECT);
$results_users   = $wpdb->get_results('SELECT display_name,id FROM wp_users', OBJECT);

if (isset($_POST['submit'])) {
    $where = '';
    if (validateDate($_REQUEST['date_start']) && validateDate($_REQUEST['date_end'])) {
        $where .= 'AND `date_add` BETWEEN \''.$_REQUEST['date_start'].'\' AND \''.$_REQUEST['date_end'].'\' ';
    }
    if (!empty($_REQUEST['user'])) {
        $where .= 'AND id_user ='.$_REQUEST['user'].' ';
    }
    if (!empty($_REQUEST['client'])) {
        $where .= 'AND id_client ='.$_REQUEST['client'].' ';
    }

    $where2 = '';
    if (!empty($_REQUEST['paid']) || !empty($_REQUEST['notpaid']) || !empty($_REQUEST['overdue'])) {
        if (!empty($_REQUEST['paid'])) {
            if (empty($where2)) {
                $where2 .= 'AND (DATEDIFF(now(),date_paid) is not null ';
            } else{
                $where2 .= 'OR DATEDIFF(now(),date_paid) is not null ';
            }
        }

        if (!empty($_REQUEST['notpaid'])) {
            if (empty($where2)) {
                $where2 .= 'AND ((DATEDIFF(now(),date_paid) is null AND DATEDIFF(now(),date_payment) <=0) ';
            } else{
                $where2 .= 'OR (DATEDIFF(now(),date_paid) is null AND DATEDIFF(now(),date_payment) <=0) ';
            }
        }

        if (!empty($_REQUEST['overdue'])) {
            if (empty($where2)) {
                $where2 .= 'AND ((DATEDIFF(now(),date_paid) is null AND DATEDIFF(now(),date_payment) >=0) ';
            } else{
                $where2 .= 'OR (DATEDIFF(now(),date_paid) is null AND DATEDIFF(now(),date_payment) >=0) ';
            }
        }

        $where2 .= ') ';
    }


    $sql_invoices = 'SELECT *,DATEDIFF(now(),date_paid) as \'is_paid\',
                      CASE WHEN DATEDIFF(now(),date_paid) IS NOT NULL THEN \'Zapłacono\'
                            ELSE DATEDIFF(now(),date_payment) 
                      END as \'termin\'
                     FROM wp_czm_invoices
                     WHERE typ=\'Sprzedaż\' 
                     AND id > 81 '.$where.$where2;

    $results_invoices = $wpdb->get_results($sql_invoices, OBJECT);

    $sql_notes = 'SELECT *,DATEDIFF(now(),date_paid) as \'is_paid\',DATEDIFF(now(),date_payment) as \'termin\'
                      FROM wp_czm_noty
                      WHERE DATEDIFF(now(),date_paid) is null '.$where;

    $results_notes = $wpdb->get_results($sql_notes, OBJECT);

    $results_clients = $wpdb->get_results('SELECT name FROM wp_czm_clients WHERE id='.$_POST['client'], OBJECT);
    $results_users = $wpdb->get_results('SELECT kraj,display_name FROM wp_users WHERE id='.$_POST['user'], OBJECT);


    ?>
    <?php ob_start(); ?>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/print.css"
              id="link_theme"/>
        <title></title>
    </head>
    <body>
    <style>
        body, table, td {
            font-family: tahoma;
            font-size: 10px;
        }

        td {
            padding: 2px;
        }
    </style>
    <style>
        td {
            padding: 5px;
        }
    </style>
    <table style="width: 740px; margin: 0 auto; min-height: 100%;">

        <tr>
            <td class="l"><img class="" alt="" src="<?php echo $template_url; ?>/img/logo.png" style="height: 80px;"/>
            </td>
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
                <?php echo($_POST['lang'] == 1 ? 'Report' : 'Raport'); ?>
                <br/>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px 0 20px; font-size: 12px;" class="b c">
                <?php echo($_POST['lang'] == 1 ? 'For customer:' : 'Dla klienta:').' '.(isset($results_clients[0]->name)?$results_clients[0]->name:($_POST['lang'] == 1 ? 'All' : 'Wszyscy')); ?>
            </td>
            <td style="padding: 10px 0 20px; font-size: 12px;" class="b c">
                <?php echo($_POST['lang'] == 1 ? 'Issued by:' : 'Wystawione przez:').' '.(isset($results_users[0]->display_name)?$results_users[0]->display_name:($_POST['lang'] == 1 ? 'All' : 'Wszyscy')); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table>
                    <?php if($_POST['lang'] ==1){?>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Number</th>
                        <th>Customer</th>
                        <th>Order No.</th>
                        <th>Payment till</th>
                        <th>Price</th>
                        <th>Tax amount </th>
                        <th>Value</th>
                        <th>Overdue</th>
                    </tr>
<!--                    <tr>-->
<!--                        <th colspan="9">Zaległe faktury</th>-->
<!--                    </tr>-->
        <?php }else{?>
                        <tr>
                            <th>ID</th>
                            <th>Dokument</th>
                            <th>Numer</th>
                            <th>Klient</th>
                            <th>Numer zlecenia</th>
                            <th>Termin płatności</th>
                            <th>Netto</th>
                            <th>VAT</th>
                            <th>Brutto</th>
                            <th>Po terminie</th>
                        </tr>
<!--                        <tr>-->
<!--                            <th colspan="9">Zaległe faktury</th>-->
<!--                        </tr>-->
    <?php }?>
                    <?php
                    $invoice_sum = array();
                    $nota_sum    = array();

                    foreach($results_invoices as $invoice) {
                        $results_orders  = $wpdb->get_results('SELECT number FROM wp_czm_orders WHERE id='.$invoice->id_order, OBJECT);
                        $results_clients = $wpdb->get_results('SELECT kraj,short_name FROM wp_czm_clients WHERE id='.$invoice->id_client, OBJECT);

                        if ($results_clients[0]->kraj == 'PL') {

                            switch ($invoice->currency) {
                                case 'pln':
                                    $invoice_sum['PLN']['netto'][$invoice->numer] = $invoice->value / 10000;
                                    $waluta                                       = 'PLN';
                                    break;
                                case 'eur':
                                    $invoice_sum['PLN']['netto'][$invoice->numer] = ($invoice->value / 10000) * $invoice->eur / 10000;
                                    $waluta                                       = 'PLN';
                                    break;
                                case 'usd':
                                    $invoice_sum['PLN']['netto'][$invoice->numer] = ($invoice->value / 10000) * $invoice->usd / 10000;
                                    $waluta                                       = 'PLN';
                                    break;
                            }
                            if ($invoice->vat > 0) {
                                switch ($invoice->vat_currency) {
                                    case 'pln':
                                        $invoice_sum['PLN']['vat'][$invoice->numer] = $invoice->vat / 10000;
                                        $waluta                                     = 'PLN';
                                        break;
                                    case 'eur':
                                        $invoice_sum['PLN']['vat'][$invoice->numer] = ($invoice->vat / 10000) * $invoice->eur / 10000;
                                        $waluta                                     = 'PLN';
                                        break;
                                    case 'usd':
                                        $invoice_sum['PLN']['vat'][$invoice->numer] = ($invoice->vat / 10000) * $invoice->usd / 10000;
                                        $waluta                                     = 'PLN';
                                        break;
                                }
                            } else{
                                $invoice_sum['PLN']['vat'][$invoice->numer] = 0;
                            }
                        } else{
                            switch ($invoice->currency) {
                                case 'pln':
                                    $invoice_sum['PLN']['netto'][$invoice->numer] = $invoice->value / 10000;
                                    $waluta                                       = 'PLN';
                                    break;
                                case 'eur':
                                    $invoice_sum['EUR']['netto'][$invoice->numer] = $invoice->value / 10000;
                                    $waluta                                       = 'EUR';
                                    break;
                                case 'usd':
                                    $invoice_sum['USD']['netto'][$invoice->numer] = $invoice->value / 10000;
                                    $waluta                                       = 'USD';
                                    break;
                            }
                            if ($invoice->vat > 0) {
                                switch ($invoice->vat_currency) {
                                    case 'pln':
                                        $invoice_sum['PLN']['vat'][$invoice->numer] = $invoice->vat / 10000;
                                        $waluta                                     = 'PLN';
                                        break;
                                    case 'eur':
                                        $invoice_sum['EUR']['vat'][$invoice->numer] = $invoice->vat / 10000;
                                        $waluta                                     = 'EUR';
                                        break;
                                    case 'usd':
                                        $invoice_sum['USD']['vat'][$invoice->numer] = $invoice->vat / 10000;
                                        $waluta                                     = 'USD';
                                        break;
                                }
                            } else{
                                $invoice_sum['PLN']['vat'][$invoice->numer] = 0;

                            }
                        } ?>
                        <tr>
                            <td><?php echo $invoice->id; ?></td>
                            <td><?php echo($_POST['lang'] == 1 ? 'Invoice' : 'Faktura')?></td>
                            <td><?php echo $invoice->numer; ?></td>
                            <td><?php echo $results_clients[0]->short_name; ?></td>
                            <td><?php echo $results_orders[0]->number; ?></td>
                            <td><?php echo $invoice->date_payment; ?></td>
                            <?php switch ($waluta) {
                                case 'PLN':
                                    ?>
                                    <td><?php echo number_format($invoice_sum['PLN']['netto'][$invoice->numer], 2, ',', ' '); ?>
                                        PLN
                                    </td>
                                    <td><?php echo number_format($invoice_sum['PLN']['vat'][$invoice->numer], 2, ',', ' '); ?>
                                        PLN
                                    </td>
                                    <td><?php echo number_format($invoice_sum['PLN']['netto'][$invoice->numer] + $invoice_sum['PLN']['vat'][$invoice->numer], 2, ',', ' '); ?>
                                        PLN
                                    </td>
                                    <?php
                                    break;
                                case 'EUR':
                                    ?>
                                    <td><?php echo number_format($invoice_sum['EUR']['netto'][$invoice->numer], 2, ',', ' '); ?>
                                        EUR
                                    </td>
                                    <td><?php echo number_format($invoice_sum['EUR']['vat'][$invoice->numer], 2, ',', ' '); ?>
                                        EUR
                                    </td>
                                    <td><?php echo number_format($invoice_sum['EUR']['netto'][$invoice->numer] + $invoice_sum['EUR']['vat'][$invoice->numer], 2, ',', ' '); ?>
                                        EUR
                                    </td>
                                    <?php
                                    break;
                                case 'USD':
                                    ?>
                                    <td><?php echo number_format($invoice_sum['USD']['netto'][$invoice->numer], 2, ',', ' '); ?>
                                        USD
                                    </td>
                                    <td><?php echo number_format($invoice_sum['USD']['vat'][$invoice->numer], 2, ',', ' '); ?>
                                        USD
                                    </td>
                                    <td><?php echo number_format($invoice_sum['USD']['netto'][$invoice->numer] + $invoice_sum['USD']['vat'][$invoice->numer], 2, ',', ' '); ?>
                                        USD
                                    </td>
                                    <?php
                                    break;
                            } ?>
                            <td><?php echo ($_POST['lang'] == 1 ? (!is_numeric($invoice->termin )?'Paid':$invoice->termin):$invoice->termin)?></td>
                        </tr>
                        <?php
                    }
                    $allInvoices['PLN'] = array_sum($invoice_sum['PLN']['netto']) + array_sum($invoice_sum['PLN']['vat']);
                    $allInvoices['EUR'] = array_sum($invoice_sum['EUR']['netto']) + array_sum($invoice_sum['EUR']['vat']);
                    $allInvoices['USD'] = array_sum($invoice_sum['USD']['netto']) + array_sum($invoice_sum['USD']['vat']);
                    ?>
                    <tr>
                        <td colspan="8"></td>
                        <td><b><?php echo number_format($allInvoices['PLN'], 2, ',', ' '); ?> PLN</b></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="8"></td>
                        <td><b><?php echo number_format($allInvoices['EUR'], 2, ',', ' '); ?> EUR</b></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="8"></td>
                        <td><b><?php echo number_format($allInvoices['USD'], 2, ',', ' '); ?> USD</b></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="9">Zaległe noty</th>
                    </tr>
                    <?php
                    foreach($results_notes as $nota) {
                        $results_clients = $wpdb->get_results('SELECT kraj,short_name FROM wp_czm_clients WHERE id='.$nota->id_client, OBJECT);
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

                        ?>

                        <tr>
                            <td><?php echo $nota->id; ?></td>
                            <td>Nota</td>
                            <td><?php echo $nota->number; ?></td>
                            <td><?php echo $results_clients[0]->short_name; ?></td>
                            <td><?php echo $results_orders[0]->number; ?></td>
                            <td><?php echo $nota->date_payment; ?></td>
                            <td></td>
                            <td></td>
                            <td><?php echo number_format($nota_sum['brutto'][$nota->number], 2, ',', ' '); ?></td>
                            <td><?php echo $nota->termin ?></td>
                        </tr>
                        <?php
                    }
                    $allNoty = array_sum($nota_sum['brutto']);

                    ?>
                    <tr>
                        <td colspan="7"></td>
                        <td><b><?php echo number_format($allNoty, 2, ',', ' '); ?></b></td>
                        <td></td>
                    </tr>
                    <!--                <tr>-->
                    <!--                    <td colspan="7" style="text-align: right"><b>Suma zaległości brutto</b></td>-->
                    <!--                     <td><b>-->
                    <?php //echo number_format($allNoty + $allInvoices, 2, ',', ' '); ?><!--</b></td>-->
                    <!--                    <td></td>-->
                    <!--                </tr>-->
                </table>
            </td>
        </tr>
    </table>
    </body>
    </html>
    <?php
    $tabela = ob_get_clean();

    $pdf         = new Pdf($tabela);
    $pdf->binary = 'wkhtmltox/bin/wkhtmltopdf';

    $pdf->send();
    die();
    ?>
    <?php
}
