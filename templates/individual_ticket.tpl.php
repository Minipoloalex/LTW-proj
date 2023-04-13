<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../database/ticket.class.php');
?>

<?php function output_single_ticket(Ticket $ticket) { ?>
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
