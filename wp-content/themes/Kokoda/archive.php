<?php get_header(); ?>

<div class="banner-wrap">
	<div class="banner container">
		<div class="row">
			<div class="banner-content">
				<h1>
				<?php single_month_title(' '); ?>
				</h1>
			</div>
		</div>
	</div>
</div>

<div class="stripe blog">
	<div class="container">
		<div class="row">
		
			<div class="items col-md-9 col-sm-8">
				<div class="row">
					<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
					
					<?php if (have_posts()) : $count = 0; ?>
						<?php while (have_posts()) : the_post(); $count++; ?>
						
							<?php $categories = get_the_category();
							$cat_slug = '';
							if ( ! empty( $categories ) ) {
							    foreach( $categories as $category ) {
							    	$cat_slug = $category->slug;
							    }
							} ?>
								
							<div class="item <?php echo $cat_slug; ?> col-xs-12">
								<h3><?php if(get_field('post_link')): ?><a href="<?php the_field('post_link'); ?>"><?php endif; ?><?php the_title(); ?><?php if(get_field('post_link')): ?></a><?php endif; ?></h3>
								<em><?php echo get_the_date(); ?></em>
								<?php the_field('post_content'); ?>
							</div>
							
						<?php endwhile; else: ?>
					<?php endif; ?>
						
					<div class="pagination-wrap"><?php the_pagination(); ?></div>	
						
					<?php wp_reset_query(); ?>
				</div>
			</div>
			<div class="side col-md-3 col-sm-4">
			
				<h2>View by month:</h2>
			
				<ul>
					<?php wp_get_archives(array('before'=>'- ')); ?>
				</ul>
			
			</div>
			
		</div>
	</div>
</div>

<?php get_footer(); ?>