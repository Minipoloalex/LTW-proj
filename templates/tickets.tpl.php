<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/ticket.class.php');
?>

<?php function drawTicket(Ticket $ticket) { ?>
    <tr>
        <td><?=$ticket->title?></td>
        <td><?=$ticket->ticketid?></td>
        <td><?=$ticket->username?></td>
        <td><?=$ticket->status?></td>
        <td><?=$ticket->submitdate?></td>
        <td><?=$ticket->priority?></td>
        <td><? foreach ($ticket->hashtags as $hashtag) { ?>
            <ul>
                <li><?=$hashtag?></li>
            </ul>
        <? } ?>
        </td>
        <td><?=$ticket->description?></td>
        <td><?=$ticket->assignedagent?></td>
        <td><?=$ticket->departmentName?></td>
    </tr>
<?php } ?>

<?php function drawTicketsTable($tickets, $caption) { ?> 
    
    <table>
        <caption><?=$caption?></caption>
        <thead>
            <tr>
                <th>Title</th>
                <th>Ticket ID</th>
                <th>Ticket Creator</th>
                <th>Status</th>
                <th>Submit Date</th>
                <th>Priority</th>
                <th>Hashtags</th>
                <th>Description</th>
                <th>Assigned agent(s)</th>
                <th>Assigned department</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($tickets as $ticket) {
                drawTicket($ticket);
            }
            ?>
        </tbody>
    </table>
<?php } ?>

