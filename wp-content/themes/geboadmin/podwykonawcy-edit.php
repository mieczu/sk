<pre><?php

$results_sub = $wpdb->get_results('SELECT * FROM wp_czm_subcontractor WHERE id='.$_REQUEST['id'], OBJECT);

//    var_dump($results_sub);
    ?></pre>
<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Edytuj podwykonawcę</h3>

        <form id="addSubContractor" name="addSubContractor" class="form_subcontractor" method="POST"
              action="<?php the_permalink(); ?>?action=update">
            <div class="formSep">
                <div class="row-fluid">
                    <div class="span3">
                        <label><span class="error_placement">Zablokować podwykonawcę?</span> <span
                                class="f_req">*</span></label>
                        <label class="radio inline">
                            <input type="radio" value="0" name="zablokowany"<?php echo ( $results_sub[0]->disabled==0 ?' checked="checked"':'');?>/>
                            Nie
                        </label>
                        <label class="radio inline">
                            <input type="radio" value="1" name="zablokowany"<?php echo ($results_sub[0]->disabled==1?' checked="checked"':'');?>/>
                            Tak
                        </label>

                    </div>
                    <div class="span3">
<!--                        <label>Osoba kontaktowa</label>-->
<!--                        <input type="text" name="ok" class="span12"/>-->
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label>Nazwa<span class="f_req">*</span></label>
                        <input type="text" name="fname" class="span12" value="<?php echo $results_sub[0]->name;?>"/>
                    </div>
<!--                    <div class="span3">-->
<!--                        <label>Nazwa skrócona<span class="f_req">*</span></label>-->
<!--                        <input type="text" name="fshort_name" class="span12"/>-->
<!--                    </div>-->
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label>Adres<span class="f_req">*</span></label>
                        <input type="text" name="adres" class="span12" value="<?php echo $results_sub[0]->address;?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span1">
                        <label style="white-space: nowrap;">Kod pocztowy<span
                                class="f_req">*</span></label>
                        <input type="text" name="post_code" class="span12" value="<?php echo $results_sub[0]->post_code;?>"/>
                    </div>
                    <div class="span2">
                        <label>Miasto<span class="f_req">*</span></label>
                        <input type="text" name="city" class="span12" value="<?php echo $results_sub[0]->city;?>"/>
                    </div>
                    <div class="span3">
                        <label>Państwo<span class="f_req">*</span></label>
                        <select id="kraj" name="kraj">
                            <option value=""></option>
                            <?php
                            $results = $wpdb->get_results('SELECT name, iso_code FROM wp_czm_country ORDER BY name', OBJECT_K);
                            foreach($results as $country) {
                                ?>
                                <option<?php echo ($results_sub[0]->kraj==$country->iso_code?' selected="selected"':'');?> value="<?php echo $country->iso_code; ?>"><?php echo $country->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row-fluid">

                    <div class="span6">
                        <label>NIP</label>
                        <div class="input-prepend nip">
                            <span class="add-on"><?php echo $results_sub[0]->kraj;?></span><input type="text" name="fnip" class="span10" value="<?php echo $results_sub[0]->nip;?>"/>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="row-fluid">
                    <div class="span3">
                        <label>Adres e-mail</label>
                        <input type="text" name="email" class="span12" value="<?php echo $results_sub[0]->email;?>"/>
                    </div>
                    <div class="span3">
                        <label>Konto bankowe</label>
                        <input type="text" name="account" class="span12" value="<?php echo $results_sub[0]->bank_account;?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label>Notatki</label>
                        <textarea name="message" id="message" cols="10" rows="3"
                                  class="span12"><?php echo $results_sub[0]->note;?></textarea>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <input type="hidden" name="id" value="<?php echo $results_sub[0]->id;?>">
                <button class="btn btn-inverse" type="submit">Zapisz</button>
                <button type="button"
                        onclick="window.location.href = 'http://'+window.location.host+window.location.pathname;"
                        class="btn">Anuluj
                </button>
            </div>
        </form>

    </div>
</div>