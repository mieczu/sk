<?php

if(!is_user_logged_in()) {
    auth_redirect();
}

global $wpdb, $current_user;

$results_clients = $wpdb->get_results('SELECT DISTINCT short_name,id FROM wp_czm_clients', OBJECT);
if(is_app_admin($current_user)) {
    $results = $wpdb->get_results('SELECT * FROM wp_czm_orders WHERE id='.$_REQUEST['id'], OBJECT);
}
else {
    $orderTypes1['air']  = get_user_meta($current_user->ID, 'air', true);
    $orderTypes1['sea']  = get_user_meta($current_user->ID, 'sea', true);
    $orderTypes1['land'] = get_user_meta($current_user->ID, 'land', true);

    $where   = [];
    $where[] = ($orderTypes1['air'] == 1 ? '"Lotniczy"' : '""');
    $where[] = $orderTypes1['sea'] == 1 ? '"Morski"' : '""';
    $where[] = $orderTypes1['land'] == 1 ? '"Drogowy"' : '""';
    $where   = implode(',', $where);

    $results = $wpdb->get_results('SELECT * FROM wp_czm_orders WHERE id='.$_REQUEST['id'].' AND (id_user ='.$current_user->ID.' OR transport IN('.$where.'))' , OBJECT);
}


if($results) {
    $result = $results[0];

//    if($current_user->ID == 1) {
//        echo(print_r($results, true));
//    }

    if($result->disabled == 1) {
        echo '<h3>Zlecenie zablokowane!</h3>';
    }
    else {

        //var_dump($current_user->ID);
        ?>
        <div class="row-fluid">
        <div class="span12">
        <h3 class="heading">Edytuj zlecenie</h3>

        <form id="editZlecenie" name="editZlecenie" class="form_order" method="POST"
              action="<?php the_permalink(); ?>?action=update">
        <div class="formSep">
            <div class="row-fluid">
                <div class="span4">
                    <label><span class="error_placement">Transport</span> <span class="f_req">*</span></label>
                    <label class="radio inline">
                        <input type="radio" value="Drogowy"
                               name="transport"<?php echo($result->transport == 'Drogowy' ? ' checked="checked"' : ''); ?>/>
                        Drogowy
                    </label>
                    <label class="radio inline">
                        <input type="radio" value="Lotniczy"
                               name="transport"<?php echo($result->transport == 'Lotniczy' ? ' checked="checked"' : ''); ?>/>
                        Lotniczy
                    </label>
                    <label class="radio inline">
                        <input type="radio" value="Morski"
                               name="transport"<?php echo($result->transport == 'Morski' ? ' checked="checked"' : ''); ?>/>
                        Morski
                    </label>
                </div>
                <div class="span4">
                    <label><span class="error_placement"></span> <span class="f_req">*</span></label>
                    <label class="radio inline">
                        <input type="radio" value="import"
                               name="typ"<?php echo($result->typ == 'import' ? ' checked="checked"' : ''); ?>/>
                        Import
                    </label>
                    <label class="radio inline">
                        <input type="radio" value="export"
                               name="typ"<?php echo($result->typ == 'export' ? ' checked="checked"' : ''); ?>/>
                        Export
                    </label>
                    <label class="radio inline">
                        <input type="radio" value="dw"
                               name="typ"<?php echo($result->typ == 'dw' ? ' checked="checked"' : ''); ?>/>
                        DW
                    </label>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span3">
                    <div class="ui-widget">
                        <label>Nadawca/Załadunek:<span class="f_req">*</span> </label>
                        <input type="text" id="nadawca" name="nadawca" value="<?php echo $result->nadawca; ?>"/>
                        <input type="hidden" id="idnadawca" name="idnadawca"
                               value="<?php echo $result->id_nadawca; ?>"/>
                        <!--                    <select id="nadawca" name="nadawca">-->
                        <!--                        -->
                        <!--                        --><?php //foreach($results_clients as $client) { ?>
                        <!--                            <option--><?php //echo($result->id_nadawca == $client->id ? ' selected="selected"' : ''); ?>
                        <!--                                value="--><?php //echo $client->id; ?><!--">-->
                        <?php //echo $client->short_name; ?><!--</option>-->
                        <!--                        --><?php //} ?>
                        <!---->
                        <!--                    </select>-->
                    </div>
                </div>
                <div class="span3">
                    <div class="ui-widget">
                        <label>Odbiorca/Rozładunek:<span class="f_req">*</span> </label>
                        <input type="text" id="odbiorca" name="odbiorca" value="<?php echo $result->odbiorca; ?>"/>
                        <input type="hidden" id="idodbiorca" name="idodbiorca"
                               value="<?php echo $result->id_odbiorca; ?>"/>
                        <!--                    <select id="odbiorca" name="odbiorca">-->
                        <!--                        --><?php //foreach($results_clients as $client) { ?>
                        <!--                            <option--><?php //echo($result->id_odbiorca == $client->id ? ' selected="selected"' : ''); ?>
                        <!--                                value="--><?php //echo $client->id; ?><!--">-->
                        <?php //echo $client->short_name; ?><!--</option>-->
                        <!--                        --><?php //} ?>
                        <!--                    </select>-->
                    </div>
                </div>
                <div class="span3">
                    <div class="ui-widget">
                        <label>Płatnik:<span class="f_req">*</span> </label>
                        <input type="text" id="platnik" name="platnik" value="<?php echo $result->platnik; ?>"/>
                        <input type="hidden" id="idplatnik" name="idplatnik"
                               value="<?php echo $result->id_platnik; ?>"/>


                        <!--                    <select id="platnik" name="platnik">-->
                        <!--                        -->
                        <!--                        --><?php //foreach($results_clients as $client) { ?>
                        <!--                            <option--><?php //echo($result->id_platnik == $client->id ? ' selected="selected"' : ''); ?>
                        <!--                                value="--><?php //echo $client->id; ?><!--">-->
                        <?php //echo $client->short_name; ?><!--</option>-->
                        <!--                        --><?php //} ?>
                        <!---->
                        <!--                    </select>-->
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span3">

                    <button class="btn btn-gebo" type="submit" form="link_k">Dodaj klienta</button>

                    <!--                <input type="hidden" name="user_id" value="-->
                    <!--        --><?php //echo $current_user->ID; ?><!--"/>-->
                    <!--                <button type="button" class="btn btn-info btn-lg" data-toggle="modal"-->
                    <!--                        data-target="#addClientForm">Dodaj klienta-->
                    <!--                </button>-->

                </div>
            </div>
        </div>
        <div class="formSep">
            <div class="row-fluid">
                <div class="span2">
                    <label>Icoterms</label>
                    <input type="text" id="icoterms" name="icoterms" class="span10"
                           value="<?php echo $result->icoterms; ?>"/>
                </div>

                <div id="lotniczy" class=""
                     style="<?php echo $result->transport == 'Lotniczy' ? '' : 'display: none;'; ?>">
                    <div class="span2">
                        <label>AWB</label>
                        <input id="awb" type="text" name="awb" class="span10" value="<?php echo $result->awb; ?>"/>
                    </div>
                    <div class="span2">
                        <label>HAWB</label>
                        <input type="text" name="hawb" class="span10" value="<?php echo $result->hawb; ?>"/>
                    </div>
                </div>
                <div id="morski" class="" style="<?php echo $result->transport == 'Morski' ? '' : 'display: none;'; ?>">
                    <div class="span2">
                        <label>B/L</label>
                        <input type="text" name="bl" class="span10" value="<?php echo $result->bl; ?>"/>
                    </div>
                    <div class="span2">
                        <label>HB/L</label>
                        <input type="text" name="hbl" class="span10" value="<?php echo $result->hbl; ?>"/>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span2">
                    <label>Orgin<span class="f_req">*</span></label>
                    <input type="text" name="orgin" class="span10" value="<?php echo $result->orgin; ?>"/>
                </div>
                <div class="span2">
                    <label>Destination<span class="f_req">*</span></label>
                    <input type="text" name="destination" class="span10" value="<?php echo $result->destination; ?>"/>
                </div>
                <div class="span2">
                    <label>Booking/ETD</label>
                    <input type="text" name="etd" id="etd" class="span10"
                           value="<?php echo $result->etd; ?>"/>
                </div>
                <div class="span2">
                    <label>Booking/ETA</label>
                    <input type="text" name="eta" id="eta" class="span10"
                           value="<?php echo $result->eta; ?>"/>

                </div>
            </div>
            <div class="row-fluid">
                <div class="span2">
                    <label>Waga brutto</label>
                    <input type="text" name="waga_b" class="span10" value="<?php echo $result->waga_b; ?>"/>
                </div>
                <div class="span2">
                    <label>Waga płatna</label>
                    <input type="text" name="waga_p" class="span10" value="<?php echo $result->waga_p; ?>"/>
                </div>
                <div class="span2">
                    <label>Data wykonania</label>
                    <input type="text" name="date_execute" id="date_execute" class="span10"
                           value="<?php echo $result->date_execute; ?>"/>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span2">
                    <label>Ilość opakowań</label>
                    <input type="text" name="quantity" class="span10" value="<?php echo $result->quantity; ?>"/>
                    <!-- <span class="help-block">help block</span>-->
                </div>
                <div class="span2">
                    <label>Kubatura</label>
                    <input type="text" name="kub" class="span10" value="<?php echo $result->kub; ?>"/>
                </div>
                <div class="span2">
                    <label>CMR</label>
                    <input type="text" name="cmr" class="span10" value="<?php echo $result->cmr; ?>"/>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span2">
                <label class="checkbox">
                    <input type="checkbox" value="1"
                           name="timocom"<?php echo($result->timocom == '1' ? ' checked="checked"' : ''); ?>/>
                    TIMO COM
                </label>
                <label class="checkbox">
                    <input type="checkbox" value="1"
                           name="wtransnet"<?php echo($result->wtransnet == '1' ? ' checked="checked"' : ''); ?>/>
                    WTRANSNET
                </label>
                <label class="checkbox">
                    <input type="checkbox" value="1"
                           name="teleroute"<?php echo($result->teleroute == '1' ? ' checked="checked"' : ''); ?>/>
                    TELEROUTE
                </label>
                <label class="checkbox">
                    <input type="checkbox" value="1"
                           name="oc"<?php echo($result->oc == '1' ? ' checked="checked"' : ''); ?>/>
                    Odprawa celna
                </label>
                <label class="checkbox">
                    <input type="checkbox" value="1"
                           name="fak"<?php echo($result->fak == '1' ? ' checked="checked"' : ''); ?>/>
                    Faktura za usługę
                </label>
                <label class="checkbox">
                    <input type="checkbox" value="1"
                           name="ocp"<?php echo($result->ocp == '1' ? ' checked="checked"' : ''); ?>/>
                    Weryfikacja OCP
                </label>
            </div>
            <div class="span6">
                <div class="row-fluid">
                    <div class="span4">
                        <label>ID</label>
                        <input type="text" name="transid" id="transid" class="span10"
                               value="<?php echo $result->transid; ?>"/>
                    </div>
                    <div class="span4">
                        <label>Orginał CMR/Faktury otrzymano</label>
                        <input type="text" name="fin" id="fin" class="span10" value="<?php echo $result->fin; ?>"/>
                    </div>
                    <div class="span4">
                        <label>Orginał CMR/Faktury wysłano</label>
                        <input type="text" name="fout" id="fout" class="span10" value="<?php echo $result->fout; ?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <label>Notatki</label>
                        <textarea name="message" id="message" cols="10" rows="3"
                                  class="span12"><?php echo $result->note; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>"/>
            <input id="id_order" type="hidden" name="id_order" value="<?php echo $_REQUEST['id']; ?>"/>
            <button class="btn btn-inverse" type="submit">Zapisz</button>
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

        </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="tabbable tabbable-bordered">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_br1" data-toggle="tab">Podwykonawcy</a></li>
                        <li><a href="#tab_br2" data-toggle="tab">Faktury</a></li>
                        <li><a href="#tab_br3" data-toggle="tab">Noty</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_br1">
                            <?php get_template_part('zlecenia', 'sub'); ?>
                        </div>
                        <div class="tab-pane" id="tab_br2">
                            <?php get_template_part('zlecenia', 'fak'); ?>
                        </div>
                        <div class="tab-pane" id="tab_br3">
                            <?php get_template_part('zlecenia', 'nota'); ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    <?
    }
}
else {
    echo '<h3>To zlecenie zostało usunięte lub nie należy do Ciebie, możesz edytować tylko zlecenia które sam dodałeś!</h3>';
    //    var_dump(array("'SELECT * FROM wp_czm_orders WHERE id='".$_REQUEST['id']."' AND id_user ='.$current_user->ID'",$results));
}