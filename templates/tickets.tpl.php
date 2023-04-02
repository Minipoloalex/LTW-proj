<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/ticket.class.php');
?>

<?php function drawTicket(Ticket $ticket) { ?>
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
<?php } ?>

<?php function drawTicketsTable($tickets, $title) { ?> 
    
    <table>
        <caption><?=$title?></caption>
        <thead>
            <tr>
                <th>Ticket</th>
                <th>Description</th>
                <th>Agent(s)</th>
                <th>Tags</th>
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
