<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Orders List</h1>
</div>


<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th>#</th>
            <th>Customer name</th>
            <th>Caravan Model</th>
            <th>Customer Email</th>
            <th>Customer Phone</th>
            <th>Quote Submit</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($orders as $order): ?>
            <tr>
                <td><?php echo $order['order_id'] ?></td>
                <td><?php echo $order['customer_first_name']. ' ' . $quote['customer_last_name'] ?></td>
                <td><?php echo $order['product_name'] ?></td>
                <td><?php echo $order['customer_email'] ?></td>
                <td><?php echo $order['customer_phone'] ?></td>
                <td><?php echo $order['date_created'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>