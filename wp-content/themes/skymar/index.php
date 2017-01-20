<?php get_header(); ?>

    <div id="rightSide">

    <!-- Top fixed navigation -->
    <div class="topNav">
        <div class="wrapper">
            <div class="welcome"><a href="#" title=""><img src="images/userPic.png" alt="" /></a><span>Howdy, Eugene!</span></div>
            <div class="userNav">
                <ul>
                    <li><a href="#" title=""><img src="images/icons/topnav/profile.png" alt="" /><span>Profile</span></a></li>
                    <li><a href="#" title=""><img src="images/icons/topnav/tasks.png" alt="" /><span>Tasks</span></a></li>
                    <li class="dd"><a title=""><img src="images/icons/topnav/messages.png" alt="" /><span>Messages</span><span class="numberTop">8</span></a>
                        <ul class="userDropdown">
                            <li><a href="#" title="" class="sAdd">new message</a></li>
                            <li><a href="#" title="" class="sInbox">inbox</a></li>
                            <li><a href="#" title="" class="sOutbox">outbox</a></li>
                            <li><a href="#" title="" class="sTrash">trash</a></li>
                        </ul>
                    </li>
                    <li><a href="#" title=""><img src="images/icons/topnav/settings.png" alt="" /><span>Settings</span></a></li>
                    <li><a href="login.html" title=""><img src="images/icons/topnav/logout.png" alt="" /><span>Logout</span></a></li>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <!-- Responsive header -->
    <div class="resp">
        <div class="respHead">
            <a href="index.html" title=""><img src="images/loginLogo.png" alt="" /></a>
        </div>

        <div class="cLine"></div>
        <div class="smalldd">
            <span class="goTo"><img src="images/icons/light/home.png" alt="" />Dashboard</span>
            <ul class="smallDropdown">
                <li><a href="index.html" title=""><img src="images/icons/light/home.png" alt="" />Dashboard</a></li>
                <li><a href="charts.html" title=""><img src="images/icons/light/stats.png" alt="" />Statistics and charts</a></li>
                <li><a href="#" title="" class="exp"><img src="images/icons/light/pencil.png" alt="" />Forms stuff<strong>4</strong></a>
                    <ul>
                        <li><a href="forms.html" title="">Form elements</a></li>
                        <li><a href="form_validation.html" title="">Validation</a></li>
                        <li><a href="form_editor.html" title="">WYSIWYG and file uploader</a></li>
                        <li class="last"><a href="form_wizards.html" title="">Wizards</a></li>
                    </ul>
                </li>
                <li><a href="ui_elements.html" title=""><img src="images/icons/light/users.png" alt="" />Interface elements</a></li>
                <li><a href="tables.html" title="" class="exp"><img src="images/icons/light/frames.png" alt="" />Tables<strong>3</strong></a>
                    <ul>
                        <li><a href="table_static.html" title="">Static tables</a></li>
                        <li><a href="table_dynamic.html" title="">Dynamic table</a></li>
                        <li class="last"><a href="table_sortable_resizable.html" title="">Sortable &amp; resizable tables</a></li>
                    </ul>
                </li>
                <li><a href="#" title="" class="exp"><img src="images/icons/light/fullscreen.png" alt="" />Widgets and grid<strong>2</strong></a>
                    <ul>
                        <li><a href="widgets.html" title="">Widgets</a></li>
                        <li class="last"><a href="grid.html" title="">Grid</a></li>
                    </ul>
                </li>
                <li><a href="#" title="" class="exp"><img src="images/icons/light/alert.png" alt="" />Error pages<strong>6</strong></a>
                    <ul class="sub">
                        <li><a href="403.html" title="">403 page</a></li>
                        <li><a href="404.html" title="">404 page</a></li>
                        <li><a href="405.html" title="">405 page</a></li>
                        <li><a href="500.html" title="">500 page</a></li>
                        <li><a href="503.html" title="">503 page</a></li>
                        <li class="last"><a href="offline.html" title="">Website is offline</a></li>
                    </ul>
                </li>
                <li><a href="file_manager.html" title=""><img src="images/icons/light/files.png" alt="" />File manager</a></li>
                <li><a href="#" title="" class="exp"><img src="images/icons/light/create.png" alt="" />Other pages<strong>3</strong></a>
                    <ul>
                        <li><a href="typography.html" title="">Typography</a></li>
                        <li><a href="calendar.html" title="">Calendar</a></li>
                        <li class="last"><a href="gallery.html" title="">Gallery</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="cLine"></div>
    </div>

    <!-- Title area -->
    <div class="titleArea">
        <div class="wrapper">
            <div class="pageTitle">
                <h5>Dashboard</h5>
                <span>Do your layouts deserve better than Lorem Ipsum.</span>
            </div>
            <div class="middleNav">
                <ul>
                    <li class="mUser"><a title=""><span class="users"></span></a>
                        <ul class="mSub1">
                            <li><a href="#" title="">Add user</a></li>
                            <li><a href="#" title="">Statistics</a></li>
                            <li><a href="#" title="">Orders</a></li>
                        </ul>
                    </li>
                    <li class="mMessages"><a title=""><span class="messages"></span></a>
                        <ul class="mSub2">
                            <li><a href="#" title="">New tickets<span class="numberRight">8</span></a></li>
                            <li><a href="#" title="">Pending tickets<span class="numberRight">12</span></a></li>
                            <li><a href="#" title="">Closed tickets</a></li>
                        </ul>
                    </li>
                    <li class="mFiles"><a href="#" title="Or you can use a tooltip" class="tipN"><span class="files"></span></a></li>
                    <li class="mOrders"><a title=""><span class="orders"></span><span class="numberMiddle">8</span></a>
                        <ul class="mSub4">
                            <li><a href="#" title="">Pending uploads</a></li>
                            <li><a href="#" title="">Statistics</a></li>
                            <li><a href="#" title="">Trash</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div class="line"></div>

    <!-- Page statistics and control buttons area -->
    <div class="statsRow">
        <div class="wrapper">
            <div class="controlB">
                <ul>
                    <li><a href="#" title=""><img src="images/icons/control/32/plus.png" alt="" /><span>Add new session</span></a></li>
                    <li><a href="#" title=""><img src="images/icons/control/32/database.png" alt="" /><span>New DB entry</span></a></li>
                    <li><a href="#" title=""><img src="images/icons/control/32/hire-me.png" alt="" /><span>Add new user</span></a></li>
                    <li><a href="#" title=""><img src="images/icons/control/32/statistics.png" alt="" /><span>Check statistics</span></a></li>
                    <li><a href="#" title=""><img src="images/icons/control/32/comment.png" alt="" /><span>Review comments</span></a></li>
                    <li><a href="#" title=""><img src="images/icons/control/32/order-149.png" alt="" /><span>Check orders</span></a></li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
    </div>

    <div class="line"></div>

    <!-- Main content wrapper -->
    <div class="wrapper">

    <!-- Note -->
    <div class="nNote nInformation hideit">
        <p><strong>INFORMATION: </strong>Top buttons area has 3 versions - 2 kinds of buttons and statistics. All of them could be viewed on <a href="ui_elements.html" title="">Interface elements page</a></p>
    </div>

    <!-- Chart -->
    <div class="widget chartWrapper">
        <div class="title"><img src="images/icons/dark/stats.png" alt="" class="titleIcon" /><h6>Chart</h6></div>
        <div class="body"><div class="chart"></div></div>
    </div>

    <!-- Widgets -->
    <div class="widgets">
    <div class="oneTwo">

        <!-- Partners list widget -->
        <div class="widget">
            <div class="title"><img src="images/icons/dark/users.png" alt="" class="titleIcon" /><h6>Partners list</h6></div>
            <ul class="partners">
                <li>
                    <a href="#" title="" class="floatL"><img src="images/user.png" alt="" /></a>
                    <div class="pInfo">
                        <a href="#" title=""><strong>Dave Armstrong</strong></a>
                        <i>Creative director at Google Inc. Zurich</i>
                    </div>
                    <div class="pLinks">
                        <a href="#" title="Direct call" class="tipW"><img src="images/icons/pSkype.png" alt="" /></a>
                        <a href="#" title="Send an email" class="tipW"><img src="images/icons/pEmail.png" alt="" /></a>
                    </div>
                    <div class="clear"></div>
                </li>
                <li>
                    <a href="#" title="" class="floatL"><img src="images/user.png" alt="" /></a>
                    <div class="pInfo">
                        <a href="#" title=""><strong>Nora McDonald</strong></a>
                        <i>Lead developer, Alaska</i>
                    </div>
                    <div class="pLinks">
                        <a href="#" title="Direct call" class="tipW"><img src="images/icons/pSkype.png" alt="" /></a>
                        <a href="#" title="Send an email" class="tipW"><img src="images/icons/pEmail.png" alt="" /></a>
                    </div>
                    <div class="clear"></div>
                </li>
                <li>
                    <a href="#" title="" class="floatL"><img src="images/user.png" alt="" /></a>
                    <div class="pInfo">
                        <a href="#" title=""><strong>Natalie Zimmerman</strong></a>
                        <i>Actually it's a guy. Yeah, unexpected</i>
                    </div>
                    <div class="pLinks">
                        <a href="#" title="Direct call" class="tipW"><img src="images/icons/pSkype.png" alt="" /></a>
                        <a href="#" title="Send an email" class="tipW"><img src="images/icons/pEmail.png" alt="" /></a>
                    </div>
                    <div class="clear"></div>
                </li>
                <li>
                    <a href="#" title="" class="floatL"><img src="images/user.png" alt="" /></a>
                    <div class="pInfo">
                        <a href="#" title=""><strong>Maria Paradeux</strong></a>
                        <i>Very hot secretary, Playboy rockstar</i>
                    </div>
                    <div class="pLinks">
                        <a href="#" title="Direct call" class="tipW"><img src="images/icons/pSkype.png" alt="" /></a>
                        <a href="#" title="Send an email" class="tipW"><img src="images/icons/pEmail.png" alt="" /></a>
                    </div>
                    <div class="clear"></div>
                </li>
            </ul>
        </div>

        <!-- Website stats widget -->
        <div class="widget">
            <div class="title"><img src="images/icons/dark/stats.png" alt="" class="titleIcon" /><h6>Website statistics</h6></div>
            <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
                <thead>
                <tr>
                    <td width="80">Amount</td>
                    <td>Description</td>
                    <td width="80">Changes</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td align="center"><a href="#" title="" class="webStatsLink">980</a></td>
                    <td>returned visitors</td>
                    <td><span class="statsPlus">0.32%</span></td>
                </tr>
                <tr>
                    <td align="center"><a href="#" title="" class="webStatsLink">1545</a></td>
                    <td>new registrations</td>
                    <td><span class="statsMinus">82.3%</span></td>
                </tr>
                <tr>
                    <td align="center"><a href="#" title="" class="webStatsLink">457</a></td>
                    <td>new affiliates registrations</td>
                    <td><span class="statsPlus">100%</span></td>
                </tr>
                <tr>
                    <td align="center"><a href="#" title="" class="webStatsLink">9543</a></td>
                    <td>new visitors</td>
                    <td><span class="statsPlus">4.99%</span></td>
                </tr>
                <tr>
                    <td align="center"><a href="#" title="" class="webStatsLink">354</a></td>
                    <td>new pending comments</td>
                    <td><span class="statsMinus">9.67%</span></td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Latest update widget -->
        <div class="widget">
            <div class="title"><img src="images/icons/dark/refresh4.png" alt="" class="titleIcon" /><h6>Latest updates</h6></div>

            <div class="updates">
                <div class="newUpdate">
                    <div class="uDone">
                        <a href="#" title=""><strong>A new server is on the board!</strong></a>
                        <span>We've just set up a new server. Our gurus ...</span>
                    </div>
                    <div class="uDate"><span class="uDay">08</span>feb</div>
                    <div class="clear"></div>
                </div>

                <div class="newUpdate">
                            <span class="uAlert">
                                <a href="#" title=""><strong>[ URGENT ] ex.ua was closed by government</strong></a>
                                <span>But already everything was solved. It will ...</span>
                            </span>
                    <span class="uDate"><span class="uDay">08</span>feb</span>
                    <div class="clear"></div>
                </div>

                <div class="newUpdate">
                            <span class="uDone">
                                <a href="#" title=""><strong>The goal was reached!</strong></a>
                                <span>We just passed 1000 sales! Congrats to all</span>
                            </span>
                    <span class="uDate"><span class="uDay">07</span>feb</span>
                    <div class="clear"></div>
                </div>

                <div class="newUpdate">
                            <span class="uNotice">
                                <a href="#" title=""><strong>Meat a new team member - Don Corleone</strong></a>
                                <span>Very dyplomatic and flexible sales manager</span>
                            </span>
                    <span class="uDate"><span class="uDay">06</span>feb</span>
                    <div class="clear"></div>
                </div>

            </div>
        </div>
    </div>

    <!-- 2 columns widgets -->
    <div class="oneTwo">

        <!-- Search -->
        <div class="searchWidget">
            <form action="">
                <input type="text" name="search" placeholder="Enter search text..." />
                <input type="submit" name="find" value="" />
            </form>
        </div>

        <!-- Purchase info widget -->
        <div class="widget">
            <div class="title">
                <img src="images/icons/dark/money.png" alt="" class="titleIcon" />
                <h6>Purchase info</h6>
                <div class="topIcons">
                    <a href="#" class="tipS" title="Download statement"><img src="images/icons/downloadTop.png" alt="" /></a>
                    <a href="#" class="tipS" title="Print invoice"><img src="images/icons/printTop.png" alt="" /></a>
                    <a href="#" class="tipS" title="Edit"><img src="images/icons/editTop.png" alt="" /></a>
                </div>
            </div>
            <div class="newOrder">
                <div class="userRow">
                    <a href="#" title=""><img src="images/user.png" alt="" class="floatL" /></a>
                    <ul class="leftList">
                        <li><a href="#" title=""><strong>Julia Maria Shine</strong></a></li>
                        <li>Order status:</li>
                    </ul>
                    <ul class="rightList">
                        <li><a href="#" title=""> <strong>#2112</strong></a></li>
                        <li class="orderIcons"><span class="oUnfinished"></span><span class="oShipped tipN" title="Shipped on Feb 2nd, 2012"></span><span class="oPaid tipN" title="Paid on Feb 1st, 2012"></span></li>
                    </ul>
                    <div class="clear"></div>
                </div>

                <div class="cLine"></div>

                <div class="orderRow">
                    <ul class="leftList">
                        <li>Date and time:</li>
                        <li>Subtotal amount:</li>
                        <li>Taxes</li>
                    </ul>
                    <ul class="rightList">
                        <li><strong>Jan 31, 2012</strong> |  12:51</li>
                        <li><strong class="green">$5,514.36</strong></li>
                        <li><strong class="orange">- $1,158.54</strong></li>
                    </ul>
                    <div class="clear"></div>
                </div>

                <div class="cLine"></div>
                <div class="totalAmount"><h6 class="floatL blue">Total:</h6><h6 class="floatR blue">$12,157.99</h6><div class="clear"></div></div>
            </div>
        </div>

        <!-- New users widget -->



        <div class="clear"></div>

    </div>
    <div class="clear"></div>
    </div>

    <!-- Events calendar -->
    <div class="widget">
        <div class="title"><img src="images/icons/dark/monthCalendar.png" alt="" class="titleIcon" /><h6>Events</h6></div>
        <div class="calendar"></div>
    </div>

    <!-- Media table -->




    </div>


