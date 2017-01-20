<?php
date_default_timezone_set('Europe/Warsaw');

remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // index link
remove_action('wp_head', 'parent_post_rel_link'); // prev link
remove_action('wp_head', 'start_post_rel_link'); // start link
remove_action('wp_head', 'adjacent_posts_rel_link');
remove_action('wp_head', 'wp_generator');
add_filter('xmlrpc_enabled', '__return_false');

//function disable_emojis() {
//    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
//    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
//    remove_action( 'wp_print_styles', 'print_emoji_styles' );
//    remove_action( 'admin_print_styles', 'print_emoji_styles' );
//    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
//    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
//    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
//    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
//}
//add_action( 'init', 'disable_emojis' );

function validateDate($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}


add_filter('site_url', 'wplogin_filter', 10, 3);
function wplogin_filter($url, $path, $orig_scheme) {
    $old = array("/(wp-login\.php)/");
    $new = array("login");

    return preg_replace($old, $new, $url, 1);
}

/**********************************************************************************************************************
 * LOGIN FORM
 */

function my_login() {
    echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('stylesheet_directory').'/login/style-login.css" />';
    //   echo $aaa='<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
    //        <link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.min.css" />
    //        <!-- theme color-->
    //            <link rel="stylesheet" href="css/blue.css" />
    //        <!-- tooltip -->
    //			<link rel="stylesheet" href="lib/qtip2/jquery.qtip.min.css" />
    //        <!-- main styles -->
    //            <link rel="stylesheet" href="css/style.css" />
    //
    //        <link href="http://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet" type="text/css">';
}

add_action('login_head', 'my_login');

function my_login_logo_url() {
    return get_bloginfo('url');
}

add_filter('login_headerurl', 'my_login_logo_url');

function my_login_logo_url_title() {
    return 'SkyMar';
}

add_filter('login_headertitle', 'my_login_logo_url_title');

function login_error_override() {
    return '<strong>BŁĄD</strong>: Niewłaściwa nazwa użytkownika lub hasło.';
}

add_filter('login_errors', 'login_error_override');


function admin_login_redirect($redirect_to, $request, $user) {
    global $user;

    return home_url().'/zlecenia';

    //    file_put_contents('aaaaroles.txt',print_r($user->roles,true));
    if(isset($user->roles) && is_array($user->roles)) {
        if(in_array("administrator", $user->roles)) {
            return $redirect_to;
        }
        else {
            return home_url();
        }
    }
    else {
        return $redirect_to;
    }
}

add_filter("login_redirect", "admin_login_redirect", 10, 3);

/**********************************************************************************************************************
 * LOGIN FORM END
 */

add_filter('show_admin_bar', '__return_false');

add_theme_support('post-thumbnails');

//Add content width (desktop default)
if(!isset($content_width)) {
    $content_width = 768;
}

//Add menu support and register main menu
if(function_exists('register_nav_menus')) {
    register_nav_menus(array(
        'main_menu' => 'Main Menu'
    ));
}


// filter the Gravity Forms button type
add_filter('gform_submit_button', 'form_submit_button', 10, 2);
function form_submit_button($button, $form) {
    return "<button class='button btn' id='gform_submit_button_{$form["id"]}'><span>{$form['button']['text']}</span></button>";
}

// Register sidebar
add_action('widgets_init', 'theme_register_sidebar');
function theme_register_sidebar() {
    if(function_exists('register_sidebar')) {
        register_sidebar(array(
            'id'            => 'sidebar-1',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4>',
            'after_title'   => '</h4>',
        ));
    }
}

/**
 * Load site scripts.
 */
function bootstrap_theme_enqueue_scripts() {
    $template_url = get_template_directory_uri();

 ;


//    	wp_enqueue_script( 'mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js' );


    wp_enqueue_style('main-style', get_stylesheet_uri());

    // Load Thread comments WordPress script.

}

add_action('wp_head', 'bootstrap_theme_enqueue_scripts', 1);

function is_url_exist($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if($code == 200) {
        $status = true;
    }
    else {
        $status = false;
    }
    curl_close($ch);

    return $status;
}


function is_app_admin($user) {

    $admin = false;
    if(in_array('administrator', $user->roles) OR in_array('client_admin', $user->roles)) {
        $admin = true;
    }

    return $admin;
}

