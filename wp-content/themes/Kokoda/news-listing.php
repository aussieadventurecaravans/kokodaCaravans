<?php 

/**
 * Template Name: News Listing
 */

get_header(); ?>

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

<div class="stripe blog">
	<div class="container">
		<div class="row">
		
			<div class="items col-md-9 col-sm-8">
				<div class="row">
					<?php 
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					$listing_category = get_field('page_category');
					
					$args = array(
						'post_type' => 'post',
						'paged' => $paged,
						'tax_query' => array(
							array(
								'taxonomy' => 'category',
								'field'    => 'term_id',
								'terms'    => $listing_category,
							),
						)
					); 
					?>
					
					<?php query_posts($args); ?>
						<?php if (have_posts()) : $count = 0; ?>
							<?php while (have_posts()) : the_post(); $count++; ?>
							
							<?php $categories = get_the_category();
							$cat_slug = '';
							if ( ! empty( $categories ) ) {
							    foreach( $categories as $category ) {
							    	$cat_slug = $category->slug;
							    }
							} 
							?>
							
								<div class="item <?php echo $cat_slug; ?> col-xs-12">
									<h3><?php if(get_field('post_link')): ?><a href="<?php the_field('post_link'); ?>"><?php endif; ?><?php the_title(); ?><?php if(get_field('post_link')): ?></a><?php endif; ?></h3>
									<!--<em><?php /*echo get_the_date(); */?> <?php /*if(get_field('end_date')):*/?> - <?php /*the_field('end_date'); endif; */?></em>-->
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