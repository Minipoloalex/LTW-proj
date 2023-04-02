<?php
declare(strict_types = 1);
?>
<?php function output_create_ticket_form() { ?>
    <form>
        <label>Ticket title
            <input type='text' name='ticket'>
        </label>
        <label>Ticket description
            <textarea></textarea>
        </label>
        <button formaction="action_create_ticket.php" formmethod="post" type="submit">
            Create ticket
        </button>
    </form>
<?php } ?>
