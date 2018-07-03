<?php 

/**
 * Template Name: Caravan Archive Page
 *
 * this template show all the old caravans
 *
 * dealer go to this page to check the specs and other details
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

<div class="stripe center listing">
	<div class="container">
		<div class="row">
		
			<div class="filter col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12">
				<h2>Filter By</h2>
				<form class="form-inline">
					<div class="form-group">
						<label>Price</label>
						<select class="form-control filters-select" data-filter-group="filter-price">
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
						<select class="form-control filters-select" data-filter-group="filter-size">
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
	
	<div class="featured clearfix item-list archive-item-list">
	
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


        <?php $archive  =  get_posts($args); ?>
        <?php  $count = 0; ?>

        <?php foreach ($archive as $caravan):  ?>
            <?php
            $product_img = get_field('banner_image',$caravan->ID);
            $badge_img = get_field('banner_badge',$caravan->ID);

            $filter_price = get_field('price_thousands',$caravan->ID);
            $filter_size = get_field('size_feet',$caravan->ID);

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
            <?php //Starting Element Row ?>
            <?php if($count ==  0): ?>
                <div class="row">
            <?php endif; ?>

             <?php if($count <  3): ?>
                    <div class="item archive-item <?php echo $filter_price; ?> <?php echo $filter_size; ?>  col-sm-4">
                        <a href="<?php the_permalink(); ?>">
                            <div class="item-img" <?php if($product_img): ?>style="background-image: url('<?php echo $product_img['sizes']['medium']; ?>');"<?php endif; ?>>
                                <?php if(!empty($badge_img)): ?>
                                    <div class="banner-badge" style="background-image:url('<?php echo $badge_img['url'] ?>')"></div>
                                <?php endif; ?>
                            </div>
                            <div class="item-details">
                                <div class="details">
                                    <h3><?php echo get_the_title($caravan); ?></h3>
                                    <div class="product-meta clearfix">
                                        <?php if(get_field('price_thousands',$caravan->ID)): ?><span class="price">$<?php the_field('price_thousands',$caravan->ID); ?>,<?php the_field('price_hundreds',$caravan->ID); ?><i>+ORC</i></span><?php endif; ?>
                                        <?php if(get_field('size_feet',$caravan->ID)): ?><span class="size"><?php the_field('size_feet',$caravan->ID); ?>'<?php if(get_field('size_inches',$caravan->ID)): ?><?php the_field('size_inches',$caravan->ID); ?>"<?php endif; ?></span><?php endif; ?>
                                        <?php if(get_field('occupants',$caravan->ID)): ?><span class="occupants"><?php the_field('occupants',$caravan->ID); ?></span><?php endif; ?>
                                    </div>
                                    <?php if(get_field('banner_description',$caravan->ID)): ?><p><?php the_field('banner_description',$caravan->ID); ?></p><?php endif; ?>
                                    <?php if(get_field('tare',$caravan->ID)): ?><span class="tare">Tare (approx): <?php the_field('tare',$caravan->ID); ?></span><br><?php endif; ?>
                                    <?php if(get_field('ball_weight',$caravan->ID)): ?><span class="ball">Ball weight (approx): <?php the_field('ball_weight',$caravan->ID); ?></span><?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </div>
            <?php  $count++; $open_element = true ;?>
                <?php //close element Row ?>
                <?php if($count ==  3): ?>
                    </div>
                    <?php  $count= 0; $open_element = false; ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php //close element Row at last product ?>
        <?php if($open_element ==  true): ?>
            </div>
        <?php endif; ?>
			
	</div>
</div>

<?php get_footer(); ?>