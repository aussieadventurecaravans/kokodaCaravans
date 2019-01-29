
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <!-- <div class="btn-toolbar mb-2 mb-md-0">
         <div class="btn-group mr-2">
             <button class="btn btn-sm btn-outline-secondary">Share</button>
             <button class="btn btn-sm btn-outline-secondary">Export</button>
         </div>
         <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
             This week
         </button>
     </div>-->
</div>

<!--
<canvas class="my-4 w-100 chartjs-render-monitor" id="myChart" width="818" height="345" style="display: block; width: 818px; height: 345px;"></canvas>
-->



<!-- JUMBOTRON -->
<div class="jumbotron m-top-60">
    <h1 class="display-3"><?php echo $message; ?></h1>
    <hr class="my-4">
    <p class="lead">This is a custom order dashboard of Kokoda Caravans. it displays the quotes, orders and customer details  </p>

    <p class="lead">
        <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
    </p>
</div>


<h2>Recent Ticket Requests</h2>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th>Request ID</th>
            <th>Customer name</th>
            <th>Caravan Model</th>
            <th>Customer Email</th>
            <th>Customer Phone</th>
            <th>Date Submit</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!$quote_requests): ?>
            <div class="alert alert-warning">
                <?php echo 'There are no quote request from website at the moment'; ?>
            </div>
        <?php else: ?>
            <?php foreach($quote_requests as $quote_request): ?>
                <?php if($quote_request['status'] == 'In Progress'): ?>
                    <tr>
                        <td><?php echo $quote_request['quote_id'] ?></td>
                        <td><?php echo $quote_request['customer_first_name'] . ' ' . $quote_request['customer_last_name'] ?></td>
                        <td><?php echo $quote_request['product_name'] ?></td>
                        <td><?php echo $quote_request['customer_email'] ?></td>
                        <td><?php echo $quote_request['customer_phone'] ?></td>
                        <td><?php echo $quote_request['date_created'] ?></td>
                    </tr>
                <?php endif; ?>
             <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>