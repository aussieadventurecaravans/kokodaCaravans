<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login To Kokoda Custom Order</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/custom.css" />
</head>


<body>
<?php

$email = array(
    'name' => 'user_email',
    'type' => 'email',
    'id'  => 'user_email',
    'value' => '',
    'class' => 'form-control login-email-input',
    'placeholder' => 'Enter Email'
);

$pass = array(
    'name' => 'user_password',
    'id'  => 'user_password',
    'class' => 'form-control login-pass-input',
    'value' => '',
    'type' => 'password',
    'placeholder' => 'Enter Password'
);
$attr = array(
    'id'=>'add_posts',
    'class'=> 'm-top-20'
);
$submit = array(
    'name'=> 'submitpost',
    'value' => 'Login',
    'class' => 'btn btn-lg btn-success btn-block',
    'type'  => 'submit'
);
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-4">
            <div class="login-panel panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Custom Order Dashboard</h3>
                </div>
                <?php
                $success_msg= $this->session->flashdata('success_msg');
                $error_msg= $this->session->flashdata('error_msg');

                if($success_msg){
                    ?>
                    <div class="alert alert-success">
                        <?php echo $success_msg; ?>
                    </div>
                    <?php
                }
                if($error_msg){
                    ?>
                    <div class="alert alert-danger">
                        <?php echo $error_msg; ?>
                    </div>
                    <?php
                }
                ?>

                <div class="panel-body">

                    <?php echo form_open('user/login_user',$attr); ?>
                    <?php echo form_input($email); ?>

                    <?php echo form_password($pass); ?>

                    <?php echo form_submit($submit);  ?>

                    <?php echo form_close(); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- add the javascript -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>


</body>

</html>