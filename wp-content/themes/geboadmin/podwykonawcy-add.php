<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Dodaj podwykonawcę</h3>

        <form id="addSubContractor" name="addSubContractor" class="form_subcontractor" method="POST"
              action="<?php the_permalink(); ?>?action=new">
            <div class="formSep">
                <div class="row-fluid">
                    <div class="span3">
                        <label><span class="error_placement">Zablokować podwykonawcę?</span> <span
                                class="f_req">*</span></label>
                        <label class="radio inline">
                            <input type="radio" value="0" name="zablokowany"<?php echo (!isset($_POST['zablokowany']) || $_POST['zablokowany']==0 ?' checked="checked"':'');?>/>
                            Nie
                        </label>
                        <label class="radio inline">
                            <input type="radio" value="1" name="zablokowany"<?php echo ($_POST['zablokowany']==1?' checked="checked"':'');?>/>
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
                        <input type="text" name="fname" class="span12" value="<?php echo $_POST['fname'];?>"/>
                    </div>
<!--                    <div class="span3">-->
<!--                        <label>Nazwa skrócona<span class="f_req">*</span></label>-->
<!--                        <input type="text" name="fshort_name" class="span12"/>-->
<!--                    </div>-->
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label>Adres<span class="f_req">*</span></label>
                        <input type="text" name="adres" class="span12" value="<?php echo $_POST['adres'];?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span1">
                        <label style="white-space: nowrap;">Kod pocztowy<span
                                class="f_req">*</span></label>
                        <input type="text" name="post_code" class="span12" value="<?php echo $_POST['post_code'];?>"/>
                    </div>
                    <div class="span2">
                        <label>Miasto<span class="f_req">*</span></label>
                        <input type="text" name="city" class="span12" value="<?php echo $_POST['city'];?>"/>
                    </div>
                    <div class="span3">
                        <label>Państwo<span class="f_req">*</span></label>
                        <select id="kraj" name="kraj">
                            <option value=""></option>
                            <?php
                            $results = $wpdb->get_results('SELECT name, iso_code FROM wp_czm_country ORDER BY name', OBJECT_K);
                            foreach($results as $country) {
                                ?>
                                <option<?php echo ($_POST['kraj']==$country->iso_code?' selected="selected"':'');?> value="<?php echo $country->iso_code; ?>"><?php echo $country->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row-fluid">

                    <div class="span6">
                        <label>NIP</label>
                        <div class="input-prepend nip">
                            <span class="add-on"><?php echo $_POST['kraj'];?></span><input type="text" name="fnip" class="span10" value="<?php echo $_POST['fnip'];?>"/>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="row-fluid">
                    <div class="span3">
                        <label>Adres e-mail</label>
                        <input type="text" name="email" class="span12" value="<?php echo $_POST['email'];?>"/>
                    </div>
                    <div class="span3">
                        <label>Konto bankowe</label>
                        <input type="text" name="account" class="span12" value="<?php echo $_POST['account'];?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label>Notatki</label>
                        <textarea name="message" id="message" cols="10" rows="3"
                                  class="span12"><?php echo $_POST['message'];?></textarea>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-inverse" type="submit">Dodaj podwykonawcę</button>
                <button type="button"
                        onclick="window.location.href = 'http://'+window.location.host+window.location.pathname;"
                        class="btn">Anuluj
                </button>
            </div>
        </form>

    </div>
</div>