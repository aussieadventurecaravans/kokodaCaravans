<?php
/**
 * Search results are contained within a div.searchwp-live-search-results
 * which you can style accordingly as you would any other element on your site
 *
 * Some base styles are output in wp_footer that do nothing but position the
 * results container and apply a default transition, you can disable that by
 * adding the following to your theme's functions.php:
 *
 * add_filter( 'searchwp_live_search_base_styles', '__return_false' );
 *
 * There is a separate stylesheet that is also enqueued that applies the default
 * results theme (the visual styles) but you can disable that too by adding
 * the following to your theme's functions.php:
 *
 * wp_dequeue_style( 'searchwp-live-search' );
 *
 * You can use ~/searchwp-live-search/assets/styles/style.css as a guide to customize
 */
?>

<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php $post_type = get_post_type_object( get_post_type() ); ?>
		<div class="searchwp-live-search-result">
			<div class="clearfix">
				<div class="result-thumbnail"><?php get_the_image(array('meta_key' => 'banner_image')); ?></div>
				<div class="result-item">
					<h4><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a></h4>
					<?php if (get_field('price_thousands')) : ?>
						<span class="search-meta">$<?php the_field('price_thousands'); ?>,<?php the_field('price_hundreds'); ?></span>
					<?php endif; ?>
					<?php if (get_field('size_feet')) : ?>
						<span class="search-meta"><strong>Size:</strong> <?php the_field('size_feet'); ?>"<?php if (get_field('size_inches')) : ?><?php the_field('size_inches'); ?>'<?php endif; ?></span>
					<?php endif; ?>
					<?php if (get_field('occupants')) : ?>
						<span class="search-meta"><strong>Occupants:</strong> <?php the_field('occupants'); ?></span>
					<?php endif; ?>
					<?php if (get_field('tare')) : ?>
						<span class="search-meta"><strong>Tare:</strong> <?php the_field('tare'); ?>kg</span>
					<?php endif; ?>
					<?php if (get_field('ball_weight')) : ?>
						<span class="search-meta"><strong>Ball weight:</strong> <?php the_field('ball_weight'); ?>kg</span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endwhile; ?>
<?php else : ?>
	<p class="searchwp-live-search-no-results">
		<em><?php _ex( 'No results found.', 'swplas' ); ?></em>
	</p>
<?php endif; ?>
