<?php

$user_role = $this->session->userdata('user_role');

$quote_id = array(
    'quote_id' => $quote['quote_id']
);

$quote_status = array(
    'name' => 'status',
    'id'  => 'status',
    'value' => $quote['status'],
    'class' => 'form-control',
    'options' => array(
                    'in progress'  => 'In Progress',
                    'in review'  => 'In Review',
                    'in order'  => 'In Order',
                    'in cancel'  => 'In Cancel'
                ),
    'selected' => array($quote['status'])
);


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


$submit = array(
    'name'=> 'updateQuote',
    'value' => 'Update Status',
    'class' => 'btn btn-lg btn-success',
    'type'  => 'submit'
);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quote Detail</h1>
</div>



<div class="fluid-container">
    <div class="row">
        <div class="col-md-12 text-left">

            <?php echo form_open('quote/update',$attr); ?>

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
                    <?php echo form_label('Addess', 'customer_address'); ?>
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

            <?php echo form_hidden($quote_id); ?>

            <div class="row quote-buttons">
                <div class="col-12">
                    <?php echo form_submit($submit);  ?>
                </div>
            </div>

            <?php echo form_close(); ?>

        </div>
    </div>
</div>
