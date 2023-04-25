<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/client.class.php');

require_once(__DIR__ . '/../utils/session.php');

?>

<?php function drawMainPage(Client $client, string $type) { ?>

<div class="mainpage">
    <h1>Hello, <?=$client->name?>!</h1>
  <h2>Welcome to our trouble ticket website</h2>
  <p1>Our website is designed to help you efficiently manage and resolve any issues or problems you may encounter. You can create a ticket to report an issue, view all your tickets or the ones assigned to you, and also access our knowledge base for frequently asked questions.</p1>
  <p2>You can also manage your account and update your personal information in the Profile section. If you have any questions or need further assistance, please don't hesitate to contact us through the available channels.</p2>

  <div class="features">
  <?php if ($type === 'Client') { ?>
    <p3>As a <?=$type?>, you can:</p3>
    <ul>
      <li>create tickets</li>
      <li>view your tickets</li>
      <li>Reply to ticket messages from agents</li>

      <li>edit your profile</li>
    </ul>

    <?php } else if ($type === 'Agent') { ?>
        <p3>As an <?=$type?>, you can:</p3>
    <ul>
    <li>create tickets</li>
      <li>view your tickets and all tickets from you department</li>
      <li>Send ticket messages to clients</li>
      <li>Change the status of a ticket</li>
      <li>assign tickets to other agents</li>
       <li>List all changes done to a ticket (e.g., status changes, assignments, edits).</li>
      <li>edit your profile</li>
      
    </ul>

    <?php } else if ($type === 'Admin') { ?>
        <p3>As an <?=$type?>, you can:</p3>
    <ul>
    <li>create tickets</li>
      <li>view your tickets and all tickets from you department</li>
      <li>Send ticket messages to clients</li>
      <li>Change the status of a ticket</li>
      <li>assign tickets to other agents</li>
       <li>List all changes done to a ticket (e.g., status changes, assignments, edits).</li>
      <li>edit your profile</li>
      <li>Upgrade a client to an agent or an admin.</li>
        <li>Add new departments, statuses, and other relevant entities.</li>
        <li>Assign agents to departments.</li>
        <li>Control the whole system.</li>
    </ul>
  </div>
</div>

<?php } ?>

<?php } ?>