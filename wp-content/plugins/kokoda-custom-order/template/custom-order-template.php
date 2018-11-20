<?php
/**
 * Template Name: Custom Order Quote template
 */


get_header();

?>

<?php $banner_img = get_field('content_page_banner'); ?>
<?php
$uploads = wp_upload_dir();


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

//get the ID of category with name "Caravan Archive"
$caravan_archive_category_id = 0;
foreach ( $terms as $term ){
    if(in_array( $term->name ,array('Caravan Archive')))
    {
        $caravan_archive_category_id = $term->term_id;
        break;
    }
}

//query find the caravans belong to category specified by page
// and these caravans also don't belong to archive category.
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
        'nopaging' => true,
        'meta_query'	=> array(
            array(
                'key'	 	=> 'custom_order_active',
                'compare' 	=> '=',
                'value'	  	=> 1,
            ),
        ),
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
        'nopaging' => true,
        'meta_query'	=> array(
            array(
                'key'	 	=> 'custom_order_active',
                'compare' 	=> '=',
                'value'	  	=> 1,
            ),
        ),
    );
}
//all caravans object
$caravans  =  get_posts($args);


//product title
$caravan_title= array();
foreach ($caravans as $caravan)
{
    $caravan_title[$caravan->ID] = $caravan->post_title;
}

//Custom Exterior of all Models
$custom_exterior= array();
foreach ($caravans as $caravan)
{
    $custom_exterior[$caravan->ID] = get_field('custom_exterior',$caravan->ID);
}

//Floor Plan of All Models
foreach ($caravans as $caravan)
{
    if(!empty(get_field('custom_floorplan',$caravan->ID)))
    {
        $custom_floorplan[$caravan->ID] = get_field('custom_floorplan',$caravan->ID);
    }
    else
    {
        $custom_floorplan[$caravan->ID] = get_field('floor_plan',$caravan->ID);
    }
}

//product prices for all caravans
$primary_prices = array();
foreach ($caravans as $caravan)
{
    $primary_prices[$caravan->ID] = get_field('price_thousands',$caravan->ID) . get_field('price_hundreds',$caravan->ID);
}



//each caravan has a list of accessories files CSV
$acs_files = array();
foreach ($caravans as $caravan)
{
    if(!empty(get_field('accessories',$caravan->ID)))
    {
        $acs_files[$caravan->ID] = get_field('accessories', $caravan->ID);
    }
    else
    {
        $acs_files[$caravan->ID] = '';
    }
}

//get all dealers from the plugin store locator
$sql = "SELECT * FROM wp_store_locator";
$sql .= " WHERE sl_tags = 'Dealer'";
$sql .= " ORDER BY sl_id asc";
$dealers = $wpdb->get_results( $sql, 'ARRAY_A' );


?>
<div class="banner-wrap">
    <div class="banner fluid-container">
        <div class="row">
            <div class="banner-content page-heading col-sm-12">
                <h1>
                    Build Your Caravan
                </h1>
            </div>
        </div>
    </div>
</div>

