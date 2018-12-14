<?php $banner_img = get_field('home_banner_image'); ?>
<?php $banner_img_2 = get_field('home_banner_image_2'); ?>
<?php $banner_img_3 = get_field('home_banner_image_3'); ?>
<?php $banner_img_4 = get_field('home_banner_image_4'); ?>
<div class="banner">
    <div class="banner-list-wrap" id="banner-list">
        <?php if(!empty($banner_img)): ?>
            <div class="banner-wrap item">
                <div class="banner container-fluid">
                    <div class="row">
                        <img src="<?php echo $banner_img; ?>" />
                        <div class="banner-content">
                            <?php the_field('home_banner_text'); ?>
                            <a href="<?php the_field('home_banner_button_link'); ?>" class="btn btn-default"><?php the_field('home_banner_button_text'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if(!empty($banner_img_2)): ?>
            <div class="banner-wrap item">
                <div class="banner container-fluid">
                    <div class="row">
                        <img src="<?php echo $banner_img_2; ?>" />
                        <div class="banner-content">
                            <?php the_field('home_banner_text_2'); ?>
                            <a href="<?php the_field('home_banner_button_link_2'); ?>" class="btn btn-default"><?php the_field('home_banner_button_text_2'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if(!empty($banner_img_3)): ?>
            <div class="banner-wrap item">
                <div class="banner container-fluid">
                    <div class="row">
                        <img src="<?php echo $banner_img_3; ?>" />
                        <div class="banner-content">
                            <?php the_field('home_banner_text_3'); ?>
                            <a href="<?php the_field('home_banner_button_link_3'); ?>" class="btn btn-default"><?php the_field('home_banner_button_text_3'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if(!empty($banner_img_4)): ?>
            <div class="banner-wrap item">
                <div class="banner container-fluid">
                    <div class="row">
                        <img src="<?php echo $banner_img_4; ?>" />
                        <div class="banner-content">
                            <?php the_field('home_banner_text_4'); ?>
                            <a href="<?php the_field('home_banner_button_link_4'); ?>" class="btn btn-default"><?php the_field('home_banner_button_text_4'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
  <!--  <div class="arrow-left">
        <img src="/wp-content/themes/Kokoda/_img/banner-arrow-left.png" class="white-arrow-left"/>
    </div>
    <div class="arrow-right">
        <img src="/wp-content/themes/Kokoda/_img/banner-arrow-right.png" class="white-arrow-right"/>
    </div>-->
</div>

<div class="scroll-btn">
    <a href="#break"><span class="arrow-down"></span></a>
</div>
<div class="mobile-banner-wrap stripe center">
    <div class="banner container">
        <div class="row">
            <div class="banner-content content-1">
                <?php the_field('home_banner_text'); ?>
                <a href="<?php the_field('home_banner_button_link'); ?>" class="btn btn-default"><?php the_field('home_banner_button_text'); ?></a>
            </div>
            <div class="banner-content content-2">
                <?php the_field('home_banner_text_2'); ?>
                <a href="<?php the_field('home_banner_button_link_2'); ?>" class="btn btn-default"><?php the_field('home_banner_button_text_2'); ?></a>
            </div>
            <div class="banner-content content-3">
                <?php the_field('home_banner_text_3'); ?>
                <a href="<?php the_field('home_banner_button_link_3'); ?>" class="btn btn-default"><?php the_field('home_banner_button_text_3'); ?></a>
            </div>
            <div class="banner-content content-4">
                <?php the_field('home_banner_text_4'); ?>
                <a href="<?php the_field('home_banner_button_link_4'); ?>" class="btn btn-default"><?php the_field('home_banner_button_text_4'); ?></a>
            </div>
        </div>
    </div>
</div>
<div class="break" id="break">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2><?php the_field('home_break_heading'); ?></h2>
            </div>
        </div>
    </div>
</div>

<?php if(have_rows('home_featured_caravans')): ?>


	<div class="featured clearfix">
        <div class="row">
            <div class="header-wrapper">
                <h2>Our Caravans</h2>
            </div>
        </div>
		<?php while (have_rows('home_featured_caravans')) : the_row(); ?>
	
			<?php $post_object = get_sub_field('home_featured_caravan');
			if($post_object): 
			
				$post = $post_object;
				setup_postdata( $post ); 
				
				$badge_img = get_field('banner_badge');
				$product_img = get_field('banner_image'); ?>
				
			    <div class="item">

				    	<div class="item-img">
				    		<?php if(!empty($badge_img)): ?>
								<div class="banner-badge" style="background-image:url('<?php echo $badge_img['url'] ?>')"></div>
							<?php endif; ?>
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php echo $product_img['sizes']['medium']; ?>" alt="product image"/>
                            </a>
				    	</div>
				    	<div class="item-details clearfix">
				    		<div class="details">
				    			<h3><?php the_title(); ?></h3>
				    			<p><?php the_field('banner_description'); ?></p>
				    		</div>
                            <div class="find-out-more">
                                <a href="<?php the_permalink(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/_img/arrow-right-black.png"/> Find Out More</a>
                            </div>
				    	</div>

			    </div>
			    
			    <?php wp_reset_postdata(); ?>
			<?php endif; ?>
		
		<?php endwhile; ?>
		
	</div>
	
<?php endif; ?>
<div class="custom-order-panel">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <img src="<?php echo get_field('custom_order_banner'); ?>" />
                <div class="custom-order-content">
                    <?php the_field('custom_order_text'); ?>
                    <a href="<?php the_field('custom_order_link'); ?>" class="btn btn-default">Build It</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if(have_rows('home_benefit_icons')): ?>

	<div class="benefits">
		<div class="container">
			<div class="row">
	
				<?php while (have_rows('home_benefit_icons')) : the_row(); ?>
		
					<div class="icon col-sm-6 col-md-3">
						<img src="<?php the_sub_field('icon'); ?>">
						<h4><?php the_sub_field('text'); ?></h4>
					</div>
				<?php endwhile; ?>
		
			</div>
		</div>
	</div>

<?php endif; ?>

<?php if(have_rows('home_featured_news')): ?>

	<div class="blog">
		<div class="container">
			<div class="row">
			
				<div class="col-xs-12">
					<h2>Latest News</h2>
				</div>
			
			</div>
			<div class="row">
				
				<?php while (have_rows('home_featured_news')) : the_row(); ?>
				
					<?php $post_object = get_sub_field('home_featured_news_item');
					if($post_object): 
					
						$post = $post_object;
						setup_postdata( $post );
						
							$categories = get_the_category();
							$cat_slug = '';
							if ( ! empty( $categories ) ) {
							    foreach( $categories as $category ) {
							        $cat_slug = $category->slug;
							    }
							} 
							
							if($cat_slug == "article") :
								$cat_link = "news";
							elseif($cat_slug == "event") :
								$cat_link = "events";
							else :
								$cat_link = "media";
							endif;
							
							?>
						
							<div class="item <?php echo $cat_slug; ?> col-sm-4">
								<h3>
									<?php if(get_field('post_link')): ?>
										<a href="<?php the_field('post_link'); ?>"><?php the_title(); ?></a>
									<?php else: ?>
										<a href="<?php bloginfo('url'); ?>/whats-on/<?php echo $cat_link; ?>"><?php the_title(); ?></a>
									<?php endif; ?>
								</h3>
								<!--<em><?php /*echo get_the_date(); */?><?php /*if(get_field('end_date')):*/?> - <?php /*the_field('end_date'); endif; */?></em>-->
								<p><?php the_field('post_snippet'); ?></p>
							</div>
						
						<?php wp_reset_postdata(); ?>
					<?php endif; ?>
				
				<?php endwhile; ?>
			
			</div>
		</div>
	</div>
	
<?php endif; ?>


<?php get_footer(); ?>

<!-- Important Owl stylesheet -->
<link rel="stylesheet" href="/wp-content/themes/Kokoda/owl-carousel/owl.carousel.css">
<!-- Default Theme -->
<link rel="stylesheet" href="/wp-content/themes/Kokoda/owl-carousel/owl.theme.css">
<!-- Include js plugin -->
<script src="/wp-content/themes/Kokoda/owl-carousel/owl.carousel.js"></script>


<script type="text/javascript">
    jQuery(document).ready(function($){

        var owl =  $("#banner-list");

       $("#banner-list").owlCarousel({

            navigation : false, // Show next and prev buttons
            slideSpeed :2000,
            pagination: true,
            paginationSpeed : 2000,
            singleItem:true,
            autoPlay: 7000,
            afterAction : afterOwlAction
        });

        function afterOwlAction()
        {
            var current_feature = this.owl.currentItem + 1;
            $(".home .mobile-banner-wrap .banner-content").hide();
            $(".home .mobile-banner-wrap .banner-content.content-" + current_feature).show();

        }

      /*  $(".home.page .banner .arrow-left img").on('click', function(){
            $("#banner-list").trigger('owl.prev');
        });
        $(".home.page .banner .arrow-right img").on('click',function(){
            $("#banner-list").trigger('owl.next');
        });*/

        var logo_offset = $('.navbar-default .navbar-header .brand img').offset();

        $('.page.home .banner-wrap .banner .row .banner-content').css({'left' : logo_offset.left});
        $( window ).resize(function(){
            if($(window).width() > 776 )
            {
                var logo_offset = $('.navbar-default .navbar-header .brand img').offset();
                $('.page.home .banner-wrap .banner .row .banner-content').css({'left' : logo_offset.left});
            }

        });
    });
</script>
