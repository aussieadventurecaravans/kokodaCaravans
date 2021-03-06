<?php get_header(); ?>
<?php
$terms = get_terms('product-cat','orderby=name' );

$caravan_archive_category = 0;

foreach ( $terms as $term ){
    if(in_array( $term->name ,array('Caravan Archive')))
    {
        $caravan_archive_category = $term->term_id;
        break;
    }
}
?>
<div class="page-content search">
	<div class="container">
	
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1">
			
				<div class="row">
					<div class="col-xs-12">
					
						<h2>Results for "<?php the_search_query(); ?>"</h2>
						
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                            <?php //make sure post which has archive category can't show at result page ?>
                            <?php $categories = array(); ?>
                            <?php $terms = wp_get_post_terms( get_the_ID(), 'product-cat' ); ?>
                            <?php foreach ( $terms as $term ){ $categories[] = $term->term_id; }?>
                            <?php if(!in_array($caravan_archive_category,$categories)): ?>

                                <div class="result row">
                                    <div class="result-thumbnail col-xs-3">
                                        <?php get_the_image(array('meta_key' => 'banner_image')); ?>
                                    </div>
                                    <div class="result-item col-xs-9">
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

                            <?php endif; ?>

							<?php endwhile; else : ?>
								<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
						<?php endif; ?>

					</div>
				</div>			
			
			</div>
		</div>
		
	</div>
</div>

<?php get_footer(); ?>