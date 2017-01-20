<?php
if(!is_user_logged_in()) {
    auth_redirect();
}

global $current_user;

$results_clients = $wpdb->get_results('SELECT * FROM wp_czm_clients WHERE id='.$_REQUEST['id'], OBJECT);

//var_dump($results_clients)

?>
<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Edytuj klienta</h3>

        <form id="addClient" name="addClient" class="form_client" method="POST"
              action="<?php the_permalink(); ?>/?action=update">
            <div class="formSep">
                <div class="row-fluid">
                    <div class="span3">
                        <label><span class="error_placement">Zablokować klienta?</span> <span
                                class="f_req">*</span></label>
                        <label class="radio inline">
                            <input type="radio" value="0"
                                   name="zablokowany"<?php echo($results_clients[0]->disabled == 0 ? ' checked="checked"' : ''); ?>/>
                            Nie
                        </label>
                        <label class="radio inline">
                            <input type="radio" value="1"
                                   name="zablokowany"<?php echo($results_clients[0]->disabled == 1 ? ' checked="checked"' : ''); ?>/>
                            Tak
                        </label>

                    </div>
                    <div class="span3">
                        <label>Osoba kontaktowa</label>
                        <input type="text" name="ok" class="span12" value="<?php echo $results_clients[0]->ok; ?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <label>Nazwa<span class="f_req">*</span></label>
                        <input type="text" name="fname" class="span12"
                               value="<?php echo $results_clients[0]->name; ?>"/>
                    </div>
                    <div class="span3">
                        <label>Nazwa skrócona<span class="f_req">*</span></label>
                        <input type="text" name="fshort_name" class="span12"
                               value="<?php echo $results_clients[0]->short_name; ?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label>Adres<span class="f_req">*</span></label>
                        <input type="text" name="adres" class="span12"
                               value="<?php echo $results_clients[0]->address; ?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span1">
                        <label style="white-space: nowrap;">Kod pocztowy<span
                                class="f_req">*</span></label>
                        <input type="text" name="post_code" class="span12"
                               value="<?php echo $results_clients[0]->post_code; ?>"/>
                    </div>
                    <div class="span2">
                        <label>Miasto<span class="f_req">*</span></label>
                        <input type="text" name="city" class="span12" value="<?php echo $results_clients[0]->city; ?>"/>
                    </div>
                    <div class="span3">
                        <label>Państwo<span class="f_req">*</span></label>
                        <select id="kraj" name="kraj">
                            <option value=""></option>
                            <?php
                            $results = $wpdb->get_results('SELECT name, iso_code FROM wp_czm_country ORDER BY name', OBJECT_K);
                            foreach($results as $country) {
                                ?>
                                <option<?php echo($results_clients[0]->kraj == $country->iso_code ? ' selected="selected"' : ''); ?>
                                    value="<?php echo $country->iso_code; ?>"><?php echo $country->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span7">
                        <label>NIP<span class="f_req">*</span></label>

                        <div class="input-prepend nip">
                            <span class="add-on"><?php echo $results_clients[0]->kraj; ?></span><input type="text"
                                                                                                       name="fnip"
                                                                                                       class="span10"
                                                                                                       value="<?php echo $results_clients[0]->nip; ?>"/>
                        </div>
                        <button id="vies" class="btn btn-gebo" type="button">Sprawć numer w systemie VIES</button>
                        <button id="gus" class="btn btn-gebo"
                                type="button" <?php echo($results_clients[0]->kraj != 'PL' ? 'disabled' : ''); ?>>
                            Pobierz dane z GUS
                        </button>
                        <button id="vies2" class="btn btn-gebo" type="submit" name="check" value="Weryfikuj"
                                form="drugi">Sprawdź na stronie VIES
                        </button>
                    </div>
                </div>
            </div>
            <div>
                <div class="row-fluid">
                    <div class="span3">
                        <label>Adres e-mail</label>
                        <input type="text" name="email" class="span12"
                               value="<?php echo $results_clients[0]->email; ?>"/>
                    </div>
                    <div class="span3">
                        <label>Konto bankowe</label>
                        <input type="text" name="account" class="span12"
                               value="<?php echo $results_clients[0]->bank_account; ?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label>Notatki</label>
                        <textarea name="message" id="message" cols="10" rows="3"
                                  class="span12"><?php echo $results_clients[0]->note; ?></textarea>
                    </div>
                </div>
                <?php if(is_app_admin($current_user)) { ?>
                    <div class="row-fluid">
                        <div class="span3">
                            <label>Limit maksymalnej zaległości</label>
                            <input type="text" name="limity" class="span12"
                                   value="<?php echo $results_clients[0]->limity; ?>"/>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="form-actions">
                <input type="hidden" name="id" value="<?php echo $results_clients[0]->id; ?>"/>
                <button class="btn btn-inverse" type="submit">Zapisz</button>
                <button type="button"
                        onclick="window.location.href = 'http://'+window.location.host+window.location.pathname;"
                        class="btn">Anuluj
                </button>
            </div>
        </form>

        <form id="drugi" class="form_client2" method="post"
              action="http://ec.europa.eu/taxation_customs/vies/vatResponse.html" target="_blank">
            <input type="text" id="memberStateCode" name="memberStateCode"
                   value="<?php echo $results_clients[0]->kraj; ?>" style="visibility: hidden;">
            <input type="text" id="number" name="number" value="<?php echo $results_clients[0]->nip; ?>"
                   style="visibility: hidden;">
            <input type="hidden" name="requesterMemberStateCode" value="PL">
            <input type="hidden" name="requesterNumber" value="5641054535">
            <input type="hidden" name="action" value="check">
        </form>
    </div>
</div>