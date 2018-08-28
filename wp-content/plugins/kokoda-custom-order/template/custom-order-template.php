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
//Custom Exterior of all Models
$caravans  =  get_posts($args);
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
    <div class="container">
        <div class="row option-select-header-section">
            <div class="col-sm-12">
               <nav>
                    <ol class="cd-breadcrumb triangle">
                        <li class="current"><a href="#" class="tablinks" tab-content="models">Models</a></li>
                        <li><a href="#" class="tablinks" tab-content="exterior" >Exterior</a></li>
                        <li><a href="#" class="tablinks" tab-content="floorplan">Floor Plan</a></li>
                        <li><a href="#" class="tablinks" tab-content="accessories">Accessories</a></li>
                        <li><a href="#" class="tablinks"  tab-content="review">Review</a></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row option-select-value-section">
            <div class="col-sm-12">
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

                <div id="review" class="tabcontent">
                    <div class="tab-header">
                        <h4>
                            Submit Your Details
                        </h4>
                    </div>
                    <div class="custom-options-form">
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

                        <form id="customer_details_form" class="form-horizontal" method="post">
                            <fieldset>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="customer_name">Full Name</label>
                                    <div class="col-md-5">
                                        <input id="customer_name" name="customer_name" type="text" placeholder="" class="form-control input-md" required autocomplete="off"/>

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="customer_address">Address</label>
                                    <div class="col-md-5">
                                        <input id="customer_address" name="customer_address" type="text" placeholder="" class="form-control input-md" required autocomplete="off" />

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="customer_postcode">Postcode</label>
                                    <div class="col-md-2">
                                        <input id="customer_postcode" type="number" name="customer_postcode" placeholder="" class="form-control input-md" maxlength="4" required autocomplete="off" />

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="customer_state">State</label>
                                    <div class="col-md-2">
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
                                    <label class="col-md-4 control-label" for="dealer_name">Dealers:</label>
                                    <div class="col-md-2">
                                        <select class="form-control input-lg" id="dealer_name" required disabled>
                                            <option selected value="">Choose Dealer</option>
                                        </select>
                                    </div>
                                </div>



                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="customer_phone">Phone</label>
                                    <div class="col-md-5">
                                        <input id="customer_phone" name="customer_phone" type="number" placeholder="" class="form-control input-md" required autocomplete="off" />
                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="customer_email">Email</label>
                                    <div class="col-md-5">
                                        <input id="customer_email" name="customer_email" type="email" placeholder="" class="form-control input-md" required autocomplete="off" />

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="payment_method">Payment Methods</label>
                                    <div class="col-md-2">
                                        <select class="form-control input-lg" id="payment_method" required>
                                            <option selected value="">Choose Payment</option>
                                            <option value="cash">Cash</option>
                                            <option value="loan">Loan</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Button -->
                                <div class="form-group">
                                    <div class="col-md-4">

                                    </div>
                                    <div class="col-md-5 text-right">
                                        <input id="reset_order" type="reset" class="btn btn-primary btn-lg" value="Reset Quote" />
                                        <input id="submit_order" type="submit" class="btn btn-primary btn-lg" value="Submit Quote" />
                                    </div>
                                    <div class="col-md-3">

                                    </div>
                                </div>

                            </fieldset>
                        </form>

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

    var custom_order = {
        customer: {},
        caravan : '',
        caravan_options :{},
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
                        var el = '<div class="item col-md-6 selected" floorplan="default"><img src="' +  custom_floorplan[select_model_id]  +'" style="width:100%" />';
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

                submitCustomOrder();
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

        }


        function exteriorRenderImageWrapper()
        {
            options = custom_order.caravan_options;
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

            custom_order.customer.customer_name= $('input#customer_name').val();
            custom_order.customer.customer_address= $('input#customer_address').val();
            custom_order.customer.customer_postcode = $('input#customer_postcode').val();
            custom_order.customer.customer_state = $('select#customer_state').val();
            custom_order.customer.customer_phone = $('input#customer_phone').val();
            custom_order.customer.customer_email = $('input#customer_email').val();
            custom_order.customer.payment_method = $('select#payment_method').val();

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
                    $('.custom-quote-section .option-select-value-section  #review .feedback-notice-messages .alert').hide();
                    $('#review input#submit_order').attr("disabled", true);
                    $('#review input#submit_order').attr('value','Loading....');

                },
                success:function(data)
                {
                    if (data == true)
                    {
                        $('.custom-quote-section .option-select-value-section  #review .feedback-notice-messages .alert.alert-success').show();
                        $('#review input#submit_order').attr('value', 'Complete');
                    }
                    else
                        {
                        $('.custom-quote-section .option-select-value-section  #review .feedback-notice-messages .alert.alert-danger').show();
                        $('#review input#submit_order').attr('value', 'Submit Quote');
                        $('#review input#submit_order').removeAttr("disabled");
                    }

                }
            });
        }
    });
</script>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/_css/steps/style.css'?>" >

<?php
get_footer();
?>



