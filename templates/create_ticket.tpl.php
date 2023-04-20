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
            $selected = "selected" ? $department->departmentName === $departmentName : "";
        ?>
        <!-- TODO: verify this $selected works - do it too for output_agent_form inside individual_ticket.tpl.php -->
            <option value=<?= $department->departmentId ?> selected="<?=$selected?>"><?= $department->departmentName ?>
            </option>
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
