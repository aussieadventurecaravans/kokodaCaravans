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
    $primary_prices[$caravan->ID] = get_field('price_thousands',$caravan->ID) . get_field('price_hundreds',$caravan->ID); ;
}



//eacho caravan has custom add for accessories
$custom_accessories = array();
foreach ($caravans as $caravan)
{
    if(!empty(get_field('custom_accessories',$caravan->ID)))
    {
        $custom_accessories[$caravan->ID] = get_field('custom_accessories',$caravan->ID);
    }
    else
    {
        $custom_accessories[$caravan->ID] = 'custom_accessories';
    }

}

//get all dealers from the plugin store locator
$sql = "SELECT * FROM wp_store_locator";
$sql .= " WHERE sl_tags = 'Dealer'";
$sql .= " ORDER BY sl_id asc";
$dealers = $wpdb->get_results( $sql, 'ARRAY_A' );


?>


<div class="banner-wrap">
    <div class="banner container">
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
               <nav>
                    <ol class="cd-breadcrumb triangle">
                        <li class="current"><a href="#" class="tablinks" tab-content="models">Models</a></li>
                        <li><a href="#" class="tablinks" tab-content="exterior" >Exterior</a></li>
                        <li><a href="#" class="tablinks" tab-content="floorplan">Floor Plan</a></li>
                        <li><a href="#" class="tablinks" tab-content="accessories">Accessories</a></li>
                        <li><a href="#" class="tablinks"  tab-content="summary">Summary</a></li>
                        <li><a href="#" class="tablinks"  tab-content="enquiry">Submit</a></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row option-select-value-section">
            <div class="col-md-8">
                <div id="models" class="tabcontent">
                    <div class="tab-header">
                        <h4>
                            Choose Your Models
                        </h4>
                    </div>

                    <div class="row model-list">
                        <?php foreach ($caravans as $caravan): ?>
                            <div class="item model col-xs-12 col-sm-6 col-md-6 col-lg-4" select-model="<?php echo $caravan->ID; ?>" >
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
                                        <?php if(get_field('banner_description',$caravan->ID)): ?><p><?php the_field('banner_description',$caravan->ID); ?></p><?php endif; ?>
                                        <?php if(get_field('tare',$caravan->ID)): ?><span class="tare">Tare (approx): <?php the_field('tare',$caravan->ID); ?></span><br><?php endif; ?>
                                        <?php if(get_field('ball_weight',$caravan->ID)): ?><span class="ball">Ball weight (approx): <?php the_field('ball_weight',$caravan->ID); ?></span><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div id="exterior" class="tabcontent">
                    <div class="tab-header">
                        <h4>
                            Choose Your Exterior
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
                                <select class="form-control input-lg" id="composite_panel" class="comnposite-color-choose">
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
                        <div class="col-md-6  col-xs-6  text-center">
                            <button type="button" class="btn btn-primary btn-lg btn-pre">Previous</button>
                        </div>
                        <div class="col-md-6 col-xs-6 text-center">
                            <button type="button" class="btn btn-primary btn-lg btn-next">Next</button>
                        </div>
                    </div>
                </div>

                <div id="floorplan" class="tabcontent">
                    <div class="tab-header">
                        <h4>
                            Choose Your Floorplan
                        </h4>
                    </div>
                    <div class="option-select-image-section">
                        <div class="option-display-image-wrapper row floorplan-list">
                                IMAGE
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-6 text-center">
                            <button type="button" class="btn btn-primary btn-lg btn-pre">Previous</button>
                        </div>
                        <div class="col-md-6 col-xs-6 text-center">
                            <button type="button" class="btn btn-primary btn-lg btn-next">Next</button>
                        </div>
                    </div>
                </div>

                <div id="accessories" class="tabcontent">

                    <div class="tab-header">
                        <h4>
                            Choose Your Extra Accessories
                        </h4>
                    </div>
                    <div class="row accessories-list">

                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-6 text-center">
                            <button type="button" class="btn btn-primary btn-lg btn-pre">Previous</button>
                        </div>
                        <div class="col-md-6 col-xs-6 text-center">
                            <button type="button" class="btn btn-primary btn-lg btn-next">Next</button>
                        </div>
                    </div>
                </div>

                <div id="summary" class="tabcontent">
                    <div class="tab-header">
                        <h4>
                            Caravan Summary

                        </h4>
                        <button type="button" class="btn btn-primary btn-lg btn-download"><span class="icon-moon"></span> Download</button>
                        <button type="button" class="btn btn-primary btn-lg btn-print"><span class="icon-moon"></span> Print</button>
                    </div>
                    <div class="display-image-wrapper row" id="summary-display-image-wrapper">
                    </div>
                    <div class="display-accessories-wrapper row" id="summary-display-accessories-wrapper">
                    </div>
                    <div class="display-features-wrapper row" id="summary-display-specs-wrapper">
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xs-6 text-center">
                            <button type="button" class="btn btn-primary btn-lg btn-pre">Previous</button>
                        </div>
                        <div class="col-md-6 col-xs-6 text-center">
                            <button type="button" class="btn btn-primary btn-lg btn-next">Next</button>
                        </div>
                    </div>
                </div>

                <div id="enquiry" class="tabcontent">
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
                    <div class="row custom-options-form ">
                        <div class="col-md-12">
                            <form id="customer_details_form" class="form-horizontal" method="post">
                                <fieldset>
                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="customer_name">Full Name</label>
                                        <div class="col-md-7">
                                            <input id="customer_name" name="customer_name" type="text" placeholder="" class="form-control input-md" required autocomplete="off"/>

                                        </div>
                                    </div>

                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="customer_address">Address</label>
                                        <div class="col-md-7">
                                            <input id="customer_address" name="customer_address" type="text" placeholder="" class="form-control input-md" required autocomplete="off" />

                                        </div>
                                    </div>

                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="customer_postcode">Postcode</label>
                                        <div class="col-md-3">
                                            <input id="customer_postcode" type="number" name="customer_postcode" placeholder="" class="form-control input-md" maxlength="4" required autocomplete="off" />

                                        </div>
                                    </div>

                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="customer_state">State</label>
                                        <div class="col-md-5">
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
                                        <label class="col-md-3 control-label" for="dealer_name">Dealers:</label>
                                        <div class="col-md-5">
                                            <select class="form-control input-lg" id="dealer_name" required disabled>
                                                <option selected value="">Choose Dealer</option>
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
                                        <label class="col-md-3 control-label" for="payment_method">Payment Methods</label>
                                        <div class="col-md-5">
                                            <select class="form-control input-lg" id="payment_method" required>
                                                <option selected value="">Choose Payment</option>
                                                <option value="cash">Cash</option>
                                                <option value="loan">Loan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Button -->
                                    <div class="form-group">
                                        <div class="col-xs-6 text-center">
                                            <button type="button" class="btn btn-primary btn-lg btn-back">Back</button>

                                        </div>
                                        <div  class="col-xs-6 text-center">
                                            <input id="submit_order" type="submit" class="btn btn-primary btn-lg" value="Submit" />
                                        </div>
                                    </div>

                                </fieldset>
                            </form>
                        </div>
                    </div>
                    <div class="row apply-finance-company" style="display: none;">
                        <div class="col-md-12">
                            <fieldset>
                                <div class="form-group">
                                    <div class="col-md-12 text-center">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                Apply Caravan Finance
                                            </div>
                                            <div class="panel-body">
                                                <p class="description">
                                                    <span class="apply_later" style="display:none">
                                                        Our dealer will contact and help you to arrange financing.
                                                    </span>
                                                    <span class="apply_now" style="display:none">
                                                       We will pass your contact information to our preferred financier to begin a credit application.
                                                    </span>
                                                    <span class="self_arrange" style="display:none">
                                                        If you have already applied your financier, our dealer will contact to you shortly
                                                    </span>
                                                </p>

                                                <div class="row">
                                                    <div class="col-md-4 text-center">
                                                        <div class="outside" id="apply_later" value="apply later">
                                                            <div class="inside">
                                                                <span>Apply Later</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <div class="outside" id="self_arrange"  value="self arrange">
                                                            <div class="inside">
                                                                <span>Self-Arranged Finance</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <div class="outside" id="apply_now" value="apply creditone">
                                                            <div class="inside">
                                                                <span>
                                                                    Apply Now
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-6 text-center">
                                                        <div id="back_button" class="btn btn-primary btn-lg">Back</div>

                                                    </div>

                                                    <div class="col-xs-6 text-center">
                                                        <div id="apply_button" class="btn btn-primary btn-lg">Submit</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-4 text-left">
                <fieldset class="finance-section">
                    <legend class="finance-header">Total Price</legend>
                    <!-- TOTAL PRICE SUMMARY  -->
                    <div class="finance-section-details cash-summary text-center">
                        <h2 class="price-label primary-price">
                            $0 + ORC
                        </h2>
                        <p class="primary-price-label">
                            Drive Away Price
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
                            Total Price (EST)
                        </p>
                    </div>
                    <!-- LOAN ESTIMATE SUMMARY  -->
                    <div class="finance-section-details loan-summary">
                        <fieldset class="loan-detail-section">
                            <legend class="loan-section-header">Loan Estimate</legend>
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
                                    Payment: $<span class="mp-amount">0</span> /month
                                </p>
                            </div>
                        </fieldset>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() . '/_js/ui-choose/ui-choose.js'; ?>"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() . '/_js/konva.min.js'?>"></script>
<script src="<?php echo get_stylesheet_directory_uri() .'/owl-carousel/owl.carousel.js'?>"></script>

<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() .'/owl-carousel/owl.carousel.css';?> ">
<link rel="stylesheet"  href="<?php echo get_stylesheet_directory_uri() .'/owl-carousel/owl.theme.css';?> ">

<script type="text/javascript">
    //pass the php parameter to javascript variable
    var $caravan_title = <?php echo json_encode($caravan_title); ?>;
    var $custom_exterior = <?php echo json_encode($custom_exterior); ?>;
    var $custom_floorplan = <?php echo json_encode($custom_floorplan); ?>;
    var $dealers = <?php echo json_encode($dealers); ?>;
    var $primary_prices = <?php echo json_encode($primary_prices); ?>;
    var $custom_accessories = <?php echo json_encode($custom_accessories); ?>;
    var $base_url = '<?php echo $uploads['baseurl']; ?>';
    var $site_url = '<?php echo site_url() ?>';
</script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() . '/_js/custom_order.js'?>"></script>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/_css/steps/style.css'?>" >
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/_js/ui-choose/ui-choose.css'?>" >
<?php
get_footer();
?>


