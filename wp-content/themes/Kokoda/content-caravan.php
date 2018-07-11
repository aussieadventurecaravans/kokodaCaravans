<?php
$caravan_id = get_query_var('caravan_id');;
global $post;
$post = get_post($caravan_id,OBJECT);
setup_postdata($post);


?>
<?php $banner_img = get_field('banner_image'); ?>
<?php $badge_img = get_field('banner_badge'); ?>

<div class="banner-wrap">
    <div class="banner-grad">
        <?php if(!empty($badge_img)): ?>
            <div class="banner-badge" style="background-image:url('<?php echo $badge_img['url'] ?>')"></div>
        <?php endif; ?>

        <img src="<?php echo $banner_img['url']; ?>" alt="caravan image" style="width: 100%"/>
    </div>
</div>
<div class="mobile-banner-wrap stripe center">
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


<?php if(get_field('floor_plan') || get_field('tech_specs') || get_field('virtual_tour_link')): ?>
    <div class="stripe center floorplan" id="floorplan">
        <?php if(get_field('floor_plan')): ?>
            <h2>Floor Plan</h2>
            <img src="<?php the_field('floor_plan'); ?>" class="img-responsive">
        <?php endif; ?>

        <?php if(get_field('virtual_tour_link')): ?><a href="<?php the_field('virtual_tour_link'); ?>" class="btn btn-sub virtual-tour" target="_blank">Virtual Tour</a><?php endif; ?>

        <?php if(have_rows('tech_specs')): ?>
            <?php while (have_rows('tech_specs')) : the_row(); ?>
               <p>
                   <span><?php the_sub_field('left'); ?></span>
                   <span><?php the_sub_field('right'); ?></span>
               </p>
            <?php endwhile; ?>
        <?php endif; ?>


    </div>
<?php endif; ?>

<?php if(have_rows('specifications')): ?>

    <div class="stripe specs" id="specifications">
        <div class="container-fluid">

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
<?php wp_reset_postdata();?>