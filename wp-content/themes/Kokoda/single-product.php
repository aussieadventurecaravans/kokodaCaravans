<?php get_header(); ?>

<nav class="page-nav navbar-fixed-top single_nav">
	<div class="container-fluid nav-container">
        <input type="checkbox" id="product-list-toggle"/>
        <label for="product-list-toggle" class="product-list-toggle-label"><?php echo the_title() . '/ OVERVIEW';  ?>
            <img src="/wp-content/themes/Kokoda/_img/banner_down_icons_arrow_black.png" class="arrow">
        </label>

		<ul class="nav navbar-nav navbar-right">
			<?php if(get_field('floor_plan')): ?><li><a href="#floorplan">Floor Plan</a></li><?php endif; ?>
			<?php if(have_rows('features')): ?><li><a href="#features">Features</a></li><?php endif; ?>
			<?php if(have_rows('specifications')): ?><li><a href="#specifications">Specifications</a></li><?php endif; ?>
			<?php if(have_rows('gallery')): ?><li><a href="#gallery">Gallery</a></li><?php endif; ?>
			<?php if(get_field('brochure_pdf')): ?><li><a href="<?php the_field('brochure_pdf'); ?>" target="_blank">Brochure</a></li><?php endif; ?>
			<?php $post_objects = get_field('other_models');
			if( $post_objects ): ?>
				<li class="models dropdown">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-haspopup="true">Other Models <span class="caret-dark"></span></a>
					<ul class="dropdown-menu">
						<?php foreach( $post_objects as $post): ?>
						    <?php setup_postdata($post); ?>
						    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>			        
						<?php endforeach; ?>
					</ul>
				</li>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</ul>
	</div>
</nav>

<?php $banner_img = get_field('banner_image'); ?>
<?php $badge_img = get_field('banner_badge'); ?>

<div class="banner-wrap" style="background-image: url('<?php echo $banner_img['url']; ?>');">
	<div class="banner-grad">
		<div class="banner container">
			<div class="row">
				<div class="banner-content">
					<h1><?php the_title(); ?></h1>
					<p class="lead"><?php the_field('banner_description'); ?></p>
                    <?php if(get_field('brochure_pdf')): ?>
                        <div class="product-brochure-button">
                          <a href="<?php the_field('brochure_pdf'); ?>" target="_blank">Download Brochure</a>
                        </div>
                    <?php endif; ?>
                    <div class="product-meta">
                        <?php if(get_field('price_thousands')): ?><span class="price">$<?php the_field('price_thousands'); ?>,<?php the_field('price_hundreds'); ?><i>+ORC</i></span><?php endif; ?>
                        <?php if(get_field('size_feet')): ?><span class="size"><?php the_field('size_feet'); ?>'<?php if(get_field('size_inches')): ?><?php the_field('size_inches'); ?>"<?php endif; ?></span><?php endif; ?>
                        <?php if(get_field('occupants')): ?><span class="occupants"><?php the_field('occupants'); ?></span><?php endif; ?>
                    </div>
				</div>
			</div>
		</div>
		<?php if(!empty($badge_img)): ?>
		<div class="banner-badge" style="background-image:url('<?php echo $badge_img['url'] ?>')"></div>
		<?php endif; ?>
	</div>
</div>

<div class="mobile-banner-wrap stripe center">
        <div class="banner container">
            <div class="row">
                <div class="banner-content">
                    <h1><?php the_title(); ?></h1>
                    <p class="lead"><?php the_field('banner_description'); ?></p>

                    <div class="product-meta">
                        <?php if(get_field('price_thousands')): ?><span class="price">$<?php the_field('price_thousands'); ?>,<?php the_field('price_hundreds'); ?><i>+ORC</i></span><?php endif; ?>
                        <?php if(get_field('size_feet')): ?><span class="size"><?php the_field('size_feet'); ?>'<?php if(get_field('size_inches')): ?><?php the_field('size_inches'); ?>"<?php endif; ?></span><?php endif; ?>
                        <?php if(get_field('occupants')): ?><span class="occupants"><?php the_field('occupants'); ?></span><?php endif; ?>
                    </div>
                    <?php if(get_field('brochure_pdf')): ?>
                        <div class="product-brochure-button">
                            <a href="<?php the_field('brochure_pdf'); ?>" target="_blank">Download Brochure</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
