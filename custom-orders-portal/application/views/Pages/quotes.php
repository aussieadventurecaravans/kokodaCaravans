<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quotes List</h1>
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
        <?php foreach($quotes as $quote): ?>
            <tr>
                <td><?php echo $quote['quote_id'] ?></td>
                <td><?php echo $quote['customer_first_name']. ' ' . $quote['customer_last_name'] ?></td>
                <td><?php echo $quote['product_name'] ?></td>
                <td><?php echo $quote['customer_email'] ?></td>
                <td><?php echo $quote['customer_phone'] ?></td>
                <td><?php echo $quote['date_created'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>