if(!function_exists('str_split')) {
    function str_split($string, $len = 1) {
        if($len < 1) return false;
        for($i = 0, $rt = Array(); $i < ceil(strlen($string) / $len); $i++) {
            $rt[$i] = substr($string, $len * $i, $len);
        }

        return ($rt);
    }
}

$slowa = Array(
    'minus',

    Array(
        'zero',
        'jeden',
        'dwa',
        'trzy',
        'cztery',
        'pięć',
        'sześć',
        'siedem',
        'osiem',
        'dziewięć'
    ),

    Array(
        'dziesięć',
        'jedenaście',
        'dwanaście',
        'trzynaście',
        'czternaście',
        'piętnaście',
        'szesnaście',
        'siedemnaście',
        'osiemnaście',
        'dziewiętnaście'
    ),

    Array(
        'dziesięć',
        'dwadzieścia',
        'trzydzieści',
        'czterdzieści',
        'pięćdziesiąt',
        'sześćdziesiąt',
        'siedemdziesiąt',
        'osiemdziesiąt',
        'dziewięćdziesiąt'
    ),

    Array(
        'sto',
        'dwieście',
        'trzysta',
        'czterysta',
        'pięćset',
        'sześćset',
        'siedemset',
        'osiemset',
        'dziewięćset'
    ),

    Array(
        'tysiąc',
        'tysiące',
        'tysięcy'
    ),

    Array(
        'milion',
        'miliony',
        'milionów'
    ),

    Array(
        'miliard',
        'miliardy',
        'miliardów'
    )
);

$slowa_en = Array(
    'minus',
    Array(
        'zero',
        'one',
        'two',
        'three',
        'four',
        'five',
        'six',
        'seven',
        'eight',
        'nine'
    ),

    Array(
        'ten',
        'eleven',
        'twelve',
        'thirteen',
        'fourteen',
        'fifteen',
        'sixteen',
        'seventeen',
        'eighteen',
        'nineteen'
    ),

    Array(
        'ten',
        'twenty',
        'thirty',
        'forty',
        'fifty',
        'sixty',
        'seventy',
        'eighty',
        'ninety'
    ),

    Array(
        'one hundred',
        'two hundred',
        'three hundred',
        'four hundred',
        'five hundred',
        'six hundred',
        'seven hundred',
        'eight hundred',
        'nine hundred'
    ),

    Array(
        'thousand',
        'thousands',
        'thousands'
    ),

    Array(
        'million',
        'millions',
        'millions'
    ),

    Array(
        'billion',
        'billions',
        'billions'
    )
);

function odmiana($odmiany, $int) { // $odmiany = Array('jeden','dwa','pięć')
    $txt = $odmiany[2];
    if($int == 1) $txt = $odmiany[0];
    $jednosci = (int)substr($int, -1);
    $reszta   = $int % 100;
    if(($jednosci > 1 && $jednosci < 5) & !($reszta > 10 && $reszta < 20)) $txt = $odmiany[1];

    return $txt;
}

function liczba($int) { // odmiana dla liczb < 1000
    global $slowa;
    $wynik = '';
    $j     = abs((int)$int);

    if($j == 0) return $slowa[1][0];
    $jednosci   = $j % 10;
    $dziesiatki = ($j % 100 - $jednosci) / 10;
    $setki      = ($j - $dziesiatki * 10 - $jednosci) / 100;

    if($setki > 0) $wynik .= $slowa[4][$setki - 1].' ';

    if($dziesiatki > 0) if($dziesiatki == 1) $wynik .= $slowa[2][$jednosci].' ';
    else
        $wynik .= $slowa[3][$dziesiatki - 1].' ';

    if($jednosci > 0 && $dziesiatki != 1) $wynik .= $slowa[1][$jednosci].' ';

    return $wynik;
}

function slownie($int) {

    global $slowa;
    $in  = preg_replace('/[^-\d]+/', '', $int);
    $out = '';

    if($in{0} == '-') {
        $in  = substr($in, 1);
        $out = $slowa[0].' ';
    }

    $txt = str_split(strrev($in), 3);

    if($in == 0) $out = $slowa[1][0].' ';

    for($i = count($txt) - 1; $i >= 0; $i--) {
        $liczba = (int)strrev($txt[$i]);
        if($liczba > 0) if($i == 0) $out .= liczba($liczba).' ';
        else
            $out .= ($liczba > 1 ? liczba($liczba).' ' : '').odmiana($slowa[4 + $i], $liczba).' ';
    }

    return trim($out);
}

