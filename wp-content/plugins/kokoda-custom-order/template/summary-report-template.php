<?php
$custom_order = get_query_var('custom_order');
$caravan_id = get_query_var('caravan_id');
$caravan_image = get_query_var('caravan_image');

$caravan_ids = array(5417);
if( in_array($caravan_id, $caravan_ids ))
{
    $_MAXIMUM_LINES = 40;
}
else
{
    $_MAXIMUM_LINES = 38;
}

global $post;
$post = get_post($caravan_id,OBJECT);
setup_postdata($post);

?>


<?php $html = '<div class="tab-header">'; ?>
<?php $html .= ' <h3>Caravan Summary </h3>'; ?>
<?php $html .= ' </div>'; ?>


<?php $html .= ' <div class="header-wrapper">'; ?>
<?php $html .= ' <h2>Model: ' . get_the_title() .  ' </h2>'; ?>
<?php $html .= ' </div>'; ?>
<?php $html .= ' <div class="display-image-wrapper row" id="summary-display-image-wrapper">'; ?>
<?php $html .= '<img src="'. $caravan_image .'"/>'; ?>
<?php $html .= ' </div>' ; ?>



<?php $html .= '<div class="container-fluid caravan_custom_options">'; ?>
<?php $html .= ' <div class="header-wrapper">'; ?>
<?php $html .= ' <h3>Exterior Color</h3>'; ?>
<?php $html .= ' </div>'; ?>
<?php $html .= '<div class="row">'; ?>
<?php $html .= '<div class="col-xs-6 text-left">'; ?>
<?php $html .= '<span> Panel Color </span>'; ?>
<?php $html .= '</div>'; ?>
<?php $html .= '<div class="col-xs-6 text-right">'; ?>
<?php $html .= '<span>' . $custom_order['caravan_options']['panel'] . '</span>'; ?>
<?php $html .= '</div>'; ?>
<?php $html .= '</div>'; ?>

<?php $html .= '<div class="row">'; ?>
<?php $html .= '<div class="col-xs-6 text-left">'; ?>
<?php $html .= '<span> Checker Plate Color </span>'; ?>
<?php $html .= '</div>'; ?>
<?php $html .= '<div class="col-xs-6 text-right">'; ?>
<?php $html .= '<span>' . $custom_order['caravan_options']['checker_plate'] . '</span>'; ?>
<?php $html .= '</div>'; ?>
<?php $html .= '</div>'; ?>
<?php $html .= '</div>'; ?>



<?php $html .= ' <div class="floorplan-header header-wrapper">'; ?>
<?php $html .= ' <h2>Floor Plan </h2>'; ?>
<?php $html .= ' </div>'; ?>
<?php $html .= ' <div class="option-display-image-wrapper row">'; ?>
    <?php $html .= '<div class="item col-md-12 text-center selected">'; ?>
        <?php  $html .= '<img src="' .  get_field('floor_plan')   .  '" style=" width:80%">'; ?>
        <?php  $html .= '<div class="floorplan-details">'; ?>
         <?php $html .= '</div>'; ?>
     <?php $html .= '</div>'; ?>
<?php $html .= '</div>'; ?>


<?php if(have_rows('tech_specs')): ?>
    <?php $html .= ' <div class="container-fluid tech_specs">'; ?>
    <?php $html .= ' <div class="header-wrapper">'; ?>
    <?php $html .= ' <h3>Tech Specs</h3>'; ?>
    <?php $html .= ' </div>'; ?>
    <?php while (have_rows('tech_specs')) : the_row(); ?>
        <?php $html .= '<div class="row tech_specs_field">'; ?>
        <?php $html .= '<div class="col-xs-6 text-left">'; ?>
        <?php $html .= '<span>' . get_sub_field('left') . ' </span>'; ?>
        <?php $html .= '</div>'; ?>
        <?php $html .= '<div class="col-xs-6 text-right">'; ?>
        <?php $html .= '<span>' . get_sub_field('right') . '</span>'; ?>
        <?php $html .= '</div>'; ?>
        <?php $html .= '</div>'; ?>
    <?php endwhile; ?>
    <?php $html .= '</div>'; ?>
<?php endif; ?>

<?php if(count($custom_order['accessories']) > 0) : ?>

<?php $html1 .=' <div class="tab-header"><h3>Add-on Accessories</h3></div>' ?>

