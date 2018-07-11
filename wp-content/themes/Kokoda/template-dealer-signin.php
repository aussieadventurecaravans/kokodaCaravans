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
                <?php  if (!is_user_logged_in()) : ?>
                    <h1><?php the_title(); ?></h1>
                <?php else: ?>
                    <?php   $current_user = wp_get_current_user(); ?>
                    <h1>Hello <?php echo  ucfirst($current_user->user_login); ?></h1>
                <?php endif; ?>
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
<div class="stripe center user-panel">
    <div class="container">
        <div class="row">

                <?php  if (!is_user_logged_in()) : ?>
                    <div class="col-12">
                        <?php echo do_shortcode('[wpmp_login_form]'); ?>
                    </div>
                <?php else: ?>
                    <div class="col-lg-4 col-md-4 user-control">
                        <?php
                        $current_user = wp_get_current_user();
                        $logout_redirect = (empty($wpmp_form_settings['wpmp_logout_redirect']) || $wpmp_form_settings['wpmp_logout_redirect'] == '-1') ? '' : $wpmp_form_settings['wpmp_logout_redirect'];

                        echo 'Logged in as <strong>' . ucfirst($current_user->user_login) . '</strong>. <a href="' . wp_logout_url(get_permalink($logout_redirect)) . '">Log out ? </a>';
                        ?>
                    </div>
                    <div class="col-lg-8 col-md-8 user-content">

                        test
                    </div>
                <?php endif; ?>
        </div>
    </div>
</div>

<?php

get_footer();
?>
