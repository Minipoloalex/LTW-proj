<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/hashtag.class.php');
?>

<?php function drawTicket(Ticket $ticket)
{ ?>
    <tr>
        <td><?= $ticket->title?> </td>
        <td><a href="../pages/individual_ticket.php?id=<?=$ticket->ticketid?>"><?=$ticket->ticketid?></a></td>
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

    <table id="ticketTable" class="display-table">
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
        <tbody id="tableData">
            <?php
            foreach ($tickets as $ticket) {
                drawTicket($ticket);
            }
            ?>
        </tbody>
    </table>
<?php } ?>


<?php function drawFilterMenu(array $filterValues)
{ 
    $type = 'ticket';
    ?>

<div class="filter-toggle">
  <h3>Filters <i class="fa fa-caret-right"></i></h3>
</div>

<section id="filter-section" class="filter hidden" data-type="<?php echo $type?>">
    
        <!-- <h3 class="filter-header">Filters</h3> -->
        <div class="filter-options">
        <legend>Status</legend>
        <?php
        foreach ($filterValues[0] as $fv) { ?>
            <input type="checkbox" name="status" id="<?php echo $fv ?>" value="<?php echo $fv ?>" /><label
                for="<?php echo $fv ?>"><?php echo $fv ?></label><br />
        <?php } ?>
        <legend>Priority</legend>
        <?php
        foreach ($filterValues[1] as $fv) { ?>
            <input type="checkbox" name="priorities" id="<?php echo $fv ?>" value="<?php echo $fv ?>" /><label
                for="<?php echo $fv ?>"><?php echo $fv ?></label><br />
        <?php } ?>
        <legend>Hashtags</legend>
        <?php
        foreach ($filterValues[2] as $fv) { ?>
            <input type="checkbox" name="hashtags" id="<?php echo $fv['HashtagName'] ?>" value="<?php echo $fv['HashtagID'] ?>" />
            <label for="<?php echo $fv['HashtagName'] ?>"><?php echo $fv['HashtagName'] ?></label><br />
        <?php } ?>
        <legend>Agent</legend>
        <?php
        foreach ($filterValues[3] as $fv) { ?>
            <input type="checkbox" name="agents" id="<?php echo $fv['Username'] ?>" value="<?php echo $fv['UserID'] ?>" />
            <label for="<?php echo $fv['Username'] ?>"><?php echo $fv['Username'] ?></label><br />
        <?php } ?>
        <legend>Department</legend>
        <?php
        foreach ($filterValues[4] as $fv) { ?>
            <input type="checkbox" name="departments" id="<?php echo $fv['DepartmentName'] ?>" value="<?php echo $fv['DepartmentID'] ?>" />
            <label for="<?php echo $fv['DepartmentName'] ?>"><?php echo $fv['DepartmentName'] ?></label><br />
        <?php } ?>
        </div>
        

        <!-- !NOTE: see filter.js -->
        <button type="button" id="clear-filters">Clear Filters</button>
        <button type="submit" id="filter-values">Filter</button>

        <!-- =========================================================================================== -->

        <!-- !NOTE: Old way -->
        <?php
        /*<button type="button" id="clear-filters" onclick="clearFilters('<?php echo $type?>')">Clear Filters</button>
        <button type="submit" id="filter-values" onclick="getFilterValues('<?php echo $type?>')">Filter</button> -->
        */
        ?>
    </section>
<?php } ?>
