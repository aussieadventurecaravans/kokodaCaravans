<!DOCTYPE html>
<html lang="en">
	<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title><?php wp_title( '|', true, 'right' ); ?></title>

		<link rel="icon" href="favicon.ico">

		<?php wp_head(); ?>

		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

	</head>
	<body <?php body_class($class); ?>>
		<nav id="navbar-top" class="hidden-xs navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container-fluid nav-container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="navbar-header">
                            <a class="brand auto-top" href="<?php bloginfo('url') ?>"><img src="<?php the_field('site_logo', 'options'); ?>"></a>
                        </div>
                        <div class="collapse navbar-collapse">
                            <div class="top-nav-panel">
                                <?php
                                wp_nav_menu( array(
                                       'theme_location' => 'top-menu',
                                       'depth' => 2,
                                       'container' => 'false',
                                       'menu_class' => 'top-menu'
                                       )
                               );
                                ?>
                            </div>
                            <div class="main-navi-panel">
                                    <div class="search-box">
                                        <div class="container nav-container">
                                            <?php get_search_form(); ?>
                                        </div>
                                    </div>
                                    <ul class="nav navbar-nav navbar-left">
                                        <?php wp_nav_menu( array(
                                            'theme_location' => 'primary',
                                            'depth' => 2,
                                            'container' => 'false',
                                            'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
                                            'items_wrap' => '%3$s',
                                            'walker' => new wp_bootstrap_navwalker() )
                                            ); ?>
                                        <li class="nav-search hidden-xs ico-search"><a href="#">Search</a></li>
                                    </ul>
                            </div>
                        </div>
                    </div>
                </div>
			</div>

		</nav>
		
		<nav id="navbar-top-mob" class="visible-xs navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container-fluid nav-container">
				<div class="navbar-header">
					<div class="nav-search visible-xs">
						<a href="#" data-toggle="modal" data-target="#mobileSearch">Search</a><li class="menu-item menu-search visible-xs">
					</div>
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
						<span class="sr-only">Toggle Navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="brand auto-top" href="<?php bloginfo('url') ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/_img/logo.png"></a>
				</div>
				<div class="collapse navbar-collapse" id="navbar-collapse">
					<ul class="nav navbar-nav navbar-left">
						<?php wp_nav_menu( array( 
							'theme_location' => 'primary',
							'depth' => 2,
							'container' => 'false',
							'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
							'items_wrap' => '%3$s', 
							'walker' => new wp_bootstrap_navwalker() ) 
							); ?>
					</ul>
				</div>
			</div>
		</nav>