<?php

if (!is_user_logged_in()) {
    auth_redirect();
}

global $current_user;

//$results_clients = $wpdb->get_results('SELECT DISTINCT short_name,id FROM wp_czm_clients', OBJECT);
//$results_orders  = $wpdb->get_results('SELECT DISTINCT number,id FROM wp_czm_orders ORDER BY id DESC', OBJECT);
?>
<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Dodaj koszt stały</h3>
        <style>
            .hide-calendar .ui-datepicker-calendar{
                display: none !important;
            }
        </style>

        <form id="addKoszt" name="addKoszt" class="form_koszty" method="POST"
              action="<?php the_permalink(); ?>?action=new">
            <div class="formSep">
                <div class="row-fluid">
                    <div class="span2">
                        <label>Nr. faktury:</label>
                        <input type="text" id="number" name="number" class="span12" value="<?php echo $_POST['number'];?>"/>
                    </div>
                    <div class="span2">
                        <label><span class="error_placement">Rodzaj:</span> <span class="f_req">*</span></label>
                        <select name="typ" class="span12">
                            <option value="koszty stałe"<?php echo ($_POST['typ']=='koszty stałe'?' selected="selected"':'');?>>Koszty stałe</option>
                            <option value="koszty osobowe"<?php echo ($_POST['typ']=='koszty osobowe'?' selected="selected"':'');?>>Koszty osobowe</option>
                            <option value="podatki i ubezpieczenie"<?php echo ($_POST['typ']=='podatki i ubezpieczenie'?' selected="selected"':'');?>>Podatki i ubezpieczenie</option>
                            <option value="inne"<?php echo ($_POST['typ']=='inne'?' selected="selected"':'');?>>Inne</option>
                        </select>

                    </div>
                    <div class="span2">
                        <label>Dotyczy miesiąca:<span class="f_req">*</span></label>
                        <input id="date_applies" type="text" name="date_applies" class="span10" value="<?php echo $_POST['date_applies'];?>" data-calendar="false"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span2">
                        <div class="ui-widget">
                            <label>Firma:</label>
                            <input type="text" id="issuer" name="issuer" class="span12" value="<?php echo $_POST['issuer'];?>"/>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="ui-widget">
                            <label>Tytułem:<span class="f_req">*</span> </label>
                            <input type="text" id="fname" name="fname" class="span12" value="<?php echo $_POST['fname'];?>"/>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span2">
                        <div class="ui-widget">
                            <label>Netto:<span class="f_req">*</span> </label>
                            <input type="text" id="netto" name="netto" class="span9" value="<?php echo $_POST['netto'];?>"/> PLN
                        </div>
                    </div>
                    <div class="span2">
                        <div class="ui-widget">
                            <label>VAT:<span class="f_req">*</span> </label>
                            <input type="text" id="vat" name="vat" class="span9" value="<?php echo $_POST['vat'];?>"/> PLN
                        </div>
                    </div>
                    <div class="span2">
                        <div class="ui-widget">
                            <label>Brutto:<span class="f_req">*</span> </label>
                            <input type="text" id="brutto" name="brutto" class="span9" value="<?php echo $_POST['brutto'];?>"/> PLN
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span2">
                    <label>Termin płatności:<span class="f_req">*</span></label>
                    <input id="date_payment" type="text" name="date_payment" class="span10" value="<?php echo $_POST['date_payment'];?>" data-calendar="true"/>
                </div>
                <div class="span2">
                    <label>Data zapłaty</label>
                    <input id="date_paid" type="text" name="date_paid" class="span10" value="<?php echo $_POST['date_paid'];?>" data-calendar="true"/>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <label>Notatka</label>
                    <textarea name="note" id="note" cols="10" rows="3" class="span12"><?php echo $_POST['note'];?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-inverse" type="submit">Dodaj koszt</button>
                <button type="button"
                        onclick="window.location.href = 'http://'+window.location.host+window.location.pathname;"
                        class="btn">Anuluj
                </button>
            </div>
        </form>

    </div>
</div>