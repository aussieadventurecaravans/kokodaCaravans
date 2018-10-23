<?php

$user_role = $this->session->userdata('user_role');

$order_id = array(
    'order_id' => $order['order_id']
);

$custom_options = unserialize($order['custom_options']);
$add_on_options = unserialize($order['add_on_options']);


$order_status = array(
    'name' => 'status',
    'id'  => 'status',
    'value' => $order['status'],
    'class' => 'form-control',
    'options' => array(
        'In Order'  => 'In Order',
        'In Complete'  => 'In Complete',
        'In Cancel'  => 'In Cancel'
    ),
    'selected' => array($order['status']),
);


$firstName = array(
    'name' => 'customer_first_name',
    'type' => 'text',
    'id'  => 'customer_first_name',
    'value' => $order['customer_first_name'],
    'class' => 'form-control',
    'required' => true,
);

$lastName = array(
    'name' => 'customer_last_name',
    'type' => 'text',
    'id'  => 'customer_last_name',
    'value' => $order['customer_last_name'],
    'class' => 'form-control',
    'required' => true
);

$customer_address = array(
    'name' => 'customer_address',
    'type' => 'text',
    'id'  => 'customer_address',
    'value' => $order['customer_address'],
    'class' => 'form-control',
    'required' => true
);

$customer_postcode = array(
    'name' => 'customer_postcode',
    'type' => 'text',
    'id'  => 'customer_postcode',
    'value' => $order['customer_postcode'],
    'class' => 'form-control',
    'required' => true
);

$customer_state = array(
    'name' => 'customer_state',
    'type' => 'text',
    'id'  => 'customer_state',
    'value' => strtoupper($order['customer_state']),
    'class' => 'form-control',
    'required' => true
);

$customer_email = array(
    'name' => 'customer_email',
    'type' => 'email',
    'id'  => 'customer_email',
    'value' => $order['customer_email'],
    'class' => 'form-control',
    'required' => true
);

$customer_phone = array(
    'name' => 'customer_phone',
    'type' => 'text',
    'id'  => 'customer_phone',
    'class' => 'form-control',
    'required' => true,
    'value' => $order['customer_phone']
);

$product_name = array(
    'name' => 'product_name',
    'type' => 'text',
    'id'  => 'product_name',
    'value' => $order['product_name'],
    'class' => 'form-control',
    'readonly' => 'readonly',
    'required' => true
);


$updateOrderButton = array(
    'name'=> 'updateQuote-btn',
    'value' => 'Update',
    'class' => 'btn btn-lg btn-success',
    'type'  => 'button',
    'content' => 'Update Order',
    'data-toggle' => "modal",
    'data-target' => "#update-order-confirm-modal"

);

$updateOrder = array(
    'name'=> 'updateOrder',
    'value' => 'Update',
    'class' => 'btn btn-lg btn-success',
    'type'  => 'submit'
);

$attr = array(
    'id'=>'add_posts',
    'class'=> 'm-top-20'
);

?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Order #0<?php echo $order['order_id']; ?> Detail</h1>
</div>



<div class="fluid-container">
    <div class="row">
        <div class="col-md-12 text-left">

            <?php echo form_open('order/update',$attr); ?>

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
                    <?php echo form_label('Status', 'status'); ?>
                    <?php echo form_dropdown($order_status); ?>
                    <?php echo '<div class="errors">'.form_error('$order_status').'</div>'; ?>
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

            <?php echo form_hidden($order_id); ?>

            <div class="row order-buttons">
                <div class="col-12">
                    <?php echo form_button($updateOrderButton);  ?>
                </div>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="update-order-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="update-order-confirm-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="update-order-confirm-modal-title">Update Quote</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to update this order detail ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">No</button>
                            <?php echo form_submit($updateOrder); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>

        </div>
    </div>
</div>
