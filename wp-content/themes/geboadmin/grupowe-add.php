
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
//    var_dump($results_sto);
$results_sto[0]->id_subcontractor
?>
<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Dodaj fakturę grupową</h3>

        <form id="addGrupowa" name="addGrupowa" class="form_grupowa" method="POST"
              action="<?php the_permalink(); ?>?action=new">
            <div class="formSep">
                <div class="row-fluid">
<!--                    <div class="span3">-->
<!--                        <label><span class="error_placement">Podwykonawca</span> <span class="f_req">*</span></label>-->
<!--                        <select id="order" name="order">-->
<!--                            <option value=""></option>-->
<!--                            --><?php //foreach($results_sub as $sub) { ?>
<!--                                <option value="--><?php //echo $sub->id; ?><!--">--><?php //echo $sub->name; ?><!--</option>-->
<!--                            --><?php //} ?>
<!---->
<!--                        </select>-->
<!--                    </div>-->
                    <div class="span3">
                        <label><span class="error_placement">Podwykonawca</span> <span class="f_req">*</span></label>
                        <input type="text" name="subcontractor" id="subcontractor" value=""/>
                        <input type="hidden" name="idsubcontractor" id="idsubcontractor" value=""/>

                    </div>
                    <div class="span3">
                        <label><span class="error_placement">Typ faktury</span> <span class="f_req">*</span></label>
                        <input type="text" value="Grupowa" disabled>
                        <input type="hidden" id="typ" name="typ" value="Grupowa">
                    </div>
                </div>
            </div>

            <div class="formSep">

                <div class="row-fluid">
                    <div class="span2"
                         id="numer">
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
                <button class="btn btn-inverse" type="submit">Dodaj fakturę grupową</button>
                <button class="btn">Anuluj</button>
            </div>
        </form>

    </div>
</div>