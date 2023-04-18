<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/client.class.php');
?>

<?php function drawProfileForm(Client $client) { ?>
  <h2>Profile</h2>
  <form action="../actions/action_edit_profile.php" method="post" class="profile">

    <label for="name">Name:</label>
    <input id="name" type="text" name="name" value="<?=$client->name?>">
    
    <label for="username">Last Name:</label>
    <input id="username" type="text" name="username" value="<?=$client->username?>">  
    
    <label for="email">Email:</label>
    <input id="email" type="text" name="email" value="<?=$client->email?>">

    <button type="submit">Save</button>
  </form>
<?php } ?>

<?php function drawProfile() { ?>
  
<?php } ?>
