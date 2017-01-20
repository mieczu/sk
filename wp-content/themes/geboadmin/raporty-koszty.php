<?php

global $wpdb, $current_user;

if (!is_user_logged_in()) {
    auth_redirect();
}

if (!is_app_admin($current_user)) {
    ?>
<div class="row-fluid">
        <div class="span12">
            <h3 class="heading">Raport kosztów stałych</h3>
    <p class="warning">
        Ta strona jest dostępna tylko dla administratora!
    </p>
</div></div>
    <?php
}else{

    ?>
    <div class="row-fluid">
        <div class="span12">
            <h3 class="heading">Raport kosztów stałych</h3>

            <form id="rep3" name="search_client" id="search_client" action="" method="post">
                <!--                <div class="formSep">-->
                <!--                    <div class="row-fluid">-->
                <!--                        <div class="span2">-->
                <!--                            <label for="date_start">Data dodania od:</label>-->
                <!--                            <input class="span10" id="date_start" type="text" name="date_start"-->
                <!--                                   value="--><?php //echo $_REQUEST['date_start'];
                ?><!--"/>-->
                <!--                        </div>-->
                <!--                        <div class="span2">-->
                <!--                            <label for="date_end">Data dodania do:</label>-->
                <!--                            <input class="span10" id="date_end" type="text" name="date_end"-->
                <!--                                   value="--><?php //echo $_REQUEST['date_end'];
                ?><!--"/>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                    <div class="row-fluid">-->
                <!--                        <div class="span4">-->
                <!--                            <div class="row-fluid">-->
                <!--                                <div class="span4">-->
                <!--                                    <input class="span1" id="paid" type="checkbox"-->
                <!--                                           name="paid"--><?php //echo(!isset($_POST) || (isset($_POST) && isset($_REQUEST['paid'])) ? ' checked="checked"' : '');
                ?>
                <!--                                           value="paid"/>-->
                <!--                                    <label for="paid" style="display: inline">Zapłacone</label>-->
                <!--                                </div>-->
                <!--                                <div class="span4">-->
                <!--                                    <input class="span1" id="notpaid" type="checkbox"-->
                <!--                                           name="notpaid"--><?php //echo(!isset($_POST) || (isset($_POST) && isset($_REQUEST['notpaid'])) ? ' checked="checked"' : '');
                ?>
                <!--                                    "-->
                <!--                                    value="notpaid"/>-->
                <!--                                    <label for="notpaid" style="display: inline">Nie zapłacone</label>-->
                <!--                                </div>-->
                <!--                                <div class="span4">-->
                <!--                                    <input class="span1" id="overdue" type="checkbox"-->
                <!--                                           name="overdue"--><?php //echo(!isset($_POST) || (isset($_POST) && isset($_REQUEST['overdue'])) ? ' checked="checked"' : '');
                ?>
                <!--                                           value="overdue"/>-->
                <!--                                    <label for="overdue" style="display: inline">Po terminie</label>-->
                <!--                                </div>-->
                <!--                            </div>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <div class="row-fluid">
                    <div class="span2">
                        <label for="month">Miesiąc</label>
                        <select id="month" name="month[]" class="span12" multiple="multiple">
                            <?php
                            $now = new DateTime('NOW');
                            $now->modify('first day of this month');
                            $date = new DateTime('2016-07-01');
                            while ($now >= $date) {
                                ?>
                                <option
                                    value="<?php echo $now->format("Y-m"); ?>-01"<?php echo(in_array($now->format("Y-m").'-01', $_POST['month']) ? ' selected="selected"' : '') ?>><?php echo $now->format("Y-m"); ?></option>
                                <?php
                                $now->sub(new DateInterval('P1M'));
                                $now->modify('first day of this month');
                            } ?>
                        </select>
                    </div>
                    <!--                        <div class="span1">-->
                    <!--                            <input id="pdf" type="checkbox" name="pdf"-->
                    <!--                                   value="pdf"-->
                    <?php //echo(isset($_POST['pdf']) ? ' checked="checked"' : '')
                    ?><!--/>Wygeneruj-->
                    <!--                            PDF-->
                    <!--                        </div>-->
                    <div class="span2">
                        <input type="submit" name="submit" value="Pokaż"/>
                    </div>
                </div>
            </form>
        </div>

    </div>


    <?php
}
if (isset($_POST['submit'])) {
//    var_dump($_POST);
    $where = '';

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


    $sql_costs = 'SELECT typ, sum(netto) as \'netto\',sum(vat) as \'vat\',sum(brutto) as \'brutto\', date_applies FROM wp_czm_costs WHERE date_applies in(\''.implode('\',\'', $_POST['month']).'\') GROUP BY typ ORDER BY date_applies DESC';

    $results_costs = $wpdb->get_results($sql_costs, OBJECT);
    //  echo '<br><pre>';
    //var_dump($results_costs);
    //    echo '</pre><br><br>';
    $netto  = array();
    $vat    = array();
    $brutto = array();

    ?>
    <div class="row-fluid">
        <div class="span12">
            <style>
                td {
                    padding: 5px;
                }
            </style>
            <table id="dt_r_koszty">
                <thead>
                <tr>
                    <th>Rodzaj</th>
                    <th>Netto</th>
                    <th>VAT</th>
                    <th>Brutto</th>
                    <th>Miesiąc</th>
                </tr>
                </thead>
                <tbody>
                <?php

                foreach($results_costs as $cost) {
                    ?>
                    <tr>
                        <td><?php echo $cost->typ; ?></td>
                        <td><?php echo number_format($cost->netto / 10000, 2, ',', '') ?> PLN</td>
                        <td><?php echo number_format($cost->vat / 10000, 2, ',', '') ?> PLN</td>
                        <td><?php echo number_format($cost->brutto / 10000, 2, ',', '') ?> PLN</td>

                        <td><?php echo $cost->date_applies ?></td>
                    </tr>
                    <?php
                    $netto[]  = $cost->netto / 10000;
                    $vat[]    = $cost->vat / 10000;
                    $brutto[] = $cost->brutto / 10000;
                }
                $allCosts['netto']  = array_sum($netto);
                $allCosts['vat']    = array_sum($vat);
                $allCosts['brutto'] = array_sum($brutto);
                ?>
                </tbody>

                <tfoot>
                <tr>
                    <td></td>
                    <td><b><?php echo number_format($allCosts['netto'], 2, ',', ' '); ?> PLN</b></td>
                    <td><b><?php echo number_format($allCosts['vat'], 2, ',', ' '); ?> PLN</b></td>
                    <td><b><?php echo number_format($allCosts['brutto'], 2, ',', ' '); ?> PLN</b></td>
                    <td></td>
                </tr>
                </tfoot>
            </table>

            <br/>
        </div>
    </div>
    <?php
}
