<?php

$user_role = $this->session->userdata('user_role');

$quote_id = array(
    'quote_id' => $quote['quote_id']
);

$custom_options = unserialize($quote['custom_options']);
$add_on_accessories = unserialize($quote['add_on_accessories']);


if($quote['status'] == 'In Order')
{
    $quote_status = array(
        'name' => 'status',
        'id'  => 'status',
        'value' => $quote['status'],
        'class' => 'form-control',
        'options' => array(
            'In Progress'  => 'In Progress',
            'In Review'  => 'In Review',
            'In Order'  => 'In Order',
            'In Cancel'  => 'In Cancel'
        ),
        'selected' => array($quote['status']),
        'readonly' => 'readonly'
    );

}
else
{
        $quote_status = array(
            'name' => 'status',
            'id'  => 'status',
            'value' => $quote['status'],
            'class' => 'form-control',
            'options' => array(
                'In Progress'  => 'In Progress',
                'In Review'  => 'In Review',
                'In Cancel'  => 'In Cancel'
            ),
            'selected' => array($quote['status']),
        );
}


$firstName = array(
    'name' => 'customer_first_name',
    'type' => 'text',
    'id'  => 'customer_first_name',
    'value' => $quote['customer_first_name'],
    'class' => 'form-control',
    ($user_role == 'admin') ? '' : 'readonly' => 'readonly',
    'required' => true,
);

$lastName = array(
    'name' => 'customer_last_name',
    'type' => 'text',
    'id'  => 'customer_last_name',
    'value' => $quote['customer_last_name'],
    'class' => 'form-control',
    ($user_role == 'admin') ? '' : 'readonly' => 'readonly',
    'required' => true
);

$customer_address = array(
    'name' => 'customer_address',
    'type' => 'text',
    'id'  => 'customer_address',
    'value' => $quote['customer_address'],
    'class' => 'form-control',
    ($user_role == 'admin') ? '' : 'readonly' => 'readonly',
    'required' => true
);

$customer_postcode = array(
    'name' => 'customer_postcode',
    'type' => 'text',
    'id'  => 'customer_postcode',
    'value' => $quote['customer_postcode'],
    'class' => 'form-control',
    ($user_role == 'admin') ? '' : 'readonly' => 'readonly',
    'required' => true
);

$customer_state = array(
    'name' => 'customer_state',
    'type' => 'text',
    'id'  => 'customer_state',
    'value' => strtoupper($quote['customer_state']),
    'class' => 'form-control',
    ($user_role == 'admin') ? '' : 'readonly' => 'readonly',
    'required' => true
);

$customer_email = array(
    'name' => 'customer_email',
    'type' => 'email',
    'id'  => 'customer_email',
    'value' => $quote['customer_email'],
    'class' => 'form-control',
    ($user_role == 'admin') ? '' : 'readonly' => 'readonly',
    'required' => true
);

$customer_phone = array(
    'name' => 'customer_phone',
    'type' => 'text',
    'id'  => 'customer_phone',
    'value' => $quote['customer_phone'],
    'class' => 'form-control',
    ($user_role == 'admin') ? '' : 'readonly' => 'readonly',
    'required' => true
);

$product_name = array(
    'name' => 'product_name',
    'type' => 'text',
    'id'  => 'product_name',
    'value' => $quote['product_name'],
    'class' => 'form-control',
    ($user_role == 'admin') ? '' : 'readonly' => 'readonly',
    'required' => true
);


$updateQuoteButton = array(
    'name'=> 'updateQuote-btn',
    'value' => 'Update',
    'class' => 'btn btn-lg btn-success',
    'type'  => 'button',
    'content' => 'Place Order',
    'data-toggle' => "modal",
    'data-target' => "#update-quote-confirm-modal"

);
$updateQuote = array(
    'name'=> 'updateQuote',
    'value' => 'Update',
    'class' => 'btn btn-lg btn-success',
    'type'  => 'submit'

);
$placeorderButton = array(
    'name'=> 'placeOrder-btn',
    'value' => 'Place Order',
    'class' => 'btn btn-lg btn-primary',
    'type'  => 'button',
    'content' => 'Place Order',
    'data-toggle' => "modal",
    'data-target' => "#place-order-confirm-modal"
);

$placeorder = array(
    'name'=> 'placeOrder',
    'value' => 'Place Order',
    'class' => 'btn btn-lg btn-primary',
    'type'  => 'submit',
    'content' => 'Place Order',
);


?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quote Detail</h1>
</div>



<div class="fluid-container">
    <div class="row">
        <div class="col-md-12 text-left">

            <?php echo form_open('quote/update'); ?>

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
                    <?php echo form_label('Quote Status', 'status'); ?>
                    <?php echo form_dropdown($quote_status); ?>
                    <?php echo '<div class="errors">'.form_error('$quote_status').'</div>'; ?>
                </div>
            </div>

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
                    <?php echo form_input($product_name); ?>
                    <?php echo '<div class="errors">'.form_error('$product_name').'</div>'; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <?php echo form_label('Custom Options', 'custom_options'); ?>
                    <ul class="list-group">
                        <?php foreach($custom_options as $key => $value ): ?>
                            <li class="list-group-item">
                                <span class="font-weight-bold text-capitalize"><?php echo preg_replace('/[^A-Za-z0-9\-]/', ' ', $key) . ':'; ?> </span>
                                <span class="text-capitalize"><?php echo $value; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <?php if(sizeof($add_on_accessories) > 0): ?>
                <div class="row">
                    <div class="col-12">
                        <?php echo form_label('Add On Options', 'add_on_options'); ?>
                        <ul class="list-group">
                            <?php foreach($add_on_accessories as $option): ?>
                                <li class="list-group-item">
                                    <span class="font-weight-bold text-capitalize"><?php echo $option['label'] . ':'; ?> </span><br/>
                                    <span class="text-capitalize"><?php echo 'Retail Price: $' . $option['retail_price']; ?></span><br/>
                                    <span class="text-capitalize"><?php echo 'Whole Sale Price: $' . $option['wholesale_price']; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row finance-row">
                <div class="col-6">
                    <fieldset class="total-price-section">
                        <legend class="header">Order Total Estimate</legend>
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

                <div class="col-6">
                    <fieldset class="finance-section">
                        <legend class="header">Payment Information</legend>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span class="font-weight-bold text-capitalize">Payment Method: </span>
                                <span class="text-capitalize"><?php echo $quote['payment_method']; ?></span>
                            </li>
                        </ul>
                    </fieldset>
                </div>
            </div>



            <?php echo form_hidden($quote_id); ?>

            <?php if($quote['status'] != 'In Order'): ?>
            <div class="row quote-buttons">
                <div class="col-12 text-right">
                    <?php echo form_submit($updateQuoteButton);  ?>
                    <?php echo ($user_role == 'admin') ? form_button($placeorderButton) : '' ;  ?>
                </div>
            </div>
            <?php endif; ?>


            <!-- Modal -->
            <div class="modal fade" id="place-order-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="place-order-confirm-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="place-order-confirm-modal-title">Order Submit</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to place this quote to order?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">No</button>
                           <?php echo form_submit($placeorder); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="update-quote-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="update-quote-confirm-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="update-quote-confirm-modal-title">Update Quote</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to update this quote detail ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">No</button>
                            <?php echo form_submit($updateQuote); ?>
                        </div>
                    </div>
                </div>
            </div>


            <?php echo form_close(); ?>

        </div>
    </div>

</div>