</div>


<?php if(get_field('overview_heading') || get_field('overview_text') || get_field('video')):?>
<div class="stripe center intro">
	<div class="container">
		<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12">
	
			<h2><?php the_field('overview_heading'); ?></h2>
			
			<p><?php the_field('overview_text'); ?></p>
			
			<?php if(get_field('video')):?>
			<?php $featvideo = get_field('video'); ?>
			<iframe class="video-embed" width="100%" src="https://www.youtube.com/embed/<?php echo $featvideo; ?>?rel=0" frameborder="0" allowfullscreen></iframe>
			<?php endif; ?>
			
		</div>
	</div>
</div>
<?php endif; ?>

<?php if(get_field('floor_plan') || get_field('tech_specs') || get_field('virtual_tour_link')): ?>
<div class="stripe center floorplan" id="floorplan">
	<div class="container">
		<div>
		
			<?php if(get_field('floor_plan')): ?>
			<h2>Floor Plan</h2>
			
			<img src="<?php the_field('floor_plan'); ?>" class="img-responsive">
			<?php endif; ?>
			
			<?php if(get_field('tech_specs')): ?><button data-toggle="modal" data-target="#techSpecs" class="btn btn-sub" target="_blank">Tech Specs</button><?php endif; ?>
			<?php if(get_field('virtual_tour_link')): ?><a href="<?php the_field('virtual_tour_link'); ?>" class="btn btn-sub virtual-tour" target="_blank">Virtual Tour</a><?php endif; ?>
			
		</div>
	</div>
</div>
<?php endif; ?>

<?php if(have_rows('features')): ?>

	<div class="stripe feature" id="features">
        <div class="container">
            <div class="row">
                <div class="header-wrapper">
                    <h2>Key Features</h2>
                </div>
                <div class="mobile-features-navigation">
                    <span class="fea-prev">
                        <img src="/wp-content/themes/Kokoda/_img/arrow-left-grey.png" class="arrow"/>
                    </span>
                    <span class="feature-number">

                    </span>
                    <span class="fea-next">
                        <img src="/wp-content/themes/Kokoda/_img/arrow-right-grey.png" class="arrow"/>
                    </span>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row" id="features-list">
                <?php while (have_rows('features')) : the_row(); ?>
                <div class="item col-md-4">
                    <div class="feature-img">
                        <img src="<?php the_sub_field('feature_image'); ?>" class="img-responsive">
                    </div>
                    <h3><?php the_sub_field('feature_heading'); ?></h3>
                    <p><?php the_sub_field('feature_text'); ?></p>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

	</div>

<?php endif; ?>

