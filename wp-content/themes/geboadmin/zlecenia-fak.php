<?php
if(!is_user_logged_in()) {
    auth_redirect();
}

if (is_numeric($_GET['id'])) {

    $results_sub = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id_order='.$_GET['id'], OBJECT);
}
    ?>
<!--<button class="btn btn-gebo" id="subAdd" type="button">Dodaj</button>-->
<!--<button class="btn btn-gebo" id="subSave" type="button">Zapisz</button>-->
<!--<button class="btn btn-gebo" id="subRefresh" type="button">Odświerz</button>-->
<form id="addFaktura" name="addFaktura" class="form_faktura" method="POST" action="<?php bloginfo('url') ?>/faktury?action=addsp">

    <input class="id_order" name="id_order" value="<?php echo $_REQUEST['id']; ?>" type="hidden"/>
<!--    <button type="submit" class="btn btn-gebo">Dodaj fakturę</button>-->
</form>
<table id="dt_faktury" class="display" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>ID</th>
        <th>Numer</th>
        <th>Typ</th>
        <th>Numer zamówienia</th>
        <th>Podwykonawca</th>
        <th>Klient</th>
        <th>Wartość</th>
        <th>Termin płatności</th>
        <th>Wysłano/otrzymano</th>
        <th></th>
    </tr>
    <?php foreach ($results_sub as $sub){?>


    <?php }?>
    </thead>
</table>