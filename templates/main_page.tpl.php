<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/client.class.php');

require_once(__DIR__ . '/../utils/session.php');

?>

<?php function drawMainPage(Client $client, string $type) { ?>

<div class="mainpage">
  <h1>Hello, <?=$client->name?>!</h1>
  <h2>Welcome to our trouble ticket website</h2>
  <!-- <p>User guide:</p> -->

  <?php if ($type === 'Client') { ?>
    <p1>Our website is designed to help you efficiently manage and resolve any issues or problems you may encounter. You can create a ticket to report an issue, view all your tickets and also access our knowledge base for frequently asked questions.</p1>
    <p2>You can also manage your account and update your personal information in the Profile section. If you have any questions or need further assistance, please don't hesitate to contact us.</p2>
  <?php } else { ?>
    <p1>Our website is designed to help clients efficiently manage and resolve any issues or problems they may encounter. They can create a ticket to report an issue, view all their tickets and also access our knowledge base for frequently asked questions.</p1>
    <p2>They can also manage their account and update their personal information in the Profile section. On the list below you can see what you're able to do! </p2>
  <?php } ?>

  <div class="features">
  <?php if ($type === 'Client') { ?>
    <p3>As a <?=$type?>, you can:</p3>
    <ul>
      <li>Create tickets</li>
      <li>View your created tickets</li>
      <li>Reply to ticket messages from agents</li>
      <li>Edit your profile</li>
      <li>Check FAQs and ask more questions</li>
    </ul>

    <?php } else if ($type === 'Agent') { ?>
        <p3>As an <?=$type?>, you can:</p3>
    <ul>
      <li>Create tickets</li>
      <li>View your tickets and all tickets from you department</li>
      <li>Send ticket messages to clients</li>
      <li>Change the status of a ticket</li>
      <li>Assign tickets to other agents</li>
      <li>View all changes done to a ticket</li>
      <li>Edit your profile</li>
      <li>Answer to FAQs and create new ones</li>
    </ul>

    <?php } else if ($type === 'Admin') { ?>
        <p3>As an <?=$type?>, you can:</p3>
    <ul>
      <li>Create tickets</li>
      <li>View your tickets, assigned tickets and all tickets</li>
      <li>Send ticket messages to clients</li>
      <li>Change the status of a ticket</li>
      <li>Assign tickets to other agents</li>
      <li>View all changes done to a ticket</li>
      <li>Edit your profile</li>
      <li>Control all users</li>
      <li>Manage FAQs</li>
      <li>Add new departments and hashtags</li>
      <li>Control the whole system</li>
    </ul>
  <?php } ?>
  </div>
</div>

<?php } ?>


<?php function output_admin_chart() { ?>
  <section id="charts">
    <canvas id = "open-tickets-chart"></canvas>  
    <canvas id = "closed-tickets-chart"></canvas>
  </section>
<?php } ?>
