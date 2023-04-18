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
    <section id="messages-list">
        <?php
        foreach($messages as $message) {
            output_message($message);
        }
        ?>
    </section>

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
    <article class="message">
        <span class="user">UserID: <?=$message->userID?></span>
        <span class="date">DATE: <?=date('F j', $message->date)?></span>
        <p class="message">CONTENT: <?=$message->text?></p>
    </article>
<?php }?>

<?php function output_action(Action $action) { ?>
    
<?php } ?>

<?php function output_message_form(int $ticketID) { ?>
    <form id="message-form">
        <!-- the user can change this value (validate in action.php)-->
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
