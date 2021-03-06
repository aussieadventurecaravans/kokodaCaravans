<?php
$quote = get_query_var('quote');

$caravan_id = $quote->product_id;

$custom_options = unserialize($quote->custom_options);

$caravan_ids = array(
    5417 => 41,
    5195 => 39,
    4032 => 43,
    4165 => 42,
    7169 => 42,
    5026 => 39,
    4190 => 42
);
$_MAXIMUM_LINES = $caravan_ids[$caravan_id];

/*
 * Render the caravan image
 */
$uploads = wp_upload_dir();
list($width, $height) = getimagesize($uploads['baseurl'] . '/custom_order/' . $caravan_id . '/checkerplate/' . $custom_options['checker_plate']['value'] . '.png');

$checkerPlate = imagecreatefrompng($uploads['baseurl'] . '/custom_order/' . $caravan_id . '/checkerplate/' . $custom_options['checker_plate']['value'] . '.png' );
$panel = imagecreatefrompng($uploads['baseurl'] . '/custom_order/' . $caravan_id . '/panel/' . $custom_options['panel']['value'] . '.png');
$dest_image = imagecreatetruecolor($width, $height);
// set background to white
$white = imagecolorallocate($dest_image, 255, 255, 255);
imagefill($dest_image, 0, 0, $white);

imagecopy($dest_image,$checkerPlate, 0, 0, 0, 0, $width, $height);
imagecopy($dest_image, $panel, 0, 0, 0, 0, $width, $height);

ob_start ();
imagejpeg($dest_image);
$image_data = ob_get_contents ();
ob_end_clean ();

$image_data_base64 = base64_encode ($image_data);
//generate the data uri from image
$image_data_uri = 'data:image/jpeg' .  ';base64,'  . $image_data_base64 ;


global $post;
$post = get_post($caravan_id,OBJECT);
setup_postdata($post);

?>



<?php $html .= '<div class="row logo-row">'; ?>
<?php $html .= '<div class="col-xs-12">'; ?>
<?php $html .= '<img class="logo" src="' . get_stylesheet_directory_uri() .'/_img/logo_black_text_small.png" style="width:120px;padding-left:5px;" />'; ?>
<?php $html .= '</div>'; ?>
<?php $html .= '</div>'; ?>

<?php $html .= '<div class="container-fluid first-page-header">'; ?>
<?php $html .= '<div class="row">'; ?>

<?php $html .= '<div class="col-xs-6 text-left">'; ?>
<?php $html .= '<h3>Caravan Summary'. '</h3>'; ?>
<?php $html .= '</div>'; ?>
<?php $html .= '<div class="col-xs-6 text-right" style="width:50%">'; ?>
<?php $html .= '<h3>'. 'Kokoda '.  get_the_title() . '</h3>'; ?>
<?php $html .= '</div>'; ?>

<?php $html .= '</div>'; ?>
<?php $html .= '</div>'; ?>


<?php $html .= ' <div class="display-image-wrapper row">'; ?>
<?php $html .= '<img src="'. $image_data_uri .'" width="90%"/>'; ?>
<?php $html .= ' </div>' ; ?>



<?php $html .= '<div class="container-fluid caravan_custom_options">'; ?>
<?php $html .= ' <div class="header-wrapper">'; ?>
<?php $html .= ' <h3>Exterior Colour</h3>'; ?>
<?php $html .= ' </div>'; ?>


<?php
$exterior_options = $custom_options;
foreach($exterior_options as $key => $exterior_option): ?>
<?php $html .= '<div class="row">'; ?>
<?php $html .= '<div class="col-xs-6 text-left">'; ?>
<?php $html .= '<span>' . ucfirst(preg_replace('/[^A-Za-z0-9 ]/', ' ', $key)). ' Colour : ' . $exterior_option['value']   .'</span>'; ?>
<?php $html .= '</div>'; ?>
<?php $html .= '<div class="col-xs-6 text-right">'; ?>
<?php $html .= '<span>$' . $exterior_option['price'] . '</span>'; ?>
<?php $html .= '</div>'; ?>
<?php $html .= '</div>'; ?>
<?php endforeach ?>

