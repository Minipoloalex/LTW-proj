<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/message.class.php');
require_once(__DIR__ . '/../database/agent.class.php');
require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/hashtag.class.php');

require_once(__DIR__ . '/create_ticket.tpl.php');
?>
<?php function output_single_ticket_agent_view(Ticket $ticket, array $messages, array $actions,
array $all_hashtags, array $all_agents, array $all_departments) { 
    if (!$ticket->isClosed()) {
        output_change_ticket_info_form($ticket, $all_agents, $all_departments, $all_hashtags);
    }
    else {
        output_single_ticket_info($ticket, $messages, $actions);
        output_reopen_ticket_form($ticket);
    }
}
function output_single_ticket_info(Ticket $ticket, array $messages, array $actions) { ?>
    <span id="agent">Agent: <?=$ticket->assignedagent ?? "None"?></span>
    <span id="department">Department: <?=$ticket->departmentName ?? "None"?></span>
    <span id="priority">Priority: <?=$ticket->priority?></span>
    
    <div id="hashtags">
        Hashtags
        <ul id="hashtags">
            <?php foreach ($ticket->hashtags as $hashtag) { ?>
                <li class="hashtag"><?=$hashtag->hashtagname?></li>
            <?php } ?>
        </ul>
    </div>
<?php }
function output_single_ticket(Ticket $ticket, array $messages, array $actions,
array $all_hashtags, array $all_agents, array $all_departments, int $sessionID, bool $isAgentView) { ?>
    <article id="individual-ticket">
        <header><h1 id="ticket-title"><?=$ticket->title?></h1></header>
        <p id="ticket-description"><?=$ticket->description?></p>
        
        <section id="ticket-info">
            <header><h3>Ticket info</h3></header>
            <?php

            output_ticket_status($ticket->status);
            ?>
            <div id="ticket-created">
                <span id="ticket-user"><?=$ticket->username?></span>
                <span id="ticket-date"><?=date('F j', $ticket->submitdate)?></span>
            </div>
            <?php
            if ($isAgentView) {
                output_single_ticket_agent_view($ticket, $messages, $actions, $all_hashtags, $all_agents, $all_departments);
            }
            else {
                output_single_ticket_info($ticket, $messages, $actions);
            }
            ?>
        </section>
        <section id="messages-list">
            <?php
            foreach($messages as $message) {
                output_message($message, $sessionID, $ticket->username);
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
    </article>
<?php } ?>


<?php function output_message(Message $message, int $sessionID, string $createdBy) { ?>
    <?php
    if ($sessionID === $message->userID) $class = 'self';
    else if ($createdBy === $message->username) $class = 'original-poster';
    else $class = 'other';
    ?>
    <article class="message <?=$class?>">
        <header>
            <span class="user">UserID: <?=$message->userID?></span>
            <span class="date">DATE: <?=date('F j', $message->date)?></span>
        </header>
        <p class="text">CONTENT: <?=htmlentities($message->text)?></p>
    </article>
<?php }?>

<?php function output_action(Action $action) { ?>
    
<?php } ?>

<?php function output_message_form(int $ticketID) { ?>
    <form id="message-form">
        <!-- the user can change this value (TODO: validate data-id in action.php)-->
        <label>Add a message:
            <input data-id="<?=$ticketID?>" type="text" name="message" id="message-input">
        </label>
        <button id="add-message" type="submit">Submit</button>
    </form>
<?php } ?>

<?php function output_change_ticket_info_form(Ticket $ticket, array $agents, array $departments, array $hashtags) { ?>
    <!-- <form> -->
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
        
        <button id="close-ticket">Close ticket</button>
    <!-- </form> -->
<?php } ?>

<?php function output_agent_form(array $agents, ?string $assignedagent) { ?>
    <label id="agent">
        Agent
        <select name="agent">
            <option></option>
            <?php foreach($agents as $agent) { ?> 
                <?php if ($agent->username === $assignedagent) { ?>
                    <option value=<?=$agent->id?> selected><?=$agent->username?></option>
                <?php } else { ?>
                    <option value=<?=$agent->id?>><?=$agent->username?></option>
                <?php } ?>
            <?php } ?>
        </select>
    </label>
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
