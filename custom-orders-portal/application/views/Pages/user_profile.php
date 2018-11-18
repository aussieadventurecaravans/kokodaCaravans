<div class="container">
    <div class="row">
        <div class="col-md-12">

            <table class="table table-bordered table-striped">


                <tr>
                    <th colspan="2"><h4 class="text-left">Users Details</h4></th>

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
</div>