<?php $html .= '</div>'; ?>



<?php $html .= ' <div class="floorplan-display-wrapper row">'; ?>
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
        <?php $html .= '<span>' . get_sub_field('left') . ' : </span>'; ?>
        <?php $html .= '</div>'; ?>
        <?php $html .= '<div class="col-xs-6 text-right">'; ?>
        <?php $html .= '<span>' . get_sub_field('right') . '</span>'; ?>
        <?php $html .= '</div>'; ?>
        <?php $html .= '</div>'; ?>
    <?php endwhile; ?>
    <?php $html .= '</div>'; ?>
<?php endif; ?>

<?php if(isset($quote->add_on_accessories)) : ?>

    <?php $accessories =  unserialize($quote->add_on_accessories); ?>

    <?php if(is_array($accessories)): ?>

        <?php if(count($accessories) > 0): ?>

            <?php $html1 .='<div class="tab-header"><h3>Add-On Accessories</h3></div>' ?>

            <?php $html1 .= '<div class="display-accessories-wrapper row" id="summary-display-accessories-wrapper">'; ?>


            <?php $html1 .=  '<div class="col-md-12 text-center">'; ?>
            <?php foreach($accessories as $accessory):?>
                    <?php $html1 .= '<div class="item"><div class="item-detail">'; ?>
                    <?php $html1 .= '<img src="' . content_url('uploads') . '/custom_order/Accessories/' . $accessory['label'] . '.png" />'; ?>
                    <?php $html1 .= '<h3>' . $accessory['label']  .'</h3>'; ?>
                    <?php $html1 .= '<h3>(SKU: ' . $accessory['sku']  .')</h3>'; ?>
                    <?php $html1 .=  '</div></div>'; ?>
            <?php  endforeach; ?>

            <?php $html1 .= '</div>'; ?>

            <?php $html1 .= '</div>'; ?>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>

<?php $html2 = '<div class="tab-header"><h3>Specifications</h3></div>'; ?>
<?php if(have_rows('specifications')): ?>

    <?php $specs = get_field('specifications');?>

    <?php $html2 .= '<div class="specifications-list-wrapper">'; ?>

    <?php  $specs = get_field('specifications');?>

    <?php  if($specs): ?>
        <?php $html2 .= '<div class="row spec-list" style="margin:0">'; ?>

            <?php $max_lines = $_MAXIMUM_LINES; ?>
            <?php $col_index = 0;?>
            <?php foreach ($specs as $spec): ?>

                 <?php if($max_lines == $_MAXIMUM_LINES ): ?>
                    <?php if($col_index % 2 == 0): ?>
                        <?php $html2 .= '<div class="col-xs-6 spec-section" style="padding-right: 20px">' ; ?>
                    <?php else: ?>
                        <?php $html2 .= '<div class="col-xs-6 spec-section">' ; ?>
                    <?php endif; ?>
                    <?php $col_index++  ?>
                <?php endif; ?>
                <?php $max_lines = $max_lines - 2; ?>
                <?php $html2 .= '<h4 >' .  $spec['group_heading'] . '</h4>'; ?>

                <?php //spec items ?>
                <?php $spec_its = $spec['specification_item'];?>
                <?php $html2 .= '<ul style="padding: 0;list-style: none;">'; ?>
                <?php $index = 0 ;?>
                <?php  foreach ($spec_its as $spec_it) : $index++ ?>

                    <?php if($max_lines <= 0 && $index != count($spec_its)): ?>
                        <?php $html2 .= '</ul>'; ?>
                        <?php $html2 .= '</div>'; ?>
                        <?php $max_lines = $_MAXIMUM_LINES; ?>

                        <?php if($col_index % 2 == 0): ?>
                            <?php $html2 .= '<div class="col-xs-6 spec-section" style="padding-right: 20px">' ; ?>
                        <?php else: ?>
                            <?php $html2 .= '<div class="col-xs-6 spec-section">' ; ?>
                        <?php endif; ?>
                        <?php $col_index++  ?>

                        <?php $html2 .= '<ul style="padding: 0;list-style: none;" >'; ?>
                    <?php endif; ?>

                <?php if($index %2 == 0 ) : ?>
                    <?php $html2 .='<li>';  ?>
                <?php else: ?>
                    <?php $html2 .='<li style="background-color:#ddd;">' ?>
                <?php endif; ?>
                    <?php $html2 .= $spec_it['heading']; ?>
                    <?php $html2 .= '</li>'; ?>
                    <?php $max_lines = $max_lines - 1; ?>

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
$product_price = $quote->product_cost;
$accessories_price =  $quote->add_on_cost;

