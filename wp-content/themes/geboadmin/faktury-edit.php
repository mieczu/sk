<?php if(!is_user_logged_in()) {
    auth_redirect();
}

global $wpdb, $current_user;

$results_clients = $wpdb->get_results('SELECT DISTINCT short_name,id FROM wp_czm_clients ORDER BY 2 DESC', OBJECT);
$results_orders  = $wpdb->get_results('SELECT DISTINCT number,id FROM wp_czm_orders ORDER BY 2 DESC', OBJECT);
$results_sub     = $wpdb->get_results('SELECT DISTINCT name,id FROM wp_czm_subcontractor ORDER BY 2 DESC', OBJECT);

//if(is_app_admin($current_user)) {
    $results = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id='.$_REQUEST['id'], OBJECT);
//}
//else {
//    $results = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id='.$_REQUEST['id'].' AND id_user ='.$current_user->ID, OBJECT);
//}

$results = $results[0];
?>
<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Edytuj fakturę sprzedaży</h3>

        <form id="addFaktura" name="addFaktura" class="form_faktura3" method="POST"
              action="<?php the_permalink(); ?>?action=update_sp">
            <div class="formSep">
                <div class="row-fluid">
                    <div class="span2">
                        <label><span class="error_placement">Zlecenie</span> <span class="f_req">*</span></label>
                        <select id="order" name="order">
                            <option value=""></option>
                            <?php foreach($results_orders as $order) { ?>
                                <option
                                    value="<?php echo $order->id; ?>"<?php echo($results->id_order == $order->id ? ' selected="selected"' : ''); ?>><?php echo $order->number; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="span2">
                        <label><span class="error_placement">Typ faktury</span> <span class="f_req">*</span></label>
                        <input type="text" class="span10" value="<?php echo $results->typ; ?>" disabled/>
                        <input type="hidden" id="typ" name="typ" value="<?php echo $results->typ; ?>"/>
                    </div>
                    <div class="span2">
                        <label><span class="error_placement">Klient</span> <span class="f_req">*</span></label>
                        <select id="client" name="client">
                            <option value=""></option>
                            <?php foreach($results_clients as $client) { ?>
                                <option
                                    value="<?php echo $client->id; ?>"<?php echo($results->id_client == $client->id ? ' selected="selected"' : ''); ?>><?php echo $client->short_name; ?></option>
                            <?php } ?>

                        </select>
                    </div>
                </div>
            </div>
            <div class="formSep">
                <div class="row-fluid">
                    <div class="span2">
                        <label>Wartość netto:</label>
                        <input type="text" name="value" class="span10" value="<?php echo number_format($results->value / 10000,2,',',''); ?>" disabled/>
                    </div>
                    <div class="span2">
                        <label>VAT</label>
                        <input type="text" name="vat" class="span10" value="<?php echo number_format($results->vat / 10000,2,',',''); ?>" disabled/>
                    </div>
                    <?php
                    $brutto = 0;
                    $eur = $results->eur / 10000;
                    $usd = $results->usd / 10000;
                    $vat = $results->vat / 10000;
                    $value = $results->value / 10000;

                    switch ($results->currency){
                        case 'pln':
                            switch ($results->vat_currency){
                                case 'pln':
                                    $brutto = $value + $vat;
                                    break;
                                case 'eur':
                                    $brutto = $value + ($vat * $eur);
                                    break;
                                case 'usd':
                                    $brutto = $value + ($vat * $usd);
                                    break;
                            }
                            break;
                        case 'eur':
                            switch ($results->vat_currency){
                                case 'pln':
                                    $brutto = $value + ($vat / $eur);
                                    break;
                                case 'eur':
                                    $brutto = $value + $vat;
                                    break;
                                case 'usd':
                                    $brutto = $value + (($vat * $usd) / $eur);
                                    break;
                            }
                            break;
                        case 'usd':
                            switch ($results->vat_currency){
                                case 'pln':
                                    $brutto = $value + ($vat / $usd);
                                    break;
                                case 'eur':
                                    $brutto = $value + (($vat * $eur) / $usd);
                                    break;
                                case 'usd':
                                    $brutto = $value + $vat;
                                    break;
                            }
                            break;
                    }

                    ?>

                    <div class="span2">
                        <label>Wartość brutto</label>
                        <input type="text" name="brutto" class="span10" value="<?php echo number_format($brutto,2,',',''); ?>" disabled/>
                    </div>

                </div>
            </div>
            <div class="formSep">
                <div class="row-fluid">
                    <div class="span2">
                        <label><span class="error_placement">Szablon</span> <span
                                class="f_req">*</span></label>
                        <select id="lang" name="lang" class="span10">
                            <option value="0"<?php echo($results->lang == 0 ? ' selected="selected"' : ''); ?>>PL
                            </option>
                            <option value="1"<?php echo($results->lang == 1 ? ' selected="selected"' : ''); ?>>EN
                            </option>
                        </select>
                    </div>
                    <div class="span2">
                        <label>Fakturę wysłano</label>
                        <input id="date_received" type="text" name="date_received" class="span10"
                               value="<?php echo $results->date_received; ?>"/>
                    </div>

                    <div class="span2">
                        <label>Kwota pozostała do zapłaty</label>
                        <input type="text" name="paid" class="span10"
                               value="<?php echo $results->paid / 10000; ?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span2">
                        <label>Termin płatności<span class="f_req">*</span></label>
                        <input id="date_payment" type="text" name="date_payment" class="span10"
                               value="<?php echo $results->date_payment; ?>"/>
                    </div>
                    <div class="span2">
                        <label>Zapłacono dnia</label>
                        <input id="date_paid" type="text" name="date_paid" class="span10"
                               value="<?php echo $results->date_paid; ?>"/>
                    </div>
                    <div class="span2">
                        <label>Waluta faktury: <span class="f_req">*</span></label>
                        <select name="currency" id="currency" class="span10">
                            <option value="pln"<?php echo ($results->currency=='pln'?' selected="selected"':'')?>>PLN</option>
                            <option value="usd"<?php echo ($results->currency=='usd'?' selected="selected"':'')?>>USD</option>
                            <option value="eur"<?php echo ($results->currency=='eur'?' selected="selected"':'')?>>EUR</option>
                        </select>
                    </div>
                    <div class="span2">
                        <label>Waluta VAT-u: <span class="f_req">*</span></label>
                        <select name="vat_currency" id="currency" class="span10">
                            <option value="pln"<?php echo ($results->vat_currency=='pln'?' selected="selected"':'')?>>PLN</option>
                            <option value="usd"<?php echo ($results->vat_currency=='usd'?' selected="selected"':'')?>>USD</option>
                            <option value="eur"<?php echo ($results->vat_currency=='eur'?' selected="selected"':'')?>>EUR</option>
                        </select>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span2">
                        <label>Kurs EURO</label>
                        <input id="eur" type="text" name="eur" class="span10" value="<?php echo $results->eur/10000; ?>"/>
                    </div>
                    <div class="span2">
                        <label>Kurs USD</label>
                        <input id="usd" type="text" name="usd" class="span10" value="<?php echo $results->usd/10000; ?>"/>
                    </div>
                    <div class="span2">
                        <label>Pobierz kurs</label>
                        <select name="rok" id="rok" style="width: 60px;" class="rok">
                            <option selected="selected" value="<?php echo date('Y');?>"><?php echo date('Y');?></option>
                            <?php
                            for ($i = date('Y')-1;$i>=2015;$i--){?>
                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                            <?php }
                            ?>
                        </select>

                        <select name="tabela" id="tabela" style="width: 75px;" class="tabela">
                            <option></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="formSep">
                <div class="row-fluid">
                    <div class="span12">
                        <?php get_template_part('faktury', 'items'); ?>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span8">
                    <label>Notatki</label>
                    <textarea name="message" id="message" cols="10" rows="3"
                              class="span12"><?php echo $results->note; ?></textarea>
                </div>
            </div>
            <div class="form-actions">
                <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST['id']; ?>"/>
                <button class="btn btn-inverse" type="submit">Zapisz fakturę</button>
                <button type="button"
                        onclick="window.location.href = 'http://'+window.location.host+window.location.pathname;"
                        class="btn">Anuluj
                </button>
            </div>
            <!--            <div class="row-fluid">-->
            <!--                <div class="span12">-->
            <!--                    <div class="tabbable tabbable-bordered">-->
            <!--                        <ul class="nav nav-tabs">-->
            <!--                            <li class="active"><a href="#tab_br1" data-toggle="tab">Towary</a></li>-->
            <!--                        </ul>-->
            <!--                        <div class="tab-content">-->
            <!--                            <div class="tab-pane active" id="tab_br1">-->
            <!--                                --><?php //get_template_part('faktury', 'items'); ?>
            <!--                            </div>-->
            <!--                        </div>-->
            <!---->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
        </form>
    </div>
</div>
</div>


