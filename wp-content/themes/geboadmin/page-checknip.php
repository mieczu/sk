<?php
/*
 * Template Name: Check NIP Page
 *
 */

if(!is_user_logged_in()) {
    auth_redirect();
}

global $wpdb;

if (!$wpdb instanceof wpdb){
    die ('Bezpośredni dostęp do pliku zabroniony');
}

if(isset($_POST['vies'])){
//    $countryCode = 'PL';
//    $vatNo       = 5221712807;
//    countryCode = 'ES';
//    $vatNo       = 'B61262226';

    $countryCode = $_REQUEST['countryCode'];
    $vatNo       = $_REQUEST['nip'];

    $client = new SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");

    try {
        $result = $client->checkVat(array(
            'countryCode' => $countryCode,
            'vatNumber'   => $vatNo
        ));
    } catch (Exception $e) {
//        var_dump($e);
        if ($e->getMessage()=='INVALID_INPUT'){
            die('Niepoprawny numer NIP lub wybrany karaj nie znajduje się w systemie VIES!');
        }else{
            die($e->getMessage());
        }
    }

//    var_dump($result);
//    print_r($result);
    if($result->valid){
        die('Numer NIP jest aktywny');
    }else{
        die('Numer NIP jest nieaktywny');
    }

}

//require_once __DIR__.'/../../plugins/basic-auth/vendor/autoload.php';
require_once dirname(__FILE__).'/../../../vendor/autoload.php';

session_start();

use GusApi\GusApi;
use GusApi\RegonConstantsInterface;
use GusApi\Exception\InvalidUserKeyException;
use GusApi\ReportTypes;

$key = 'afe9abdc4ea14b7abc74'; // <--- your user key / twój klucz użytkownika

$gus = new GusApi(
    $key,
    new \GusApi\Adapter\Soap\SoapAdapter(
        RegonConstantsInterface::BASE_WSDL_URL_TEST,
        RegonConstantsInterface::BASE_WSDL_ADDRESS_TEST
//        RegonConstantsInterface::BASE_WSDL_URL,
//        RegonConstantsInterface::BASE_WSDL_ADDRESS

        //<--- production server / serwer produkcyjny
    //for test serwer use RegonConstantsInterface::BASE_WSDL_ADDRESS_TEST
    //w przypadku serwera testowego użyj: RegonConstantsInterface::BASE_WSDL_ADDRESS_TEST
    )
);

if (isset($_GET['reset'])) {
    $_SESSION = [];
    $_SESSION['checked'] = false;
}

if ($gus->serviceStatus() === RegonConstantsInterface::SERVICE_AVAILABLE) {

    try {

        if (!isset($_SESSION['sid']) || !$gus->isLogged($_SESSION['sid'])) {
            $_SESSION['sid'] = $gus->login();
            $_SESSION['checked'] = false;
        }

        if (isset($_POST['captcha'])) {
            $_SESSION['checked'] = $gus->checkCaptcha($_SESSION['sid'], $_POST['captcha']);
        }

        if (!$_SESSION['checked']) {
            $image = fopen("captcha.jpeg",'w+');
            $captcha = $gus->getCaptcha($_SESSION['sid']);
            fwrite($image, base64_decode($captcha));
            fclose($image);

            printCaptchaForm();

        } else {
            if(!isset($_REQUEST['nip']))
            printNipForm();
        }

        if (isset($_REQUEST['nip'])) {

            $nip = $_REQUEST['nip'];
            try {
//                var_dump($_POST);
                $gusReport = $gus->getByNip($_SESSION['sid'], $nip);
//                var_dump($gusReport);
//                echo '<br><br>';
//                var_dump(
//                    $gus->getFullReport(
//                        $_SESSION['sid'],
//                        $gusReport,
//                        ReportTypes::REPORT_PUBLIC_LAW
////                        ReportTypes::REPORT_ACTIVITY_LAW_PUBLIC
//                    );
//                );
                echo $gusReport->getName().'<br>';
                echo $gusReport->getCommunity().'<br>';
                echo $gusReport->getDistrict().'<br>';
                echo $gusReport->getCity().'<br>';
                echo $gusReport->getStreet().'<br>';
                echo $gusReport->getZipCode().'<br>';
//                var_dump($_SERVER);

            } catch (\GusApi\Exception\NotFoundException $e) {
                echo 'No data found <br>';
                echo 'For more information read server message belowe: <br>';
                echo $gus->getResultSearchMessage();
                if (strcmp($gus->getResultSearchMessage(),'Wymagane pobranie i sprawdzenie kodu Captcha') == 0){

                    $_SESSION = [];
                    $_POST = [];
                    $_SESSION['checked']=false;
                    session_destroy();
                    header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
                }
            }
        }

    } catch (InvalidUserKeyException $e) {
        echo 'Bad user key!';
    }

} else if ($gus->serviceStatus() === RegonConstantsInterface::SERVICE_UNAVAILABLE) {

    echo 'Server is unavailable now. Please try again later <br>';
    echo 'For more information read server message belowe: <br>';
    echo $gus->serviceMessage();

} else {

    echo 'Server technical break. Please try again later <br>';
    echo 'For more information read server message belowe: <br>';
    echo $gus->serviceMessage();

}

