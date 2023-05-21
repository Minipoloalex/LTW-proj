<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/agent.class.php');

?>
<?php function drawAddDepartmentForm(){ ?>
    <div class="add-department-form">
    <p>Want to add a new department?</p>
        <form id="addDepartmentForm">
            <label for="department_name">Department Name (limit 20 characters):</label>
            <input type="text" id="department_name" name="department_name" maxlength="20" required>
            <button type="submit">Add</button>
            <?php
            output_empty_feedback_message("add-department-feedback");
            ?>
        </form>
    </div>
<?php } ?>
