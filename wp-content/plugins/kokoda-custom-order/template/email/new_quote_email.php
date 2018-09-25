<?php
?>

<h3>You have a new contact from quote enquirey</h3>

<p>
    <strong>Name:</strong><?= $_quote->customer_name ?>
</p>

<p>
    <strong>email:</strong>
    <a href="mailto:<?= $_quote->customer_email ?>"><?= $_quote->customer_email; ?></a>
</p>

