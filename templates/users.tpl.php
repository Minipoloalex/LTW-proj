<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/agent.class.php');
require_once(__DIR__ . '/../database/client.class.php');

?>

<!--- confirmar o GET BY AGENT e BY USER (ver se interpretei isso bem), como fazer o user type... --->
<?php function drawUser(Client $user, PDO $db, array $all_departments) { ?>
    <?php $nr_tickets_created = count(Ticket::getByUser($db, $user->id))?>
    <?php $nr_tickets_assigned = count(Ticket::getByAgent($db, $user->id))?>
    <?php $user_type = Client::getType($db, $user->id)?>

    <tr data-id="<?=$user->id?>">
        <td class="name"><?=$user->name?></td>
        <td class="id"><?=$user->id?></td>
        <td class="username"><?=$user->username?></td>
        <?php /* <td><?=$user_type?></td> */ ?>
        <td class="user-type"><?php drawUserType($user->id, $user_type) ?></td>
        <td><?=$nr_tickets_created?></td>
        
        <td><?=($user_type != "Client") ? $nr_tickets_assigned : '-'; ?></td>
        
        <?php $user_department = ($user_type != "Client") ? (Agent::getDepartment($db, $user->id) ?? '-') : '-'; ?>
        <td><?php drawUserDepartment($user, $user_type, $all_departments, $user_department) ?></td>
<?php } ?>

<?php function drawUsersTable(array $users, PDO $db, $all_departments) { ?>
    <input id="visible-users" type="hidden" name="user_ids" value="">
    <table class="display-table">
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
                drawUser($user, $db, $all_departments);
            }
            ?>
        </tbody>
    </table>
    <button id="users-table-save-button">Save</button>
    <button id="cancel"><a href="users_list.php">Cancel</a></button>
<?php } ?>

<?php function drawUserType(int $userID, string $user_type) { ?>
        <?php
        if ($user_type == "Client") {
            drawCheckedType($userID, "client", "Client");
            drawUncheckedType($userID, "agent", "Agent");
            drawUncheckedType($userID, "admin", "Admin");
        }
        else if ($user_type == "Admin") {
            drawUncheckedType($userID, "client", "Client");
            drawUncheckedType($userID, "agent", "Agent");
            drawCheckedType($userID, "admin", "Admin");
        }
        else {
            drawUncheckedType($userID, "client", "Client");   
            drawCheckedType($userID, "agent", "Agent");
            drawUncheckedType($userID, "admin", "Admin");
        }
        
        ?>

<?php } ?>

<?php function drawCheckedType(int $userID, string $type, string $user_type) { ?>
    <?php $id = $type . "-" . $userID; ?>
    <input type="radio" id="<?=$id?>" name="type-<?=$userID?>" value="<?=$user_type?>" checked>
    <label for="<?=$id?>"><?=$user_type?></label><br>
<?php } ?>  
<?php function drawUncheckedType(int $userID, string $type, string $user_type) { ?>
    <?php $id = $type . "-" . $userID; ?>
    <input type="radio" id="<?=$id?>" name="type-<?=$userID?>" value="<?=$user_type?>">
    <label for="<?=$id?>"><?=$user_type?></label><br>
<?php } ?>

<?php function drawUserDepartment(Client $user, string $user_type, array $departments, string $user_department) {
    if ($user_type !== "Client") {
        drawDepartmentSelect($user->id, $departments, $user_department);
    }
    else echo '-';
}
?>

<?php function drawDepartmentSelect(int $userID, array $departments, string $agent_department) { ?>
    <!-- TODO: duplicate code with create_ticket.tpl.php -->
    <select class="department" name="department-<?=$userID?>">  
        <option></option>
        <?php foreach ($departments as $department) {
            if ($department->departmentName === $agent_department) { ?>
                <option value=<?=$department->departmentId?> selected><?=$department->departmentName?></option>
            <?php } else { ?>
                <option value=<?=$department->departmentId?>><?=$department->departmentName?></option>
            <?php } ?>
        <?php } ?>
    </select>
<?php } ?>
