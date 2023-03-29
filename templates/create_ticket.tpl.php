<?php
declare(strict_types = 1);
?>
<?php function output_create_ticket_form() { ?>
    <form>
        <input type='text' name='ticket'>
        <textarea></textarea>
        <button formaction="action_create_ticket.php" formmethod="post" type="submit">
            Create ticket
        </button>
    </form>
<?php } ?>
