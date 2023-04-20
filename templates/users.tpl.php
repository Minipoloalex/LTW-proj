<?php 
declare(strict_types = 1);

require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/agent.class.php');
require_once(__DIR__ . '/../database/client.class.php');

?>

<!--- confirmar o GET BY AGENT e BY USER (ver se interpretei isso bem), como fazer o user type... --->
<?php function drawUser(Client $user, PDO $db) { ?>
    <?php $nr_tickets_created = count(Ticket::getByUser($db, $user->id))?>
    <?php $nr_tickets_assigned = count(Ticket::getByAgent($db, $user->id))?>
    <?php $user_type = Client::getType($db, $user->id)?>

    <tr>
        <td><?=$user->name?></td>
        <td><?=$user->id?></td>
        <td><?=$user->username?></td>
        <td><?=$user_type?></td>
        <td><?=$nr_tickets_created?></td>
        
        <td><?=($user_type != "Client") ? $nr_tickets_assigned : '-'; ?></td>
        
        <td><?=($user_type != "Client") ? Agent::getDepartment($db, $user->id) : '-' ?></td>
<?php } ?>

<?php function drawUsersTable(array $users, PDO $db) { ?> 
    <section id="users">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>User ID</th>
                <th>Username</th>
                <th>User type</th>
                <th>Nr Tickets (created)</th>
                <th>Nr Tickets (assigned)</th>
                <th>Department</th>
            </tr>
        </thead>

        <tbody>
            <?php
            foreach($users as $user) {
                drawUser($user, $db);
            }
            ?>
        </tbody>
    </table>
    </section>
<?php } ?>