<?php if(have_rows('specifications')): ?>

	<div class="stripe specs" id="specifications">
		<div class="container">

            <div class="row">
                <div class="header-wrapper">
                    <h2>Specifications</h2>
                </div>
            </div>

            <div class="row">
                <div class="panel-group" id="accordion">
                    <?php  $specs = get_field('specifications');?>
                    <?php  if($specs): ?>
                        <?php $i = 1;?>
                        <?php foreach ($specs as $spec): ?>
                            <div class="panel panel-default">
                                <!-- spec heading --->
                                <div class="panel-heading" id="heading<?php echo $i; ?>" data-toggle="collapse" data-target="#collapse<?php echo $i;?>">

                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-target="#collapse<?php echo $i;?>"
                                            <?php if ($i == 1) : ?>
                                                aria-expanded="false"  class="collapsed"
                                            <?php else: ?>
                                                aria-expanded="false" class="collapsed"
                                            <?php endif; ?>
                                           data-parent="#accordion">
                                            <?php echo $spec["group_heading"]; ?>
                                        </a>
                                    </h4>
                                </div>
                                <!-- spec content --->
                                <div id="collapse<?php echo $i; ?>"

                                    <?php if ($i == 1) : ?>

                                        class="panel-collapse collapse"

                                    <?php  else : ?>

                                        class="panel-collapse collapse"

                                    <?php endif; ?>

                                     aria-labelledby="heading<?php echo $i; ?>" data-parent="#accordion">

                                    <div class="panel-body">

                                        <?php $spec_it = $spec['specification_item'];?>
                                        <table class="spec_table">
                                            <tbody>

                                           <?php  foreach ($spec_it as $spec_its) : ?>
                                               <?php $spec_head_split =  explode(' - ',$spec_its['heading']); ?>
                                                <tr>
                                                    <td>
                                                        <div class="left_conto" style="<?php if ($spec_its['spec_options'] != '') { ?>width:50%;float:left;<?php } else { ?>width:100%;float:left;<?php } ?>">

                                                            <p> <?php echo $spec_head_split[0]; ?></p>

                                                            <div class="spec_ds">
                                                                <?php echo ($spec_its['description']); ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="padding:0">
                                                        <div class="spec_opt" style="float:left;">
                                                            <table class="spec_opt_table">
                                                                <tr>
                                                                    <td>
                                                                        <p><?php echo $spec_head_split[1]  ?></p>
                                                                    </td>
                                                                    <?php if ($spec_its['options'] != '') : ?>
                                                                    <td <?php if(!empty($spec_head_split[1])): ?> class="options" <?php endif; ?> >
                                                                        <h4 style="font-weight:bold;">Options</h4>
                                                                        <?php echo ($spec_its['options']) ?>
                                                                    </td>
                                                                    <?php endif; ?>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach;   ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                            </div>
                            <?php $i++;  ?>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
    	</div>
    </div>
<?php endif; //specifications ?>
<?php if(have_rows('upgrade_package')): ?>

<?php  $upgrade_packages = get_field('upgrade_package');?>



<div class="stripe upgrade" id="upgrade-package">
    <div class="container">
        <div class="row">
            <div class="header-wrapper">
                <h2>Upgrade Package</h2>
            </div>
        </div>
        <?php foreach ($upgrade_packages as $package): ?>
            <div class="row">
                <!-- show spec items for upgradte package with image icon -->
                <?php $spec_its = $package['specification_item'];?>
                <ul class="primary-upgrade-item">
                    <?php foreach($spec_its as $spec_it): ?>
                        <?php if(!empty($spec_it['icon'])): ?>
                            <li><img src="<?php echo $spec_it['icon']; ?>" class="overlay" /><?php echo $spec_it['heading']; ?></li>
                        <?php endif; ?>
                    <?php endforeach;?>
                </ul>
            </div>
            <div class="row">
                <hr style="border-top: 1px solid #0c121d;"/>
            </div>
            <div class="row">
                <!-- show spec items for upgradte package without Icon Image-->
                <?php $spec_its = $package['specification_item'];?>
                <ul>
                    <?php foreach($spec_its as $spec_it): ?>
                        <?php if(empty($spec_it['icon'])): ?>
                            <li><?php echo $spec_it['heading']; ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="row">
                <hr style="border-top: 1px solid #0c121d;"/>
            </div>
            <div class="row">
                <div class="package-price">
                    <p>
                       Package Price : $<?php echo $package['price']  ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; //specifications ?>

<?php $images = get_field('gallery'); ?>
<?php if($images): ?>
	<div class="stripe center gallery" id="gallery">
		<div class="container-fluid">
            <div class="row">
                <div class="header-wrapper">
                    <h2>Gallery</h2>
                </div>
            </div>
			<div class="row">
			
				<div class="col-xs-12">
				
					<div class="flexslider">
						<ul class="slides">
							<?php foreach( $images as $image ): ?>
								<li>
									<a data-lightbox="product-gallery" href="<?php echo $image['url']; ?>">
										<img src="<?php echo get_stylesheet_directory_uri(); ?>/_img/bg-black-20.png" class="overlay">
										<img src="<?php echo $image['sizes']['medium_large']; ?>" alt="<?php echo $image['alt']; ?>" class="gallery-img">
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				
				</div>
			
			</div>
		</div>
	</div>
