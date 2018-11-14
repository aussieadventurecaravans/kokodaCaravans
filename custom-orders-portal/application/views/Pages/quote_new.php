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
    'name' => 'product_name',
    'type' => 'text',
    'id'  => 'product_name',
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
    'type'  => 'submit'

);


?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">New Quote</h1>
</div>



<div class="fluid-container">
    <div class="row">
        <div class="col-md-12 text-left">

            <?php echo form_open('quote/insert'); ?>

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


            <div class="row">
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

            <div class="row">
                <div class="col-6">
                    <?php echo form_label('Address', 'customer_address'); ?>
                    <?php echo form_input($customer_address); ?>
                    <?php echo '<div class="errors">'.form_error('$customer_address').'</div>'; ?>
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
            <div class="row">
                <div class="col-12">
                    <?php echo form_label('Email', 'customer_email'); ?>
                    <?php echo form_input($customer_email); ?>
                    <?php echo '<div class="errors">'.form_error('$customer_email').'</div>'; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <?php echo form_label('Phone', 'customer_phone'); ?>
                    <?php echo form_input($customer_phone); ?>
                    <?php echo '<div class="errors">'.form_error('$customer_phone').'</div>'; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <?php echo form_label('Model', 'product_name'); ?>
                    <?php echo form_dropdown($product_name); ?>
                    <?php echo '<div class="errors">'.form_error('$product_name').'</div>'; ?>
                </div>
            </div>

         <!--   <div class="row">
                <div class="col-12">
                    <?php /*echo form_label('Custom Options', 'custom_options'); */?>
                    <?php /*echo print_r($custom_options,true); */?>

                    <?php /*foreach($custom_options as $key => $value ): */?>

                    <?php /*endforeach; */?>
                </div>
            </div>-->

          <!--<?php /*if(is_array($add_on_accessories)):*/?>
                <?php /*if(sizeof($add_on_accessories) > 0): */?>
                    <div class="row">
                        <div class="col-12">
                            <?php /*echo form_label('Add On Accessories', 'add_on_options'); */?>
                            <ul class="list-group">
                                <?php /*foreach($add_on_accessories as $option): */?>
                                    <li class="list-group-item">
                                        <span class="font-weight-bold text-capitalize"><?php /*echo $option['label'] . ':'; */?> </span><br/>
                                        <span class="text-capitalize"><?php /*echo 'Retail Price: $' . $option['retail_price']; */?></span><br/>
                                        <span class="text-capitalize"><?php /*echo 'Whole Sale Price: $' . $option['wholesale_price']; */?></span>
                                    </li>
                                <?php /*endforeach; */?>
                            </ul>
                        </div>
                    </div>
                <?php /*endif; */?>
            --><?php /*endif; */?>

            <div class="row">
                <div class="col-12">
                    <fieldset class="finance-section">
                        <legend class="header">Payment Information</legend>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span class="font-weight-bold text-capitalize">Payment Method: </span>
                            </li>
                        </ul>
                        <?php echo form_dropdown($payment_method); ?>
                        <?php echo '<div class="errors">'.form_error('$product_name').'</div>'; ?>
                    </fieldset>
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
