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
            <h3 class="heading">Edytuj fakturę</h3>
            <?php
//            var_dump($results);
            ?>

            <form id="addFaktura" name="addFaktura" class="form_faktura2" method="POST"
                  action="<?php the_permalink(); ?>?action=update">
                <div class="formSep">
                    <div class="row-fluid">
                        <div class="span2">
                            <label><span class="error_placement">Podwykonawca</span> <span class="f_req">*</span></label>
                            <input type="text" id="subcontractor" name="subcontractor" value="<?php echo $results->subcontractor; ?>" class="span10"/>
                            <input type="hidden" id="idsubcontractor" name="idsubcontractor" value="<?php echo $results->id_subcontractor; ?>"/>
                        </div>
                        <div class="span3">
                            <label><span class="error_placement">Typ faktury</span> <span class="f_req">*</span></label>
                            <input type="text" value="Zakup" disabled>
                            <input type="hidden" id="typ" name="typ" value="Grupowe">
                        </div>
                    </div>
                </div>
                <div class="formSep">
                    <div class="row-fluid">
                        <div class="span2" id="numer">
                            <label>Numer faktury <span class="f_req">*</span></label>
                            <input type="text" name="fnumer" class="span10" value="<?php echo $results->numer; ?>"/>
                        </div>
                        <div class="span2">
                            <label>Wartość netto: <span class="f_req">*</span></label>
                            <input type="text" name="value" class="span10" value="<?php echo $results->value/10000; ?>"/>
                        </div>
                        <div class="span2">
                            <label>Waluta: <span class="f_req">*</span></label>
                            <select name="currency" id="currency" class="span10">
                                <option value="pln"<?php echo ($results->currency=='pln'?' selected="selected"':'')?>>PLN</option>
                                <option value="usd"<?php echo ($results->currency=='usd'?' selected="selected"':'')?>>USD</option>
                                <option value="eur"<?php echo ($results->currency=='eur'?' selected="selected"':'')?>>EUR</option>
                            </select>
                        </div>
                        <div class="span2">
                            <label>VAT(kwota) <span class="f_req">*</span></label>
                            <input type="text" name="vat" class="span10" value="<?php echo $results->vat/10000; ?>"/>
                        </div>
                        <div class="span2">
                            <label>Waluta VAT-u: <span class="f_req">*</span></label>
                            <select name="vat_currency" id="vat_currency" class="span10">
                                <option value=""></option>
                                <option value="pln"<?php echo ($results->vat_currency=='pln'?' selected="selected"':'')?>>PLN</option>
                                <option value="usd"<?php echo ($results->vat_currency=='usd'?' selected="selected"':'')?>>USD</option>
                                <option value="eur"<?php echo ($results->vat_currency=='eur'?' selected="selected"':'')?>>EUR</option>
                            </select>
                        </div>
                    </div>
                    <div class="row-fluid">

                        <div class="span2">
                            <label>Fakturę otrzymano</label>
                            <input id="date_received" type="text" name="date_received" class="span10" value="<?php echo $results->date_received; ?>"/>
                        </div>

                        <div class="span2">
                            <label>Kwota pozostała do zapłaty</label>
                            <input type="text" name="paid" class="span10" value="<?php echo $results->paid/10000; ?>"/>
                        </div>
                        <div class="span2">
                            <label>Termin płatności<span class="f_req">*</span></label>
                            <input id="date_payment" type="text" name="date_payment" class="span10" value="<?php echo $results->date_payment; ?>"/>
                        </div>
                        <div class="span2">
                            <label>Zapłacono dnia</label>
                            <input id="date_paid" type="text" name="date_paid" class="span10" value="<?php echo $results->date_paid; ?>"/>
                        </div>
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
                <div class="row-fluid">
                    <div class="span8">
                        <label>Notatki</label>
                        <textarea name="message" id="message" cols="10" rows="3" class="span12"><?php echo $results->note; ?></textarea>
                    </div>
                </div>
                <div class="form-actions">
                    <input class="id_subcontractor_to_order" name="id_subcontractor_to_order" value="<?php echo $results->id_subcontractor_to_order; ?>" type="hidden">
                    <input class="id" name="id" value="<?php echo $_REQUEST['id'];?>" type="hidden"/>
                    <button class="btn btn-inverse" type="submit">Zapisz fakturę</button>
                    <button type="button"
                            onclick="window.location.href = 'http://'+window.location.host+window.location.pathname+'/?action=zakup';"
                            class="btn">Anuluj
                    </button>
                </div>
            </form>

        </div>
    </div>
