<?php 

/**
 * Template Name: Product Listing
 */

get_header(); ?>

<nav class="page-nav navbar-fixed-top hidden-xs">
	<div class="container-fluid nav-container">
		<ul class="nav navbar-nav navbar-right">
			<?php $category_submenu = get_field('page_category_submenu'); ?>
			<?php wp_nav_menu( array( 
				'theme_location' => $category_submenu,
				'depth' => 1,
				'container' => 'false',
				'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
				'items_wrap' => '%3$s', 
				'walker' => new wp_bootstrap_navwalker() ) 
				); ?>
		</ul>
	</div>
</nav>

<?php $banner_img = get_field('page_banner'); ?>

<div class="banner-wrap"<?php if(!empty($banner_img)) : ?> style="background-image: url('<?php echo $banner_img['url']; ?>');"<?php endif; ?>>
	<div class="banner container">
		<div class="row">
			<div class="banner-content">
				<h1><?php the_title(); ?></h1>
			</div>
		</div>
	</div>
</div>

<?php if(get_field('page_intro_heading') || get_field('page_intro_text')): ?>
<div class="stripe center intro">
	<div class="container">
		<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12">
	
			<?php if(get_field('page_intro_heading')): ?><h2><?php the_field('page_intro_heading'); ?></h2><?php endif; ?>
			
			<?php if(get_field('page_intro_text')): ?><p><?php the_field('page_intro_text'); ?></p><?php endif; ?>
		</div>
	</div>
</div>
<?php endif; ?>

<div class="stripe center listing">
	<div class="container">
		<div class="row">
		
			<div class="filter col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12">
				<h2>Display By</h2>
				<form class="form-inline">
					<div class="form-group">
						<label>Price</label>
						<select class="form-control" data-filter-group="filter-price">
							<option value="*" data-filter-value="">All</option>
							<option value=".price-41-50" data-filter-value=".price-41-50">$41k - $50k</option>
							<option value=".price-51-60" data-filter-value=".price-51-60">$51k - $60k</option>
							<option value=".price-61-70" data-filter-value=".price-61-70">$61k - $70k</option>
							<option value=".price-71-80" data-filter-value=".price-71-80">$71k - $80k</option>
							<option value=".price-81-90" data-filter-value=".price-81-90">$81k - $90k</option>
							<option value=".price-91-100" data-filter-value=".price-91-100">$91k - $100k</option>
							<option value=".price-100" data-filter-value=".price-91">$100k+</option>
						</select>
					</div>
					<div class="form-group">
						<label>Size</label>
						<select class="form-control" data-filter-group="filter-size">
							<option value="*" data-filter-value="">All</option>
							<option value=".size-14-19" data-filter-value=".size-14-19">14" - 19"</option>
							<option value=".size-20-25" data-filter-value=".size-20-25">20" - 25"</option>
							<option value=".size-26" data-filter-value=".size-26">26"+</option>
						</select>
					</div>
				</form>
			</div>
			
		</div>
	</div>
	
	<div class="container featured clearfix list">
	
		<?php $listing_category = get_field('page_category');
		$args = array(
			'post_type' => 'product',
			'tax_query' => array(
				array(
					'taxonomy' => 'product-cat',
					'field'    => 'term_id',
					'terms'    => $listing_category,
				),
			),
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'nopaging' => true
		); ?>
							
		<?php query_posts($args); ?>
			<?php if (have_posts()) : $count = 0; ?>
				<?php while (have_posts()) : the_post(); ?>
                    <?php //Starting Element Row ?>
                     <?php if($count ==  0): ?>
                            <?php //echo "<div class='row'>"; ?>
                    <?php endif; ?>

                    <?php $count++ ?>

                    <?php
                    $product_img = get_field('banner_image');
                    $badge_img = get_field('banner_badge');

                    $filter_price = get_field('price_thousands');
                    $filter_size = get_field('size_feet');

                    if($filter_price <= 51) :
                        $filter_price = "price-41-50";
                    elseif($filter_price > 51 && $filter_price <= 61) :
                        $filter_price = "price-51-60";
                    elseif($filter_price > 61 && $filter_price <= 71) :
                        $filter_price = "price-61-70";
                    elseif($filter_price > 71 && $filter_price <= 81) :
                        $filter_price = "price-71-80";
                    elseif($filter_price > 81 && $filter_price <= 91) :
                        $filter_price = "price-81-90";
                    elseif($filter_price > 91 && $filter_price <= 101) :
                        $filter_price = "price-91-100";
                    elseif($filter_price > 100) :
                        $filter_price = "price-91";
                    endif;

                    if($filter_size <= 19) :
                        $filter_size = "size-14-19";
                    elseif($filter_size > 19 && $filter_size <= 25) :
                        $filter_size = "size-20-25";
                    elseif($filter_size > 25) :
                        $filter_size = "size-26";
                    endif;

                    ?>

					<div class="item <?php echo $filter_price; ?> <?php echo $filter_size; ?> col-xs-12 col-sm-6 col-md-6 col-lg-4">
						<a href="<?php the_permalink(); ?>">
							<div class="item-img" <?php if($product_img): ?>style="background-image: url('<?php echo $product_img['sizes']['medium']; ?>');"<?php endif; ?>>
								<?php if(!empty($badge_img)): ?>
									<div class="banner-badge" style="background-image:url('<?php echo $badge_img['url'] ?>')"></div>
								<?php endif; ?>
							</div>
							<div class="item-details">
								<div class="details">
									<h3><?php the_title(); ?></h3>
									<div class="product-meta clearfix">
										<?php if(get_field('price_thousands')): ?><span class="price">$<?php the_field('price_thousands'); ?>,<?php the_field('price_hundreds'); ?><i>+ORC</i></span><?php endif; ?>
										<?php if(get_field('size_feet')): ?><span class="size"><?php the_field('size_feet'); ?>'<?php if(get_field('size_inches')): ?><?php the_field('size_inches'); ?>"<?php endif; ?></span><?php endif; ?>
										<?php if(get_field('occupants')): ?><span class="occupants"><?php the_field('occupants'); ?></span><?php endif; ?>
									</div>
									<?php if(get_field('banner_description')): ?><p><?php the_field('banner_description'); ?></p><?php endif; ?>
									<?php if(get_field('tare')): ?><span class="tare">Tare (approx): <?php the_field('tare'); ?></span><br><?php endif; ?>
									<?php if(get_field('ball_weight')): ?><span class="ball">Ball weight (approx): <?php the_field('ball_weight'); ?></span><?php endif; ?>
								</div>
							</div>
						</a>
					</div>
                    <?php //close element Row ?>
                    <?php if($count ==  3): ?>
                        <?php   //echo "</div>"; ?>
                        <?php  $count= 0; $open_element = false; ?>
                    <?php endif; ?>
				<?php endwhile; ?>


                <?php //close element Row ?>
                <?php if($count <  3): ?>
                    <?php   //echo "</div>"; ?>
                <?php endif; ?>

            <?php else: ?>

            <?php endif; ?>


		<?php wp_reset_query(); ?>
			
	</div>
