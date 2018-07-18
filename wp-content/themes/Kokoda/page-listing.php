<?php 

/**
 * Template Name: Product Listing
 */

get_header(); ?>

<!--<nav class="page-nav navbar-fixed-top hidden-xs">
	<div class="container-fluid nav-container">
		<ul class="nav navbar-nav navbar-right">
			<?php /*$category_submenu = get_field('page_category_submenu'); */?>
			<?php /*wp_nav_menu( array(
				'theme_location' => $category_submenu,
				'depth' => 1,
				'container' => 'false',
				'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
				'items_wrap' => '%3$s', 
				'walker' => new wp_bootstrap_navwalker() ) 
				); */?>
		</ul>
	</div>
</nav>
-->
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
	<div class="container-fluid filter">
            <div class="row">
                <div class="col-12">
                </div>
            </div>
            <div class="row filter-form-group">
                <div class="container">
                    <div class="row">
                        <?php  if(get_field('page_category') == '' ): ?>
                            <div class="form-group col-lg-3">
                                <h3 class="header">Type</h3>
                                <label><input type="radio" checked data-filter-group="filter-type" name="filter-type" value="*" data-filter-value="">All</label><br />
                                <label><input type="radio" data-filter-group="filter-type" name="filter-type" value=".type-caravan" data-filter-value=".type-caravan">Caravans</label><br />
                                <label><input type="radio" data-filter-group="filter-type" name="filter-type" value=".type-hybrid" data-filter-value=".type-hybrid">Hybrid</label><br />
                                <label><input type="radio" data-filter-group="filter-type" name="filter-type" value=".type-camper" data-filter-value=".type-camper">Camper</label><br />
                            </div>
                        <?php endif; ?>
                        <div class="form-group col-lg-2">
                            <h3 class="header">Size</h3>
                            <label><input type="radio" checked data-filter-group="filter-size" name="filter-size" value="*" data-filter-value="">All</label><br />
                            <label><input type="radio" data-filter-group="filter-size" name="filter-size" value=".size-14-19" data-filter-value=".size-14-19">14" - 19"</label><br />
                            <label><input type="radio" data-filter-group="filter-size" name="filter-size" value=".size-20-25" data-filter-value=".size-20-25">20" - 25"</label><br />
                            <label><input type="radio" data-filter-group="filter-size" name="filter-size" value=".size-26" data-filter-value=".size-26">26" + </label><br />
                        </div>

                        <div class="form-group col-lg-2">
                            <h3 class="header">Sleeps</h3>
                            <label><input type="radio" checked data-filter-group="filter-occupant" name="filter-occupant" value="*" data-filter-value="">All</label><br />
                            <label><input type="radio" data-filter-group="filter-occupant" name="filter-occupant" value=".occupant-2" data-filter-value=".occupant-2">0 - 2</label><br />
                            <label><input type="radio" data-filter-group="filter-occupant" name="filter-occupant" value=".occupant-3-4" data-filter-value=".occupant-3-4">3 - 4</label><br />
                            <label><input type="radio" data-filter-group="filter-occupant" name="filter-occupant" value=".occupant-5" data-filter-value=".occupant-5">5 +</label><br />
                        </div>
                        <div class="form-group col-lg-2">
                            <h3 class="header">Terrain</h3>
                            <label><input type="radio" checked data-filter-group="filter-terrain" name="filter-terrain" value="*" data-filter-value="">All</label><br />
                            <label><input type="radio" data-filter-group="filter-terrain" name="filter-terrain" value=".terrain-on-road" data-filter-value=".terrain-on-road">On Road</label><br />
                            <label><input type="radio" data-filter-group="filter-terrain" name="filter-terrain" value=".terrain-semi-off-road" data-filter-value=".terrain-semi-off-road">Semi Off Road</label><br />
                            <label><input type="radio" data-filter-group="filter-terrain" name="filter-terrain" value=".terrain-off-road" data-filter-value=".terrain-off-road">Off Road</label><br />
                        </div>

                        <div class="form-group col-lg-3">
                            <h3 class="header">Price</h3>
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
                    </div>
                </div>
            </div>
	</div>
	
	<div class="container-fluid featured clearfix list">

		<?php
        //if we have category specified at field page_category
        if(get_field('page_category'))
        {
            $listing_category = get_field('page_category');
        }
        else
        {
            $listing_category = "all";
        }

        $terms = get_terms('product-cat','orderby=name' );

        //get the archive category ID
        $caravan_archive_category_id = 0;
        foreach ( $terms as $term ){
            if(in_array( $term->name ,array('Caravan Archive')))
            {
                $caravan_archive_category_id = $term->term_id;
                break;
            }
        }

        //query find the caravans belong to cateogory specified by page
        // and these caravanas also don't belong to archive category.
        if($listing_category == 'all')
        {
            $args = array(
                'post_type' => 'product',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product-cat',
                        'field'    => 'term_id',
                        'terms'    => array($caravan_archive_category_id),
                        'operator' => 'NOT IN'
                    )
                ),
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'nopaging' => true
            );
        }
        else
        {
            $args = array(
                'post_type' => 'product',
                'tax_query' => array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'product-cat',
                        'field'    => 'term_id',
                        'terms'    => $listing_category,
                    ),
                    array(
                        'taxonomy' => 'product-cat',
                        'field'    => 'term_id',
                        'terms'    => array($caravan_archive_category_id),
                        'operator' => 'NOT IN'
                    )
                ),
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'nopaging' => true
            );
        }
		 ?>
							
		<?php query_posts($args); ?>
			<?php if (have_posts()) : $count = 0; ?>
				<?php while (have_posts()) : the_post(); ?>
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


                    $occupant = get_field('occupants');
                    if($occupant <= 2)
                    {
                        $filter_occupant = "occupant-2";
                    }
                    elseif ($occupant <= 4)
                    {
                        $filter_occupant = "occupant-3-4";
                    }
                    else
                    {
                        $filter_occupant = "occupant-5";
                    }


                    //fitler type, terrain and family bunk
                    global $post;
                    $terms =  get_the_terms( $post, 'product-cat' );
                    $hyprid_filter = array('Hybrid - Semi Off-Road','Hybrid - On-Road','Hybrid - Off-Road');
                    $caravan_filter= array('Caravan - Off-Road','Caravan - On-Road','Caravan - Semi Off-Road');
                    $camper_filter = array('Camper - Off-Road','Camper - On-Road','Camper - Semi Off-Road');
                    $filter_type = '';
                    $filter_terrain='';
                    foreach ( $terms as $term )
                    {

                        if(in_array( $term->name ,$hyprid_filter))
                        {
                            $split = explode(' - ',$term->name);
                            $filter_type = 'type-hybrid';
                            $filter_terrain= 'terrain-' .  str_replace(' ','-',strtolower($split[1])) ;
                            break;

                        }
                        elseif(in_array( $term->name ,$camper_filter))
                        {
                            $split = explode(' - ',$term->name);
                            $filter_type = 'type-camper';
                            $filter_terrain= 'terrain-' . str_replace(' ','-',strtolower($split[1])) ;
                            break;
                        }
                        elseif(in_array( $term->name ,$caravan_filter))
                        {
                            $split = explode(' - ',$term->name);
                            $filter_type = 'type-caravan';
                            $filter_terrain= 'terrain-' .  str_replace(' ','-',strtolower($split[1])) ;
                            break;
                        }
                        else
                        {
                            //do nothing
                        }
                    }

                    ?>

					<div class="item <?php echo $filter_price; ?> <?php echo $filter_size; ?> <?php echo $filter_occupant; ?> <?php echo $filter_terrain; ?> <?php echo $filter_type; ?> col-xs-12 col-sm-6 col-md-6 col-lg-4">
						<a href="<?php the_permalink(); ?>">
                            <?php if($product_img): ?>
                                <div class="item-img">
                                    <?php if(!empty($badge_img)): ?>
                                        <div class="banner-badge" style="background-image:url('<?php echo $badge_img['url'] ?>')"></div>
                                    <?php endif; ?>
                                    <img alt="caravan image" src="<?php echo $product_img['sizes']['medium']; ?>"/>
                                </div>
                            <?php endif; ?>
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

            <?php else: ?>

            <?php endif; ?>
		<?php wp_reset_query(); ?>
	</div>
