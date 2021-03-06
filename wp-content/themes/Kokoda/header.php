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
	<body <?php if( isset($class) && !empty($class)){ body_class($class); }else{ body_class(); } ?> >
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
                    <a class="brand auto-top" href="<?php bloginfo('url') ?>">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/_img/logo_black_text.png">
                    </a>
                    <div class="all-caravans-menu">
                        <a class="caravans-header" href="#"><h3>Caravans <span class="caret"></span></h3></a>
                    </div>
					<button type="button" class="navbar-toggle" data-target="#navbar-collapse">
						<span class="sr-only">Toggle Navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

				<div class="collapse navbar-collapse" id="navbar-collapse">
					<ul class="nav navbar-nav">
						<?php

                        wp_nav_menu( array(
							'theme_location' => 'mobile-main-menu',
							'depth' => 2,
							'container' => 'false',
							'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
							'items_wrap' => '%3$s', 
							'walker' => new wp_bootstrap_navwalker() ) 
						);

						?>
                        <!-- Add search button to mobile menu-->
                        <li class="menu-item menu-search visible-xs nav-search">
                            <a href="#" class="mobile-search-btn" data-toggle="modal" data-target="#mobileSearch">Search</a>
                        </li>
					</ul>
				</div>
			</div>
		</nav>
<?php
    //retrieve the product that are included to main navigation
    $args = array(
        'post_type' => 'product',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'nopaging' => true
    ); ?>
    <?php query_posts($args); ?>
    <?php if (have_posts()) : $count = 0; ?>
    <div class="products-navigation">
        <div class="product-list container-fluid">
            <?php while (have_posts()) : the_post(); ?>

             <?php  $add_navigation = get_field('add_to_main_menu');  ?>
             <?php if($add_navigation == true): ?>
                <?php if($count ==  0): ?>
                    <?php echo "<div class=\"row\">"; ?>

                <?php endif; ?>

                <?php if($count <  3): ?>
                    <?php $product_img = get_field('banner_image');  ?>
                    <div class="product-list-item col-lg-4">
                        <div class="item-img">
                            <?php if($product_img): ?>
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php echo $product_img['sizes']['medium']; ?>" alt="<?php echo $product_img['alt']; ?>" class="product-img" />
                            </a>
                            <?php endif; ?>
                        </div>
                        <div class="item-details">
                            <div class="details">
                                <h4 class="item-title"><?php the_title(); ?></h4>
                                <?php if(get_field('banner_description')): ?><p><?php the_field('banner_description'); ?></p><?php endif; ?>
                            </div>
                            <div class="overview">
                               <a href="<?php the_permalink(); ?>"> <img src="<?php echo get_stylesheet_directory_uri(); ?>/_img/arrow-right-black.png"/> overview </a>
                            </div>
                        </div>
                    </div>
                    <?php  $count++; $open_element = true ;?>
                 <?php endif; ?>

                <?php if($count ==  3): ?>
                        <?php echo "</div>"; ?>
                        <?php  $count= 0; $open_element = false; ?>
                <?php endif; ?>

             <?php endif; ?>

            <?php endwhile; ?>

            <?php if($open_element ==  true): ?>
                <?php echo "</div>"; ?>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-12 show-all">
                    <h4 class="desktop-range-button">
                        <a href="<?php echo get_home_url(); ?>/range/"> >>> Explore The Range <<< </a>
                    </h4>
                    <h4 class="mobile-range-button">
                        <a href="<?php echo get_home_url(); ?>/range/">Explore The Range</a>
                    </h4>
                </div>
            </div>

        </div>

    </div>
    <?php endif; ?>
<?php wp_reset_query();?>
