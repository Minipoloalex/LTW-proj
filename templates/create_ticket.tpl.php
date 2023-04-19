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

        <label>
            Hashtags*
        </label>
        <?php output_hashtag_form($hashtags); ?>

        <label>Ticket description*
        </label>
        <textarea name="description"></textarea>
        <?php output_department_form($departments); ?>
        <button formaction="../actions/action_create_ticket.php" formmethod="post" type="submit" class="submit">
            Create ticket
        </button>
    </form>
<?php } ?>

<?php function output_department_form(array $departments) { ?>
    <label>Department</label>
    <select name='department' id='deps'>
        <?php foreach ($departments as $department) { ?>
            <option value=<?= $department->departmentId ?>><?= $department->departmentName ?>
            </option>
        <?php } ?>
    </select>
<?php } ?>
<?php function output_hashtag_form(array $hashtags) { ?>
    <div class="hashtag-select">
        <?php foreach ($hashtags as $hashtag) { ?>
            <input type="checkbox" name="hashtags[]" value="<?= $hashtag->hashtagid ?>">
            <label>
                <?= $hashtag->hashtagname ?>
            </label>
        <?php } ?>
    </div>
<?php } ?>
