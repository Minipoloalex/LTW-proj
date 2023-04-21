<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/hashtag.class.php');
require_once(__DIR__ . '/../database/department.class.php');

?>

<?php function output_create_ticket_form(PDO $db)
{
    $hashtags = Hashtag::getHashtags($db);
    $departments = Department::getDepartments($db);
    ?>
    <form class="createticket">
        <label>
            Ticket title*
        </label>
        <input type='text' name='title'>

        <?php output_priority_form(NULL); ?>
        <?php output_hashtag_form($hashtags, array()); ?>

        <label>Ticket description*
        </label>
        <textarea name="description"></textarea>
        <?php output_department_form($departments, NULL); ?>
        <button formaction="../actions/action_create_ticket.php" formmethod="post" type="submit" class="submit">
            Create ticket
        </button>
    </form>
<?php } ?>

<?php function output_department_form(array $departments, ?string $departmentName) { ?>
    <label>Department</label>
    <select name='department' id='deps'>
        <option></option>
        <?php foreach ($departments as $department) {
            if ($department->departmentName === $departmentName) { ?>
                <option value=<?=$department->departmentId?> selected><?=$department->departmentName?></option>

            <?php } else { ?>
                <option value=<?=$department->departmentId?>><?=$department->departmentName?></option>
            <?php } ?>
        <?php } ?>
    </select>
<?php } ?>
<?php function output_hashtag_form(array $not_selected_hashtags, array $selected_hashtags) { ?>
    <label>
        Hashtags*
    </label>
    <div class="hashtag-select">
        <?php foreach ($not_selected_hashtags as $hashtag) { ?>
            <input type="checkbox" name="hashtags[]" value="<?= $hashtag->hashtagid ?>">
            <label>
                <?= $hashtag->hashtagname ?>
            </label>
        <?php } ?>
        <?php foreach ($selected_hashtags as $hashtag) { ?>
            <input type="checkbox" name="hashtags[]" value="<?= $hashtag->hashtagid ?>" checked>
            <label>
                <?= $hashtag->hashtagname ?>
            </label>
        <?php } ?>
    </div>
<?php } ?>

<?php function output_priority_form(?string $selected_prio) { ?>
    <h4>Priority</h4>
    <label>High
        <?php if ($selected_prio === "high") { ?>
            <input type="radio" name="priority" value="high" checked>
        <?php } else { ?>
            <input type="radio" name="priority" value="high">
        <?php } ?>
    </label>
    <label>Medium
        <?php if ($selected_prio === "medium") { ?>
            <input type="radio" name="priority" value="medium" checked>
        <?php } else { ?>
            <input type="radio" name="priority" value="medium">
        <?php } ?>
    </label>
    <label>Low
    <?php if ($selected_prio === "low") { ?>
            <input type="radio" name="priority" value="low" checked>
        <?php } else { ?>
            <input type="radio" name="priority" value="low">
        <?php } ?>
    </label>
<?php } ?>
