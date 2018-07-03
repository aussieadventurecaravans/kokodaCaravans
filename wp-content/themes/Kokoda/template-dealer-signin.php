<?php
/**
 * Template Name: Dealer Login page
 */


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
<div class="stripe center intro">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
             <?php echo do_shortcode('[wpmp_login_form]'); ?>
            </div>
        </div>
</div>

<?php

get_footer();
?>
