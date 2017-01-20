<?php if(!is_user_logged_in()) {
    auth_redirect();
}

global $current_user;

$results_clients = $wpdb->get_results('SELECT short_name,id FROM wp_czm_clients ORDER BY 2 DESC', OBJECT);
$results_orders = $wpdb->get_results('SELECT number,id FROM wp_czm_orders ORDER BY 2 DESC', OBJECT);
$results_sub = $wpdb->get_results('SELECT name,id FROM wp_czm_subcontractor ORDER BY 2 DESC', OBJECT);

if(isset($_REQUEST['id_order']))
$results_order = $wpdb->get_results('SELECT id_platnik,id FROM wp_czm_orders WHERE id='.$_REQUEST['id_order'].' ORDER BY 2 DESC', OBJECT);

//var_dump($_REQUEST);
?>
<div class="row-fluid">
<div class="span12">
<h3 class="heading">Dodaj fakturę</h3>

<form id="addFaktura" name="addFaktura" class="form_faktura" method="POST"
      action="<?php the_permalink(); ?>?action=new_sp">
<div class="formSep">
    <div class="row-fluid">
        <div class="span2">
            <label><span class="error_placement">Zlecenie</span> <span class="f_req">*</span></label>
            <select id="order" name="order" class="span10">
                <option value="0"></option>
                <?php foreach($results_orders as $order) { ?>
                    <option value="<?php echo $order->id; ?>"<?php echo (isset($_REQUEST['id_order']) && $_REQUEST['id_order']==$order->id?' selected="selected"':'')?>><?php echo $order->number; ?></option>
                <?php } ?>

            </select>
            <table><tr><td><input type="checkbox" name="old" id="old"/></td><td><label>Faktura bez zlecenia</label></td></tr></table>

        </div>
        <div class="span2">
            <label><span class="error_placement">Typ faktury</span> <span class="f_req">*</span></label>
            <input type="text" value="Sprzedaż" disabled class="span10">
            <input type="hidden" id="typ" name="typ" value="Sprzedaż">
        </div>
        <div class="span3">
            <label><span class="error_placement">Klient</span> <span class="f_req">*</span></label>
            <select id="client" name="client" class="span10">
                <option value="0"></option>
                <?php foreach($results_clients as $client) { ?>
                    <option
                        value="<?php echo $client->id; ?>"<?php echo (isset($results_order[0]) && $results_order[0]->id_platnik==$client->id?' selected="selected"':'')?>><?php echo $client->short_name; ?></option>
                <?php } ?>

            </select>
        </div>
    </div>
</div>
<div class="formSep">

    <div class="row-fluid">
        <div class="span2">
            <label><span class="error_placement">Szablon</span> <span class="f_req">*</span></label>
            <select id="lang" name="lang" class="span10">
                <option value="0">PL</option>
                <option value="1">EN</option>
            </select>
        </div>
        <div class="span2">
            <label>Kwota pozostała do zapłaty</label>
            <input type="text" name="paid" class="span10"/>
        </div>
        <div class="span2">
            <label>Fakturę wysłano</label>
            <input id="date_received" type="text" name="date_received" class="span10"/>
        </div>
    </div>
    <div class="row-fluid">
<!--        <div class="span2">-->
<!--            <label>Wartość netto</label>-->
<!--            <input type="text" name="value" class="span10"/>-->
<!--        </div>-->
<!--        <div class="span2">-->
<!--            <label>VAT (wartość)</label>-->
<!--            <input type="text" name="vat" class="span10"/>-->
<!--        </div>-->
        <div class="span2">
            <label><span class="error_placement">Termin płatności</span> <span class="f_req">*</span></label>
            <input id="date_payment" type="text" name="date_payment" class="span10"/>
        </div>
        <div class="span2">
            <label>Zapłacono dnia</label>
            <input id="date_paid" type="text" name="date_paid" class="span10"/>
        </div>
        <div class="span2">
            <label>Waluta faktury: <span class="f_req">*</span></label>
            <select name="currency" id="currency" class="span10">
                <option value="pln">PLN</option>
                <option value="usd">USD</option>
                <option value="eur">EUR</option>
            </select>
        </div>
        <div class="span2">
            <label>Waluta VAT-u: <span class="f_req">*</span></label>
            <select name="vat_currency" id="currency" class="span10">
                <option value="pln">PLN</option>
                <option value="usd">USD</option>
                <option value="eur">EUR</option>
            </select>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span2">
            <label>Kurs EURO</label>
            <input id="eur" type="text" name="eur" class="span10"/>
        </div>
        <div class="span2">
            <label>Kurs USD</label>
            <input id="usd" type="text" name="usd" class="span10"/>
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
        <?php get_template_part('faktury','sub'); ?>
    </div>
</div>
    <div class="row-fluid">
        <div class="span8">
            <label>Notatki</label>
            <textarea name="message" id="message" cols="10" rows="3" class="span12"></textarea>
        </div>
    </div>
<div class="form-actions">
    <input class="id_subcontractor" name="id_subcontractor_to_order" value="<?php echo $_REQUEST['id_subcontractor_to_order'];?>" type="hidden">
<!--    <input class="id_order" name="id_order" value="" type="hidden">-->
    <button class="btn btn-inverse" type="submit">Dodaj fakturę</button>
    <button type="button"
            onclick="window.location.href = 'http://'+window.location.host+window.location.pathname;"
            class="btn">Anuluj
    </button>
</div>
</form>

</div>
</div>