<div class="custom-quote-section">
    <div class="fluid-container">
        <div class="row option-select-header-section">
            <div class="col-sm-12">
                <ol class="cd-multi-steps text-bottom count">
                    <li class="current"><a href="#" class="tablinks" tab-content="model">Model</a></li>
                    <li><a href="#" class="tablinks" tab-content="floorplan">Floor Plan</a></li>
                    <li><a href="#" class="tablinks" tab-content="exterior" >Exterior</a></li>
                    <li><a href="#" class="tablinks" tab-content="accessories">Accessories</a></li>
                    <li><a href="#" class="tablinks"  tab-content="summary">Summary</a></li>
                    <li><a href="#" class="tablinks"  tab-content="enquiry">Submit</a></li>
                </ol>
            </div>
        </div>
        <div class="row option-select-value-section">
            <div class="col-md-12 option-select-value-section-content">
                <div id="model" class="tabcontent">
                    <div class="tab-header">
                        <h4>
                            Select Model
                        </h4>
                    </div>

                    <div class="row model-list">
                        <?php foreach ($caravans as $caravan): ?>
                            <div class="item model col-xs-12 col-sm-6 col-md-4 col-lg-3" select-model="<?php echo $caravan->ID; ?>" >
                                <div class="item-wrapper">
                                    <?php  $product_img = get_field('banner_image',$caravan->ID); ?>
                                    <div class="item-img">
                                        <img alt="caravan image" src="<?php echo $product_img['sizes']['medium']; ?>"/>
                                    </div>
                                    <div class="item-details">
                                        <div class="details">
                                            <h3><?php echo get_the_title($caravan); ?></h3>
                                            <div class="product-meta clearfix">
                                                <?php if(get_field('price_thousands',$caravan->ID)): ?><span class="price">$<?php the_field('price_thousands',$caravan->ID); ?>,<?php the_field('price_hundreds',$caravan->ID); ?><i>+ORC</i></span><?php endif; ?>
                                                <?php if(get_field('size_feet',$caravan->ID)): ?><span class="size"><?php the_field('size_feet',$caravan->ID); ?>'<?php if(get_field('size_inches',$caravan->ID)): ?><?php the_field('size_inches',$caravan->ID); ?>"<?php endif; ?></span><?php endif; ?>
                                                <?php if(get_field('occupants',$caravan->ID)): ?><span class="occupants"><?php the_field('occupants',$caravan->ID); ?></span><?php endif; ?>
                                            </div>
                                            <?php /* if(get_field('banner_description',$caravan->ID)): ?><p><?php the_field('banner_description',$caravan->ID); ?></p><?php endif; */ ?>
                                            <?php if(get_field('tare',$caravan->ID)): ?><span class="tare">Tare (approx): <?php the_field('tare',$caravan->ID); ?></span><br><?php endif; ?>
                                            <?php if(get_field('ball_weight',$caravan->ID)): ?><span class="ball">Ball weight (approx): <?php the_field('ball_weight',$caravan->ID); ?></span><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div id="floorplan" class="tabcontent">
                    <div class="tab-header">
                        <h4 class="floorplan-header">
                            Floorplan
                        </h4>
                        <div class="display-model-section form-inline">
                            <label class="select-model-label">Related Models</label>
                            <select class="form-control" id="select_model"> </select>
                        </div>
                    </div>
                    <div class="option-select-image-section">
                        <div class="option-display-image-wrapper row floorplan-list">
                            IMAGE
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 text-right">
                            <button type="button" class="btn btn-primary btn-lg btn-pre"><span class="icon-moon icon-left-arrow"></span>Back To Models</button>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 text-left">
                            <button type="button" class="btn btn-primary btn-lg btn-next">Next To Exterior<span class="icon-moon icon-right-arrow"></span></button>
                        </div>
                    </div>
                </div>

                <div id="exterior" class="tabcontent">
                    <div class="tab-header">
                        <h4>
                            Customize Exterior
                        </h4>
                    </div>
                    <div class="row option-select-image-section">
                        <div class="col-sm-12">
                            <div class="option-display-image-wrapper" id="exterior-display-image-wrapper">
                                IMAGE
                            </div>
                        </div>
                    </div>
                    <div class="row custom-options-form">
                        <div class="form-group">

                            <div class="col-md-12">
                                <label class="control-label" for="composite_panel">Composite Panel</label>
                                <select class="form-control input-lg" id="composite_panel" class="composite-color-choose">
                                    <option selected>Choose Colour</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="control-label" for="checker_plate">Checkerplate</label>
                                <select class="form-control input-lg" id="checker_plate">
                                    <option selected>Choose Colour</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12  text-right">
                            <button type="button" class="btn btn-primary btn-lg btn-pre"><span class="icon-moon icon-left-arrow"></span>Back To Floorplan</button>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 text-left">
                            <button type="button" class="btn btn-primary btn-lg btn-next">Next To Accessories<span class="icon-moon icon-right-arrow"></span></button>
                        </div>
                    </div>
                </div>

                <div id="accessories" class="tabcontent">

                    <div class="tab-header">
                        <h4>
                            Add-On Accessories
                        </h4>
                    </div>
                    <div class="row accessories-list">

                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 text-right">
                            <button type="button" class="btn btn-primary btn-lg btn-pre"><span class="icon-moon icon-left-arrow"></span>Back To Exterior</button>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 text-left">
                            <button type="button" class="btn btn-primary btn-lg btn-next">Next To Summary<span class="icon-moon icon-right-arrow"></span></button>
                        </div>
                    </div>
                </div>

                <div id="summary" class="tabcontent">
                    <div class="tab-header">
                        <h4>
                            Caravan Summary
                        </h4>
                        <button type="button" class="btn btn-primary btn-lg btn-download"><span class="icon-moon"></span>Download</button>
                        <button type="button" class="btn btn-primary btn-lg btn-print"><span class="icon-moon"></span>Print</button>
                    </div>
                    <div class="display-image-wrapper row" id="summary-display-image-wrapper">
                    </div>
                    <div class="display-accessories-wrapper row" id="summary-display-accessories-wrapper">
                    </div>
                    <div class="display-features-wrapper row" id="summary-display-specs-wrapper">
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 text-right">
                            <button type="button" class="btn btn-primary btn-lg btn-pre"><span class="icon-moon icon-left-arrow"></span>Back To Accessories</button>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 text-left">
                            <button type="button" class="btn btn-primary btn-lg btn-next">Next To Submit<span class="icon-moon icon-right-arrow"></span></button>
                        </div>
                    </div>
                </div>

                <div id="enquiry" class="tabcontent">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="tab-header">
                                <h4>
                                    Submit Your Details
                                </h4>
                            </div>
                            <div class="feedback-notice-messages">
                                <div class="alert alert-danger alert-dismissible fade in">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Failed!</strong> This quote is failed to submit, please contact to our dealer for more info or reload the page.
                                </div>
                                <div class="alert alert-success alert-dismissible fade in">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Success!</strong> This quote is successfully submited, our dealer will contact to you shortly.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                    </div>

                    <div class="row custom-options-form ">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
                            <form id="customer_details_form" class="form-horizontal" method="post">
                                <fieldset>

                                    <?php wp_nonce_field( 'submit_new_quote' ,'kokoda_wpnonce'); ?>

                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="customer_first_name">First Name</label>
                                        <div class="col-md-7">
                                            <input id="customer_first_name" name="customer_first_name" type="text" placeholder="" class="form-control input-md" required autocomplete="off"/>

                                        </div>
                                    </div>

                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="customer_last_name">Last Name</label>
                                        <div class="col-md-7">
                                            <input id="customer_last_name" name="customer_last_name" type="text" placeholder="" class="form-control input-md" required autocomplete="off"/>
                                        </div>
                                    </div>

                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="customer_address">Street Address</label>
                                        <div class="col-md-3">
                                            <input id="customer_address" name="customer_address" type="text" placeholder="" class="form-control input-md" required autocomplete="off" />
                                        </div>
                                        <label class="col-md-1 control-label" for="customer_city">City</label>
                                        <div class="col-md-3">
                                            <input id="customer_city" name="customer_city" type="text" placeholder="" class="form-control input-md" required autocomplete="off" />
                                        </div>
                                    </div>

                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="customer_postcode">Postcode</label>
                                        <div class="col-md-3">
                                            <input id="customer_postcode" type="number" name="customer_postcode" placeholder="" class="form-control input-md" maxlength="4" required autocomplete="off" />
                                        </div>
                                        <label class="col-md-1 control-label" for="customer_state">State</label>
                                        <div class="col-md-3">
                                            <select class="form-control input-lg" id="customer_state" required>
                                                <option selected value="">Choose State</option>
                                                <option value="vic">Victoria</option>
                                                <option value="nsw">New South Wales</option>
                                                <option value="sa">South Australia</option>
                                                <option value="nt">Northern Territory</option>
                                                <option value="qld">Queensland</option>
                                                <option value="wa">Western Australia</option>
                                                <option value="tas">Tasmania</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="customer_phone">Phone</label>
                                        <div class="col-md-7">
                                            <input id="customer_phone" name="customer_phone" type="number" placeholder="" class="form-control input-md" required autocomplete="off" />
                                        </div>
                                    </div>

                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="customer_email">Email</label>
                                        <div class="col-md-7">
                                            <input id="customer_email" name="customer_email" type="email" placeholder="" class="form-control input-md" required autocomplete="off" />

                                        </div>
                                    </div>

                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="dealer_name">Dealers:</label>
                                        <div class="col-md-4">
                                            <select class="form-control input-lg" id="dealer_name" required disabled>
                                                <option selected value="">Choose Dealer</option>
                                            </select>
                                        </div>
                                    </div>


                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="payment_method">Payment Methods</label>
                                        <div class="col-md-4">
                                            <select class="form-control input-lg" id="payment_method" required>
                                                <option selected value="">Choose Payment</option>
                                                <option value="cash">Cash</option>
                                                <option value="loan">Loan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group apply-finance-form" style="display: none;">
                                        <label class="col-md-3 control-label" for="loan_options">Loan Options</label>
                                        <div class="col-md-5">
                                            <div class="radio">
                                                <label><input type="radio" name="loan_options" value="self arrange" id="self_arrange" checked>Self-Arranged Finance</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="loan_options" value="apply later" id="apply_later">Apply Finance Later</label>
                                            </div>
                                            <div class="finance-options-detail">
                                                 <span class="self_arrange" style="display:none">
                                                    If you have already applied your financier, our dealer will contact to you shortly
                                                 </span>
                                                 <span class="apply_later" style="display:none">
                                                    Our dealer will contact and help you to arrange financing.
                                                 </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Button -->
                                    <div class="form-group">
                                        <div class="col-xs-6 text-right">
                                            <button type="button" class="btn btn-primary btn-lg btn-back"><span class="icon-moon icon-left-arrow"></span>Back To Summary</button>

                                        </div>
                                        <div  class="col-xs-6 text-left">
                                            <input id="submit_order" type="submit" class="btn btn-primary btn-lg" value="Submit Your Details" />
                                        </div>
                                    </div>

                                </fieldset>
                            </form>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
            </div>


            <div class="total-summary-loan-section">
                <fieldset class="finance-section">
                    <legend class="finance-header">Price Summary</legend>
                    <!-- TOTAL PRICE SUMMARY  -->
                    <div class="finance-section-details cash-summary text-center">
                        <h2 class="price-label primary-price">
                            $0 + ORC
                        </h2>
                        <p class="primary-price-label">
                            Drive Away Price
                        </p>
                        <h2> + </h2>
                        <h2 class="price-label custom-options-price">
                            $0
                        </h2>
                        <p class="custom-options-price-label">
                            Custom Exterior Price
                        </p>
                        <h2> + </h2>
                        <h2 class="price-label add-on-price">
                            $0
                        </h2>
                        <p class="add-on-price-label">
                            Accessories Price
                        </p>

                        <h2 class="price-label total-price">
                            $0
                        </h2>
                        <p class="total-price-label">
                            Total Price *
                        </p>
                    </div>

                    <div class="finance-disclaim-section-details text-left">
                        <p style="text-align: justify;padding: 0 12px;font-size: 13px;"><b>* Please Note:</b>
                            All the prices are subject to change without prior notice. The price estimates are provided on a basis production cost and
                            it may be changed base upon on some specific features customer need.
                            On-Road Cost (ORC) can varies between states and city.Please contact our dealers for more detail.
                        </p>
                    </div>
                </fieldset>
                <!-- LOAN CACULATOR  -->
                <div class="finance-section-details loan-summary">
                    <fieldset class="loan-detail-section">
                        <legend class="loan-section-header">Loan Caculator</legend>
                        <div class="loan-amount-sec">
                            <p><span>Loan Amount($):<input type="text" class="form-control input-md loan-amount"  value="0"/></p>
                        </div>
                        <div class="interest-sec">
                            <p><span>Interest Rate(%):<input type="text" class="form-control input-md interest-rate" placeholder="4.59" value="4.59"/></p>
                        </div>
                        <div class="period-sec">
                            <p><span>Loan Term(months):<input type="text" class="form-control input-md loan-terms"  value="60"/></p>
                        </div>

                        <div class="balloon-amount-sec">
                            <p><span>Balloon Amount($):<input type="text" class="form-control input-md balloon-amount" placeholder="$" value="0"/></p>
                        </div>

                        <div class="monthly-payment-sec">
                            <p>
                                Repayment Cost: $<span class="mp-amount">0</span> /month
                            </p>
                        </div>

                        <div class="finance-disclaim-section-details text-left">
                            <p style="text-align: justify;font-size: 13px;"><b>Please Note:</b>
                                Enter the loan amount, interest rate, terms months and Balloon Amount(large payment due at the end of a loan), it will automatically give you repayment cost.
                            </p>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() . '/_js/ui-choose/ui-choose.js'; ?>"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() . '/_js/konva.min.js'?>"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() .'/owl-carousel/owl.carousel.js'?>"></script>

<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() .'/owl-carousel/owl.carousel.css';?> ">
<link rel="stylesheet"  href="<?php echo get_stylesheet_directory_uri() .'/owl-carousel/owl.theme.css';?> ">

<script type="text/javascript">
    //pass the php parameter to javascript variable
    var $caravan_title = <?php echo json_encode($caravan_title); ?>;
    var $custom_exterior = <?php echo json_encode($custom_exterior); ?>;
    var $custom_floorplan = <?php echo json_encode($custom_floorplan); ?>;
    var $dealers = <?php echo json_encode($dealers); ?>;
    var $primary_prices = <?php echo json_encode($primary_prices); ?>;
    var $base_url = '<?php echo $uploads['baseurl']; ?>';
    var $site_url = '<?php echo site_url() ?>';
    var $acs_files = <?php echo json_encode($acs_files); ?>;
</script>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/_css/custom_order.css'?>" >
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() . '/_js/custom_order.js'?>"></script>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/_css/steps/style.css'?>" >
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/_js/ui-choose/ui-choose.css'?>" >

<?php
get_footer();
?>