<?php $html1 .= ' <div class="display-accessories-wrapper row" id="summary-display-accessories-wrapper">'; ?>

<?php $accessories =  $custom_order['accessories']; ?>
<?php $html1 .=  '<div class="col-md-12 text-center">'; ?>
<?php foreach($accessories as $accessory):?>
        <?php $html1 .= '<div class="item"><div class="item-detail">'; ?>
        <?php $html1 .= '<img src="' . content_url('uploads') . '/custom_order/'. $caravan_id . '/Accessories/' . $accessory['accessory_label'] . '.png" />'; ?>
        <?php $html1 .= '<h3>' . $accessory['accessory_label']  .'</h3>'; ?>
        <?php $html1 .=  '</div></div>'; ?>
<?php  endforeach; ?>

<?php $html1 .= ' </div>'; ?>

<?php $html1 .= ' </div>'; ?>

<?php endif; ?>

<?php $html2 = '<div class="tab-header"><h3>Specifications</h3></div>'; ?>
<?php if(have_rows('specifications')): ?>

    <?php $specs = get_field('specifications');?>

    <?php $html2 .= '<div class="specifications-list-wrapper container-fluid">'; ?>

    <?php  $specs = get_field('specifications');?>

    <?php  if($specs): ?>
        <?php $html2 .= '<div class="row spec-list" style="margin:0">'; ?>

            <?php $max_lines = $_MAXIMUM_LINES; ?>
            <?php foreach ($specs as $spec): ?>

                 <?php if($max_lines == $_MAXIMUM_LINES ): ?>
                    <?php $html2 .= '<div class="col-xs-6 spec-section">'; ?>
                <?php endif; ?>
                <?php $max_lines = $max_lines - 1; ?>
                <?php $html2 .= '<h4 >' .  $spec['group_heading'] . '</h4>'; ?>

                <?php //spec items ?>
                <?php $spec_its = $spec['specification_item'];?>
                <?php $html2 .= '<ul style="padding: 0;list-style: none;">'; ?>
                <?php $index = 0 ;?>
                <?php  foreach ($spec_its as $spec_it) : $index++ ?>

                    <?php $html2 .='<li>';  ?>
                    <?php $html2 .= $spec_it['heading']; ?>
                    <?php $html2 .= '</li>'; ?>

                    <?php $max_lines = $max_lines - 1; ?>
                    <?php if($max_lines <= 0 && $index != count($spec_its)): ?>
                        <?php $html2 .= '</ul>'; ?>
                        <?php $html2 .= '</div>'; ?>
                        <?php $max_lines = $_MAXIMUM_LINES; ?>
                        <?php $html2 .= '<div class="col-xs-6 spec-section">'; ?>
                        <?php $html2 .= '<ul style="padding: 0;list-style: none;" >'; ?>
                    <?php endif; ?>

                <?php endforeach; ?>
                <?php $html2 .= '</ul>'; ?>

                 <?php if($max_lines <= 0 ): ?>
                    <?php $html2 .= '</div>'; ?>
                    <?php $max_lines = $_MAXIMUM_LINES; ?>
                <?php endif; ?>

            <?php endforeach; ?>

        <?php $html2 .= '</div>'; ?>
    <?php endif; ?>

    <?php $html2 .= '</div>'; ?>

<?php endif; ?>




<?php


require_once KOKODA_CUSTOM_ORDER_PLUGIN_URL . 'assets/mpdf/vendor/autoload.php';


$cssPart1 = file_get_contents(KOKODA_CUSTOM_ORDER_PLUGIN_URL . 'assets/bootstrap/css/bootstrap.css');
$cssPart2 = file_get_contents(KOKODA_CUSTOM_ORDER_PLUGIN_URL . 'assets/bootstrap/css/custom.css');

$mpdf = new \Mpdf\Mpdf(['mode' => 'c','margin_top' => 10,'margin_bottom' => 10]);


//add the Caravan Page with custom Options and Accessories
$mpdf->AddPage();
$mpdf->WriteHTML($cssPart1,1);
$mpdf->WriteHTML($cssPart2,1);
$mpdf->WriteHTML($html);



// add the add on accessories page
if(count($custom_order['accessories']) > 0)
{
    $mpdf->AddPage();
    $mpdf->WriteHTML($html1);
}


// add the specification page
$mpdf->AddPage();
$mpdf->WriteHTML($html2);


echo base64_encode($mpdf->Output('quote_summary.pdf', 'S'));




?>
