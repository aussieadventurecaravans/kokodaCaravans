<div class="banner-wrap" style="background-image: url('<?php the_field('home_banner_image'); ?>');">
	<div class="banner container">
		<div class="row">
			<div class="banner-content">
				<h1><?php the_field('home_banner_text'); ?></h1>
				<a href="<?php the_field('home_banner_button_link'); ?>" class="btn btn-default"><?php the_field('home_banner_button_text'); ?></a>
			</div>
		</div>
	</div>
	<div class="scroll-btn">
		<a href="#break">Scroll down to continue <span class="arrow-down"></span></a>
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
	
		<?php while (have_rows('home_featured_caravans')) : the_row(); ?>
	
			<?php $post_object = get_sub_field('home_featured_caravan');
			if($post_object): 
			
				$post = $post_object;
				setup_postdata( $post ); 
				
				$badge_img = get_field('banner_badge');
				$product_img = get_field('banner_image'); ?>
				
			    <div class="item">
			    	<a href="<?php the_permalink(); ?>">
				    	<div class="item-img" style="background-image: url('<?php echo $product_img['sizes']['medium']; ?>');">
				    		<?php if(!empty($badge_img)): ?>
								<div class="banner-badge" style="background-image:url('<?php echo $badge_img['url'] ?>')"></div>
							<?php endif; ?>
				    	</div>
				    	<div class="item-details clearfix">
				    		<div class="details">
				    			<h3><?php the_title(); ?></h3>
				    			<p><?php the_field('banner_description'); ?></p>
				    		</div>
				    		<div class="arrow">
				    			<span></span>
				    		</div>
				    	</div>
			    	</a>
			    </div>
			    
			    <?php wp_reset_postdata(); ?>
			<?php endif; ?>
		
		<?php endwhile; ?>
		
	</div>
	
<?php endif; ?>

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
								<em><?php echo get_the_date(); ?><?php if(get_field('end_date')):?> - <?php the_field('end_date'); endif; ?></em>
								<p><?php the_field('post_snippet'); ?></p>
							</div>
						
						<?php wp_reset_postdata(); ?>
					<?php endif; ?>
				
				<?php endwhile; ?>
			
			</div>
		</div>
	</div>
	
<?php endif; ?>