</div>

<?php get_footer(); ?>


<script type="text/javascript">
    jQuery(document).ready(function($){

        var $boxes = $('.page-template-page-listing .featured .item'),
            filters = {};


        $('select.form-control[data-filter-group]').change(function(e) {
                e.preventDefault();
                var $this = $(this);

                // store filter value in object
                var group = $this.attr('data-filter-group');
                filters[group] = $this.find(':selected').attr('data-filter-value');

                var filter_lines='';
                for (var prop in filters)
                {
                    filter_lines +=  filters[prop];

                }

                if (filter_lines == '' ) {
                    $boxes.removeClass('is-animated')
                        .fadeOut(300).finish().promise().done(function() {
                        $boxes.each(function(i) {
                            $(this).addClass('is-animated').delay((i++) * 200).fadeIn();
                        });
                    });
                }
                else
                    {
                    $boxes.removeClass('is-animated')
                        .fadeOut(300).finish().promise().done(function() {
                        $boxes.filter(filter_lines ).each(function(i) {
                            $(this).addClass('is-animated').delay((i++) * 200).fadeIn();
                        });
                    });
                }

        });


        $('input[type=radio][data-filter-group]').change(function(e) {
            e.preventDefault();
            var $this = $(this);


            // store filter value in object
            var group = $this.attr('data-filter-group');
            filters[group] = $this.attr('data-filter-value');


            var filter_lines='';
            for (var prop in filters)
            {
                filter_lines +=  filters[prop];

            }
            if (filter_lines == '' ) {
                $boxes.removeClass('is-animated')
                    .fadeOut(300).finish().promise().done(function() {
                    $boxes.each(function(i) {
                        $(this).addClass('is-animated').delay((i++) * 200).fadeIn();
                    });
                });
            }
            else
            {
                $boxes.removeClass('is-animated')
                    .fadeOut(300).finish().promise().done(function() {
                    $boxes.filter(filter_lines ).each(function(i) {
                        $(this).addClass('is-animated').delay((i++) * 200).fadeIn();
                    });
                });
            }

        });

    });

</script>