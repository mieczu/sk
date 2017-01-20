<?php
if(!is_user_logged_in()) {
    auth_redirect();
}

//if (is_numeric($_GET['id'])) {
//
//    $results_sub = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id_order='.$_GET['id'], OBJECT);
//}
    ?>
<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Noty</h3>

        <table class="table table-bordered table-striped table_vam" id="dt_noty"
               style="width: 100%;">
            <thead>
            <tr>
                <th>ID</th>
                <th>Numer</th>
                <th>Klient</th>
                <th>Zlecenie</th>
                <th>Użytkownik</th>
                <th>Typ</th>
                <th>Data dodania</th>
                <th></th>

            </tr>
            </thead>

        </table>

    </div>
</div>