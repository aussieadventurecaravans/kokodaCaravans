
<?php
/**
 * Template Name: Why Kokoda
 */


?>
<?php

get_header();

?>
<?php $banner_img = get_field('content_page_banner'); ?>

<div class="banner-wrap"<?php if(!empty($banner_img)) : ?> style="background-image: url('<?php echo $banner_img; ?>');"<?php endif; ?>>
	<div class="banner container">
		<div class="row">
			<div class="banner-content page-heading">
				<h1><?php the_title(); ?></h1>
			</div>
		</div>
	</div>
</div>

<?php if(get_field('page_break_heading') || get_field('page_break_text')): ?>
<div class="stripe center intro">
	<div class="container">
		<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12">
	
			<?php if(get_field('page_break_heading')): ?><h2><?php the_field('page_break_heading'); ?></h2><?php endif; ?>
			
			<?php if(get_field('page_break_text')): ?><p><?php the_field('page_break_text'); ?></p><?php endif; ?>
			
		</div>
	</div>
</div>
<?php endif; ?>

<?php if(have_rows('page_features')): ?>

	<div id="features">
				
		<?php if(have_rows('page_features')): ?>
			<?php while (have_rows('page_features')) : the_row(); ?>	
			
				<?php if(get_row_layout() == 'page_image_right'): ?>	
					
					<div class="stripe feature <?php the_sub_field('feature_background_colour'); ?>">
						<div class="container">
					
							<div class="row">
								<div class="col-sm-6">
									<?php if(get_sub_field('feature_heading')): ?><h3><?php the_sub_field('feature_heading'); ?></h3><?php endif; ?>
									<p><?php the_sub_field('feature_text'); ?></p>
								</div>
								<div class="col-sm-6 feature-img">
									<img src="<?php the_sub_field('feature_image'); ?>" class="img-responsive">
								</div>
							</div>
					
						</div>
					</div>
					
				<?php elseif(get_row_layout() == 'page_image_left'): ?>
		
					<div class="stripe feature <?php the_sub_field('feature_background_colour'); ?>">
						<div class="container">
						
							<div class="row">
								<div class="col-sm-6 col-sm-push-6">
									<?php if(get_sub_field('feature_heading')): ?><h3><?php the_sub_field('feature_heading'); ?></h3><?php endif; ?>
									<p><?php the_sub_field('feature_text'); ?></p>
								</div>
								<div class="col-sm-6 col-sm-pull-6 feature-img">
									<img src="<?php the_sub_field('feature_image'); ?>" class="img-responsive">
								</div>
							</div>
						</div>
					</div>
					
				<?php elseif(get_row_layout() == 'one_text'): ?>
				
					<div class="stripe feature <?php the_sub_field('feature_background_colour'); ?> <?php if(get_sub_field('center_text')): echo 'center'; endif;?>">
						<div class="container">
				
							<div class="row one-text">
								<div class="col-xs-12">
									<?php if(get_sub_field('feature_heading')): ?><h3><?php the_sub_field('feature_heading'); ?></h3><?php endif; ?>
									<?php the_sub_field('feature_text'); ?>
								</div>
							</div>
					
						</div>
					</div>
					
				<?php elseif(get_row_layout() == 'two_text'): ?>
				
					<div class="stripe feature <?php the_sub_field('feature_background_colour'); ?>">
						<div class="container">
						
							<div class="row two-text">
								<div class="col-sm-8 col-xs-12">
									<?php if(get_sub_field('feature_heading_left')): ?><h3><?php the_sub_field('feature_heading_left'); ?></h3><?php endif; ?>
									<?php the_sub_field('feature_text_left'); ?>
								</div>
								<div class="col-sm-4 col-xs-12">
									<?php if(get_sub_field('feature_heading_right')): ?><h3><?php the_sub_field('feature_heading_right'); ?></h3><?php endif; ?>
									<?php the_sub_field('feature_text_right'); ?>
								</div>
							</div>
						
						</div>
					</div>
		
				<?php endif; ?>
							
			<?php endwhile; ?>
		<?php endif; ?>
				
	</div>

<?php endif; ?>

<?php if(get_field('page_form')) : ?>

<div class="stripe form">
	<div class="container">
		<div class="row">
		
			<div class="col-xs-12">
				
				<?php $form = get_field('page_form'); 			
				echo FrmFormsController::show_form($form, $key = '', $title=false, $description=false); ?>
			
			</div>
		
		</div>
	</div>
</div>

<?php endif; ?>


<?php

get_footer();

?>

