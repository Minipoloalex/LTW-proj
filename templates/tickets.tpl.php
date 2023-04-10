<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/hashtag.class.php');
?>

<?php function drawTicket(Ticket $ticket) { ?>
    <tr>
        <td><?=$ticket->title?></td>
        <td><?=$ticket->ticketid?></td>
        <td><?=$ticket->username?></td>
        <td><?=$ticket->status?></td>
        <td><?=$ticket->submitdate?></td>
        <td><?=$ticket->priority?></td>
        <td>
            <ul>
                <?php foreach ($ticket->hashtags as $hashtag) { ?>
                    <li><?=$hashtag->hashtagname?></li>
                <?php } ?>
            </ul>
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

<?php function drawFilterMenu(array $filterValues) { ?>
    <section>
        <h4>Filters</h4>
        <legend>Status</legend>
        <?php
            foreach($filterValues[0] as $st){ ?>
                <input type="radio" name=<?php $st?> id=<?php $st?> value=<?php $st?>/><label for=<?php $st?>><?php $st?></label><br/>
            <?php } ?>
    </section>


<?php } ?>
