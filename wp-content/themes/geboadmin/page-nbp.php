<?php
/*
 * Template Name: Kurs NBP Page
 *
 */
if(!is_user_logged_in()) {
    auth_redirect();
}

function aktualne_kursy($file) {
    $tresc = file_get_contents('http://www.nbp.pl/kursy/xml/'.$file.'.xml');

    if(empty($tresc)) exit('Blad: Nie znaleziono tabeli kursow.');

    return $tresc;
}

global $wpdb;

if(!$wpdb instanceof wpdb) {
    die ('Bezpośredni dostęp do pliku zabroniony');
}

$rok = $_REQUEST['rok'];
$filename = $_REQUEST['file'];
//$filename = 'a238z151208';


switch ($_REQUEST['action']) {
    case 'get':

        $aktualne_kursy = aktualne_kursy($filename);

        $xml = new SimpleXMLElement($aktualne_kursy);
        $result = array();
        if(!empty($_REQUEST['currency'])){
            $valid_currency = array($_REQUEST['currency']);
        }else{
            $valid_currency = array('EUR','USD','PLN');
        }
        foreach($xml->pozycja as $pozycja) {
//            echo '1 '.$pozycja->kod_waluty.' = ';
//            echo $pozycja->kurs_sredni." PLN\n";
            if(in_array($pozycja->kod_waluty, $valid_currency)) {
                $result[strtolower($pozycja->kod_waluty)] = $pozycja;
            }

        }

//        var_dump($result);
        die(json_encode($result));

        break;
    default:

        $years = array();
        for($i = 2015; $i < date('Y'); $i++) {
            $years[] = $i;
        }

        if(in_array($rok, $years)) {
            $files = file('http://www.nbp.pl/kursy/xml/dir'.$rok.'.txt');
        }
        else {
            $files = file('http://www.nbp.pl/kursy/xml/dir.txt');
        }

        $fl_array = array_reverse(preg_grep("/^a........../", $files));

        $result = array();
        foreach($fl_array as $key => $a) {
            $result[$key]['val'] = trim($a);
            $result[$key]['sub'] = substr($a, 5, 6);
        }

        die(json_encode($result));
}


//var_dump($result);
//print_r($fl_array);