</div>

<?php get_footer(); ?>


<script type="text/javascript">
    (function($) {

        'use strict';

        var $filters = $('.filter [data-filter]'),
            $boxes = $('.boxes [data-category]');

        $filters.on('click', function(e) {
            e.preventDefault();
            var $this = $(this);

            $filters.removeClass('active');
            $this.addClass('active');

            var $filterColor = $this.attr('data-filter');

            if ($filterColor == 'all') {
                $boxes.removeClass('is-animated')
                    .fadeOut().finish().promise().done(function() {
                    $boxes.each(function(i) {
                        $(this).addClass('is-animated').delay((i++) * 200).fadeIn();
                    });
                });
            }
            else if ($filterColor == 'red-blue')
            {
                $boxes.removeClass('is-animated')
                    .fadeOut().finish().promise().done(function() {
                    $boxes.filter('[data-category = red],[data-category = blue]').each(function(i) {
                        $(this).addClass('is-animated').delay((i++) * 200).fadeIn();
                    });
                });

            }
            else if ($filterColor == 'blue-green')
            {
                $boxes.removeClass('is-animated')
                    .fadeOut().finish().promise().done(function() {
                    $boxes.filter('[data-category = blue],[data-category = green]').each(function(i) {
                        $(this).addClass('is-animated').delay((i++) * 200).fadeIn();
                    });
                });

            }
            else {
                $boxes.removeClass('is-animated')
                    .fadeOut().finish().promise().done(function() {
                    $boxes.filter('[data-category = "' + $filterColor + '"]').each(function(i) {
                        $(this).addClass('is-animated').delay((i++) * 200).fadeIn();
                    });
                });
            }
        });

    })(jQuery);

