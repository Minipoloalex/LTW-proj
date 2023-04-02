<?php 
declare(strict_types = 1);

?>

<?php function output_single_user(Client $user) { ?>
    <li>

    </li>
<?php } ?>

<?php function output_users(array $users) { ?> 
    <section id="users">
        <ul>
            <?php
            foreach($users as $user) output_single_user($user);
            ?>
            
        </ul>
    </section>
<?php } ?>