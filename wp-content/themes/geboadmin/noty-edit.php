<?php

if(!is_user_logged_in()) {
    auth_redirect();
}

global $current_user;


//if(is_app_admin($current_user)) {
    $results = $wpdb->get_results('SELECT * FROM wp_czm_noty WHERE id='.$_REQUEST['id'], OBJECT);
//}
//else {
//    $results = $wpdb->get_results('SELECT * FROM wp_czm_noty WHERE id='.$_REQUEST['id'].' AND id_user ='.$current_user->ID, OBJECT);
//}

$results=$results[0];

$results_clients = $wpdb->get_results('SELECT DISTINCT short_name,id FROM wp_czm_clients', OBJECT);
$results_orders = $wpdb->get_results('SELECT DISTINCT number,id FROM wp_czm_orders ORDER BY id DESC', OBJECT);
?>
<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Edytuj notę</h3>
<?php
//echo '<pre>';
//var_dump($results);
//echo '</pre>';
?>
        <form id="addNota" name="addNota" class="form_nota" method="POST"
              action="<?php the_permalink(); ?>?action=update">
            <div class="formSep">
                <div class="row-fluid">

                    <div class="span2">
                        <label><span class="error_placement">Typ:</span> <span class="f_req">*</span></label>
                        <label class="radio inline">
                            <input type="radio" value="Księgowa" name="typ"<?php echo ($results->typ=='Księgowa'?' checked="checked"':'') ?>/>
                            Księgowa
                        </label>
                        <label class="radio inline">
                            <input type="radio" value="Transportowa" name="typ"<?php echo ($results->typ=='Transportowa'?' checked="checked"':'') ?>/>
                            Transportowa
                        </label>
                    </div>
                    <div class="span2">
                        <label>Numer</label>
                        <input type="text" name="num" class="span10" value="<?php echo $results->number;?>" disabled/>
                        </div>
                    <div class="span3">
                        <label><span class="error_placement">Szablon:</span> <span class="f_req">*</span></label>
                        <select name="lang">
                            <option value="0"<?php echo ($results->lang==0?' selected="selected"':'') ?>>PL</option>
                            <option value="1"<?php echo ($results->lang==1?' selected="selected"':'') ?>>EN</option>
                        </select>

                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <div class="ui-widget">
                            <label>Klient:<span class="f_req">*</span> </label>
                            <select id="klient" name="klient">
                                <option value=""></option>
                                <?php foreach($results_clients as $client) { ?>
                                    <option value="<?php echo $client->id; ?>"<?php echo ($results->id_client==$client->id?' selected="selected"':'') ?>><?php echo $client->short_name; ?></option>
                                <?php } ?>

                            </select>

                        </div>
                    </div>
                    <div class="span3">
                        <div class="ui-widget">
                            <label>Zlecenie:<span class="f_req"></span> </label>
                            <select id="zlecenie" name="zlecenie">
                                <option value=""></option>
                                <?php foreach($results_orders as $order) { ?>
                                    <option
                                        value="<?php echo $order->id; ?>"<?php echo ($results->id_order==$order->id?' selected="selected"':'') ?>><?php echo $order->number; ?></option>
                                <?php } ?>

                            </select>

                        </div>
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
            <div class="formSep" id="ks" style="<?php echo $results->typ == 'Księgowa'?'':'display: none;'; ?>">
                <div class="row-fluid">
                    <div class="span2">
                        <label>Należność</label>
                        <input type="text" name="value" class="span10" value="<?php echo number_format($results->value / 10000, 2, ',', '');?>"/>
                    </div>
                    <div class="span2">
                        <label>Waluta</label>
                        <select name="currency" class="span10">
                            <option value="pln"<?php echo ($results->currency=='pln'?' selected="selected"':'') ?>>PLN</option>
                            <option value="usd"<?php echo ($results->currency=='usd'?' selected="selected"':'') ?>>USD</option>
                            <option value="eur"<?php echo ($results->currency=='eur'?' selected="selected"':'') ?>>EUR</option>
                        </select>
                    </div>
                    <div class="span2">
                        <label>CŁO</label>
                        <input type="text" name="clo" class="span10" value="<?php echo number_format($results->duty/10000, 2, ',', '');?>"/>
                    </div>
                    <div class="span2">
                        <label>VAT</label>
                        <input type="text" name="vat" class="span10" value="<?php echo number_format($results->tax/10000, 2, ',', '');?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <label>Dokument SAD OGL</label>
                        <input type="text" name="sad" class="span12" value="<?php echo $results->sad;?>"/>
                    </div>
                    <div class="span2">
                        <label>Data dokumentu SAD</label>
                        <input type="text" name="sad_date" id="sad_date" class="span12" value="<?php echo $results->sad_date;?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span2">
                        <label>Termin płatności</label>
                        <input type="text" id="date_payment" name="date_payment" class="span10" value="<?php echo $results->date_payment;?>"/>
                    </div>
                    <div class="span2">
                        <label>Zapłacono przez klienta</label>
                        <input type="text" name="date_paid" id="date_paid" class="span10" value="<?php echo $results->date_paid;?>"/>
                    </div>
                    <div class="span2">
                        <label>Zapłacono CŁO</label>
                        <input type="text" name="date_paid2" id="date_paid2" class="span10" value="<?php echo $results->date_paid2;?>"/>
                    </div>
                </div>
            </div>
            <div class="formSep" id="tr" style="<?php echo $results->typ == 'Transportowa'?'':'display: none;'; ?>">
                <div class="row-fluid">
                    <div class="span2">
                        <label>Należność</label>
                        <input type="text" name="valuetr" class="span10" value="<?php echo number_format($results->value / 10000, 2, ',', '');?>"/>
                    </div>
                    <div class="span2">
                        <label>Waluta</label>
                        <select name="currencytr" class="span10">
                            <option value="pln"<?php echo ($results->currency=='pln'?' selected="selected"':'') ?>>PLN</option>
                            <option value="usd"<?php echo ($results->currency=='usd'?' selected="selected"':'') ?>>USD</option>
                            <option value="eur"<?php echo ($results->currency=='eur'?' selected="selected"':'') ?>>EUR</option>
                        </select>
                    </div>
                    <div class="span2">
                        <label>VAT</label>
                        <input type="text" name="vattr" class="span10" value="<?php echo number_format($results->tax/10000, 2, ',', '');?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span2">
                        <label>Termin płatności</label>
                        <input type="text" id="date_paymenttr" name="date_paymenttr" class="span10" value="<?php echo $results->date_payment;?>"/>
                    </div>
                    <div class="span2">
                        <label>Zapłacono przez klienta</label>
                        <input type="text" name="date_paidtr" id="date_paidtr" class="span10" value="<?php echo $results->date_paid;?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label>Tytułem</label>
                        <textarea name="content" id="content" cols="10" rows="3" class="span12"><?php echo $results->content;?></textarea>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <label>Notatka</label>
                    <textarea name="note" id="note" cols="10" rows="3" class="span12"><?php echo $results->note;?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <input name="id" type="hidden" value="<?php echo $_REQUEST['id'];?>"/>
                <button class="btn btn-inverse" type="submit">Zapisz notę</button>
                <a href="/noty" class="btn">Anuluj</a>
            </div>
        </form>
    </div>
</div>