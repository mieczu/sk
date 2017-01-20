<?php if(!is_user_logged_in()) {
    auth_redirect();
}

//if (is_numeric($_GET['id'])) {
//
//    $results_sub = $wpdb->get_results('SELECT * FROM wp_czm_subcontractor_to_order WHERE disabled=0 AND id_order='.$_GET['id'], OBJECT);
//}

if(is_numeric($_REQUEST['id'])) {

    $results_sub = $wpdb->get_results('SELECT * FROM wp_czm_invoices_items_to_invoices WHERE id_invoice='.$_REQUEST['id'], OBJECT);
}
//echo 'SELECT * FROM wp_czm_invoices_items_to_invoices WHERE disabled=0 AND id_invoice='.$_REQUEST['id'];
//var_dump($results_sub);
?>
<button class="btn btn-gebo" id="subAdd2" type="button">Dodaj</button>
<!--<button class="btn btn-gebo" id="subSave2" type="button">Zapisz</button>-->
<table id="dt_pozycje2" class="display" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Nazwa</th>
        <th>Ilość(szt)</th>
        <th>Cena(netto)</th>
        <th>VAT (%)</th>
        <th>Waluta</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $count = 0;
    foreach($results_sub as $sub) {
        $count++;?>
        <tr>
            <td>
                <input class="subc" style="width: 550px;" id="row[<?php echo $sub->id; ?>][subc]"
                       name="row[<?php echo $sub->id; ?>][subc]" value="<?php echo $sub->name; ?>" type="text"/>
            </td>
            <td><input class="quantity" style="width: 150px;" id="row[<?php echo $sub->id; ?>][quantity]"
                       name="row[<?php echo $sub->id; ?>][quantity]" value="<?php echo $sub->quantity; ?>" type="text"/>
            </td>
            <td><input class="value" style="width: 100px;" id="row[<?php echo $sub->id; ?>][value]"
                       name="row[<?php echo $sub->id; ?>][value]"
                       value="<?php echo(!is_numeric($sub->value) ? 'NP' : $sub->value / 10000); ?>" type="text"/>
            </td>

            <td><input class="vat" style="width: 40px;" id="row[<?php echo $sub->id; ?>][vat]"
                       name="row[<?php echo $sub->id; ?>][vat]"
                       value="<?php echo(!is_numeric($sub->vat) ? 'NP' : $sub->vat / 10000); ?>" type="text"/>
            </td>
            <td>
                <select class="wal1" style="width: 75px;" id="row[<?php echo $sub->id; ?>][wal]" name="row[<?php echo $sub->id; ?>][wal]">
                    <option value="eur"<?php echo($sub->currency == 'eur' ? ' selected="selected"' : ''); ?>>EURO</option>
                    <option value="usd"<?php echo($sub->currency == 'usd' ? ' selected="selected"' : ''); ?>>USD</option>
                    <option value="pln"<?php echo($sub->currency == 'pln' ? ' selected="selected"' : ''); ?>>PLN</option>
                </select>
            </td>
            <td>
                <input class="id_sub" id="row[<?php echo $sub->id; ?>][id_sub]" name="row[<?php echo $sub->id; ?>][id_sub]" value="<?php echo $sub->id; ?>" type="hidden"/>
                <input class="id_invoice" id="row[<?php echo $sub->id; ?>][id]" name="row[<?php echo $sub->id; ?>][id]" value="<?php echo $sub->id_invoice; ?>" type="hidden"/>
                <a href="<?php echo get_bloginfo('url'); ?>/faktury?action=delete_sub&id=<?php echo $sub->id; ?>" class="ajax-link" title="Usuń"><i class="splashy-remove"></i></a>
            </td>


        </tr>

    <?php } ?>


    </tbody>
</table>