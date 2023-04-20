<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/client.class.php');

require_once(__DIR__ . '/../utils/session.php');
?>

<?php function drawEditButton() {?>
  <button onclick="editProfile()">Edit</button>
<?php } ?>

<?php function drawProfileForm(Client $client, Session $session) { ?>
  <h2>Edit Profile</h2>
  <form action="../actions/action_edit_profile.php" method="post" class="profile">

    <label for="name">Name:</label>
    <input id="name" type="text" name="name" value="<?=$client->name?>">
    
    <label for="username">Username:</label>
    <input id="username" type="text" name="username" value="<?=$client->username?>">  
    
    <label for="email">Email:</label>
    <input id="email" type="text" name="email" value="<?=$client->email?>">

    <label for="old-password">Old password:</label>
    <input id="old-password" type="password" name="old_password" value="">

    <label for="new-password">New password:</label>
    <input id="new-password" type="password" name="new_password" value="">

    <input name="csrf" type="hidden" value="<?=$session->getCsrf()?>">
    <button type="submit">Save</button>

  </form>
<?php } ?>

<?php function drawProfile(Client $client, string $type) {?>
  
  <h2>Profile</h2>
  <div class="profile">
    <label for="name">Name:</label>
    <span id="name"><?=$client->name?></span>
    
    <label for="username">Username:</label>
    <span id="username"><?=$client->username?></span>  
    
    <label for="email">Email:</label>
    <span id="email"><?=$client->email?></span>
  
    <label for="type">Type:</label>
    <span id="type"><?=$type?></span>
  </div>
  
<?php } ?>
