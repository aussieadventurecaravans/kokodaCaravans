<?php

$user_role = $this->session->userdata('user_role');

$quote_id = array(
    'quote_id' => $quote['quote_id']
);

$custom_options = unserialize($quote['custom_options']);
$add_on_accessories = unserialize($quote['add_on_accessories']);

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
    'disabled' => 'true'
);

$firstName = array(
    'name' => 'customer_first_name',
    'type' => 'text',
    'id'  => 'customer_first_name',
    'value' => $quote['customer_first_name'],
    'class' => 'form-control',
    'readonly' => 'readonly',
    'required' => true,
);

$lastName = array(
    'name' => 'customer_last_name',
    'type' => 'text',
    'id'  => 'customer_last_name',
    'value' => $quote['customer_last_name'],
    'class' => 'form-control',
    'readonly' => 'readonly',
    'required' => true
);

$customer_address = array(
    'name' => 'customer_address',
    'type' => 'text',
    'id'  => 'customer_address',
    'value' => $quote['customer_address'],
    'class' => 'form-control',
    'readonly' => 'readonly',
    'required' => true
);

$customer_city = array(
    'name' => 'customer_city',
    'type' => 'text',
    'id'  => 'customer_city',
    'value' => $quote['customer_city'],
    'class' => 'form-control',
    'readonly' => 'readonly',
    'required' => true
);

$customer_postcode = array(
    'name' => 'customer_postcode',
    'type' => 'text',
    'id'  => 'customer_postcode',
    'value' => $quote['customer_postcode'],
    'class' => 'form-control',
    'readonly' => 'readonly',
    'required' => true
);

$customer_state = array(
    'name' => 'customer_state',
    'type' => 'text',
    'id'  => 'customer_state',
    'value' => strtoupper($quote['customer_state']),
    'class' => 'form-control',
    'readonly' => 'readonly',
    'required' => true
);

$customer_email = array(
    'name' => 'customer_email',
    'type' => 'email',
    'id'  => 'customer_email',
    'value' => $quote['customer_email'],
    'class' => 'form-control',
    'readonly' => 'readonly',
    'required' => true
);

$customer_phone = array(
    'name' => 'customer_phone',
    'type' => 'text',
    'id'  => 'customer_phone',
    'value' => $quote['customer_phone'],
    'class' => 'form-control',
    'readonly' => 'readonly',
    'required' => true
);

$product_name = array(
    'name' => 'product_name',
    'type' => 'text',
    'id'  => 'product_name',
    'value' => $quote['product_name'],
    'class' => 'form-control',
    'readonly' => 'readonly',
    'required' => true
);


$updateQuote = array(
    'name'=> 'updateQuote',
    'value' => 'Update',
    'class' => 'btn btn-lg btn-success',
    'type'  => 'submit'

);


$placeorder = array(
    'name'=> 'placeOrder',
    'value' => 'Place Order',
    'class' => 'btn btn-lg btn-primary',
    'type'  => 'submit',
    'content' => 'Place Order',
);



$caravan_specs = get_field('specifications',$quote['product_id']);


?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quote Detail</h1>
</div>


<div class="fluid-container">
    <div class="row form-row">
        <div class="col-md-12 text-left">
            <?php echo form_open('quote/update'); ?>
            <div class="row form-row">
                <div class="col-sm-12">
                    <?php if( $quote['status'] == 'In Order'): ?>
                    <div class="alert alert-info">
                        <?php echo 'This quote has already been submitted to order'; ?>
                    </div>
                    <?php else:  ?>
                        <div class="alert alert-info">
                            <?php echo 'This quote is in review'; ?>
                        </div>
                    <?php endif;?>
                </div>
            </div>
            <div class="row form-row">
                <div class="col-sm-6 col-12">
                    <?php echo form_label('Quote Status', 'status'); ?>
                    <?php echo form_dropdown($quote_status); ?>
                    <?php echo '<div class="errors">'.form_error('$quote_status').'</div>'; ?>
                </div>
            </div>

            <div class="row form-row">
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
                <div class="col-6">
                    <?php echo form_label('Street Address', 'customer_address'); ?>
                    <?php echo form_input($customer_address); ?>
                    <?php echo '<div class="errors">'.form_error('$customer_address').'</div>'; ?>
                </div>
                <div class="col-3">
                    <?php echo form_label('City', 'customer_city'); ?>
                    <?php echo form_input($customer_postcode); ?>
                    <?php echo '<div class="errors">'.form_error('$customer_city').'</div>'; ?>
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
                    <?php echo form_input($product_name); ?>
                    <?php echo '<div class="errors">'.form_error('$product_name').'</div>'; ?>
                </div>
            </div>


            <?php echo form_hidden($quote_id); ?>

            <?php echo form_close(); ?>

        </div>
    </div>
    <div class="row form-row">
        <div class="col-12">
            <?php echo form_label('Custom Options', 'custom_options'); ?>
            <ul class="list-group">
                <?php $_custom_option_price = 0; ?>
                <?php foreach($custom_options as $key => $value ): ?>
                    <li class="list-group-item">
                        <span class="font-weight-bold text-capitalize"><?php echo preg_replace('/[^A-Za-z0-9\-]/', ' ', $key) . ':'; ?> </span>
                        <span class="text-capitalize"><?php echo $value['value'] ?> - Cost $<?php echo $value['price'] ?> </span>
                        <?php $_custom_option_price = $_custom_option_price + $value['price']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php if(is_array($add_on_accessories)):?>
        <?php if(sizeof($add_on_accessories) > 0): ?>
            <div class="row form-row">
                <div class="col-12">
                    <?php echo form_label('Add-On Accessories', 'add_on_accessories'); ?>
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
    <?php endif; ?>

    <div class="row dealer-row">
        <div class="col-12">
            <fieldset class="dealer-detail-section">
                <legend class="header">Dealer Detail</legend>
                <ul class="list-group">
                    <li class="list-group-item">
                        <span class="font-weight-bold text-capitalize">Dealer Name: </span>
                        <span class="text-capitalize"><?php echo $quote['dealer_name']; ?></span>
                    </li>
                    <li class="list-group-item">
                        <span class="font-weight-bold text-capitalize">Dealer Email: </span>
                        <span><?php echo $quote['dealer_email']; ?></span>
                    </li>
                    <li class="list-group-item">
                        <span class="font-weight-bold text-capitalize">Dealer Phone: </span>
                        <span class="text-capitalize"><?php echo $quote['dealer_phone']; ?></span>
                    </li>
                </ul>
            </fieldset>
        </div>
    </div>
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
                        <span class="font-weight-bold text-capitalize">Exterior Price:</span>
                        <span class="text-capitalize price-value"><?php echo ' $ ' . number_format($_custom_option_price); ?></span>
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

</div>