///////////////////////////////////////////

function odmiana_en($odmiany, $int) { // $odmiany = Array('jeden','dwa','pięć')
    $txt = $odmiany[2];
    if($int == 1) $txt = $odmiany[0];
    $jednosci = (int)substr($int, -1);
    $reszta   = $int % 100;
    if(($jednosci > 1 && $jednosci < 5) & !($reszta > 10 && $reszta < 20)) $txt = $odmiany[1];

    return $txt;
}

function liczba_en($int) { // odmiana_en dla liczb < 1000
    global $slowa_en;
    $wynik = '';
    $j     = abs((int)$int);

    if($j == 0) return $slowa_en[1][0];
    $jednosci   = $j % 10;
    $dziesiatki = ($j % 100 - $jednosci) / 10;
    $setki      = ($j - $dziesiatki * 10 - $jednosci) / 100;

    if($setki > 0) $wynik .= $slowa_en[4][$setki - 1].' ';

    if($dziesiatki > 0) if($dziesiatki == 1) $wynik .= $slowa_en[2][$jednosci].' ';
    else
        $wynik .= $slowa_en[3][$dziesiatki - 1].' ';

    if($jednosci > 0 && $dziesiatki != 1) $wynik .= $slowa_en[1][$jednosci].' ';

    return $wynik;
}

function slownie_en($int) {

    global $slowa_en;
    $in  = preg_replace('/[^-\d]+/', '', $int);
    $out = '';

    if($in{0} == '-') {
        $in  = substr($in, 1);
        $out = $slowa_en[0].' ';
    }

    $txt = str_split(strrev($in), 3);

    if($in == 0) $out = $slowa_en[1][0].' ';

    for($i = count($txt) - 1; $i >= 0; $i--) {
        $liczba_en = (int)strrev($txt[$i]);
        if($liczba_en > 0) if($i == 0) $out .= liczba_en($liczba_en).' ';
        else
            $out .= ($liczba_en > 1 ? liczba_en($liczba_en).' ' : '').odmiana_en($slowa_en[4 + $i], $liczba_en).' ';
    }

    return trim($out);
}
//////////////////////////

function inicialy($name){
    $inicialy = explode(' ',ucwords($name));
    $inicial = '';
    foreach($inicialy as $i){
        $inicial.= substr($i,0,1);
    }
    return $inicial;
}

function tofloat($aa) {
    $ptString=(string)$aa;
    if (strlen($ptString) == 0) {
        return false;
    }

    $pString = str_replace(" ", "", $ptString);

    if (substr_count($pString, ",") > 1)
        $pString = str_replace(",", "", $pString);

    if (substr_count($pString, ".") > 1)
        $pString = str_replace(".", "", $pString);

    $pregResult = array();

    $commaset = strpos($pString,',');
    if ($commaset === false) {$commaset = -1;}

    $pointset = strpos($pString,'.');
    if ($pointset === false) {$pointset = -1;}

    $pregResultA = array();
    $pregResultB = array();

    if ($pointset < $commaset) {
        preg_match('#(([-]?[0-9]+(\.[0-9])?)+(,[0-9]+)?)#', $pString, $pregResultA);
    }
    preg_match('#(([-]?[0-9]+(,[0-9])?)+(\.[0-9]+)?)#', $pString, $pregResultB);

    $old_error = error_reporting();
    error_reporting(E_ALL & ~E_NOTICE);
    if ((isset($pregResultA[0]) && (!isset($pregResultB[0])
            || strstr($preResultA[0],$pregResultB[0]) == 0
            || !$pointset))) {
        $numberString = $pregResultA[0];
        $numberString = str_replace('.','',$numberString);
        $numberString = str_replace(',','.',$numberString);
    }
    elseif (isset($pregResultB[0]) && (!isset($pregResultA[0])
            || strstr($pregResultB[0],$preResultA[0]) == 0
            || !$commaset)) {
        $numberString = $pregResultB[0];
        $numberString = str_replace(',','',$numberString);
    }
    else {
        return false;
    }

    error_reporting($old_error);

    $result = (float)$numberString;
    return $result;
}

function remove_core_updates(){
    global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
}
add_filter('pre_site_transient_update_core','remove_core_updates');
add_filter('pre_site_transient_update_plugins','remove_core_updates');
add_filter('pre_site_transient_update_themes','remove_core_updates');
