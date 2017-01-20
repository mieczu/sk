<?php

if(!is_user_logged_in()) {
    auth_redirect();
}

$template_url = get_template_directory_uri();

global $current_user, $wp_roles;

?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Dodaj użytkownika</h3>

        <form id="addUser" name="addUser" method="POST" action="<?php the_permalink(); ?>?action=new">
            <div class="formSep">
                <div class="row-fluid">
                    <div class="span3">
                        <label>Imię<span class="f_req">*</span></label>
                        <input class="text-input" name="first-name" type="text" id="first-name"
                               value="<?php echo $_POST['first-name']; ?>"/>
                    </div>
                    <div class="span3">
                        <label>Nazwisko<span class="f_req">*</span></label>
                        <input class="text-input" name="last-name" type="text" id="last-name"
                               value="<?php echo $_POST['last-name']; ?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <label>Login<span class="f_req">*</span></label>
                        <input class="text-input" name="login" type="text" id="login"
                               value="<?php echo $_POST['login']; ?>"/>
                    </div>
                    <div class="span3">
                        <label>Uprawnienia<span class="f_req">*</span></label>
                        <select name="role" id="role">
                            <option value=""></option>
                            <option
                                <?php echo($_POST['role'] == 'client_user' ? 'selected ' : ''); ?>value="client_user">
                                Użytkownik
                            </option>
                            <option
                                <?php echo($_POST['role'] == 'client_admin' ? 'selected ' : ''); ?>value="client_admin">
                                Administrator
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label>Email<span class="f_req">*</span></label>
                        <input class="text-input" name="email" type="text" id="email"
                               value="<?php echo $_POST['email']; ?>"/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <label>Hasło<span class="f_req">*</span></label>
                        <input class="text-input" name="password" type="password" id="password"
                               value="<?php echo $_POST['password']; ?>"/>
                    </div>
                    <div class="span3">
                        <label>Powtórz hasło<span class="f_req">*</span></label>
                        <input class="text-input" name="password_confirm" type="password" id="password_confirm"
                               value=""/>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label>Widoczność zleceń:</label>
                        <table style="width: 100%">
                            <tr>
                                <td><input type="checkbox" name="air" value="1"/> Zlecenia lotnicze</td>
                                <td><input type="checkbox" name="sea" value="1"/> Zlecenia morskie</td>
                                <td><input type="checkbox" name="land" value="1"/> Zlecenia lądowe</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label>Notatki</label>
                        <textarea name="message" id="message" cols="10" rows="3"
                                  class="span12"><?php echo $_POST['message']; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-inverse" type="submit" name="adduser">Dodaj</button>
                <button type="button"
                        onclick="window.location.href = 'http://'+window.location.host+window.location.pathname;"
                        class="btn">Anuluj
                </button>
            </div>
        </form>
    </div>
</div>


