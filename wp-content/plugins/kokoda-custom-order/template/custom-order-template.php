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
//drive away prices for all caravans
$primary_prices = array();
foreach ($caravans as $caravan)
{
    $primary_prices[$caravan->ID] = get_field('price_thousands',$caravan->ID) . get_field('price_hundreds',$caravan->ID); ;
}



//custom_accessories
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
                        <li><a href="#" class="tablinks"  tab-content="enquiry">Enquiry</a></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row option-select-value-section">
            <div class="col-sm-10">
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
                                        <?php if(get_field('banner_description',$caravan->ID)): ?><p><?php the_field('banner_description',$caravan->ID); ?></p><?php endif; ?>
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

                            <div class="col-md-6">
                                <label class="control-label" for="composite_panel">Composite Panel</label>
                                <select class="form-control input-lg" id="composite_panel">
                                    <option selected>Choose Colour</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="control-label" for="checker_plate">Checkerplate</label>
                                <select class="form-control input-lg" id="checker_plate">
                                    <option selected>Choose Colour</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 text-center">
                            <button type="button" class="btn btn-primary btn-lg btn-pre">Previous</button>
                        </div>
                        <div class="col-md-6 text-center">
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
                        <div class="col-md-6 text-center">
                            <button type="button" class="btn btn-primary btn-lg btn-pre">Previous</button>
                        </div>
                        <div class="col-md-6 text-center">
                            <button type="button" class="btn btn-primary btn-lg btn-next">Next</button>
                        </div>
                    </div>
                </div>

                <div id="accessories" class="tabcontent">
                    <div class="tab-header">
                        <h4>
                            Add Extra Accessories
                        </h4>
                    </div>
                    <div class="row custom-options-form">

                    </div>
                    <div class="row">
                        <div class="col-md-6 text-center">
                            <button type="button" class="btn btn-primary btn-lg btn-pre">Previous</button>
                        </div>
                        <div class="col-md-6 text-center">
                            <button type="button" class="btn btn-primary btn-lg btn-next">Next</button>
                        </div>
                    </div>
                </div>
                <div id="summary" class="tabcontent">
                    <div class="tab-header">
                        <h4>
                            Caravan Summary
                        </h4>
                    </div>
                    <div class="row custom-options-form">

                    </div>
                    <div class="row">
                        <div class="col-md-6 text-center">
                            <button type="button" class="btn btn-primary btn-lg btn-pre">Previous</button>
                        </div>
                        <div class="col-md-6 text-center">
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
                        <div class="col-md-7">
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

                                    <fieldset class="form-finance-section">
                                        <legend class="finance-header">Payment Details</legend>
                                        <div class="finance-section-details">


                                        </div>
                                    </fieldset>
                                    <!-- Button -->
                                    <div class="form-group">
                                        <div class="col-md-12 text-center">
                                            <input id="reset_order" type="reset" class="btn btn-primary btn-lg" value="Reset Quote" />
                                            <input id="submit_order" type="submit" class="btn btn-primary btn-lg" value="Submit Quote" />
                                        </div>
                                    </div>

                                </fieldset>
                            </form>
                        </div>

                        <div class="col-md-4 text-left">
                            <fieldset class="finance-section">
                                <legend class="finance-header">Payment Details</legend>
                                <div class="finance-section-details loan-summary"  style="display: none">
                                    <p>
                                        <span>Total Price:<input type="text" class="form-control input-md drive-away-price" disabled/>
                                    </p>
                                    <fieldset class="loan-detail-section">
                                        <legend class="loan-section-header">Loan Estimate</legend>
                                        <div class="loan-amount-sec">
                                            <p><span>Loan Amount($):<input type="text" class="form-control input-md loan-amount"  value="0"/></p>
                                        </div>
                                        <div class="interest-sec">
                                            <p><span>Interest Rate(%):<input type="text" class="form-control input-md interest-rate" placeholder="4.59" value="4.59"/></p>
                                        </div>
                                        <div class="period-sec">
                                            <p><span>Loan Term(months):<input type="text" class="form-control input-md loan-terms"  value="0"/></p>
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
                                <div class="finance-section-details cash-summary text-center" style="display: none">
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
                            </fieldset>
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
                                                    <div class="col-md-2">
                                                    </div>
                                                    <div class="col-md-8 text-center">

                                                        <div class="outside" id="apply_later" value="apply later">
                                                            <div class="inside">
                                                                <span>Apply Later</span>
                                                            </div>
                                                        </div>

                                                        <div class="outside" id="self_arrange"  value="self arrange">
                                                            <div class="inside">
                                                                <span>Self-Arranged Finance</span>
                                                            </div>
                                                        </div>

                                                        <div class="outside" id="apply_now" value="creditone">
                                                            <div class="inside">
                                                                <span>
                                                                    Apply Now
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
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
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() . '/_js/konva.min.js'?>"></script>
<script type="text/javascript">
    var select_model_id ='';
    var current_tab ='';
    var custom_exterior = <?php echo json_encode($custom_exterior); ?>;
    var custom_floorplan = <?php echo json_encode($custom_floorplan); ?>;
    var dealers = <?php echo json_encode($dealers); ?>;
    var primary_prices = <?php echo json_encode($primary_prices); ?>

    var custom_order = {
        customer: {},
        dealer:{},
        finance:{},
        caravan : '',
        caravan_options :{},
        accessories:{},
        floorplan: ''
    };
    jQuery(document).ready(function($)
    {

        actionsListener();

        document.getElementById($('li.current a.tablinks').attr('tab-content')).style.display = "block";


        $('a.tablinks ').click(function(event){

            event.preventDefault();


            if($(this).parent('li').hasClass('next'))
            {
                $(this).parent('li').removeClass('next');
            }
            else if($(this).parent('li').hasClass('complete'))
            {
                $(this).parent('li').removeClass('complete');
            }
            else
            {
                return;
            }

            $('a.tablinks').parent('li').removeClass('current');
            $(this).parent('li').nextAll().removeClass('next');
            $(this).parent('li').nextAll().removeClass('complete');

            $(this).parent('li').addClass('current');
            $('div.tabcontent').hide();

            if(current_tab !== $(this).attr('tab-content'))
            {

                $(this).parent('li').prevAll().addClass('complete');
                $(this).parent('li').next().addClass('next');


                current_tab = $(this).attr('tab-content');
                $('#' + current_tab + '.tabcontent').show();
            }

            renderCustomOptions($(this).attr('tab-content'));
            renderDisplayImageWrapper($(this).attr('tab-content'));

        });


        function renderCustomOptions(tab)
        {
            switch(tab)
            {
                case 'exterior' :

                    var options = custom_order.caravan_options;

                    for (var i = 0; i < custom_exterior[select_model_id].length; i++)
                    {
                        if(custom_exterior[select_model_id][i]['custom_option'] == 'composite panel')
                        {
                            $('select#composite_panel').html('');
                            var custom_options_value =custom_exterior[select_model_id][i]['option_value'];

                            for(var e  = 0;e < custom_options_value.length; e++ )
                            {
                                var el = '<option value="'+ custom_options_value[e].value  +'">' + custom_options_value[e].value + '</option>';

                                $('select#composite_panel').append(el);
                            }
                            if(typeof options.panel != 'undefined')
                            {
                                $('select#composite_panel').val(options.panel);
                            }
                            custom_order.caravan_options.panel =  $('select#composite_panel').val();

                        }
                        if(custom_exterior[select_model_id][i]['custom_option'] == 'checker plate')
                        {
                            $('select#checker_plate').html('');

                            var custom_options_value = custom_exterior[select_model_id][i]['option_value'];
                            for(var e = 0;e < custom_options_value.length; e++ )
                            {
                                var el = '  <option value="'+ custom_options_value[e].value  +'">' + custom_options_value[e].value + '</option>';
                                $('select#checker_plate').append(el);
                            }
                            if(typeof options.checker_plate != 'undefined')
                            {
                                $('select#checker_plate').val(options.checker_plate);
                            }
                            custom_order.caravan_options.checker_plate  = $('select#checker_plate').val();;
                        }
                    }
                    break;
                default:
                    //do nothing is gold
            }
        }

        function renderDisplayImageWrapper(tab)
        {
            switch(tab)
            {
                case 'exterior' :

                    exteriorRenderImageWrapper();

                    break;
                case 'floorplan' :
                    if(!Array.isArray( custom_floorplan[select_model_id]))
                    {
                        var el = '<div class="item col-md-12 text-center selected" floorplan="default"><img src="' +  custom_floorplan[select_model_id]  +'" style="width:80%" />';
                            el += '<div class="item-details"><div class="details"><h3> Default Floor Plan </h3></div></div></div>';
                            $('#floorplan .option-display-image-wrapper').html(el);
                            custom_order.floorplan = 'default';
                    }
                    else
                    {
                        $('#floorplan .option-display-image-wrapper').html('');
                    }

                    break;
                default:
                //do nothing is gold
            }
        }

        function actionsListener()
        {
            $('#models .model-list .item').click(function (e)
            {
                $('#models .model-list .item').removeClass('selected');
                $(this).addClass('selected');
                select_model_id = $(this).attr('select-model');
                custom_order.caravan = select_model_id;

                //we can go to next tab when we complete this tab
                $('a.tablinks[tab-content="models"]').parent('li').next().addClass('next');

                $('a.tablinks[tab-content="exterior"]').click();
            });


            $('#exterior select').change(function(e){

                var composite_panel_select =  $('select#composite_panel').val();
                var checker_plate_select =  $('select#checker_plate').val();
                custom_order.caravan_options = {panel : composite_panel_select, checker_plate : checker_plate_select };

                exteriorRenderImageWrapper();

            });


            $('#floorplan .floorplan-list').on('click','.item',function(e)
            {

                $('#floorplan .floorplan-list .item').removeClass('selected');
                $(this).addClass('selected');
                custom_order.floorplan =  $(this).attr('floorplan');

                //we can go to next tab when we complete this tab
                $('a.tablinks[tab-content="floorplan"]').parent('li').next().addClass('next');
                $('a.tablinks[tab-content="accessories"]').click();

            });

            $( window ).resize(function(){
                exteriorRenderImageWrapper();

            });

            $('form#customer_details_form').submit(function(event){
                if($('select#payment_method').val() == 'cash')
                {
                    custom_order.finance.option = 'none';
                    submitCustomOrder();
                }
                else
                {
                    $('div#enquiry .custom-options-form ').hide();
                    $('div#enquiry .apply-finance-company ').show();
                }

                return false;
            });


            $('form#customer_details_form select#customer_state').change(function(e)
            {

                var el = '<option selected value="">Choose Dealer</option>';
                var count = 0;
                for(var i = 0 ; i < dealers.length; i++ )
                {
                    var state = dealers[i]['sl_state'];
                    if(state.toLowerCase() == $(this).val() )
                    {
                        el += '<option value="' + dealers[i]['sl_id']   + '" dealers_name=" ' + dealers[i]['sl_store']  + '"  >' + dealers[i]['sl_store']  +  ' </option>';

                        count++;
                    }

                }

                if(count != 0)
                {
                    $('form#customer_details_form select#dealer_name').removeAttr("disabled");
                }
                else
                {
                    $('form#customer_details_form select#dealer_name').attr("disabled",true);
                }

                $('form#customer_details_form select#dealer_name').html(el);

            });

            $('.tabcontent button.btn-next').click(function(e)
            {
                event.preventDefault();

                var next_tabcontent = $(this).parent().parent().parent('div.tabcontent').next().attr('id');

                if(typeof next_tabcontent != 'undefined')
                {
                    $("a.tablinks[tab-content='" + next_tabcontent + "']" ).click();
                }
            });

            $('.tabcontent button.btn-pre').click(function(e)
            {
                event.preventDefault();
                var prev_tabcontent = $(this).parent().parent().parent('div.tabcontent').prev().attr('id');

                if(typeof prev_tabcontent != 'undefined')
                {
                    $("a.tablinks[tab-content='" + prev_tabcontent +  "']").click();
                }
            });
            $("form#customer_details_form select#payment_method").change(function(e)
            {

                finance_section_display();
                $(".finance-section-details").hide();
                if('cash' == $(this).val() )
                {
                    $(".finance-section-details.cash-summary").show()
                    $(".finance-section .finance-header").html('Cash Detail');
                }
                if('loan' == $(this).val())
                {
                    $(".finance-section-details.loan-summary").show();
                    $(".finance-section .finance-header").html('Loan Detail');
                }
            });

            $(".finance-section-details.loan-summary input").change(function(e){

                finance_section_display();

            });
            $(".finance-section-details.loan-summary input[type=text]").on('keyup', function (e) {

                finance_section_display();

                var $this = $( this );
                var raw_input = $this.val();

                var input = raw_input.replace(/[\D\s\._\-]+/g, "");

                input = input ? parseInt( input, 10 ) : 0;
                if(!$this.hasClass('interest-rate'))
                {
                    $this.val( function() {
                        return ( input === 0 ) ? "" : input.toLocaleString( "en-US" );
                    } );
                }
            });

            $(".apply-finance-company div.outside").click(function(e)
            {

                $(".apply-finance-company div.outside").removeClass('active');
                $(this).addClass('active');
                custom_order.finance.option =  $(this).attr('value');

                $(".apply-finance-company p.description span").hide();
                $(".apply-finance-company p.description span" + "." + $(this).attr('id')).show();
            });

            $(".apply-finance-company div#apply_button").click(function(e)
            {

                submitCustomOrder();

            });

        }


        function exteriorRenderImageWrapper()
        {
            var options = custom_order.caravan_options;
            var image_name = 'default';
            if(typeof options.panel != 'undefined' && typeof options.checker_plate != 'undefined')
            {
                image_name = options.panel + '_' + options.checker_plate;
            }

            var image_wrapper_width = $('#exterior .option-display-image-wrapper').width();

            var exteriorImageWrapper = new Konva.Stage({
                container: 'exterior-display-image-wrapper',
                width: image_wrapper_width
            });


            var layer = new Konva.Layer();
            var caravan = new Konva.Image();

            var imageObj = new Image();
            imageObj.src = '<?php echo $uploads['baseurl'] . '/custom_order/'; ?>/' + select_model_id  + '/' + image_name + '.png';
            imageObj.onload = function ()
            {
                var image_width = image_wrapper_width;
                caravan.setImage(imageObj);
                caravan.setWidth(image_width);
                caravan.setHeight(image_width * imageObj.height / imageObj.width);

                // add the shape to the layer
                layer.add(caravan);
                // add the layer to the stage
                exteriorImageWrapper.add(layer);
                // set height of stage canvas
                exteriorImageWrapper.setHeight(caravan.getHeight());
            };
        }


        function submitCustomOrder()
        {

            custom_order.customer.customer_name = $('input#customer_name').val();
            custom_order.customer.customer_address= $('input#customer_address').val();
            custom_order.customer.customer_postcode = $('input#customer_postcode').val();
            custom_order.customer.customer_state = $('select#customer_state').val();
            custom_order.customer.customer_phone = $('input#customer_phone').val();
            custom_order.customer.customer_email = $('input#customer_email').val();
            custom_order.customer.payment_method = $('select#payment_method').val();

            custom_order.dealer.dealer_id = $('select#dealer_name').val();
            custom_order.dealer.dealer_name = $('select#dealer_name option:selected').attr('dealers_name');
            var selected_dealer_id = custom_order.dealer.dealer_id;
            for(var i= 0; i < dealers.length; i++)
            {
                if(dealers[i]['sl_id'] == selected_dealer_id)
                {
                    custom_order.dealer.dealer_phone = dealers[i]['sl_phone'];
                    custom_order.dealer.dealer_email = dealers[i]['sl_email'];
                    custom_order.dealer.dealer_address = dealers[i]['sl_address'];
                    custom_order.dealer.dealer_city = dealers[i]['sl_city'];
                    custom_order.dealer.dealer_state = dealers[i]['sl_state'];
                    custom_order.dealer.dealer_postcode = dealers[i]['sl_zip'];
                }
            }
            var data = {
                'action':'submit_customorder',
                'custom_order' : custom_order

            };
            var url = "<?php echo site_url() ?>/wp-admin/admin-ajax.php";
            //loading the caravan detail before open panel
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                beforeSend: function()
                {
                    $('.custom-quote-section .option-select-value-section  #enquiry .feedback-notice-messages .alert').hide();
                    $('#enquiry input#submit_order').attr("disabled", true);
                    $('#enquiry input#submit_order').attr('value','Loading....');

                },
                success:function(data)
                {
                    if (data == true)
                    {
                        $('.custom-quote-section .option-select-value-section  #enquiry .feedback-notice-messages .alert.alert-success').show();
                        $('#enquiry input#submit_order').attr('value', 'Complete');

                        //refresh page after successfully submit quote to system
                        setTimeout(function ()
                        {
                            location = ''
                        }, 3000);
                    }
                    else
                     {
                        $('.custom-quote-section .option-select-value-section  #enquiry .feedback-notice-messages .alert.alert-danger').show();
                        $('#enquiry input#submit_order').attr('value', 'Submit Quote');
                        $('#enquiry input#submit_order').removeAttr("disabled");
                    }
                }
            });
        }


        function finance_section_display()
        {
            var caravan_price = primary_prices;

            var accessories_prices = 0 ;

            var total_price =  Number(caravan_price[select_model_id]) + accessories_prices;

            $(".finance-section-details.loan-summary input.drive-away-price").val("$" + total_price.toLocaleString( "en-US" ) + " + ORC");



            $(".finance-section-details.cash-summary h2.primary-price").html("$" + Number(caravan_price[select_model_id]).toLocaleString( "en-US" ) + " + ORC");


            $(".finance-section-details.cash-summary h2.total-price").html('$' +  total_price.toLocaleString( "en-US" ) + " + ORC");


            var loan =  Number($(".finance-section-details.loan-summary input.loan-amount").val().replace(/[\D\s\._\-]+/g, ""));
            var rate =  Number($(".finance-section-details.loan-summary input.interest-rate").val());
            var terms = Number($(".finance-section-details.loan-summary input.loan-terms").val().replace(/[\D\s\._\-]+/g, ""));
            var balloon = Number($(".finance-section-details.loan-summary input.balloon-amount").val().replace(/[\D\s\._\-]+/g, ""));

            var monthly_payment = 0;
            var factor = rate / 1200;

            if(balloon === 0 )
            {
                if(terms !== 0 && loan !== 0 && rate !== 0)
                {
                    monthly_payment = loan * factor / (1 - (Math.pow(1/(1 + factor), terms)));
                }
            }
            else
            {
                if(terms !== 0 && loan !== 0 && rate !== 0)
                {
                    var step_1 = loan * (factor * Math.pow(1+factor,terms) / (Math.pow(1+factor,terms) - 1) );
                    var step_2 =  balloon *  ( factor / (  Math.pow(1+factor,terms) - 1) );

                    monthly_payment = step_1 - step_2;

                }
            }
            $(".finance-section-details.loan-summary .monthly-payment-sec span.mp-amount").html(monthly_payment.toFixed(2));


        }
    });
</script>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/_css/steps/style.css'?>" >

<?php
get_footer();
?>