if(isset($custom_options))
{
    $exterior_price = $custom_options['panel']['price'] + $custom_options['checker_plate']['price'];
}
else
{
    $exterior_price = 0;
}
$total_price  = $product_price + $accessories_price + $exterior_price;

?>

<?php $html3 = '<div class="tab-header">'; ?>
<?php $html3 .= ' <h3>Total Price Estimate</h3>'; ?>
<?php $html3 .= ' </div>'; ?>

<?php $html3 .= '<div class="finance-wrapper container-fluid">'; ?>

<?php $html3 .= '<div class="col-sx-12" >'; ?>

<?php $html3 .= '<table class="table table-striped">' ; ?>
    <?php $html3 .= '<thead>' ?>
        <?php $html3 .= '<tr>'; ?>
            <?php $html3 .= '<th> Unit Item </th>'; ?>
            <?php $html3 .= '<th> Price </th>'; ?>
        <?php $html3 .= '</tr>';?>
    <?php $html3 .= '</thead>' ?>

    <?php $html3 .= '<tbody>' ?>
        <?php $html3 .= '<tr>'; ?>
            <?php $html3 .= '<td scope="row"><h4>Kokoda ' . get_the_title() .  ' </h4>' ;?>
            <?php $html3 .= '<p><img src="' .  $image_data_uri   .  '" style=" width:40%" /></p>'; ?>
            <?php $html3 .=  '</td>';  ?>

            <?php $html3 .= '<td >'; ?>
            <?php $html3 .= ' <p>$' . number_format($product_price) . '</p>'; ?>
            <?php $html3 .= ' </td>'; ?>
        <?php $html3 .= '</tr>';?>
        <?php if(isset($custom_options)): ?>
            <?php $html3 .= '<tr>'; ?>
                <?php $html3 .= '<td scope="row"><h4>Custom Exterior </h4>' ;?>

                <?php $exterior_options =  $custom_options ?>
                <?php foreach($exterior_options as $key => $exterior_option): ?>
                    <?php $html3 .= '<p style="text-transform: capitalize"> ' .  ucfirst(preg_replace('/[^A-Za-z0-9 ]/', ' ', $key)) .  ' Colour : '. $exterior_option['value'] .'</p>'; ?>
                <?php endforeach; ?>
                <?php $html3 .=  '</td>';  ?>


                <?php $html3 .= '<p style="text-transform: capitalize">Checker Plate Colour : '. $custom_options['checker_plate']['value'] .'</p>'; ?>
                <?php $html3 .=  '</td>';  ?>

                <?php $html3 .= '<td><h4 style="color:#fff"> Cost </h4>'; ?>
                <?php foreach($exterior_options as $key => $exterior_option): ?>
                    <?php $html3 .= '<p style="text-transform: capitalize">$'. number_format($exterior_option['price']) .'</p>'; ?>
                <?php endforeach; ?>
                <?php $html3 .= '</td>'; ?>
            <?php $html3 .= '</tr>';?>
        <?php endif;?>
        <?php if(is_array($accessories)) : ?>
            <?php if(count($accessories) > 0) : ?>
                <?php $html3 .= '<tr>'; ?>

                    <?php $html3 .= '<td  scope="row"><h4>Add-On Accessories</h4>'; ?>
                        <?php foreach($accessories as $accessory):?>
                            <?php $html3 .= '<div class="acc-item">'; ?>
                            <?php $html3 .= '<span class="acc-label" style="text-transform: capitalize"> + ' . $accessory['label']  .'</span>'; ?>
                            <?php $html3 .=  '</div>'; ?>
                        <?php  endforeach; ?>
                    <?php $html3 .= ' </td>'; ?>

                    <?php $html3 .= '<td>'; ?>
                    <?php $html3 .= ' <p> $' . number_format($accessories_price) . '</p>'; ?>
                    <?php $html3 .= ' </td>'; ?>

                <?php $html3 .= '</tr>'; ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php $html3 .= '<tr class="total-price-row">'; ?>
            <?php  $html3 .= ' <td class="header-wrapper"><span> Total Price (*) </span></td>'; ?>
            <?php  $html3 .= ' <td class="price-wrapper">$'.  number_format($total_price) .'</td>'; ?>
        <?php $html3 .= '</tr>'; ?>

    <?php $html3 .= '</tbody>' ?>
