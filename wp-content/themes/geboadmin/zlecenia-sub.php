<?php
if(!is_user_logged_in()) {
    auth_redirect();
}

if(is_numeric($_GET['id'])) {

    $results_sub = $wpdb->get_results('SELECT * FROM wp_czm_subcontractor_to_order WHERE disabled=0 AND id_order='.$_GET['id'], OBJECT);
}
//var_dump($results_sub);
?>
<table id="dt_podwykonawcy" class="display" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Podwykonawca</th>
        <th>Opis</th>
        <th>Koszt</th>
        <th>Waluta</th>
        <th>Kurs</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $count = 0;
    foreach($results_sub as $sub) {
        $count++;

        $results_inv = $wpdb->get_results('SELECT * FROM wp_czm_invoices WHERE id_subcontractor_to_order='.$sub->id, OBJECT);
//        echo '<tr><td>';
//        var_dump(array('SELECT * FROM wp_czm_invoices WHERE id_subcontractor_to_order='.$sub->id,$results_inv));

        ?>
        <tr>
            <td>
                <input class="subc subc<?php echo $count; ?>" style="width: 150px;"
                       id="row[<?php echo $sub->id; ?>][subc]" name="row[<?php echo $sub->id; ?>][subc]"
                       value="<?php echo $sub->subcontractor; ?>" type="text"/></td>
            <td><input class="name" style="width: 350px;" id="row[<?php echo $sub->id; ?>][name]"
                       name="row[<?php echo $sub->id; ?>][name]" value="<?php echo $sub->f_number; ?>" type="text"></td>
            <td><input class="value" style="width: 100px;" id="row[<?php echo $sub->id; ?>][value]"
                       name="row[<?php echo $sub->id; ?>][value]" value="<?php echo $sub->value / 10000; ?>"
                       type="text"></td>
            <td>
                <!--<select class="curr" id="row[--><?php ////echo $sub->id;?><!--<!--][curr]" name="row[-->
                <?php ////echo $sub->id;?><!--<!--][curr]">-->
                <!--                    <option value="eur">EURO</option>-->
                <!--                    <option value="usd">USD</option>-->
                <!--                    <option value="pln" selected="selected">PLN</option>-->
                <!---->
                <!--                </select>-->
                <select class="wal" style="width: 75px;" id="row[<?php echo $sub->id; ?>][wal]"
                        name="row[<?php echo $sub->id; ?>][wal]">
                    <option value="eur"<?php echo($sub->currency == 'eur' ? ' selected="selected"' : '') ?>>EURO
                    </option>
                    <option value="usd"<?php echo($sub->currency == 'usd' ? ' selected="selected"' : '') ?>>USD</option>
                    <option value="pln"<?php echo($sub->currency == 'pln' ? ' selected="selected"' : '') ?>>PLN</option>
                </select></td>
            <td><input class="exch" style="width: 40px;" id="row[<?php echo $sub->id; ?>][exch]"
                       name="row[<?php echo $sub->id; ?>][exch]" value="<?php echo $sub->exchange / 10000; ?>"
                       type="text">
                <select class="rok" style="width: 60px;" id="row[<?php echo $sub->id; ?>][rok]"
                        name="row[<?php echo $sub->id; ?>][rok]">
                    <?php
                    for($i = date('Y'); $i >= 2015; $i--) {
                        echo '<option value="'.$i.'"'.($i == date('Y') ? ' selected="selected"' : '').'>'.$i.'</option>';
                    }
                    ?>
                </select>

                <select class="tabela" style="width: 75px;" id="row[<?php echo $sub->id; ?>][tabela]"
                        name="row[<?php echo $sub->id; ?>][tabela]">
                    <option></option>
                    <?php
                    $files = file('http://www.nbp.pl/kursy/xml/dir.txt');

                    $fl_array = array_reverse(preg_grep("/^a........../", $files));


                    foreach($fl_array as $key => $a) {
                        echo '<option value="'.trim($a).'">'.substr($a, 5, 6).'</option>';
                    }
                    ?>
                </select>
            </td>
            <td>
                <input class="id" id="row[<?php echo $sub->id; ?>][id]" name="row[<?php echo $sub->id; ?>][id]" value=""
                       type="hidden">
                <input class="id_sub" id="row[<?php echo $sub->id; ?>][id_sub]"
                       name="row[<?php echo $sub->id; ?>][id_sub]" value="<?php echo $sub->id; ?>" type="hidden">
                <input class="id_order" id="row[<?php echo $sub->id; ?>][id_order]"
                       name="row[<?php echo $sub->id; ?>][id_order]" value="<?php echo $_REQUEST['id']; ?>"
                       type="hidden">

                <?php
//                var_dump(array('SELECT * FROM wp_czm_invoices WHERE id_subcontractor_to_order='.$sub->id,$results_inv));
                if(is_numeric($results_inv[0]->id)) { ?>
                    <a href="<?php echo get_bloginfo('url'); ?>/podwykonawcy?action=delete_sub&id=<?php echo $sub->id; ?>" class="ajax-link" title="Usuń"><i class="splashy-remove"></i></a>
                    <a title="Edytuj fakturę" class="sepV_a" href="<?php echo get_bloginfo('url'); ?>/faktury?action=editzk&amp;id=<?php echo $results_inv[0]->id; ?>"><i class="splashy-contact_blue_edit"></i></a>
                <?php }
                else { ?>
                    <form id="addFaktura" name="addFaktura" class="form_faktura" method="POST"
                          action="<?php bloginfo('url') ?>/faktury?action=add">
                        <input class="id_subcontractor" name="id_subcontractor_to_order" value="<?php echo $sub->id; ?>"
                               type="hidden"/>
                        <input class="id_order" name="id_order" value="<?php echo $_REQUEST['id']; ?>" type="hidden"/>
                        <a href="<?php echo get_bloginfo('url'); ?>/podwykonawcy?action=delete_sub&id=<?php echo $sub->id; ?>"
                           class="ajax-link" title="Usuń"><i class="splashy-remove"></i></a>

                        <button type="submit" class="btn btn-gebo">Dodaj fakturę</button>
                    </form>
                <?php } ?>
            </td>
        </tr>

    <?php } ?>


    </tbody>
</table>
<button class="btn btn-gebo" id="subAdd" type="button">Dodaj</button>
<button class="btn btn-gebo" id="subSave" type="button">Zapisz</button>