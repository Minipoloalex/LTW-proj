<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/client.class.php');

require_once(__DIR__ . '/../utils/session.php');
?>


<?php function drawProfileForm(Client $client, Session $session, string $type) { ?>
  <h2 id='yourprofile'>Your profile</h2>
  <form class='profile'  method="post"> <!--- class="edit_profile" // action="../actions/action_edit_profile.php"--->

    <label for="name">Name:</label>
    <input id="name" type="text" name="name" value="<?=$client->name?>" readonly>
    
    <label for="username">Username:</label>
    <input id="username" type="text" name="username" value="<?=$client->username?>" readonly>  
    
    <label for="email">Email:</label>
    <input id="email" type="text" name="email" value="<?=$client->email?>" readonly>

    <div class="password-change">
    <label id="oldpasswordlabel" for="old-password" hidden>Current password:</label>
    <button type=button id='changepass' hidden>Change password</button>
    </div>
    <input id="old-password" type="password" name="old_password" value="" readonly hidden>

    <label for="new-password" hidden>New password:</label>
    <input id="new-password" type="password" name="new_password" value="" readonly hidden>
    
    <label for="type">Type:</label>
    <input id="type" type="text" value="<?=$type?>" readonly>

    <input id='csrf' name="csrf" type="hidden" value="<?=$session->getCsrf()?>">
    <!-- <button type="submit" id="save-btn">Save</button> -->
    <button type="button" id="edit-btn">Edit</button>

    <div>
    <button type="button" id="save-btn" hidden>Save</button>
    <button type="reset" id="cancel-btn" hidden>Cancel</button>
    </div>

   <?php
   output_empty_feedback_message('edit-profile-feedback');
   ?>

  </form>
<?php } ?>
