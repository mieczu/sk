<?php if(!is_user_logged_in()) {
    auth_redirect();
}

$template_url = get_template_directory_uri();

global $wpdb, $current_user;

//if(is_app_admin($current_user)) {
    $results = $wpdb->get_results('SELECT * FROM wp_czm_noty WHERE id='.$_REQUEST['id'], OBJECT);
//}
//else {
//    $results = $wpdb->get_results('SELECT * FROM wp_czm_noty WHERE id='.$_REQUEST['id'].' AND id_user ='.$current_user->ID, OBJECT);
//}

if($results) {
$results = $results[0];

$results_client = $wpdb->get_results('SELECT * FROM wp_czm_clients WHERE id='.$results->id_client, OBJECT);
$results_order = $wpdb->get_results('SELECT * FROM wp_czm_orders WHERE id='.$results->id_order, OBJECT);
$results_order_user = $wpdb->get_results('SELECT * FROM wp_users  WHERE id='.$results_order[0]->id_user, OBJECT);
$results_user = $wpdb->get_results('SELECT * FROM wp_users  WHERE id='.$results->id_user, OBJECT);

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="<?php echo $template_url; ?>/css/print.css" id="link_theme"/>
    <title></title>
</head>
<body style="font-size: 14px;"><?php
//echo('<pre>'.print_r(array($results_order,'SELECT * FROM wp_users  WHERE id='.$results_order->id_user, $results_order_user),true).'</pre>');
?>
<div id="page-container">
    <table style="width: 740px; margin: 0 auto">
        <tr>
            <td class="l noprint"><a href="javascript:window.print()">drukuj</a> | <a
                    href="javascript:history.back(-1)">anuluj</a></td>
        </tr>
        <tr>
            <td class="l"><img class="" alt="" src="<?php echo $template_url; ?>/img/logo.png" style="height: 80px;"/>
            </td>
            <td class="r"></td>
        </tr>
        <tr>
            <td class="l">
                <div class="b">SKY-MAR MAREK ZAWORSKI</div>
                <?php

                $data1 = new DateTime( $results->date_add );
                $data2 = new DateTime( '2016-11-30 23:59:59' );

                if ( $data1 > $data2 ) {
                    ?>
                    <div class="b"> 17 Stycznia 39</div>
                    <?php
                } else {
                    ?>
                    <div class="b"> 17 Stycznia 32</div>
                    <?php
                }
                ?>
                <div class="">02-148 Warszawa</div>
                <div class="">Tel 022 576 4145</div>
                <div class="">Fax 022 576 4281</div>
            </td>
            <td class="r">
                <div class="b">Warszawa, <?php echo substr($results->date_add, 0, 10); ?></div>
            </td>
        </tr>
        <tr>
            <td class="l">
                <table>
                    <tr>
                        <td class="b l" style="padding: 10px;">Dla:</td>
                        <td class="b l">
                            <br/><br/>
                            <span><?php echo $results_client[0]->name; ?></span><br/>
                            <span><?php echo $results_client[0]->address; ?></span><br/>
                            <span><?php echo $results_client[0]->post_code.' '.$results_client[0]->city; ?></span><br/>
                            <span> <?php echo ($results->lang == 0 ? ' NIP: ' : 'VAT No: ').$results_client[0]->nip; ?></span><br/>
                            <br/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 15px 0 25px; font-size: 14px;" class="b c" colspan="2">
                <?php echo ($results->lang == 0 ? 'NOTA KSIĘGOWA' : 'NOTA').' '.$results->number; ?>
            </td>
        </tr>
        <?php
        if($results->typ == 'Transportowa') {
            ?>
            <tr>
                <td style="" class="b c" colspan="2">
                    <?php echo $results->content; ?>
                </td>
            </tr>
        <?php
        }
        else {
            ?>}
            <tr>
                <td style="" class="b c" colspan="2">
                    <?php echo ($results->lang == 0 ? 'NALEŻNOŚCI CELNO-PODATKOWE' : 'DUTY & TAX').' '.number_format($results->value / 10000, 2, ',', ' '); ?>
                    &nbsp;<?php echo $results->currency; ?>
                </td>
            </tr>
            <tr>
                <td style="" class="b c" colspan="2"><br/>
                    <?php echo($results->lang == 0 ? 'CŁO: ' : 'DUTY: '); ?><?php echo number_format($results->duty / 10000, 2, ',', ' '); ?>
                    &nbsp;<?php echo $results->currency; ?><br/>
                    <?php echo($results->lang == 0 ? 'VAT: ' : 'TAX: '); ?><?php echo number_format($results->tax / 10000, 2, ',', ' '); ?>
                    &nbsp;<?php echo $results->currency; ?><br/><br/>
                </td>
            </tr>
            <tr>
                <td style="" class="b c" colspan="2">
                    <?php
                    $razem = ($results->duty / 10000) + ($results->tax / 10000);
                    ?>
                    <?php echo($results->lang == 0 ? 'RAZEM: ' : 'TOTAL: '); ?><?php echo number_format($razem, 2, ',', ' '); ?>
                    &nbsp;<?php echo $results->currency; ?><br/>
                </td>
            </tr>
            <tr>
                <td class="b c" colspan="2">
                    <?php
                    $slownie = slownie((int)(($results->duty + $results->tax) / 10000)).' '.$results->currency.' '.(int)(($razem - ((int)$razem)) * 100).'/100';
                    $slownie_en = slownie_en((int)(($results->duty + $results->tax) / 10000)).' '.$results->currency.' '.(int)(($razem - ((int)$razem)) * 100).'/100';
                    ?>
                    <?php echo($results->lang == 1 ? 'say' : 'słownie'); ?>:&nbsp;<?php echo($results->lang == 0 ? $slownie : $slownie_en); ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td class="b c" colspan="2">
                (<?php echo($results->lang == 0 ? 'Nasza referencja: ' : 'REF: '); ?><?php echo $results_order[0]->number.inicialy($results_order_user[0]->display_name); ?>
                )<br/><br/><br/>
            </td>
        </tr>
        <?php
        if($results->typ != 'Transportowa') {
            ?>
            <tr>
                <td class="b l">
                    Dokument SAD OGL <?php echo $results->sad; ?><br/>
                </td>
                <td class="b l">
                    z dn.  <?php echo $results->sad_date; ?>
                </td>
            </tr>
        <?php
        }else{
            ?>
            <tr>
                <td class="b c" colspan="2">
                    <?php
                    echo ($results->lang == 0 ? 'Należność: ' : 'Charge: ');
                    echo number_format($results->value / 10000, 2, ',', ' ').' '.strtoupper($results->currency);
                    ?>
                    <br/>
                </td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td class="b c" colspan="2"><br/><br/>
                <style>
                    .r {
                        font-weight: normal;
                    }
                </style>
                <table style="font-size: 12px;">
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
                    <?php if(!empty($results->note)) { ?>
                        <tr>
                            <td class="r"><?php echo($results->lang == 1 ? 'Notes' : 'Uwagi'); ?></td>
                            <td class="l b"><?php echo $results->note; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </td>
        </tr>
        <tr>
            <td class="b c" colspan="2"><br/><br/>
                Prosimy o dokonanie wpłaty na nasze konto nr:<br/>
                18 1090 2590 0000 0001 3142 7010<br/>
                BZ WBK S.A.
            </td>
        </tr>
        <tr>
            <td style="padding: 15px 0 25px;" class="r"></td>
            <td style="padding: 15px 0 25px;" class="c">
                <br/> <?php echo($results->lang == 0 ? '(podpis)' : '(signature)'); ?></td>
        </tr>

    </table>
</body>
</html>

<?php
} else {
    echo '<h3>Ta nota została usunięta lub nie należy do Ciebie.</h3>';
}
?>
