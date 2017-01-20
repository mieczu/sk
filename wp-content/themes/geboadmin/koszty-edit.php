<?php

if (!is_user_logged_in()) {
    auth_redirect();
}

global $current_user;
$results = $wpdb->get_results('SELECT * FROM wp_czm_costs WHERE id='.$_REQUEST['id'], ARRAY_A);

if($results) {
    $results = $results[0];
}else{
    $results = $_POST;
}
//var_dump($results);
?>
<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Edytuj koszt stały</h3>
        <style>
            .hide-calendar .ui-datepicker-calendar{
                display: none !important;
            }
        </style>

        <form id="addKoszt" name="addKoszt" class="form_koszty" method="POST"
              action="<?php the_permalink(); ?>?action=update">
            <div class="formSep">
                <div class="row-fluid">
                    <div class="span2">
                        <label>Nr. faktury:</label>
                        <input type="text" id="number" name="number" class="span12" value="<?php echo $results['number'];?>"/>
                    </div>
                    <div class="span2">
                        <label><span class="error_placement">Rodzaj:</span> <span class="f_req">*</span></label>
                        <select name="typ" class="span12">
                            <option value="koszty stałe"<?php echo ($results['typ']=='koszty stałe'?' selected="selected"':'');?>>Koszty stałe</option>
                            <option value="koszty osobowe"<?php echo ($results['typ']=='koszty osobowe'?' selected="selected"':'');?>>Koszty osobowe</option>
                            <option value="podatki i ubezpieczenie"<?php echo ($results['typ']=='podatki i ubezpieczenie'?' selected="selected"':'');?>>Podatki i ubezpieczenie</option>
                            <option value="inne"<?php echo ($results['typ']=='inne'?' selected="selected"':'');?>>Inne</option>
                        </select>

                    </div>
                    <div class="span2">
                        <label>Dotyczy miesiąca:<span class="f_req">*</span></label>
                        <input id="date_applies" type="text" name="date_applies" class="span10" value="<?php echo $results['date_applies'];?>" data-calendar="false"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span2">
                        <div class="ui-widget">
                            <label>Firma:</label>
                            <input type="text" id="issuer" name="issuer" class="span12" value="<?php echo $results['issuer'];?>"/>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="ui-widget">
                            <label>Tytułem:<span class="f_req">*</span> </label>
                            <input type="text" id="fname" name="fname" class="span12" value="<?php echo $results['name'];?>"/>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span2">
                        <div class="ui-widget">
                            <label>Netto:<span class="f_req">*</span> </label>
                            <input type="text" id="netto" name="netto" class="span9" value="<?php echo $results['netto']/10000;?>"/> PLN
                        </div>
                    </div>
                    <div class="span2">
                        <div class="ui-widget">
                            <label>VAT:<span class="f_req">*</span> </label>
                            <input type="text" id="vat" name="vat" class="span9" value="<?php echo $results['vat']/10000;?>"/> PLN
                        </div>
                    </div>
                    <div class="span2">
                        <div class="ui-widget">
                            <label>Brutto:<span class="f_req">*</span> </label>
                            <input type="text" id="brutto" name="brutto" class="span9" value="<?php echo $results['brutto']/10000;?>"/> PLN
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span2">
                    <label>Termin płatności:<span class="f_req">*</span></label>
                    <input id="date_payment" type="text" name="date_payment" class="span10" value="<?php echo $results['date_payment'];?>" data-calendar="true"/>
                </div>
                <div class="span2">
                    <label>Data zapłaty</label>
                    <input id="date_paid" type="text" name="date_paid" class="span10" value="<?php echo $results['date_paid'];?>" data-calendar="true"/>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <label>Notatka</label>
                    <textarea name="note" id="note" cols="10" rows="3" class="span12"><?php echo $results['note'];?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>"/>
                <button class="btn btn-inverse" type="submit">Zapisz</button>
                <button type="button"
                        onclick="window.location.href = 'http://'+window.location.host+window.location.pathname;"
                        class="btn">Anuluj
                </button>
            </div>
        </form>

    </div>
</div>