<?php $html3 .= '</table>' ; ?>
<?php $html3 .= '<p style="text-align: justify;padding: 0 12px;font-size: 13px;"><b>* Important Note:</b>
                            All the prices are subject to change without prior notice. The price estimates are provided on a basic production cost and
                            it may be changed base upon on some specific features customer need.
                            On-Road Cost (ORC) can varies between states and city.Please contact our dealers for more detail.
                        </p>'; ?>
<?php $html3 .= ' </div></div>'; ?>



<?php $html4 = '<div class="container-fluid customer-details">'; ?>
    <?php $html4 .= '<div class="tab-header">'; ?>
        <?php $html4 .= '<h3 class="text-left">Customer Detail</h3>'; ?>
    <?php $html4 .= '</div>'; ?>
    <?php $html4 .= '<div class="row">'; ?>
        <?php $html4 .= '<div class="col-xs-6 text-left">'; ?>
            <?php $html4 .= '<span> Full Name : </span>'; ?>
        <?php $html4 .= '</div>'; ?>
        <?php $html4 .= '<div class="col-xs-6 text-right">'; ?>
            <?php $html4 .= '<span> ' .  $quote->customer_first_name  . ' ' .  $quote->customer_last_name .  '</span>'; ?>
        <?php $html4 .= '</div>'; ?>
    <?php $html4 .= '</div>'; ?>

    <?php $html4 .= '<div class="row">'; ?>
        <?php $html4 .= '<div class="col-xs-6 text-left">'; ?>
            <?php $html4 .= '<span> Address : </span>'; ?>
        <?php $html4 .= '</div>'; ?>
        <?php $html4 .= '<div class="col-xs-6 text-right">'; ?>
            <?php $html4 .= '<span> ' .  $quote->customer_address . ', ' .  ucwords($quote->customer_city) . ', ' . strtoupper($quote->customer_state) . ', ' . $quote->customer_postcode  . ', Australia' . '</span>'; ?>
        <?php $html4 .= '</div>'; ?>
    <?php $html4 .= '</div>'; ?>

    <?php $html4 .= '<div class="row">'; ?>
        <?php $html4 .= '<div class="col-xs-6 text-left">'; ?>
            <?php $html4 .= '<span> Email :</span>'; ?>
        <?php $html4 .= '</div>'; ?>
        <?php $html4 .= '<div class="col-xs-6 text-right">'; ?>
            <?php $html4 .= '<span> ' .  $quote->customer_email .  '</span>'; ?>
        <?php $html4 .= '</div>'; ?>
    <?php $html4 .= '</div>'; ?>

    <?php $html4 .= '<div class="row">'; ?>
        <?php $html4 .= '<div class="col-xs-6 text-left">'; ?>
            <?php $html4 .= '<span> Phone :</span>'; ?>
        <?php $html4 .= '</div>'; ?>
        <?php $html4 .= '<div class="col-xs-6 text-right">'; ?>
            <?php $html4 .= '<span>' .  $quote->customer_phone .  '</span>'; ?>
        <?php $html4 .= ' </div>'; ?>
    <?php $html4 .= ' </div>'; ?>
