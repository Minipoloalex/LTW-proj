<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/agent.class.php');

?>


<?php function drawDepartment(Department $department, PDO $db) { ?>
    <?php $nr_tickets = count(Ticket::getByDepartment($db, $department->departmentId))?>
    <?php $nr_agents = count(Agent::getByDepartment($db, $department->departmentId)) ?>
    <tr>
        <td><?=$department->departmentName?></td>
        <td><?=$department->departmentId?></td>
        <td><?=$nr_tickets?></td>
        <td><?=$nr_agents?></td>
<?php } ?>

<?php function drawDepartmentsTable(array $departments, PDO $db) { ?>
    <section id="departments">
    <table>
        <thead>
            <tr>
                <th>Department Name</th>
                <th>Department ID</th>
                <th>Nr of agents</th>
                <th>Nr of tickets</th>
            </tr>
        </thead>

        <tbody>
            <?php
            foreach($departments as $department) {
                drawDepartment($department, $db);
            }
            ?>
        </tbody>
    </table>
    </section>

<?php } ?>
