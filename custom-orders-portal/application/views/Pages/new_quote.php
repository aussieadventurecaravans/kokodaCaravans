<?php

$user_role = $this->session->userdata('user_role');

$firstName = array(
    'name' => 'customer_first_name',
    'type' => 'text',
    'id'  => 'customer_first_name',
    'value' => '',
    'class' => 'form-control',
    'required' => true,
);

$lastName = array(
    'name' => 'customer_last_name',
    'type' => 'text',
    'id'  => 'customer_last_name',
    'value' => '',
    'class' => 'form-control',
    'required' => true
);

$customer_address = array(
    'name' => 'customer_address',
    'type' => 'text',
    'id'  => 'customer_address',
    'value' => '',
    'class' => 'form-control',
    'required' => true
);

$customer_city = array(
    'name' => 'customer_city',
    'type' => 'text',
    'id'  => 'customer_city',
    'value' => '',
    'class' => 'form-control',
    'required' => true
);

$customer_postcode = array(
    'name' => 'customer_postcode',
    'type' => 'text',
    'id'  => 'customer_postcode',
    'value' => '',
    'class' => 'form-control',
    'required' => true
);

$customer_state = array(
    'name' => 'customer_state',
    'type' => 'text',
    'id'  => 'customer_state',
    'value' => '',
    'class' => 'form-control',
    'required' => true
);

$customer_email = array(
    'name' => 'customer_email',
    'type' => 'email',
    'id'  => 'customer_email',
    'value' => '',
    'class' => 'form-control',
    'required' => true
);

$customer_phone = array(
    'name' => 'customer_phone',
    'type' => 'text',
    'id'  => 'customer_phone',
    'value' => '',
    'class' => 'form-control',
    'required' => true
);

$product_name = array(
    'name' => 'product_name',
    'type' => 'text',
    'id'  => 'product_name',
    'value' => '',
    'options' => $products_title,
    'class' => 'form-control',
    'required' => true
);

/*$product_custom_options = array(
    'name' => 'product_name',
    'type' => 'text',
    'id'  => 'product_name',
    'value' => '',
    'options' => $custom_options,
    'class' => 'form-control',
    'required' => true
);*/


$payment_options = array(
        'cash' => 'Cash',
        'loan' => 'Loan'
);

$payment_method = array(
    'name' => 'payment_method',
    'type' => 'text',
    'id'  => 'payment_method',
    'value' => '',
    'options' => $payment_options,
    'class' => 'form-control',
    'required' => true
);

$submitQuoteButton = array(
    'name'=> 'submitQuote-btn',
    'value' => 'Submit Quote',
    'class' => 'btn btn-lg btn-success',
    'type'  => 'button',
    'content' => 'Submit Quote',
    'data-toggle' => "modal",
    'data-target' => "#submit-quote-confirm-modal"

);
$submitQuote = array(
    'name'=> 'submitQuote',
    'value' => 'Submit Quote',
    'class' => 'btn btn-lg btn-success',
    'type'  => 'submit',
    'data-toggle' => "modal",
);


?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create New Quote</h1>
</div>



