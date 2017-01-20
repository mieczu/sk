
<?php
if(!is_user_logged_in()) {
    auth_redirect();
}

//var_dump($_POST);

global $current_user;
//var_dump($results_sto);
$results_clients = $wpdb->get_results('SELECT short_name,id FROM wp_czm_clients ORDER BY 2 DESC', OBJECT);
$results_orders  = $wpdb->get_results('SELECT number,id FROM wp_czm_orders ORDER BY 2 DESC', OBJECT);

$results_sto = $wpdb->get_results('SELECT * FROM wp_czm_subcontractor_to_order WHERE id='.$_REQUEST['id_subcontractor_to_order'].' ORDER BY 1 DESC', OBJECT);
$results_sub = $wpdb->get_results('SELECT name,id FROM wp_czm_subcontractor ORDER BY 2 DESC', OBJECT);

$sql = 'SELECT id, numer FROM wp_czm_invoices WHERE typ=\'Grupowa\' ORDER BY id DESC';
$results_group = $wpdb->get_results($sql, OBJECT);

$results_sto[0]->id_subcontractor
?>
<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Dodaj fakturę</h3>

        <form id="addFaktura" name="addFaktura" class="form_faktura2" method="POST"
              action="<?php the_permalink(); ?>?action=new">
            <div class="formSep">
                <div class="row-fluid">
                    <div class="span2">
                        <label><span class="error_placement">Zlecenie</span> <span class="f_req">*</span></label>
                        <select id="order" name="order">
                            <option value=""></option>
                            <?php foreach($results_orders as $order) { ?>
                                <option
                                    value="<?php echo $order->id; ?>"<?php echo($_REQUEST['id_order'] == $order->id ? ' selected="selected"' : '') ?>><?php echo $order->number; ?></option>
                            <?php } ?>

                        </select>
                    </div>
                    <div class="span2">
                        <label><span class="error_placement">Numer faktury grupowej</span> <span class="f_req">*</span></label>
                        <select id="parent" name="parent">
                            <option value=""></option>
                            <?php foreach($results_group as $fgroup) { ?>
                                <option
                                    value="<?php echo $fgroup->id; ?>"><?php echo $fgroup->numer; ?></option>
                            <?php } ?>

                        </select>
                    </div>
                    <div class="span3">
                        <label><span class="error_placement">Podwykonawca</span> <span class="f_req">*</span></label>
                        <input type="text" name="subcontractor" value="<?php echo $results_sto[0]->subcontractor; ?>"/>
                        <input type="hidden" name="idsubcontractor"
                               value="<?php echo $results_sto[0]->id_subcontractor; ?>"/>

                    </div>
                    <div class="span2">
                        <label><span class="error_placement">Typ faktury</span> <span class="f_req">*</span></label>
                        <input type="text" value="Zakup" disabled>
                        <input type="hidden" id="typ" name="typ" value="Zakup">
                    </div>
                </div>
            </div>

            <div class="formSep">

                <div class="row-fluid">
                    <div class="span2"
                         id="numer"<?php echo(is_numeric($_REQUEST['id_order']) ? '' : ' style="display: none;"'); ?>>
                        <label>Numer faktury <span class="f_req">*</span></label>
                        <input type="text" id="fnumer" name="fnumer" class="span10"/>
                    </div>
                    <div class="span2">
                        <label>Wartość netto: <span class="f_req">*</span></label>
                        <input type="text" name="value" class="span10"/>
                    </div>
                    <div class="span2">
                        <label>Waluta: <span class="f_req">*</span></label>
                        <select name="currency" id="currency" class="span10">
                            <option value="pln">PLN</option>
                            <option value="usd">USD</option>
                            <option value="eur">EUR</option>
                        </select>
                    </div>
                    <div class="span2">
                        <label>VAT(kwota) <span class="f_req">*</span></label>
                        <input type="text" name="vat" class="span10"/>
                    </div>
                    <div class="span2">
                        <label>Waluta VAT-u: <span class="f_req">*</span></label>
                        <select name="vat_currency" id="vat_currency" class="span10">
                            <option value="pln">PLN</option>
                            <option value="usd">USD</option>
                            <option value="eur">EUR</option>
                        </select>
                    </div>
                </div>
                <div class="row-fluid">

                    <div class="span2">
                        <label>Fakturę otrzymano</label>
                        <input id="date_received" type="text" name="date_received" class="span10"/>
                    </div>

                    <div class="span2">
                        <label>Kwota pozostała do zapłaty</label>
                        <input type="text" name="paid" class="span10"/>
                    </div>
                    <div class="span2">
                        <label>Termin płatności<span class="f_req">*</span></label>
                        <input id="date_payment" type="text" name="date_payment" class="span10"/>
                    </div>
                    <div class="span2">
                        <label>Zapłacono dnia</label>
                        <input id="date_paid" type="text" name="date_paid" class="span10"/>
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
                    <textarea name="message" id="message" cols="10" rows="3" class="span12"></textarea>
                </div>
            </div>
            <div class="form-actions">
                <input class="id_subcontractor_to_order" name="id_subcontractor_to_order"
                       value="<?php echo $_REQUEST['id_subcontractor_to_order']; ?>" type="hidden">
                <!--    <input class="id_order" name="id_order" value="" type="hidden">-->
                <button class="btn btn-inverse" type="submit">Dodaj fakturę</button>
                <button class="btn">Anuluj</button>
            </div>
        </form>
    </div>
</div>