<?php endif; ?>

<div class="modal fade" id="techSpecs" tabindex="-1" role="dialog" aria-labelledby="techSpecsLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><?php the_title();?> Tech Specs</h4>
			</div>
			<div class="modal-body">

				<div class="container-fluid">
					<?php if(have_rows('tech_specs')): ?>
						<?php while (have_rows('tech_specs')) : the_row(); ?>

							<div class="row">
								<div class="col-xs-6">
									<span><?php the_sub_field('left'); ?></span>
								</div>
								<div class="col-xs-6 pull-right">
									<span><?php the_sub_field('right'); ?></span>
								</div>
							</div>

						<?php endwhile; ?>
					<?php endif; ?>
				</div>

			</div>
			<?php if(get_field('tech_specs_paragraph')): ?>
			<div class="modal-footer">
				<div class="small">
					<?php the_field('tech_specs_paragraph'); ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>

<!-- show the custom order -->
<?php if(get_option('custom_order_development-mode') == false && get_field('custom_order_active') == true): ?>
<div class="custom_order_button_panel">
    <div class="container">
            <div class="col-md-6 col-xs-6  text-left">
                <h4><?php the_title(); ?></h4>
            </div>
            <div class="col-md-6 col-xs-6 text-right">
                <div class="custom-order-button">
                    <a href="<?php echo home_url(); ?>/custom-order"  target="_blank">Custom Order</a>
                </div>
            </div>
    </div>

</div>
<?php endif; ?>


<!-- Important Owl stylesheet -->
<link rel="stylesheet" href="/wp-content/themes/Kokoda/owl-carousel/owl.carousel.css">
<!-- Default Theme -->
<link rel="stylesheet" href="/wp-content/themes/Kokoda/owl-carousel/owl.theme.css">
<!-- Include js plugin -->
<script src="/wp-content/themes/Kokoda/owl-carousel/owl.carousel.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function($){

        var windowWidth = $(window).width();
        var default_featurelist =  $("#features div.container-fluid").html();
        var owl =  $("#features-list");
        if(windowWidth <= 976)
        {
            $("#features-list").owlCarousel({
                navigation : false, // Show next and prev buttons
                slideSpeed : 300,
                pagination: false,
                paginationSpeed : 400,
                singleItem:true,
                afterAction : afterOwlAction
            });
            $("#features.stripe.feature .mobile-features-navigation").css({'display':'inline-block'});
            $("#features.stripe.feature .header-wrapper h2").css({'margin' :   '0 0 20px' });
        }

        $( window ).resize(function() {

            var windowWidth = $(window).width();

            if(windowWidth <= 976)
            {
                $("#features-list").owlCarousel({

                    navigation : false, // Show next and prev buttons
                    slideSpeed : 300,
                    pagination: false,
                    paginationSpeed : 400,
                    singleItem:true,
                    afterAction : afterOwlAction

                });
                $("#features.stripe.feature .mobile-features-navigation").css({'display':'inline-block'});
                $("#features.stripe.feature .header-wrapper h2").css({'margin' :   '0 0 20px' });
            }
            else
            {
                $("#features div.container-fluid").html(default_featurelist);
                $("#features.stripe.feature .mobile-features-navigation").hide();
                $("#features.stripe.feature .header-wrapper h2").css({'margin' :   '' });
            }

        });

        $("#features.stripe.feature .mobile-features-navigation .fea-prev img").on('click', function(){
            $("#features-list").trigger('owl.prev')
        });
        $("#features.stripe.feature .mobile-features-navigation .fea-next img").on('click',function(){
            $("#features-list").trigger('owl.next')
        });

        function afterOwlAction()
        {
            var current_feature = this.owl.currentItem + 1;
            $("#features.stripe.feature .mobile-features-navigation .feature-number").html(current_feature + ' / ' + this.owl.owlItems.length);

        }

    });

</script>


