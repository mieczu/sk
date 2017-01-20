<?php

global $wpdb, $current_user;

if (!is_user_logged_in()) {
    auth_redirect();
}

//if (!is_app_admin($current_user)) {
//    $_REQUEST['user'] = $current_user->ID;
//}
$results_clients = $wpdb->get_results('SELECT name,id FROM wp_czm_clients', OBJECT);
$results_users   = $wpdb->get_results('SELECT display_name,id FROM wp_users', OBJECT);

?>
    <div class="row-fluid">
        <div class="span12">
            <h3 class="heading">Raporty klienci</h3>

            <form id="rep2" name="search_client" id="search_client" action="" method="post">
                <div class="formSep">
                    <div class="row-fluid">
                        <div class="span2">
                            <label for="date_start">Od:</label>
                            <input class="span10" id="date_start" type="text" name="date_start"
                                   value="<?php echo $_REQUEST['date_start']; ?>"/>
                        </div>
                        <div class="span2">
                            <label for="date_end">Do:</label>
                            <input class="span10" id="date_end" type="text" name="date_end"
                                   value="<?php echo $_REQUEST['date_end']; ?>"/>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <div class="row-fluid">
                                <div class="span4">
                                    <input class="span1" id="paid" type="checkbox"
                                           name="paid"<?php echo(!isset($_POST) || (isset($_POST) && isset($_REQUEST['paid'])) ? ' checked="checked"' : ''); ?>
                                           value="paid"/>
                                    <label for="paid" style="display: inline">Zapłacone</label>
                                </div>
                                <div class="span4">
                                    <input class="span1" id="notpaid" type="checkbox"
                                           name="notpaid"<?php echo(!isset($_POST) || (isset($_POST) && isset($_REQUEST['notpaid'])) ? ' checked="checked"' : ''); ?>
                                    "
                                    value="notpaid"/>
                                    <label for="notpaid" style="display: inline">Nie zapłacone</label>
                                </div>
                                <div class="span4">
                                    <input class="span1" id="overdue" type="checkbox"
                                           name="overdue"<?php echo(!isset($_POST) || (isset($_POST) && isset($_REQUEST['overdue'])) ? ' checked="checked"' : ''); ?>
                                           value="overdue"/>
                                    <label for="overdue" style="display: inline">Po terminie</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span2">
                            <label for="client">Klient</label>
                            <select id="client" name="client" class="span12">
                                <option value="">Wszyscy</option>
                                <?php

                                foreach($results_clients as $client) {
                                    ?>
                                    <option
                                        value="<?php echo $client->id; ?>"<?php echo(isset($_POST['client']) && $client->id == $_POST['client'] ? ' selected="selected"' : '') ?>><?php echo $client->name; ?></option>
                                    <?php
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span2">
                            <label for="user">Pracownik</label>
                            <select id="user" name="user" class="span12">
                                <?php
//                                if (is_app_admin($current_user)) {
                                    ?>
                                    <option value="">Wszyscy</option>
                                    <?php
                                    foreach($results_users as $user) {
                                        ?>
                                        <option
                                            value="<?php echo $user->id; ?>"<?php echo(isset($_POST['user']) && $user->id == $_POST['user'] ? ' selected="selected"' : '') ?>>
                                            <?php echo $user->display_name; ?>
                                        </option>
                                        <?php
                                    }
//                                } else{
                                    ?>
<!--                                    <option-->
<!--                                        value="--><?php //echo $current_user->ID; ?><!--">--><?php //echo $current_user->display_name; ?><!--</option>-->
                                    <?php
//                                }
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
                    <div class="row-fluid">
                        <div class="span2">
                            <select name="lang">
                                <option value="0">Polski</option>
                                <option value="1">Angielski</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php

if (isset($_POST['submit'])) {
    //    var_dump($_POST);
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


    ?>
    <div class="row-fluid">
        <div class="span12">
            <style>
                td {
                    padding: 5px;
                }
            </style>
            <table id="dt_r_klienci">
                <thead>
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
                </thead>
                <tbody>
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
                        <td>Faktura</td>
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
                        <td><?php echo $invoice->termin ?></td>
                    </tr>
                    <?php
                }
                $allInvoices['PLN'] = array_sum($invoice_sum['PLN']['netto']) + array_sum($invoice_sum['PLN']['vat']);
                $allInvoices['EUR'] = array_sum($invoice_sum['EUR']['netto']) + array_sum($invoice_sum['EUR']['vat']);
                $allInvoices['USD'] = array_sum($invoice_sum['USD']['netto']) + array_sum($invoice_sum['USD']['vat']);
                ?>
                </tbody>

            <tfoot>
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
            </tfoot>
                </table>
<!--                <tr>-->
<!--                    <th colspan="9">Zaległe noty</th>-->
<!--                </tr>-->
                <table id="dt_rn_klienci">
                    <thead>
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
                    </thead>
                    <tbody>
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
<!--                <tr>-->
<!--                    <td colspan="7"></td>-->
<!--                    <td><b>--><?php //echo number_format($allNoty, 2, ',', ' '); ?><!--</b></td>-->
<!--                    <td></td>-->
<!--                </tr>-->


                <!--                <tr>-->
                <!--                    <td colspan="7" style="text-align: right"><b>Suma zaległości brutto</b></td>-->
                <!--                     <td><b>-->
                <?php //echo number_format($allNoty + $allInvoices, 2, ',', ' '); ?><!--</b></td>-->
                <!--                    <td></td>-->
                <!--                </tr>-->
                </tbody>
            </table>
            <br/><br/>
        </div>
    </div>
    <?php
}
