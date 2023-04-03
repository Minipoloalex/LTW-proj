<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/hashtag.class.php');
require_once(__DIR__ . '/../database/department.class.php');

?>
<!-- obter lista de hashtags -->


<?php function output_create_ticket_form(PDO $db)
{ 
    $hashtags = Hashtag::getHashtags($db);
    $departments = Department::getDepartments($db);
    print_r($hashtags);
    print_r($departments);
    ?>
    <form>
        <label>Ticket title*
            <input type='text' name='ticket'>
        </label>
        <label>
            Hashtags*
        </label>
        <select name='hashtags' id='tags' multiple>
            <? foreach ($hashtags as $hashtag) { ?>    
                <option value=<?$hashtag->hastagid?>><?$hashtag->HashtagName?><option>
            <? } ?>
        </select>
        <label>Ticket description*
            <textarea></textarea>
        </label>
        <label>Department</label>
        <select name='departments' id='deps'>
        <? foreach ($departments as $department) { ?>    
                <option value=<?$department->departmentId?>><?$department->departmentName?><option>
            <? } ?> 
        <button formaction="action_create_ticket.php" formmethod="post" type="submit">
            Create ticket
        </button>
    </form>
<?php } ?>