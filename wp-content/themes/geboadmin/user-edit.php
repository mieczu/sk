<?php
if(!is_user_logged_in()) {
    auth_redirect();
}

if (!is_numeric($_REQUEST['id'])){
    die('Nie wybrano użytkownika do edycji');
}

$user = get_user_by('id', $_REQUEST['id']);

//var_dump($user->data);
?>
<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Edytuj użytkownika</h3>
        <?php
//        var_dump($user->data);
//        var_dump($user->roles);
        ?>
        <form id="editUser" name="editUser" method="POST" action="<?php the_permalink(); ?>?action=update">
            <input type="hidden" name="user_id" value="<?php echo $user->ID;?>"/>
            <div class="formSep">
                <div class="row-fluid">
                    <div class="span3">
                        <label>Imię<span class="f_req">*</span></label>
                        <input class="text-input" name="first-name" type="text" id="first-name" value="<?php echo $user->first_name; ?>"/>
                    </div>
                    <div class="span3">
                        <label>Nazwisko<span class="f_req">*</span></label>
                        <input class="text-input" name="last-name" type="text" id="last-name" value="<?php echo $user->last_name; ?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <label>Login</label>
                        <input class="text-input" name="login" type="text" id="login" value="<?php echo $user->user_login ?>"/>
                    </div>
                    <div class="span3">
                        <label>Uprawnienia<span class="f_req">*</span></label>
                        <select name="role" id="role">
                            <option <?php echo ($user->roles[1]=='client_user'?'selected ':'');?>value="client_user">Użytkownik</option>
                            <option <?php echo ($user->roles[1]=='client_admin'?'selected ':'');?>value="client_admin">Administrator</option>
                        </select>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <label>Email<span class="f_req">*</span></label>
                        <input class="text-input" name="email" type="text" id="email" value="<?php echo $user->user_email; ?>"/>
                    </div>
                    <div class="span3">
                        <label>Status konta<span class="f_req">*</span></label>
                        <select name="lock" id="lock">
                            <?php
                            $user_status = get_user_meta( $user->ID,'account_locked');
//                            var_dump($user_status);
                            ?>
                            <option <?php echo ($user_status[0]==0?'selected ':'');?>value="0">Odblokowane</option>
                            <option <?php echo ($user_status[0]==1?'selected ':'');?>value="1">Zablokowane</option>
                        </select>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <label>Hasło<span class="f_req">*</span></label>
                        <input class="text-input" name="password" type="password" id="password" value=""/>
                    </div>
                    <div class="span3">
                        <label>Powtórz hasło<span class="f_req">*</span></label>
                        <input class="text-input" name="password_confirm" type="password" id="password_confirm" value=""/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label>Widoczność zleceń:</label>
                        <table style="width: 100%">
                            <tr>
                                <?php
                                $orderTypes = [];
                                $orderTypes['air'] = get_user_meta( $user->ID,'air');
                                $orderTypes['sea'] = get_user_meta( $user->ID,'sea');
                                $orderTypes['land'] = get_user_meta( $user->ID,'land');
                                ?>
                                <td><input type="checkbox" name="air" value="1"<?php echo ($orderTypes['air'][0]==1?' checked="checked"':'nie');?>/> Zlecenia lotnicze</td>
                                <td><input type="checkbox" name="sea" value="1"<?php echo ($orderTypes['sea'][0]==1?' checked="checked"':'nie');?>/> Zlecenia morskie</td>
                                <td><input type="checkbox" name="land" value="1"<?php echo ($orderTypes['land'][0]==1?' checked="checked"':'nie');?>/> Zlecenia lądowe</td>
                            </tr>
<!--                            <tr>-->
<!--                                <td>--><?php //var_dump($orderTypes)?><!--</td>-->
<!--                            </tr>-->
                        </table>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label>Notatki</label>
                        <textarea name="message" id="message" cols="10" rows="3"
                                  class="span12"><?php echo $user->description;?></textarea>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-inverse" type="submit" name="adduser">Zapisz</button>
                <button type="button"
                        onclick="window.location.href = 'http://'+window.location.host+window.location.pathname;"
                        class="btn">Anuluj
                </button>
            </div>
        </form>
    </div>
</div>