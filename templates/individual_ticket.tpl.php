<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/message.class.php');
require_once(__DIR__ . '/../database/agent.class.php');
require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/hashtag.class.php');

require_once(__DIR__ . '/create_ticket.tpl.php');
?>

<?php function output_single_ticket(Ticket $ticket, array $messages, array $actions,
array $all_hashtags, array $all_agents, array $all_departments) { ?>
    <article id="individual-ticket">
        <header><h1 id="title"><?=$ticket->title?></h1></header>
        <p id="description"><?=$ticket->description?></p>
        <?php

        ?>
        <?php output_ticket_status($ticket->status); ?>
        <!-- <span id="ticket-status"><?=$ticket->status?></span> -->
        <?php
            // if user is admin/agent, he sees inputs. ticket user sees only spans
        ?>
        <!-- Admin/agent view -->
        <?php 
        if (!$ticket->isClosed()) {
            output_change_ticket_info_form($ticket, $all_agents, $all_departments, $all_hashtags);
        }
        else output_reopen_ticket_form($ticket);
        ?>

        <h3> Client view </h3>
        <!-- Client view -->
        <span id="ticket-agent">Agent: <?=$ticket->assignedagent ?? "None"?></span>
        <span id="ticket-department">Department: <?=$ticket->departmentName ?? "None"?></span>
        <span id="ticket-user">Created by: <?=$ticket->username?></span>
        <span id="ticket-priority">Priority: <?=$ticket->priority?></span>
        <span id="ticket-date">Created at: <?=date('F j', $ticket->submitdate)?></span>
        <h4>Hashtags</h4>
        <ul id="ticket-hashtags">
            <?php foreach ($ticket->hashtags as $hashtag) { ?>
                <li class="hashtag"><?=$hashtag->hashtagname?></li>
            <?php } ?>
        </ul>
    </article>
    
    <section id="messages-list">
        <?php
        foreach($messages as $message) {
            output_message($message);
        }
        ?>
    </section>
    <section id="actions-list">
        <?php
        foreach($actions as $action) {
            output_action($action);
        }
        ?>
    </section>
<?php } ?>


<?php function output_message(Message $message) { ?>
    <article class="message">
        <span class="user">UserID: <?=$message->userID?></span>
        <span class="date">DATE: <?=date('F j', $message->date)?></span>
        <p class="message">CONTENT: <?=htmlentities($message->text)?></p>
    </article>
<?php }?>

<?php function output_action(Action $action) { ?>
    
<?php } ?>

<?php function output_message_form(int $ticketID) { ?>
    <form id="message-form">
        <!-- the user can change this value (validate data-id in action.php)-->
        <label>Add a message:
            <input data-id="<?=$ticketID?>" type="text" name="message" id="message-input">
        </label>
        <button id="add-message" type="submit">Submit</button> 

        <!-- Javascript
        comentário no ticket é chamada AJAX (pedido) no servidor para acrescentar, dá resposta a dizer que acrescentou.
        assim, não é necessário dar refresh à pagina e não se perde o contexto
        servidor vai à BD e responde a dizer que acrescentou -->

    </form>
<?php } ?>

<?php function output_change_ticket_info_form(Ticket $ticket, array $agents, array $departments, array $hashtags) { ?>
    <form id="update-ticket-form">
        <header><h3>Change ticket information</h3></header>
        <input id="ticket-id" type="hidden" value='<?=$ticket->ticketid?>'>
        <?php
        output_priority_form($ticket->priority);

        output_department_form($departments, $ticket->departmentName);

        output_agent_form($agents, $ticket->assignedagent);
        $checked_hashtags = $ticket->hashtags;
        $not_checked_hashtags = array_filter($hashtags, function($hashtag) use ($checked_hashtags) {
            return array_search($hashtag->hashtagid, array_column($checked_hashtags, 'hashtagid'), true) === false;
        });
        output_hashtag_form($not_checked_hashtags, $ticket->hashtags);
        ?>

        <button id="update-ticket" type="submit">Save</button>
        
        <button id="close-ticket">Close ticket as solved</button>
        <!-- Javascript to close the ticket and update the page (not done yet) -->
    </form>
<?php } ?>

<?php function output_agent_form(array $agents, ?string $assignedagent) { ?>
    <label>
        Agent
    </label>
    <select name="agent" id="agent-select">
        <option></option>
        <?php foreach($agents as $agent) { ?> 
            <?php if ($agent->username === $assignedagent) { ?>
                <option value=<?=$agent->id?> selected><?=$agent->username?></option>
            <?php } else { ?>
                <option value=<?=$agent->id?>><?=$agent->username?></option>
            <?php } ?>
        <?php } ?>
    </select>
<?php } ?>


<?php function output_ticket_status(string $status) {
    if ($status === 'closed') { ?>
        <span id="ticket-status" class="closed"><?=$status?></span>
    <?php } else { ?>
        <span id="ticket-status"><?=$status?></span>
    <?php }
} ?>

<?php function output_reopen_ticket_form(Ticket $ticket) { ?>
    <form id="reopen-ticket-form" action="../actions/action_reopen_ticket.php" method="post">
        <input name="ticketID" type="hidden" value='<?=$ticket->ticketid?>'>
        <button id="reopen-ticket" type="submit">Reopen ticket</button>
    </form>
<?php } ?>
