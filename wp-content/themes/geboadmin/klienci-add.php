<?php if(!is_user_logged_in()) {
    auth_redirect();
} ?>
<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Dodaj klienta</h3>

        <form id="addClient" name="addClient" class="form_client" method="POST"
              action="<?php the_permalink(); ?>?action=new">
            <div class="formSep">
                <div class="row-fluid">
                    <div class="span3">
                        <label><span class="error_placement">Zablokować klienta?</span> <span
                                class="f_req">*</span></label>
                        <label class="radio inline">
                            <input type="radio" value="0" name="zablokowany" checked="checked"/>
                            Nie
                        </label>
                        <label class="radio inline">
                            <input type="radio" value="1" name="zablokowany"/>
                            Tak
                        </label>

                    </div>
                    <div class="span3">
                        <label>Osoba kontaktowa</label>
                        <input type="text" name="ok" class="span12"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <label>Nazwa<span class="f_req">*</span></label>
                        <input type="text" name="fname" class="span12"/>
                    </div>
                    <div class="span3">
                        <label>Nazwa skrócona<span class="f_req">*</span></label>
                        <input type="text" name="fshort_name" class="span12"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label>Adres<span class="f_req">*</span></label>
                        <input type="text" name="adres" class="span12"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span1">
                        <label style="white-space: nowrap;">Kod pocztowy<span
                                class="f_req">*</span></label>
                        <input type="text" name="post_code" class="span12"/>
                    </div>
                    <div class="span2">
                        <label>Miasto<span class="f_req">*</span></label>
                        <input type="text" name="city" class="span12"/>
                    </div>
                    <div class="span3">
                        <label>Państwo<span class="f_req">*</span></label>
                        <select id="kraj" name="kraj">
                            <option value=""></option>
                            <?php
                            $results = $wpdb->get_results('SELECT name, iso_code FROM wp_czm_country ORDER BY name', OBJECT_K);
                            foreach($results as $country) {
                                ?>
                                <option
                                    value="<?php echo $country->iso_code; ?>"><?php echo $country->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row-fluid">

                    <div class="span7">
                        <label>NIP<span class="f_req">*</span></label>
                        <div class="input-prepend nip">
                            <span class="add-on"></span><input id="fnip" type="text" name="fnip" class="span10"/>
                        </div>
                        <button id="vies" class="btn btn-gebo" type="button" disabled>Sprawć numer w systemie VIES </button>
                        <button id="gus" class="btn btn-gebo" type="button" disabled>Pobierz dane z GUS</button>
                        <button id="vies2" class="btn btn-gebo" type="submit" name="check" value="Weryfikuj" form="drugi" disabled>Sprawdź na stronie VIES</button>
                    </div>
                </div>
            </div>
            <div>
                <div class="row-fluid">
                    <div class="span3">
                        <label>Adres e-mail</label>
                        <input type="text" name="email" class="span12"/>
                    </div>
                    <div class="span3">
                        <label>Konto bankowe</label>
                        <input type="text" name="account" class="span12"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label>Notatki</label>
                        <textarea name="message" id="message" cols="10" rows="3"
                                  class="span12"></textarea>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-inverse" type="submit">Dodaj klienta</button>
                <button type="button"
                        onclick="window.location.href = 'http://'+window.location.host+window.location.pathname;"
                        class="btn">Anuluj
                </button>
            </div>
        </form>
        <form id="drugi" class="form_client2" method="post" action="http://ec.europa.eu/taxation_customs/vies/vatResponse.html" target="_blank">
            <input type="text" id="memberStateCode" name="memberStateCode" value="" style="visibility: hidden;">
            <input type="text" id="number" name="number" value="" style="visibility: hidden;">
            <input type="hidden" name="requesterMemberStateCode" value="PL">
            <input type="hidden" name="requesterNumber" value="5641054535">
            <input type="hidden" name="action" value="check">
        </form>
    </div>
</div>