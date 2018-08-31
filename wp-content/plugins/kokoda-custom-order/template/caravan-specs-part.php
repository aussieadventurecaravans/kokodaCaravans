<?php
$caravan_id = get_query_var('caravan_id');;
global $post;
$post = get_post($caravan_id,OBJECT);
setup_postdata($post);

?>



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


<?php wp_reset_postdata();?>