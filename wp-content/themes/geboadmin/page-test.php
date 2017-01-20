<?php
/*
 * Template Name: test Page
 *
 */

get_header(); ?>
<?php
$template_url = get_template_directory_uri();
?>
    <div id="contentwrapper">
    <div class="main_content">

    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <div class="span12 ">
                    <?php

                    $args = array(
                        //    'blog_id'      => $GLOBALS['blog_id'],
                        'role'    => 'client_admin',
                        //    'meta_key'     => '',
                        //    'meta_value'   => '',
                        //    'meta_compare' => '',
                        //    'meta_query'   => array(),
                        //    'date_query'   => array(),
                        //    'include'      => array(),
                        'exclude' => array(),
                        'orderby' => 'name',
                        'order'   => 'ASC',
                        //    'offset'       => '',
                        //    'search'       => '',
                        //    'number'       => '',
                        //    'count_total'  => false,
                        //    'fields'       => 'all',
                        //    'who'          => ''
                    );

                    $client_admins = get_users($args);
//                    echo '<pre>'.print_r($client_admins, true).'</pre>';

                    $admins = array();
                    foreach($client_admins as $admin) {
                        $admins[] = $admin->data->ID;
                    }
                    echo 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaa<pre>'.print_r($admins, true).'</pre>xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

                    $args = array(
                        //    'blog_id'      => $GLOBALS['blog_id'],
                        'role'    => 'client_user',
                        //    'meta_key'     => '',
                        //    'meta_value'   => '',
                        //    'meta_compare' => '',
                        //    'meta_query'   => array(),
                        //    'date_query'   => array(),
//                        'include' => $admins,
                        //    'exclude'      => array(),
                        'orderby' => 'name',
                        'order'   => 'ASC',
                        //    'offset'       => '',
                        //    'search'       => '',
                        //    'number'       => '',
                        //    'count_total'  => false,
                        //    'fields'       => 'all',
                        //    'who'          => ''
                    );


                    echo '<pre>'.print_r(get_users($args), true).'</pre>';

                    global $wpdb;
                    $blog_id = get_current_blog_id();

                    $user_query = new WP_User_Query(array(
                        'meta_query' => array(
                            'relation' => 'OR',
                            array(
                                'key'     => $wpdb->get_blog_prefix($blog_id).'capabilities',
                                'value'   => 'client_user',
                                'compare' => 'like'
                            ),
                            array(
                                'key'     => $wpdb->get_blog_prefix($blog_id).'capabilities',
                                'value'   => 'client_admin',
                                'compare' => 'like'
                            )
                        )
                    ));

                    echo '<pre>'.print_r($user_query, true).'</pre>';
                    ?>

                </div>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <ul class="dshb_icoNav tac">
                <li><a href="javascript:void(0)" style="background-image: url('<?php echo $template_url; ?>/img/gCons/multi-agents.png')"><span class="label label-info">+10</span> Users</a></li>
                <li><a href="javascript:void(0)" style="background-image: url('<?php echo $template_url; ?>/img/gCons/world.png')">Map</a></li>
                <li><a href="javascript:void(0)" style="background-image: url('<?php echo $template_url; ?>/img/gCons/configuration.png')">Settings</a></li>
                <li><a href="javascript:void(0)" style="background-image: url('<?php echo $template_url; ?>/img/gCons/lab.png')">Lab</a></li>
                <li><a href="javascript:void(0)" style="background-image: url('<?php echo $template_url; ?>/img/gCons/van.png')"><span class="label label-success">$2851</span> Delivery</a></li>
                <li><a href="javascript:void(0)" style="background-image: url('<?php echo $template_url; ?>/img/gCons/pie-chart.png')">Charts</a></li>
                <li><a href="javascript:void(0)" style="background-image: url('<?php echo $template_url; ?>/img/gCons/edit.png')">Add New Article</a></li>
                <li><a href="javascript:void(0)" style="background-image: url('<?php echo $template_url; ?>/img/gCons/add-item.png')"> Add New Page</a></li>
                <li><a href="javascript:void(0)" style="background-image: url('<?php echo $template_url; ?>/img/gCons/chat-.png')"><span class="label label-important">26</span> Comments</a></li>
            </ul>
        </div>
    </div>


    </div>
    </div>
<?php get_sidebar(); ?>

<?php get_footer(); ?>