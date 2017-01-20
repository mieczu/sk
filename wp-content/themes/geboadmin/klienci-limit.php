<?php
if(!is_user_logged_in()) {
    auth_redirect();
}

//$results_clients = $wpdb->get_results('SELECT * FROM wp_czm_clients WHERE id='.$_REQUEST['id'], OBJECT);



?>
<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Limity nowych klientów</h3>
        <?php
        if(isset($_POST["update_settings"])) {
            $limitPl  = esc_attr($_POST["limitPl"]);
            $limitAll = esc_attr($_POST["limitAll"]);


            delete_option("limitPl");
            delete_option("limitAll");


            if(add_option("limitPl", $limitPl)) {
                ?>
                <div class="alert alert-success">Limit dla firm z Polski został zapisany<a class="close" data-dismiss="alert">×</a></div>
            <?php
            }
            else {
                ?>
                <div class="alert alert-error">Nie można zapisać limitu dla firm z Polski.<a class="close" data-dismiss="alert">×</a></div>
            <?php
            }

            if(add_option("limitAll", $limitAll)) {
                ?>
                <div class="alert alert-success">Limit dla firm zagranicznych został zapisany<a class="close" data-dismiss="alert">×</a></div>
            <?php
            }
            else {
                ?>
                <div class="alert alert-error">Nie można zapisać limitu dla firm zagranicznych.<a class="close" data-dismiss="alert">×</a></div>
            <?php
            }
        }

        $limitPl = get_option("limitPl");
        $limitAll = get_option("limitAll");

        ?>
        <div class="wrap">
            <h4>Konfiguracja limitów</h4>

            <form method="POST" action="">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">
                            <label for="limitPl">
                                Limit dla firm z Polski:
                            </label>
                        </th>
                        <td>
                            <input type="text" name="limitPl" size="40" value="<?php echo $limitPl; ?>" />PLN
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="limitAll">
                                Limit dla firm zagranicznych :
                            </label>
                        </th>
                        <td>
                            <input type="text" name="limitAll" size="40" value="<?php echo $limitAll; ?>" />PLN
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">

                        </th>
                        <td>

                            <input type="hidden" name="update_settings" value="Y" />
                            <input type="submit" value="Zapisz" name="submit" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>