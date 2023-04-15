<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/message.class.php');
?>

<?php function output_single_ticket(Ticket $ticket, array $messages, array $actions) { ?>
    <article id="ticket">
        <header><h1><?=$ticket->title?></h1></header>
        <p><?=$ticket->description?></p>
        <span class="agent"><?=$ticket->assignedagent ?? "Agent is NULL!"?></span>
        <span class="department"><?=$ticket->departmentName ?? "Department is NULL!"?></span>
        <span class="user"><?=$ticket->username?></span>
        <span class="status"><?=$ticket->status?></span>
        <span class="priority"><?=$ticket->priority?></span>
        <span class="date"><?=date('F j', $ticket->submitdate)?></span>
    </article>
    <?php foreach($messages as $message) {
        output_message($message);
    } ?>
    ?>

<?php
/*
    <td>
        <ul>
            <?php foreach ($ticket->hashtags as $hashtag) { ?>
                <li><?=$hashtag->hashtagname?></li>
            <?php } ?>
        </ul>
    </td>
    */
?>
    <!-- ticket history missing: messages, actions -->

<?php } ?>
<?php function output_message(Message $message) { ?>
    <span class="user"><?=$message->userID?></span>
    <span class="date"><?=date('F j', $message->date)?></span>
    <p class="message"><?=$message->text?></p>
<?php }?>

<?php function output_action(Action $action) { ?>
    
<?php } ?>
