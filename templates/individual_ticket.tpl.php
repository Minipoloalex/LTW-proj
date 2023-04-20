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
    <article id="ticket">
        <header><h1 class="title"><?=$ticket->title?></h1></header>
        <p class="description"><?=$ticket->description?></p>
        <?php
            // if user is admin/agent, he sees inputs. ticket user sees only spans
        ?>
        <!-- Admin/agent view -->
        <?php output_change_ticket_info_form($ticket, $all_agents, $all_departments, $all_hashtags); ?>

        <h3> Client view </h3>
        <!-- Client view -->
        <span class="agent"><?=$ticket->assignedagent ?? "Agent is NULL!"?></span>
        <span class="department"><?=$ticket->departmentName ?? "Department is NULL!"?></span>
        <span class="user"><?=$ticket->username?></span>
        <span class="status"><?=$ticket->status?></span>
        <span class="priority"><?=$ticket->priority?></span>
        <span class="date"><?=date('F j', $ticket->submitdate)?></span>
        <h4>Hashtags</h4>
        <ul class="hashtags">
            <?php foreach ($ticket->hashtags as $hashtag) { ?>
                <li><?=$hashtag->hashtagname?></li>
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
        <button type="submit">Submit</button> 
        
        <!-- Javascript
        comentário no ticket é chamada AJAX (pedido) no servidor para acrescentar, dá resposta a dizer que acrescentou.
        assim, não é necessário dar refresh à pagina e não se perde o contexto
        servidor vai à BD e responde a dizer que acrescentou -->

    </form>
<?php } ?>

<?php function output_change_ticket_info_form(Ticket $ticket, array $agents, array $departments, array $hashtags) { ?>
    <form>
        <header><h3>Change ticket information</h3></header>
        <?php output_priority_form(); ?>

        <?php        
        output_department_form($departments, $ticket->departmentName);

        output_agent_form($agents, $ticket->assignedagent);
        
        $not_checked_hashtags = array_diff($hashtags, $ticket->hashtags);
        output_hashtag_form($not_checked_hashtags, $ticket->hashtags);
        ?>

        <button type="submit" formaction=''>Save</button>
        
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
            
            <?php if ($agent->username == $assignedagent) { ?>
                <option value="<?=$agent->id?>" selected><?=$agent->username?></option>
            <?php } else { ?>
                <option value="<?=$agent->id?>"><?=$agent->username?></option>
            <?php } ?>
        <?php } ?>
        <?php /* for each agent, option with agent userID and agent username */ ?>
    </select>
<?php } ?>

<?php function output_priority_form() { ?>
    <h4>Priority</h4>
    <label>High
        <input type="radio" name="priority" value="high">
    </label>
    <label>Medium
        <input type="radio" name="priority" value="medium">
    </label>
    <label>Low
        <input type="radio" name="priority" value="low">
    </label>
<?php } ?>
