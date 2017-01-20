<table style="width: 740px; margin: 0 auto">
    <tr>
        <td class="l noprint"><a href="javascript:window.print()">drukuj</a> | <a href="javascript:history.back(-1)">anuluj</a></td>
    </tr>
    <tr>
        <td class="l"><img class="" alt="" src="<?php echo $template_url; ?>/img/logo.png" style="height: 80px;"/></td>
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
            <div class="b">Warszawa, <?php echo substr($results->date_add,0,10);?></div>
        </td>
    </tr>
    <tr>
        <td class="l">
            <table>
                <tr>
                    <td class="b l" style="padding: 10px;">Dla: </td>
                    <td class="b l">
                        <br/><br/>
                        <span><?php echo $results_client[0]->name; ?></span><br/>
                        <span><?php echo $results_client[0]->address; ?></span><br/>
                        <span><?php echo $results_client[0]->post_code.' '.$results_client[0]->city; ?></span><br/>
                        <br/>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 15px 0 25px; font-size: 14px;" class="b c" colspan="2">
            NOTA KSIĘGOWA <?php echo $results->number; ?>
        </td>
    </tr>
    <tr>
        <td style="" class="b c" colspan="2">
            NALEŻNOŚCI CELNO-PODATKOWE <?php echo $results->value/10000; ?>&nbsp;<?php echo $results->currency;?>
        </td>
    </tr>
    <tr>
        <td style="" class="b c" colspan="2"><br/>
            CŁO: <?php echo $results->duty/10000; ?>&nbsp;<?php echo $results->currency;?><br/>
            VAT: <?php echo $results->tax/10000; ?>&nbsp;<?php echo $results->currency;?><br/><br/>
        </td>
    </tr>
    <tr>
        <td style="" class="b c" colspan="2">
            <?php
            $razem = ($results->duty/10000)+($results->tax/10000);
            ?>
            RAZEM: <?php echo $razem;?>&nbsp;<?php echo $results->currency;?><br/>
        </td>
    </tr>
    <tr>
        <td class="b c" colspan="2">
            <?php echo slownie((int)(($results->duty+$results->tax)/10000));?>&nbsp;<?php echo (int)(($razem-((int)$razem))*10000);?>/100
        </td>
    </tr>
    <tr>
        <td class="b c" colspan="2">
            (Nasza referencja: <?php echo $results_order[0]->number;?>)<br/><br/><br/>
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
    }
    ?>
<!--    <tr>-->
<!--        <td class="b l">-->
<!--            Dokument SAD OGL --><?php //echo $results->sad;?><!--<br/>-->
<!--        </td>-->
<!--        <td class="b l">-->
<!--            z dn.  --><?php //echo $results->sad_date;?>
<!--        </td>-->
<!--    </tr>-->
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
        <td style="padding: 15px 0 25px;" class="r"> </td>
        <td style="padding: 15px 0 25px;" class="c"><br/>(podpis) </td>
    </tr>

</table>