<?php $html4 .= '</div>'; ?>

<?php $html4 .= '<div class="container-fluid customer-details">'; ?>
    <?php $html4 .= '<div class="tab-header">'; ?>
        <?php $html4 .= '<h3 class="text-left">Payment Detail</h3>'; ?>
    <?php $html4 .= '</div>'; ?>

    <?php $html4 .= '<div class="row">'; ?>
        <?php $html4 .= '<div class="col-xs-6 text-left">'; ?>
            <?php $html4 .= '<span> Payment Option :</span>'; ?>
        <?php $html4 .= ' </div>'; ?>
        <?php $html4 .= '<div class="col-xs-6 text-right">'; ?>
            <?php $html4 .= '<span>' .  $quote->payment_method .  '</span>'; ?>
        <?php $html4 .= ' </div>'; ?>
    <?php $html4 .= ' </div>'; ?>

    <?php if( $quote->payment_method === 'loan' ):  ?>
        <?php $loan_detail = unserialize($quote->loan_detail); ?>
        <?php $html4 .= '<div class="row">'; ?>
            <?php $html4 .= '<div class="col-xs-6 text-left">'; ?>
                    <?php $html4 .= '<span>Loan option :</span>'; ?>
            <?php $html4 .= ' </div>'; ?>
            <?php $html4 .= '<div class="col-xs-6 text-right">'; ?>
                    <?php $html4 .= '<span>' .  $quote->apply_loan_option .  '</span>'; ?>
            <?php $html4 .= ' </div>'; ?>
        <?php $html4 .= ' </div>'; ?>
        <?php $html4 .= '<div class="row">'; ?>
            <?php $html4 .= '<div class="col-xs-6 text-left">'; ?>
                <?php $html4 .= '<span>Loan Status : </span>'; ?>
            <?php $html4 .= ' </div>'; ?>
            <?php $html4 .= '<div class="col-xs-6 text-right">'; ?>
                <?php $html4 .= '<span>' .  $quote->loan_status .  '</span>'; ?>
            <?php $html4 .= ' </div>'; ?>
        <?php $html4 .= ' </div>'; ?>
    <?php endif; ?>
<?php $html4 .= '</div>'; ?>


<?php

require_once KOKODA_CUSTOM_ORDER_PLUGIN_URL .'assets/mpdf/vendor/autoload.php';


$cssPart1 = file_get_contents(KOKODA_CUSTOM_ORDER_PLUGIN_URL . 'assets/bootstrap/css/bootstrap.css');
$cssPart2 = file_get_contents(KOKODA_CUSTOM_ORDER_PLUGIN_URL . 'assets/bootstrap/css/custom_email.css');

$mpdf = new \Mpdf\Mpdf(['mode' => 'c','margin_top' => 10,'margin_bottom' => 10 ,'margin_left' => 5, 'margin_right' => 5 ]);
$mpdf->defaultheaderline = 0;
$mpdf->defaultfooterline = 0;


$mpdf->setFooter('{PAGENO} / {nb}');
//add the Caravan Page with custom Options and Accessories
$mpdf->AddPage();
$mpdf->WriteHTML($cssPart1,1);
$mpdf->WriteHTML($cssPart2,1);
$mpdf->WriteHTML($html);


// add the add on accessories page
if(isset($quote->add_on_accessories))
{
    if(is_array($accessories))
    {
        if(count($accessories) > 0)
        {
            $mpdf->AddPage();
            $mpdf->WriteHTML($html1);
        }

    }
}

//add the specification page
$mpdf->AddPage();
$mpdf->WriteHTML($html2);

// add the Quote total extimate page
$mpdf->AddPage();
$mpdf->WriteHTML($html3);

// add the Quote total extimate page
$mpdf->AddPage();
$mpdf->WriteHTML($html4);




$mpdf->Output(WP_CONTENT_DIR. '/uploads/custom_order/email_attach/quote_summary_' . $_quote->quote_id .'.pdf', 'F');


?>
