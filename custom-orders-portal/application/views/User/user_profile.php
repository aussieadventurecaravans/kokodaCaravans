<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User Profile Dashboard-CodeIgniter Login Registration</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap.min.css" />

    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/custom.css" />

</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-md-4">

            <table class="table table-bordered table-striped">


                <tr>
                    <th colspan="2"><h4 class="text-center">Users Details</h4></th>

                </tr>
                    <tr>
                        <td>User Name</td>
                        <td><?php echo isset($user_profile['user_name']) ? $user_profile['user_name'] : $this->session->userdata('user_name'); ?></td>
                    </tr>
                    <tr>
                        <td>User Email</td>
                        <td><?php echo isset($user_profile['user_email']) ? $user_profile['user_email'] : $this->session->userdata('user_email'); ?></td>
                    </tr>
                    <tr>
                        <td>User Role</td>
                        <td><?php echo isset($user_profile['user_role']) ? $user_profile['user_role'] : $this->session->userdata('user_role');  ?></td>
                    </tr>

            </table>


        </div>
    </div>
    <a href="<?php echo base_url('user/user_logout');?>" >  <button type="button" class="btn-primary">Log Out</button></a>
</div>



<!-- add the javascript -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>assets/js/feather.min.js"></script>
<script>
    feather.replace()
</script>

<script src="<?php echo base_url();?>assets/js/Chart.min.js"></script>
<script src="<?php echo base_url();?>assets/js/custom.js"></script>


</body>
</html>