function printCaptchaForm()
{?>
    <script>
        $('#caps').click(function(){
            var getUrl = window.location;
            if (getUrl.pathname.split('/').length < 4){
                var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
            }else{
                var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
            }
            $('#loading_animation').show();
            $.ajax({
                type: "POST",
                url: baseUrl + "/nip",
                data: {captcha: $('#cap').val()},//$('#checknip .nip').val()},
                dataType: "text",
                success: function (msg) {
                    $('#loading_animation').hide();
                    $('#testdialog').html(msg);
                    $('#testdialog .nip').val($('#fnip').val());
                    $('#testdialog').dialog();
                    //d = document.createElement('div');
                    //$(d).html(msg);
                    //$(d).dialog();
                    //$("#thanks").html(msg) //hide button and show thank you
                    //$("#form-content").modal('hide'); //hide popup
                },
                error: function (msg) {
                    $('#loading_animation').hide();
                    $.sticky("Błąd wysyłania", {position: "top-center", type: "st-error"});
                    console.log(msg);
                }
            });
        });
    </script>
    <?php
    echo '<img src="http://skymar.abad.pl/captcha.jpeg?'.time().'">';
    echo '<form id="capform" action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" method="POST">';
    echo '<input id="cap" type="text" name="captcha" >';
    echo '<input id="caps" type="button" value="check">';
    echo '</form>';
}

function printNipForm()
{//var_dump($_POST);var_dump($_SESSION);
?><script>
 $('#123').click(function(){
     var getUrl = window.location;
     if (getUrl.pathname.split('/').length < 4){
         var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
     }else{
         var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
     }
     $('#loading_animation').show();
     $.ajax({
         type: "POST",
         url: baseUrl + "/nip",
         data: {nip: $('#checknip .nip').val()},
         dataType: "text",
         success: function (msg) {
             $('#loading_animation').hide();
             $('#testdialog').html(msg);
             $('#testdialog').dialog();
             //d = document.createElement('div');
             //$(d).html(msg);
             //$(d).dialog();
             //$("#thanks").html(msg) //hide button and show thank you
             //$("#form-content").modal('hide'); //hide popup
         },
         error: function (msg) {
             $('#loading_animation').hide();
             $.sticky("Błąd wysyłania", {position: "top-center", type: "st-error"});
             console.log(msg);
         }
     });
 });
</script><?php
    echo '<form id="checknip" action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" method="POST">';
    echo '<input class="nip" type="text" name="nip" >';
//    echo '<input type="submit" value="check">';
    echo '<button id="123" type="button">Sprawdź</button>';
    echo '</form>';
}


