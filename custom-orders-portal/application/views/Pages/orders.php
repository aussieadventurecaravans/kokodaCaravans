<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Orders List</h1>
</div>


<div class="table-responsive orders-list">
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th>#</th>
            <th>Customer name</th>
            <th>Caravan Model</th>
            <th>Customer Email</th>
            <th>Customer Phone</th>
            <th>Status</th>
            <th>Submit</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!$orders): ?>
            <div class="alert alert-warning">
                <?php echo 'There are no orders at the moment'; ?>
            </div>
        <?php else: ?>
            <?php foreach($orders as $order):  ?>
                <tr>
                    <td><?php echo $order['order_id'] ?></td>
                    <td><?php echo $order['customer_first_name']. ' ' . $order['customer_last_name'] ?></td>
                    <td><?php echo $order['product_name'] ?></td>
                    <td><?php echo $order['customer_email'] ?></td>
                    <td><?php echo $order['customer_phone'] ?></td>
                    <td><?php echo $order['status'] ?></td>
                    <td><?php echo $order['date_created'] ?></td>
                    <td>
                        <a class="btn btn-info btn-sm" href="<?php echo base_url('order/edit') . '?order_id=' . $order['order_id']; ?>" >
                            <span class="fa-edit"></span>Edit
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>