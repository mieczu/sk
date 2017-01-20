<?php

if(!is_user_logged_in()) {
    auth_redirect();
}

global $current_user;

$results_clients = $wpdb->get_results('SELECT DISTINCT short_name,id FROM wp_czm_clients', OBJECT);
$results_orders  = $wpdb->get_results('SELECT DISTINCT number,id FROM wp_czm_orders ORDER BY id DESC', OBJECT);
?>
<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Dodaj notę</h3>

        <form id="addNota" name="addNota" class="form_order" method="POST"
              action="<?php the_permalink(); ?>?action=new">
            <div class="formSep">
                <div class="row-fluid">

                    <div class="span4">
                        <label><span class="error_placement">Typ:</span> <span class="f_req">*</span></label>
                        <label class="radio inline">
                            <input type="radio" value="Księgowa" name="typ"/>
                            Księgowa
                        </label>
                        <label class="radio inline">
                            <input type="radio" value="Transportowa" name="typ"/>
                            Transportowa
                        </label>

                    </div>
                    <div class="span3">
                        <label><span class="error_placement">Szablon:</span> <span class="f_req">*</span></label>
                        <select name="lang">
                            <option value="0">PL</option>
                            <option value="1">EN</option>
                        </select>

                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <div class="ui-widget">
                            <label>Klient:<span class="f_req">*</span> </label>
                            <!--                            <input type="text" id="klient" name="klient"/>-->
                            <!--                            <input type="hidden" id="idklient" name="idklient"/>-->
                            <select id="klient" name="klient">
                                <option value=""></option>
                                <?php foreach($results_clients as $client) { ?>
                                    <option
                                        value="<?php echo $client->id; ?>"><?php echo $client->short_name; ?></option>
                                <?php } ?>

                            </select>

                        </div>
                    </div>
                    <div class="span3">
                        <div class="ui-widget">
                            <label>Zlecenie:<span class="f_req"></span> </label>
                            <!--                            <input type="text" id="zlecenie" name="zlecenie"/>-->
                            <!--                            <input type="hidden" id="idzlecenie" name="idzlecenie"/>-->
                            <select id="zlecenie" name="zlecenie">
                                <option value=""></option>
                                <?php foreach($results_orders as $order) { ?>
                                    <option
                                        value="<?php echo $order->id; ?>"><?php echo $order->number; ?></option>
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
            <div class="formSep" id="ks" style="display: none;">
                <div class="row-fluid">
                    <div class="span2">
                        <label>Należność</label>
                        <input type="text" name="value" class="span10"/>
                    </div>
                    <div class="span2">
                        <label>Waluta</label>
                        <select name="currency" class="span10">
                            <option value="pln">PLN</option>
                            <option value="usd">USD</option>
                            <option value="eur">EUR</option>
                        </select>
                    </div>
                    <div class="span2">
                        <label>CŁO</label>
                        <input type="text" name="clo" class="span10"/>
                    </div>
                    <div class="span2">
                        <label>VAT</label>
                        <input type="text" name="vat" class="span10"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <label>Dokument SAD OGL</label>
                        <input type="text" name="sad" class="span12"/>
                    </div>
                    <div class="span2">
                        <label>Data dokumentu SAD</label>
                        <input type="text" name="sad_date" id="sad_date" class="span12"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span2">
                        <label>Termin płatności</label>
                        <input type="text" id="date_payment" name="date_payment" class="span10"/>
                    </div>
                    <div class="span2">
                        <label>Zapłacono przez klienta</label>
                        <input type="text" name="date_paid" id="date_paid" class="span10"/>
                    </div>
                    <div class="span2">
                        <label>Zapłacono CŁO</label>
                        <input type="text" name="date_paid2" id="date_paid2" class="span10"/>
                    </div>
                </div>
            </div>
            <div class="formSep" id="tr" style="display: none;">
                <div class="row-fluid">
                    <div class="span2">
                        <label>Należność</label>
                        <input type="text" name="valuetr" class="span10"/>
                    </div>
                    <div class="span2">
                        <label>Waluta</label>
                        <select name="currencytr" class="span10">
                            <option value="pln">PLN</option>
                            <option value="usd">USD</option>
                            <option value="eur">EUR</option>
                        </select>
                    </div>
                    <div class="span2">
                        <label>VAT</label>
                        <input type="text" name="vattr" class="span10"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span2">
                        <label>Termin płatności</label>
                        <input type="text" id="date_paymenttr" name="date_paymenttr" class="span10"/>
                    </div>
                    <div class="span2">
                        <label>Zapłacono przez klienta</label>
                        <input type="text" name="date_paidtr" id="date_paidtr" class="span10"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label>Tytułem</label>
                        <textarea name="content" id="content" cols="10" rows="3" class="span12"></textarea>
                    </div>
                </div>

            </div>
            <div class="row-fluid">
                <div class="span6">
                    <label>Notatka</label>
                    <textarea name="note" id="note" cols="10" rows="3" class="span12"></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-inverse" type="submit">Dodaj notę</button>
                <button type="button"
                        onclick="window.location.href = 'http://'+window.location.host+window.location.pathname;"
                        class="btn">Anuluj
                </button>
            </div>
        </form>
        <!-- Modal -->
        <div id="addClientForm" class="modal fade" role="dialog">

            <div class="modal-header">
                <a class="close" data-dismiss="modal">×</a>

                <h3>Dodaj klienta</h3>
            </div>
            <div class="modal-body">
                <form id="abc" name="abc">
                    <label class="label" for="name">Your Name</label><br>
                    <input type="text" name="name" class="input-xlarge"><br>
                    <label class="label" for="email">Your E-mail</label><br>
                    <input type="email" name="email" class="input-xlarge"><br>
                    <label class="label" for="message">Enter a Message</label><br>
                    <textarea name="message" class="input-xlarge"></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <input class="btn btn-success" type="submit" value="Send!" id="submit">
                <!--                                        <a href="#" class="btn" data-dismiss="modal">Nah.</a>-->
            </div>

            <div id="thanks"><p></p></div>
        </div>
    </div>
</div>