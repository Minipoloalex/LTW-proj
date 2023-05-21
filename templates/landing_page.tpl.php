<?php
require_once(__DIR__ . '/common.tpl.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');
?>
<?php function drawShape()
{ ?>
  <div class="custom-shape-divider-top-1681308841">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
      <path
        d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
        opacity=".25" class="shape-fill" id='1'></path>
      <path
        d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
        opacity=".5" class="shape-fill" id='2'></path>
      <path
        d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
        class="shape-fill" id='3'></path>
    </svg>
  </div>

<?php } ?>


<?php function drawLoginForm(Session $session)
{
  ?>
  <div class="login-container">
    <form class="login-form" action="../actions/action_login.php" method="post">
      <h2>Login</h2>
      <?php
        output_session_message($session, "feedback-message");
        output_csrf_input($session);
      ?>
      <input type="text" id="email" name="email" placeholder="Email" pattern=<?=get_email_regex()?>>
      <div class="password-container">
      <input type="password" id="password" name="password" placeholder="Password">
      <button type="button" id="hidepass" class="hidepass" hidden><span class="material-symbols-outlined hidepass">visibility_off</span></button>
			<button type="button" id="showpass" class="showpass"><span class="material-symbols-outlined showpass">visibility</span></button>
      </div>
      <input type="submit" value="Login">
      <p> <span>Don't have an account?</span> <a href="../pages/register.php" class="register">Sign up</a></p>
    </form>
  </div>


<?php } ?>

<?php function drawRegisterForm(Session $session)
{
  ?>
<div class="landing-register">
  <form action="../actions/action_create_account.php" method="post" class="registerform">
    <h2>Register</h2>
    <?php
    output_session_message($session, "feedback-message");
    output_csrf_input($session);
    ?>

    <input type="text" name="name" placeholder="Name" pattern="<?=get_name_regex()?>">
    <input type="text" name="username" placeholder="Username" pattern="<?=get_username_regex()?>">
    <input type="email" name="email" placeholder="Email">
    <!-- no pattern in email, verified by javascript -->

    <div class="password-container">
      <input id="password" type="password" name="password" placeholder="Password" pattern="<?=get_password_regex()?>">
      <button type="button" id="hidepass" class="hidepass" hidden><span class="material-symbols-outlined hidepass">visibility_off</span></button>
      <button type="button" id="showpass" class="showpass"><span class="material-symbols-outlined showpass">visibility</span></button>
    </div>

    <div class="password-container">
      <input id="confirm-password" type="password" name="confirm_password" placeholder="Confirm password" pattern="<?=get_password_regex()?>">
      <button type="button" id="confirm-hidepass" class="hidepass" hidden><span class="material-symbols-outlined hidepass">visibility_off</span></button>
      <button type="button" id="confirm-showpass" class="showpass"><span class="material-symbols-outlined showpass">visibility</span></button>
    </div>

    <div class="registerbuttons">
      <p class="loginback">Already have an account? <a href="../pages/landing_page.php">Login</a></p>
      <button type="submit" class="create">Create Account</button>
    </div>
  </form>
</div>
<?php } ?>

     

<?php function drawLandingPageHeader()
{ ?>
  <!DOCTYPE html>
    <html lang="en-US">

    <head>
      <title>Trouble Solver</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" href="../css/images/logomobile-escuro.png" type="image/png">
      <link rel="stylesheet" href="../css/colors.css">
      <link rel="stylesheet" href="../css/landing_page.css">
      <link rel="stylesheet" href="../css/general.css">
      <link rel="stylesheet" href="../css/buttons.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
      <script src="../javascript/utils.js" defer></script>
      <script src="../javascript/register_feedback_password.js" defer></script>
      <script src="../javascript/login.js" defer></script>
    </head>

    <body class='landingbody'>
      <main>


  <?php } ?>
