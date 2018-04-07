<div class="cta">
	<div class="container-fluid">
		<div class="row">
		
		<?php if(get_field('cta_1_link') || get_field('cta_1_heading') || get_field('cta_1_text')): ?>
			<div class="dealer col-sm-12">
                <p><?php the_field('cta_1_text'); ?></p>
                <button onclick="location.href='<?php the_field('cta_1_link'); ?>'">
                    <h2><?php the_field('cta_1_heading'); ?></h2>
                </button>
			</div>
		<?php else: ?>
			<div class="dealer col-sm-12">
                <p><?php the_field('footer_cta_1_text', 'options'); ?></p>
                <div>
                    <input class="address_postcode" type="text" id="addressInput" name="addressInput" size="50" value="" placeholder="Enter suburb or postcode"/>
                    <button class="dealer_search">
                        <h2><?php the_field('footer_cta_1_heading', 'options'); ?></h2>
                    </button>
                </div>
			</div>
		<?php endif; ?>
		</div>

        <div class="row">
            <?php if(get_field('cta_2_link') || get_field('cta_2_heading') || get_field('cta_2_text')): ?>
                <div class="contact">
                    <a href="<?php the_field('cta_2_link'); ?>">
                        <h2><?php the_field('cta_2_heading'); ?></h2>
                        <p><?php the_field('cta_2_text'); ?></p>
                    </a>
                </div>
            <?php else: ?>
                <div class="contact">
                    <p><?php the_field('footer_cta_2_text', 'options'); ?></p>
                    <a href="<?php the_field('footer_cta_2_link', 'options'); ?>">
                       <?php the_field('footer_cta_2_heading', 'options'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

	</div>
</div>




<div class="footer-wrap">
	<footer class="container">
		<div class="row">
	
			<div class="col-sm-2 footer-nav">
				<?php $location = 'footer-1';
				$menu_obj = wpse45700_get_menu_by_location($location);
				wp_nav_menu( array( 'theme_location' => 'footer-1', 'container' => 'false', 'items_wrap' => '<h4>'.esc_html($menu_obj->name).'</h4><ul id="%1$s" class="%2$s">%3$s</ul>' ) ); ?>
			</div>
			<div class="col-sm-2 footer-nav">
				<?php $location = 'footer-2';
				$menu_obj = wpse45700_get_menu_by_location($location);
				wp_nav_menu( array( 'theme_location' => 'footer-2', 'container' => 'false', 'items_wrap' => '<h4>'.esc_html($menu_obj->name).'</h4><ul id="%1$s" class="%2$s">%3$s</ul>' ) ); ?>
			</div>
			<div class="col-sm-2 footer-nav">
				<?php $location = 'footer-3';
				$menu_obj = wpse45700_get_menu_by_location($location);
				wp_nav_menu( array( 'theme_location' => 'footer-3', 'container' => 'false', 'items_wrap' => '<h4>'.esc_html($menu_obj->name).'</h4><ul id="%1$s" class="%2$s">%3$s</ul>' ) ); ?>
			</div>
			<div class="col-sm-6 col-md-4 col-md-offset-2">
			
				<?php if(have_rows('social_media', 'options')): ?>
					<div class="social">
						<ul>
							<?php while (have_rows('social_media', 'options')) : the_row(); ?>
							<li><a href="<?php the_sub_field('link'); ?>" title="Follow us on <?php the_sub_field('label'); ?>" target="_blank"><img src="<?php the_sub_field('icon'); ?>" alt="Follow us on <?php the_sub_field('label'); ?>"></a></li>
							<?php endwhile; ?>
						</ul>
					</div>
				<?php endif; ?>
				
				<div class="newsletter">
					<h4>Newsletter Signup</h4>
					<?php echo do_shortcode('[formidable id=3]'); ?>
				</div>
			
			</div>
	
		</div>
	</footer>
</div>
<div class="footer-meta">
	<div class="container">
		<div class="row">
		
			<div class="copyright col-sm-6">
				<span>Copyright &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></span>
				<span><a href="/privacy/">Privacy</a></span>
				<?php if(get_field('footer_text', 'options')):?>
					<span class="notice"><?php the_field('footer_text', 'options'); ?></span>
				<?php endif; ?>
			</div>
			<?php if(have_rows('footer_association_logos', 'options')): ?>
				<div class="certification col-sm-6">
					<ul>
						<?php while (have_rows('footer_association_logos', 'options')) : the_row(); ?>
						<li>
							<?php if(get_sub_field('link')): ?><a href="<?php the_sub_field('link'); ?>"><?php endif; ?><img src="<?php the_sub_field('logo'); ?>"><?php if(get_sub_field('link')): ?></a><?php endif; ?>
						</li>
						<?php endwhile; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<div class="modal fade" id="mobileSearch" tabindex="-1" role="dialog" aria-labelledby="mobileSearch">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
</div>

	<?php wp_footer(); ?>

	<script>
 	 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-68883352-1', 'auto');
	  ga('send', 'pageview');

	</script>
    <?php if(get_field('footer_cta_1_link', 'options') || get_field('footer_cta_1_heading', 'options') || get_field('footer_cta_1_text', 'options')): ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.dealer button.dealer_search').click(function () {
                    var formData = {
                        'action': 'search_dealer',
                        'dealer_link': "<?php echo get_field('footer_cta_1_link', 'options') ; ?>",
                        'address' : $('.dealer .address_postcode').val()
                    };
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: formData,
                        dataType   : 'json',
                    }).done(function (res) {
                        window.location = res.data.url ;

                    });
                });
            });
        </script>
   <?php endif; ?>



	</body>
</html>