<div class="fluid-container">
    <div class="row">
        <div class="col-md-12 text-left">

            <?php echo form_open('quote/insert','id="new_quote_form"'); ?>

            <?php
            $success_msg = $this->session->flashdata('success_msg');
            $error_msg = $this->session->flashdata('error_msg');
            if($success_msg):  ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-success">
                            <?php echo $success_msg; ?>
                        </div>
                    </div>
                </div>
                <?php
            endif;
            if($error_msg): ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-danger">
                        <?php echo $error_msg; ?>
                    </div>
                </div>
            </div>
            <?php  endif;  ?>


            <div class="row form-row form-group required">
                <div class="col-sm-6 col-12">
                    <?php echo form_label('First Name', 'customer_first_name'); ?>
                    <?php echo form_input($firstName); ?>
                    <?php echo '<div class="errors">'.form_error('$firstName').'</div>'; ?>
                </div>
                <div class="col-sm-6 col-12">
                    <?php echo form_label('Last Name', 'customer_last_name') . form_input($lastName); ?>
                    <?php echo '<div class="errors">'.form_error('$lastName').'</div>'; ?>
                </div>
            </div>

            <div class="row form-row">
                <div class="col-4">
                    <?php echo form_label('Street Address', 'customer_address'); ?>
                    <?php echo form_input($customer_address); ?>
                    <?php echo '<div class="errors">'.form_error('$customer_address').'</div>'; ?>
                </div>
                <div class="col-2">
                    <?php echo form_label('City', 'customer_city'); ?>
                    <?php echo form_input($customer_city); ?>
                    <?php echo '<div class="errors">'.form_error('$customer_city').'</div>'; ?>
                </div>
                <div class="col-3">
                    <?php echo form_label('Postcode', 'customer_postcode'); ?>
                    <?php echo form_input($customer_postcode); ?>
                    <?php echo '<div class="errors">'.form_error('$customer_postcode').'</div>'; ?>
                </div>
                <div class="col-3">
                    <?php echo form_label('State', 'customer_state'); ?>
                    <?php echo form_input($customer_state); ?>
                    <?php echo '<div class="errors">'.form_error('$customer_state').'</div>'; ?>
                </div>
            </div>
            <div class="row form-row">
                <div class="col-12">
                    <?php echo form_label('Email', 'customer_email'); ?>
                    <?php echo form_input($customer_email); ?>
                    <?php echo '<div class="errors">'.form_error('$customer_email').'</div>'; ?>
                </div>
            </div>

            <div class="row form-row">
                <div class="col-12">
                    <?php echo form_label('Phone', 'customer_phone'); ?>
                    <?php echo form_input($customer_phone); ?>
                    <?php echo '<div class="errors">'.form_error('$customer_phone').'</div>'; ?>
                </div>
            </div>
            <div class="row form-row">
                <div class="col-12">
                    <?php echo form_label('Model', 'product_name'); ?>
                    <?php echo form_dropdown($product_name); ?>
                    <?php echo '<div class="errors">'.form_error('$product_name').'</div>'; ?>
                </div>
            </div>



            <div class="row form-row interior-custom-options">
                <div class="col-12">
                    <?php echo form_label('Interiors', 'interior_custom_options','class="font-weight-bold text-capitalize"'); ?>
                    <table class="table table-striped interior-list">
                        <tr>
                            <td colspan="3"><span class="text-capitalize">flooring</span></td>
                        </tr>
                        <tr class="options" option_id='flooring' option_name="flooring">
                            <td>
                                <input type="text" name="flooring_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="flooring_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="flooring_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Wall Ply</span></td>
                        </tr>
                        <tr class="options" option_id='wallply'  option_name="Wall Ply">
                            <td>
                                <input type="text" name="wallply_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="wallply_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="wallply_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Partition Ply</span></td>
                        </tr>
                        <tr class="options" option_id='partitionply' option_name="Partition Ply">
                            <td>
                                <input type="text" name="partitionply_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="partitionply_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="partitionply_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Ceiling Ply</span></td>
                        </tr>
                        <tr class="options" option_id='ceilingply' option_name="Ceiling Ply">
                            <td>
                                <input type="text" name="ceilingply_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="ceilingply_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="ceilingply_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Furniture Ply</span></td>
                        </tr>
                        <tr class="options" option_id='furnitureply' option_name="Furniture Ply">
                            <td>
                                <input type="text" name="furnitureply_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="furnitureply_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="furnitureply_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Doors Top</span></td>
                        </tr>
                        <tr class="options" option_id='doorstop' option_name="Doors Top">
                            <td>
                                <input type="text" name="doorstop_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="doorstop_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="doorstop_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Doors Bottom</span></td>
                        </tr>
                        <tr class="options" option_id='doorsbottom' option_name="Doors Bottom">
                            <td>
                                <input type="text" name="doorsbottom_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="doorsbottom_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="doorsbottom_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Bench Tops</span></td>
                        </tr>
                        <tr class="options" option_id='benchtop' option_name="Bench Tops">
                            <td>
                                <input type="text" name="benchtop_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="benchtop_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="benchtop_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Splash Backs</span></td>
                        </tr>
                        <tr class="options" option_id='splashbacks' option_name="Splash Backs">
                            <td>
                                <input type="text" name="splashbacks_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="splashbacks_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="splashbacks_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Fridge Doors</span></td>
                        </tr>
                        <tr class="options" option_id='fridgedoors' option_name="Fridge Doors">
                            <td>
                                <input type="text" name="fridgedoors_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="fridgedoors_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="fridgedoors_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Door Handles</span></td>
                        </tr>
                        <tr class="options" option_id='doorhandles' option_name="Door Handles">
                            <td>
                                <input type="text" name="doorhandles_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="doorhandles_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="doorhandles_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row form-row exterior-custom-options">
                <div class="col-12">
                    <?php echo form_label('Exterior Options', 'exterior_custom_options','class="font-weight-bold text-capitalize"'); ?>
                    <table class="table table-bordered exterior-list">
                        <tr>
                            <td>
                                <span class="text-capitalize">Exterior</span>
                            </td>
                            <td>
                                <span class="text-capitalize">Selections</span>
                            </td>
                        </tr>
                        <tbody class="options">

                        </tbody>
                    </table>

                </div>
            </div>

            <div class="row form-row upholstery-custom-options">
                <div class="col-12">
                    <?php echo form_label('Upholstery', 'upholstery_options','class="font-weight-bold text-capitalize"'); ?>
                    <table class="table table-striped upholstery-list">
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Seat Top Scroll</span></td>
                        </tr>
                        <tr class='options' option_id='seattopscroll' option_name="Seat Top Scroll">
                            <td>
                                <input type="text" name="seattopscroll_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="seattopscroll_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="seattopscroll_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Seat Bottom Scroll</span></td>
                        </tr>
                        <tr class='options' option_id='seatbottomscroll' option_name="Seat Bottom Scroll">
                            <td>
                                <input type="text" name="seatbottomscroll_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="seatbottomscroll_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="seatbottomscroll_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Back Top Scroll</span></td>
                        </tr>
                        <tr class='options' option_id='backtopscroll' option_name="Back Top Scroll">
                            <td>
                                <input type="text" name="backtopscroll_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="backtopscroll_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="backtopscroll_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Back Bottom Scroll</span></td>
                        </tr>
                        <tr class='options' option_id='backbottomscroll' option_name="Back Bottom Scroll">
                            <td>
                                <input type="text" name="backbottomscroll_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="backbottomscroll_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="backbottomscroll_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Magazine Pocket</span></td>
                        </tr>
                        <tr class='options' option_id='magazinepocket' option_name="Magazine Pocket">
                            <td>
                                <input type="text" name="magazinepocket_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="magazinepocket_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="magazinepocket_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Bed Head</span></td>
                        </tr>
                        <tr class='options' option_id='bedhead' option_name="Bed Head">
                            <td>
                                <input type="text" name="bedhead_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="bedhead_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="bedhead_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="text-capitalize">Door Curtain</span></td>
                        </tr>
                        <tr class='options' option_id='doorcurtain' option_name="Door Curtain">
                            <td>
                                <input type="text" name="doorcurtain_brand" placeholder="Brand of Sample" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="doorcurtain_name" placeholder="Name of Sample" class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name="doorcurtain_code" placeholder="Code of Sample" class="form-control"/>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row form-row add-on-options">
                <div class="col-12">
                    <?php echo form_label('Add-on Extra', 'accessories_options','class="font-weight-bold text-capitalize"'); ?>
                    <table class="table table-striped add-on-list">
                        <thead>
                            <tr>
                                <td>Extras</td>
                                <td>Sku</td>
                                <td>Qty</td>
                                <td>Cost</td>
                                <td>Retail Price</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody class="add-on-options">
                            <tr>
                                <td>
                                    <input type="text" name="label" class="form-control" />
                                </td>
                                <td>
                                    <input type="text" name="sku" class="form-control"/>
                                </td>
                                <td>
                                    <input type="text" name="qty" class="form-control"/>
                                </td>
                                <td>
                                    <input type="text" name="wholesale_price" class="form-control"/>
                                </td>
                                <td>
                                    <input type="text" name="retail_price" class="form-control"/>
                                </td>
                                <td>

                                </td>
                            </tr>
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="6">
                                    <input type="button" class="btn btn-lg btn-success" id="addextra" value="Add +" />
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>


            <div class="row finance-row">
                <div class="col-12">
                    <fieldset class="total-price-section">
                        <legend class="header">Order Total</legend>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span class="font-weight-bold text-capitalize">Model Price:</span>
                                <span class="text-capitalize price-value"><?php echo ' $ ' . number_format($quote['product_cost']); ?></span>
                            </li>
                            <li class="list-group-item">
                                <span class="font-weight-bold text-capitalize">Accessories Price:</span>
                                <span class="text-capitalize price-value"><?php echo ' $ ' . number_format($quote['add_on_cost']); ?></span>
                            </li>
                        </ul>
                        <hr/>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span class="font-weight-bold text-capitalize">Total Cost:</span>
                                <span class="text-capitalize price-value"><?php echo ' $ ' . number_format($quote['total_cost']) ; ?></span>
                            </li>
                        </ul>
                    </fieldset>
                </div>
            </div>



            <?php echo form_hidden($quote_id); ?>

            <?php if($quote['status'] != 'In Order'): ?>
            <div class="row quote-buttons">
                <div class="col-12 text-right">
                    <?php echo form_submit($submitQuoteButton);  ?>
                </div>
            </div>
            <?php endif; ?>



            <!-- Modal -->
            <div class="modal fade" id="submit-quote-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="submit-quote-confirm-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="submit-quote-confirm-modal-title">Update Quote</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to submit this quote for order ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">No</button>
                            <?php echo form_submit($submitQuote); ?>
                        </div>
                    </div>
                </div>
            </div>


            <?php echo form_close(); ?>

        </div>
    </div>

