<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Enquiries List</h1>
</div>


<div class="table-responsive quotes-list">
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th>Request ID</th>
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
        <?php if(!$quote_requests): ?>
            <div class="alert alert-warning">
                <?php echo 'There are no quote requests at the moment'; ?>
            </div>
        <?php else: ?>
            <?php foreach($quote_requests as $quote_request): ?>
                <tr>
                    <td><?php echo $quote_request['quote_id'] ?></td>
                    <td><?php echo $quote_request['customer_first_name']. ' ' . $quote_request['customer_last_name'] ?></td>
                    <td><?php echo $quote_request['product_name'] ?></td>
                    <td><?php echo $quote_request['customer_email'] ?></td>
                    <td><?php echo $quote_request['customer_phone'] ?></td>
                    <td><?php echo $quote_request['status'] ?></td>
                    <td><?php echo $quote_request['date_created'] ?></td>
                    <td>
                        <?php if($quote_request['status'] != 'In Progress'): ?>
                            <a class="btn btn-success btn-sm" href="<?php echo base_url('quote_request') . '?request_id=' . $quote_request['quote_id']; ?>" >
                                <span class="fa-search"></span>View
                            </a>
                        <?php else: ?>
                            <a class="btn btn-info btn-sm" href="<?php echo base_url('quote_request/edit') . '?request_id=' . $quote_request['quote_id']; ?>" >
                                <span class="fa-edit"></span>Edit
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>