<div class="row">

	<div class="col-md-8">

		<?php if(have_posts()) : ?>
		   <?php while(have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php the_title('<h2>','</h2>'); ?>
		 		<?php the_content(); ?>
			</div>
			<?php
			if (is_singular()) {
				// support for pages split by nextpage quicktag
				wp_link_pages();

				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

				// Previous/next post navigation.
				the_post_navigation( array(
					'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'twentyfifteen' ) . '</span> ' .
						'<span class="screen-reader-text">' . __( 'Next post:', 'twentyfifteen' ) . '</span> ' .
						'<span class="post-title">%title</span>',
					'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'twentyfifteen' ) . '</span> ' .
						'<span class="screen-reader-text">' . __( 'Previous post:', 'twentyfifteen' ) . '</span> ' .
						'<span class="post-title">%title</span>',
				) );

				// tags anyone?
				the_tags();
			}
			?>
		   <?php endwhile; ?>

		<?php if (!is_singular()) : ?>
			<div class="nav-previous alignleft"><?php next_posts_link( 'Older posts' ); ?></div>
			<div class="nav-next alignright"><?php previous_posts_link( 'Newer posts' ); ?></div>
		<?php endif; ?>

		<?php else : ?>

		<div class="alert alert-info">
		  <strong>No content in this loop</strong>
		</div>

		<?php endif; ?>
	</div>

	<div class="col-md-4">

		<?php
		 if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar')) : //  Sidebar name
		?>
		<?php
		     endif;
		?>
	</div>

</div>




<?php get_footer(); ?>