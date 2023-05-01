<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/hashtag.class.php');
require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../templates/individual_ticket.tpl.php');
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
        <button formaction="../actions/action_create_ticket.php" formmethod="post" type="submit" class="submit-ticket">
            Create ticket
        </button>
    </form>
<?php } ?>

<?php function output_department_form(array $departments, ?string $departmentName) { ?>
    <label id="department">Department
        <select name='department'>
            <option></option>
            <?php foreach ($departments as $department) {
                if ($department->departmentName === $departmentName) { ?>
                    <option value=<?=$department->departmentId?> selected><?=$department->departmentName?></option>
                <?php } else { ?>
                    <option value=<?=$department->departmentId?>><?=$department->departmentName?></option>
                <?php } ?>
            <?php } ?>
        </select>
    </label>
<?php } ?>
<?php function output_hashtag_form(array $not_selected_hashtags, array $selected_hashtags) { ?>
    <!-- <form id="hashtags" class="autocomplete">
        <label for="hashtag-input">Hashtags</label>
        <input type="text" name="hashtag" id="hashtag-input" value="">
        <button id="add-hashtag" type="submit">Add</button>
    </form> -->
    <?php /*output_hashtag_list($selected_hashtags);*/ ?>
    
    <fieldset id="hashtags" class="create-ticket">
        <legend>Hashtags</legend>
        <?php foreach ($not_selected_hashtags as $hashtag) {
            $id = $hashtag->hashtagid;
            ?>
            <input id="<?=$id?>" type="checkbox" name="hashtags[]" value="<?= $id ?>">
            <label for="<?=$id?>"><?= $hashtag->hashtagname ?></label>
        <?php } ?>
        <?php foreach ($selected_hashtags as $hashtag) {
            $id = $hashtag->hashtagid;
            ?>
            <input id="<?=$id?>" type="checkbox" name="hashtags[]" value="<?= $id ?>" checked>
            <label for="<?=$id?>"><?= $hashtag->hashtagname ?></label>
        <?php } ?>
    </fieldset>

<?php } ?>

<?php function output_priority_form(?string $selected_prio) { ?>
    <fieldset id="priority" class="create-ticket">
        <legend>Priority</legend>
        
        <?php if ($selected_prio === "high") {
            output_selected_priority("High");
        } else {
            output_not_selected_priority("High");
        } ?>

        <?php if ($selected_prio === "medium") {
            output_selected_priority("Medium");
        } else {
            output_not_selected_priority("Medium");
        } ?>
        <?php if ($selected_prio === "low") {
            output_selected_priority("Low");
        } else {
            output_not_selected_priority("Low");
        } ?>
    </fieldset>
<?php } ?>

<?php function output_not_selected_priority(string $prio) { ?>
    <input id="<?=strtolower($prio)?>" type="radio" name="priority" value='<?=strtolower($prio)?>'>
    <label for="<?=strtolower($prio)?>" class="radio-button"><?=$prio?></label>
<?php } ?>
<?php function output_selected_priority(string $prio) { ?>
    <input id="<?=strtolower($prio)?>" type="radio" name="priority" value='<?=strtolower($prio)?>' checked>
    <label for="<?=strtolower($prio)?>" class="radio-button"><?=$prio?></label>
<?php } ?>
