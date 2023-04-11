<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/hashtag.class.php');
?>

<?php function drawTicket(Ticket $ticket)
{ ?>
    <tr>
        <td><?= $ticket->title ?></td>
        <td><?= $ticket->ticketid ?></td>
        <td><?= $ticket->username ?></td>
        <td><?= $ticket->status ?></td>
        <td><?= $ticket->submitdate ?></td>
        <td><?= $ticket->priority ?></td>
        <td>
            <ul>
                <?php foreach ($ticket->hashtags as $hashtag) { ?>
                    <li><?= $hashtag->hashtagname ?></li>
                <?php } ?>
            </ul>
        </td>
        <td><?= $ticket->description ?></td>
        <td><?= $ticket->assignedagent ?></td>
        <td><?= $ticket->departmentName ?></td>
    </tr>
<?php } ?>

<?php function drawTicketsTable($tickets, $caption)
{ ?>

    <table>
        <caption>
            <?= $caption ?>
        </caption>
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
            foreach ($tickets as $ticket) {
                drawTicket($ticket);
            }
            ?>
        </tbody>
    </table>
<?php } ?>

<?php function drawFilterMenu(array $filterValues)
{ ?>
    <section>
        <h4>Filters</h4>
        <legend>Status</legend>
        <?php
        foreach ($filterValues[0] as $fv) { ?>
            <input type="checkbox" name="<?php echo $fv ?>" id="<?php echo $fv ?>" value="<?php echo $fv ?>" /><label
                for="<?php echo $fv ?>"><?php echo $fv ?></label><br />
        <?php } ?>
        <legend>Priority</legend>
        <?php
        foreach ($filterValues[1] as $fv) { ?>
            <input type="checkbox" name="<?php echo $fv ?>" id="<?php echo $fv ?>" value="<?php echo $fv ?>" /><label
                for="<?php echo $fv ?>"><?php echo $fv ?></label><br />
        <?php } ?>
        <legend>Hashtags</legend>
        <?php
        foreach ($filterValues[2] as $fv) { ?>
            <?php var_dump($fv) ?>
            <input type="checkbox" name="<?php echo $fv[1] ?>" id="<?php echo $fv[1] ?>" value="<?php echo $fv[0] ?>" />
            <label for="<?php echo $fv[1] ?>"><?php echo $fv[1] ?></label><br />
        <?php } ?>
        <legend>Agent</legend>
        <?php
        foreach ($filterValues[3] as $fv) { ?>
            <input type="checkbox" name="<?php echo $fv[1] ?>" id="<?php echo $fv[1] ?>" value="<?php echo $fv[0] ?>" />
            <label for="<?php echo $fv[1] ?>"><?php echo $fv[1] ?></label><br />
        <?php } ?>
        <legend>Department</legend>
        <?php
        foreach ($filterValues[4] as $fv) { ?>
            <input type="checkbox" name="<?php echo $fv[1] ?>" id="<?php echo $fv[1] ?>" value="<?php echo $fv[0] ?>" />
            <label for="<?php echo $fv[1] ?>"><?php echo $fv[1] ?></label><br />
        <?php } ?>
    </section>


<?php } ?>