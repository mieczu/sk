<?php if(!is_user_logged_in()) {
    auth_redirect();
}

global $current_user;

//$results_sub = $wpdb->get_results('SELECT name,id FROM wp_czm_subcontractor ORDER BY 2 DESC', OBJECT);

if(isset($_REQUEST['id_invoice']) && is_numeric($_REQUEST['id_invoice'])) {
    $results = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id='.$_REQUEST['id_invoice'].' ORDER BY 2 DESC', OBJECT);
    $results = $results['0'];

    $results_clients = $wpdb->get_results('SELECT * FROM wp_czm_clients WHERE id='.$results->id_client.' ORDER BY 2 DESC', OBJECT);
    $results_clients = $results_clients['0'];

    $results_order = $wpdb->get_results('SELECT * FROM wp_czm_orders WHERE id='.$results->id_order.' ORDER BY 2 DESC', OBJECT);
    $results_order = $results_order['0'];
    ?>
    <div class="row-fluid">
        <div class="span12">
            <h3 class="heading">Dodaj fakturę korygującą</h3>

            <form id="addkorekta" name="addkorekta" class="form_korekta" method="POST"
                  action="<?php the_permalink(); ?>?action=new">
                <div class="formSep">
                    <div class="row-fluid">
                        <div class="span2">
                            <label><span class="error_placement">Numer faktury</span> <span
                                    class="f_req">*</span></label>
                            <input type="text" value="<?php echo $results->numer; ?>" disabled class="span10">
                        </div>
                        <div class="span2">
                            <label><span class="error_placement">Data wystawienia</span> <span
                                    class="f_req">*</span></label>
                            <input type="text" value="<?php echo substr($results->date_add, 0, 10); ?>" disabled
                                   class="span10">
                        </div>
                        <div class="span2">
                            <label><span class="error_placement">Numer zlecenia</span> <span
                                    class="f_req">*</span></label>
                            <input type="text" value="<?php echo $results_order->number; ?>" disabled class="span10">
                        </div>
                        <div class="span2">
                            <label><span class="error_placement">Data wykonania usługi</span> <span
                                    class="f_req">*</span></label>
                            <input type="text" value="<?php echo $results_order->date_execute; ?>" disabled
                                   class="span10">

                        </div>
                    </div>
                </div>
                <div class="formSep">
                    <div class="row-fluid">
                        <div class="span12">
                            <label><span class="error_placement">Klient</span> <span class="f_req">*</span></label>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3">
                            <label>Nazwa<span class="f_req">*</span></label>
                            <input type="text" name="fname" class="span12"
                                   value="<?php echo $results_clients->name; ?>"/>
                        </div>
                        <div class="span3">
                            <label>Adres<span class="f_req">*</span></label>
                            <input type="text" name="adres" class="span12"
                                   value="<?php echo $results_clients->address; ?>"/>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span1">
                            <label style="white-space: nowrap;">Kod pocztowy<span
                                    class="f_req">*</span></label>
                            <input type="text" name="post_code" class="span12"
                                   value="<?php echo $results_clients->post_code; ?>"/>
                        </div>
                        <div class="span2">
                            <label>Miasto<span class="f_req">*</span></label>
                            <input type="text" name="city" class="span12"
                                   value="<?php echo $results_clients->city; ?>"/>
                        </div>
                        <div class="span2">
                            <label>NIP<span class="f_req">*</span></label>

                            <div class="input-prepend nip">
                                <span class="add-on">
                                    <?php echo $results_clients->kraj; ?>
                                </span><input type="text" name="fnip" class="span10"
                                              value="<?php echo $results_clients->nip; ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span2">
                        <label><span class="error_placement">Termin płatności</span> <span
                                class="f_req">*</span></label>
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
                            <option value="usd"<?php echo($results->currency == 'usd' ? ' selected="selected"' : '') ?>>
                                USD
                            </option>
                            <option value="eur"<?php echo($results->currency == 'eur' ? ' selected="selected"' : '') ?>>
                                EUR
                            </option>
                            <option value="pln"<?php echo($results->currency == 'pln' ? ' selected="selected"' : '') ?>>
                                PLN
                            </option>
                        </select>
                    </div>
                    <div class="span2">
                        <label>Waluta VAT-u: <span class="f_req">*</span></label>
                        <select name="vat_currency" id="currency" class="span10">
                            <option
                                value="eur"<?php echo($results->vat_currency == 'eur' ? ' selected="selected"' : '') ?>>
                                EUR
                            </option>
                            <option
                                value="pln"<?php echo($results->vat_currency == 'pln' ? ' selected="selected"' : '') ?>>
                                PLN
                            </option>
                            <option
                                value="usd"<?php echo($results->vat_currency == 'usd' ? ' selected="selected"' : '') ?>>
                                USD
                            </option>
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
                            <option selected="selected"
                                    value="<?php echo date('Y'); ?>">
                                <?php echo date('Y'); ?></option>
                            <?php
                            for($i = date('Y') - 1; $i >= 2015; $i--) {

                                ?>
                                <option value="<?php echo $i; ?>">
                                    <?php echo $i; ?></option>
                            <?php
                            }

                            ?>
                        </select>

                        <select name="tabela" id="tabela" style="width: 75px;" class="tabela">
                            <option></option>
                        </select>
                    </div>
                </div>
                <div class="row-fluid">
                    <?php get_template_part('korekty', 'items'); ?>
                </div>
        </div>
        <div class="row-fluid">
            <div class="span8">
                <label>Przyczyna korekty<span class="f_req">*</span></label>
                <textarea name="reason" id="reason" cols="10" rows="3" class="span12"></textarea>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span8">
                <label>Notatki</label>
                <textarea name="message" id="message" cols="10" rows="3" class="span12"></textarea>
            </div>
        </div>
        <div class="form-actions">
            <input class="id_invoice" name="id_invoice" type="hidden"
                   value="<?php echo $_REQUEST['id_invoice']; ?>">
            <input class="id_client" name="id_client" type="hidden"
                   value="<?php echo $results->id_client; ?>">
            <button class="btn btn-inverse" type="submit">Dodaj fakturę</button>
            <button type="button"
                    onclick="window.location.href = 'http://'+window.location.host+window.location.pathname;"
                    class="btn">Anuluj
            </button>
        </div>
        </form>

    </div>
    </div>

<?php
}
else {
    echo('Błędne ID faktury sprzedaży');
}
?>