</div>
<script type="text/javascript">

    //pass the php parameter to javascript global variable
    var $_global_exterior = <?php echo json_encode($exterior_custom_options); ?>;
    var quote_data = {};
$(document).ready(function () {
    var counter = 0;


    $("#addextra").on("click", function () {

        var newRow = $("<tr>");
        var cols = "";

        cols += '<td><input type="text" class="form-control" name="label' + counter + '"/></td>';
        cols += '<td><input type="text" class="form-control" name="sku' + counter + '"/></td>';
        cols += '<td><input type="text" class="form-control" name="qty' + counter + '"/></td>';
        cols += '<td><input type="text" class="form-control" name="wholesale_price' + counter + '"/></td>';
        cols += '<td><input type="text" class="form-control" name="retail_price' + counter + '"/></td>';

        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        newRow.append(cols);
        $("table.add-on-list").append(newRow);
        counter++;
    });



    $("table.add-on-list").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        counter -= 1
    });


    $('form#new_quote_form').submit(function()
    {

        $('#submit-quote-confirm-modal').modal('toggle'); //or  $('#submit-quote-confirm-modal').modal('hide');

        collectCustomerDetail()

        collectExteriorOptions();

        collectInteriorOptions();

        collectUpholsteryOptions();

        collectAddOnAccessories();

        submitNewQuote();

        return false;
    });

    function submitNewQuote()
    {

        var data =
            {
                'action': 'submit_custom_quote',
                'quote_data': quote_data
            };
        var url = "<?php echo base_url('quote/insert'); ?>";

        $.ajax({
            url: url,
            data: data,
            type: "POST",
            beforeSend: function ()
            {


            }
        }).done(function (data)
        {

            console.log(JSON.parse(data));
        });
    }


    function collectCustomerDetail()
    {
        var customer = {
            'customer_first_name': $('input#customer_first_name').val(),
            'customer_last_name' : $('input#customer_last_name').val(),
            'customer_address' : $('input#customer_address').val(),
            'customer_city' : $('input#customer_city').val(),
            'customer_postcode' : $('input#customer_postcode').val(),
            'customer_state' : $('input#customer_state').val(),
            'customer_email' : $('input#customer_email').val(),
            'customer_phone' : $('input#customer_phone').val()
        };

        quote_data.customer = customer;

        quote_data.product_name =  $("form#new_quote_form select#product_name").find(":selected").text();

        quote_data.product_id =  $("form#new_quote_form select#product_name").val();

    }

    function collectExteriorOptions()
    {

        var TableData = new Array();

        $('table.exterior-list tbody.options tr').each(function(row, tr)
        {

            var value = $(tr).find('td:eq(1) select.custom_exterior_select option:selected').val().split('-');

            TableData[row]={
                "option_name" : $(tr).find('td:eq(0)').text(),
                "value" :   value[0],
                "price" :   value[1],
            };
        });

        quote_data.exterior_options = TableData;

    }


    function collectInteriorOptions()
    {

        var TableData = new Array();

        $('table.interior-list tr.options').each(function(row, tr)
        {

            TableData[row]={
                "option_name" :  $(tr).attr('option_name'),
                "option_id" : $(tr).attr('option_id'),
                "brand" : $(tr).find('td:eq(0) input[type=text]').val(),
                "name" : $(tr).find('td:eq(1) input[type=text]').val(),
                "code" : $(tr).find('td:eq(2) input[type=text]').val()
            };
        });

        quote_data.interior_options = TableData;

    }


    function collectUpholsteryOptions()
    {

        var TableData = new Array();

        $('table.upholstery-list tr.options').each(function(row, tr)
        {

            TableData[row]={
                "option_name" :  $(tr).attr('option_name'),
                "option_id" : $(tr).attr('option_id'),
                "brand" : $(tr).find('td:eq(0) input[type=text]').val(),
                "name" : $(tr).find('td:eq(1) input[type=text]').val(),
                "code" : $(tr).find('td:eq(2) input[type=text]').val()
            };
        });

        quote_data.upholstery_options = TableData;

    }

    function collectAddOnAccessories()
    {

        var TableData = new Array();

        $('table.add-on-list tbody.add-on-options tr').each(function(row, tr)
        {
            if($(tr).find('td:eq(0) input[type=text]').val() !== '')
            {
                TableData[row]={
                    "label" : $(tr).find('td:eq(0) input[type=text]').val() ,
                    "sku" : $(tr).find('td:eq(1) input[type=text]').val(),
                    "qty" : $(tr).find('td:eq(2) input[type=text]').val(),
                    "wholesale_price" : $(tr).find('td:eq(3) input[type=text]').val(),
                    "retail_price" : $(tr).find('td:eq(4) input[type=text]').val()
                };
            }
        });

        quote_data.add_on_accessories = TableData;

    }

});


</script>
