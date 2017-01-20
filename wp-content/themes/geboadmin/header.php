<?php if(!is_user_logged_in()) {
    auth_redirect();
} ?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <title><?php wp_title(); ?></title>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    wp_head();
    $template_url = get_template_directory_uri();
    global $current_user;
    ?>
    <!-- Bootstrap framework -->
    <link rel="stylesheet" href="<?php echo $template_url; ?>/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?php echo $template_url; ?>/bootstrap/css/bootstrap-responsive.min.css"/>
    <!-- gebo blue theme-->
    <link rel="stylesheet" href="<?php echo $template_url; ?>/css/blue.css" id="link_theme"/>
    <!-- breadcrumbs-->
    <link rel="stylesheet" href="<?php echo $template_url; ?>/lib/jBreadcrumbs/css/BreadCrumb.css"/>
    <!-- tooltips-->
    <link rel="stylesheet" href="<?php echo $template_url; ?>/lib/qtip2/jquery.qtip.min.css"/>
    <!-- colorbox -->
    <link rel="stylesheet" href="<?php echo $template_url; ?>/lib/colorbox/colorbox.css"/>
    <!-- code prettify -->
    <link rel="stylesheet" href="<?php echo $template_url; ?>/lib/google-code-prettify/prettify.css"/>
    <!-- notifications -->
    <link rel="stylesheet" href="<?php echo $template_url; ?>/lib/sticky/sticky.css"/>

    <link rel="stylesheet" href="<?php echo $template_url; ?>/lib/smoke/themes/gebo.css" />
    <!-- splashy icons -->
    <link rel="stylesheet" href="<?php echo $template_url; ?>/img/splashy/splashy.css"/>
    <!-- flags -->
    <link rel="stylesheet" href="<?php echo $template_url; ?>/img/flags/flags.css"/>
    <!-- calendar -->
    <link rel="stylesheet" href="<?php echo $template_url; ?>/lib/fullcalendar/fullcalendar_gebo.css"/>
    <!-- main styles -->
    <link rel="stylesheet" href="<?php echo $template_url; ?>/css/style.css"/>

    <link rel="stylesheet" href="<?php echo $template_url; ?>/lib/datetimepicker/jquery.datetimepicker.css"/>


    <link rel="stylesheet" href="<?php echo $template_url; ?>/lib/jquery-ui/css/smoothness/jquery-ui-1.8.20.custom.css"/>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans"/>


    <!--[if lte IE 8]>
    <link rel="stylesheet" href="css/ie.css"/>
    <script src="<?php echo $template_url; ?>/js/ie/html5.js"></script>
    <script src="<?php echo $template_url; ?>/js/ie/respond.min.js"></script>
    <script src="<?php echo $template_url; ?>/lib/flot/excanvas.min.js"></script>
    <![endif]-->

    <script>
        //* hide all elements & show preloader
        document.documentElement.className += 'js';
    </script>
    
</head>

<body <?php body_class(isset($class) ? $class : ''); ?>>

<div id="loading_layer" style="display:none"><img src="<?php echo $template_url; ?>/img/ajax_loader.gif" alt=""/></div>

<div id="maincontainer" class="clearfix">
<!-- header -->
<header>
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="brand" href="/"><i class="icon-home icon-white"></i> Skymar</a>
            <ul class="nav user_menu pull-right">
                <li><a href="#">Jesteś zalogowany jako: </a></li>
                <li class="divider-vertical hidden-phone hidden-tablet"></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle"
                       data-toggle="dropdown"><?php the_author_meta('display_name', $current_user->ID); ?> <b
                            class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo bloginfo('url').'/profil'; ?>">Edycja profilu</a></li>
                        <!--                        <li><a href="javascrip:void(0)">Another action</a></li>-->
                        <li class="divider"></li>
                        <li><a href=" <?php echo wp_logout_url(home_url('/login')); ?>">Wyloguj się</a></li>
                    </ul>
                </li>
            </ul>
            <a data-target=".nav-collapse" data-toggle="collapse" class="btn_menu">
                <span class="icon-align-justify icon-white"></span>
            </a>
            <nav>
                <div class="nav-collapse">
                    <ul class="nav">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i
                                    class="icon-file icon-white"></i> Test <b class="caret"></b></a>
                            <?php wp_nav_menu(array(
                                'menu'            => 'Main',
                                'menu_class'      => 'dropdown-menu',
                                'depth'           => 3,
                                'container'       => false,
                                'container_class' => '',
                                'container_id'    => '',
                                'menu_id'         => '',
                                'echo'            => true,
                                'before'          => '',
                                'after'           => '',
                                'link_before'     => '',
                                'link_after'      => '',
                                'items_wrap'      => '<ul class="%2$s">%3$s</ul>'
                            )); ?>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>

<?php
//$user = get_user_by( 'email', '' );
//echo 'User is ' . $user->first_name . ' ' . $user->last_name;
//echo '<pre>'.print_r($user,true).'</pre>';


?>
<div class="modal hide fade" id="myMail">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3>New messages</h3>
    </div>
    <div class="modal-body">
        <div class="alert alert-info">In this table jquery plugin turns a table row into a clickable link.</div>
        <table class="table table-condensed table-striped" data-rowlink="a">
            <thead>
            <tr>
                <th>Sender</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Size</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Declan Pamphlett</td>
                <td><a href="javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
                <td>23/05/2012</td>
                <td>25KB</td>
            </tr>
            <tr>
                <td>Erin Church</td>
                <td><a href="javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
                <td>24/05/2012</td>
                <td>15KB</td>
            </tr>
            <tr>
                <td>Koby Auld</td>
                <td><a href="javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
                <td>25/05/2012</td>
                <td>28KB</td>
            </tr>
            <tr>
                <td>Anthony Pound</td>
                <td><a href="javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
                <td>25/05/2012</td>
                <td>33KB</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" class="btn">Go to mailbox</a>
    </div>
</div>
<div class="modal hide fade" id="myTasks">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3>New Tasks</h3>
    </div>
    <div class="modal-body">
        <div class="alert alert-info">In this table jquery plugin turns a table row into a clickable link.</div>
        <table class="table table-condensed table-striped" data-rowlink="a">
            <thead>
            <tr>
                <th>id</th>
                <th>Summary</th>
                <th>Updated</th>
                <th>Priority</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>P-23</td>
                <td><a href="javascript:void(0)">Admin should not break if URL&hellip;</a></td>
                <td>23/05/2012</td>
                <td class="tac"><span class="label label-important">High</span></td>
                <td>Open</td>
            </tr>
            <tr>
                <td>P-18</td>
                <td><a href="javascript:void(0)">Displaying submenus in custom&hellip;</a></td>
                <td>22/05/2012</td>
                <td class="tac"><span class="label label-warning">Medium</span></td>
                <td>Reopen</td>
            </tr>
            <tr>
                <td>P-25</td>
                <td><a href="javascript:void(0)">Featured image on post types&hellip;</a></td>
                <td>22/05/2012</td>
                <td class="tac"><span class="label label-success">Low</span></td>
                <td>Updated</td>
            </tr>
            <tr>
                <td>P-10</td>
                <td><a href="javascript:void(0)">Multiple feed fixes and&hellip;</a></td>
                <td>17/05/2012</td>
                <td class="tac"><span class="label label-warning">Medium</span></td>
                <td>Open</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" class="btn">Go to task manager</a>
    </div>
</div>
</header>
<!-- main content -->