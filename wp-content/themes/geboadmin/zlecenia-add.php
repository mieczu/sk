<?php

if(!is_user_logged_in()) {
    auth_redirect();
}

global $current_user;

$results_clients = $wpdb->get_results('SELECT DISTINCT short_name,id FROM wp_czm_clients', OBJECT);
?>
<div class="row-fluid">
<div class="span12">
<h3 class="heading">Dodaj zlecenie</h3>

<form id="addZlecenie" name="addZlecenie" class="form_order" method="POST"
      action="<?php the_permalink(); ?>?action=new">
<div class="formSep">
    <div class="row-fluid">
        <div class="span4">
            <label><span class="error_placement">Transport</span> <span class="f_req">*</span></label>
            <label class="radio inline">
                <input type="radio" value="Drogowy" name="transport"/>
                Drogowy
            </label>
            <label class="radio inline">
                <input type="radio" value="Lotniczy" name="transport"/>
                Lotniczy
            </label>
            <label class="radio inline">
                <input type="radio" value="Morski" name="transport"/>
                Morski
            </label>
        </div>
        <div class="span4">
            <label><span class="error_placement"></span> <span class="f_req">*</span></label>
            <label class="radio inline">
                <input type="radio" value="import" name="typ"/>
                Import
            </label>
            <label class="radio inline">
                <input type="radio" value="export" name="typ"/>
                Export
            </label>
            <label class="radio inline">
                <input type="radio" value="dw" name="typ"/>
                DW
            </label>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span3">
            <div class="ui-widget">
                <label>Nadawca/Załadunek:<span class="f_req">*</span> </label>
                <!--                                    <button type="button" class="btn btn-info btn-lg" id="test123">test-->
                <!--                                    </button>-->
                <input type="text" id="nadawca" name="nadawca"/>
                <input type="hidden" id="idnadawca" name="idnadawca"/>

            </div>
        </div>
        <div class="span3">
            <div class="ui-widget">
                <label>Odbiorca/Rozładunek:<span class="f_req">*</span> </label>
                <input type="text" id="odbiorca" name="odbiorca"/>
                <input type="hidden" id="idodbiorca" name="idodbiorca"/>

            </div>
        </div>
        <div class="span3">
            <div class="ui-widget">
                <label>Płatnik:<span class="f_req">*</span> </label>
                <input type="text" id="platnik" name="platnik"/>
                <input type="hidden" id="idplatnik" name="idplatnik"/>
                <span id="platnik_limit"></span>

            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span3">
            <button class="btn btn-gebo" type="submit" form="link_k">Dodaj klienta</button>
        </div>
    </div>
</div>

<div class="formSep">
    <div class="row-fluid">
        <div class="span2">
            <label>Icoterms</label>
            <input type="text" id="icoterms" name="icoterms" class="span10"/>
<!--            <select name="icoterms1" class="span10">-->
<!--                <option value=""></option>-->
<!--                <option value="EXW">EXW</option>-->
<!--                <option value="FCA">FCA</option>-->
<!--                <option value="FOB">FOB</option>-->
<!--                <option value="CPT">CPT</option>-->
<!--                <option value="CIP">CIP</option>-->
<!--                <option value="CIF">CIF</option>-->
<!--                <option value="DAP">DAP</option>-->
<!--                <option value="DAT">DAT</option>-->
<!--                <option value="DDP">DDP</option>-->
<!--            </select>-->
        </div>
        <div id="lotniczy" class="" style="display: none;">
            <div class="span2">
                <label>AWB</label>
                <input id="awb" type="text" name="awb" class="span10"/>
            </div>
            <div class="span2">
                <label>HAWB</label>
                <input type="text" name="hawb" class="span10"/>
            </div>
        </div>
        <div id="morski" class="" style="display: none;">
            <div class="span2">
                <label>B/L</label>
                <input type="text" name="bl" class="span10"/>
            </div>
            <div class="span2">
                <label>HB/L</label>
                <input type="text" name="hbl" class="span10"/>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span2">
            <label>Orgin<span class="f_req">*</span></label>
            <input type="text" name="orgin" class="span10"/>
        </div>
        <div class="span2">
            <label>Destination<span class="f_req">*</span></label>
            <input type="text" name="destination" class="span10"/>
        </div>
        <div class="span2">
            <label>Booking/ETD</label>
            <input type="text" name="etd" id="etd" class="span10"/>
        </div>
        <div class="span2">
            <label>Booking/ETA</label>
            <input type="text" name="eta" id="eta" class="span10"/>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span2">
            <label>Waga brutto</label>
            <input type="text" name="waga_b" class="span10"/>
            <!--                                <span class="help-block">help block</span>-->
        </div>
        <div class="span2">
            <label>Waga płatna</label>
            <input type="text" name="waga_p" class="span10"/>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span2">
            <label>Ilość opakowań</label>
            <input type="text" name="quantity" class="span10"/>
            <!--                                <span class="help-block">help block</span>-->
        </div>
        <div class="span2">
            <label>Kubatura</label>
            <input type="text" name="kub" class="span10"/>
        </div>
        <div class="span2">
            <label>CMR</label>
            <input type="text" name="cmr" class="span10"/>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span2">
        <label class="checkbox">
            <input type="checkbox" value="1" name="timocom">
            TIMO COM
        </label>
        <label class="checkbox">
            <input type="checkbox" value="1" name="wtransnet">
            WTRANSNET
        </label>
        <label class="checkbox">
            <input type="checkbox" value="1" name="teleroute">
            TELEROUTE
        </label>
        <label class="checkbox">
            <input type="checkbox" value="1" name="oc">
            Odprawa celna
        </label>
        <label class="checkbox">
            <input type="checkbox" value="1" name="fak">
            Faktura za usługę
        </label>
        <label class="checkbox">
            <input type="checkbox" value="1" name="ocp">
            Weryfikacja OCP
        </label>
    </div>
    <div class="span6">
        <div class="row-fluid">
            <div class="span4">
                <label>ID</label>
                <input type="text" name="transid" id="transid" class="span10"/>
            </div>
            <div class="span4">
                <label>Orginał CMR/Faktury otrzymano</label>
                <input type="text" name="fin" id="fin" class="span10"/>
            </div>
            <div class="span4">
                <label>Orginał CMR/Faktury wysłano</label>
                <input type="text" name="fout" id="fout" class="span10"/>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <label>Notatki</label>
                <textarea name="message" id="message" cols="10" rows="3" class="span12"></textarea>
            </div>
        </div>
    </div>
</div>
<div class="form-actions">
    <button id="add_zlecenie" class="btn btn-inverse" type="submit">Dodaj zlecenie</button>
    <button type="button"
            onclick="window.location.href = 'http://'+window.location.host+window.location.pathname;"
            class="btn">Anuluj
    </button>
</div>
</form>
<form id="link_k" method="post" action="/klienci" target="_blank">
    <input type="hidden" name="action" value="add">
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