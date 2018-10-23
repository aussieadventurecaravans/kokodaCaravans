<?php

$user_role = $this->session->userdata('user_role');

$quote_id = array(
    'quote_id' => $quote['quote_id']
);

$custom_options = unserialize($quote['custom_options']);
$add_on_options = unserialize($quote['add_on_options']);

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

?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quote Detail</h1>
</div>


<div class="fluid-container">
    <div class="row">
        <div class="col-md-12 text-left">
            <?php echo form_open('quote/update'); ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-info">
                        <?php echo 'Quote Details View Only'; ?>
                    </div>
                </div>
            </div>
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

            <?php if(sizeof($add_on_options) > 0): ?>
                <div class="row">
                    <div class="col-12">
                        <?php echo form_label('Add On Options', 'add_on_options'); ?>
                        <ul class="list-group">
                            <?php foreach($add_on_options as $option): ?>
                                <li class="list-group-item">
                                    <span class="font-weight-bold text-capitalize"><?php echo $option['accessory_label'] . ':'; ?> </span>
                                    <span class="text-capitalize"><?php echo '$' . $option['accessory_price']; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>


            <?php echo form_hidden($quote_id); ?>



            <?php echo form_close(); ?>

        </div>
    </div>

</div>
