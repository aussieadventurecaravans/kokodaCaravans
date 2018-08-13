<?php
/**
 * Template Name: Custom Order Quote template
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


<div class="stripe center user-panel">
    <div class="container">
        <div class="row">
            Son
        </div>
    </div>
</div>

<?php

get_footer();
?>
