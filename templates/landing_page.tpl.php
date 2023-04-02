<?php function drawLoginForm()
{ ?>
  <form action="../actions/action_login.php" method="post" class="login">
    <input type="email" name="email" placeholder="email">
    <input type="password" name="password" placeholder="password">
    <a href="../pages/register.php">Register</a>
    <button type="submit">Login</button>
  </form>
<?php } ?>

<?php function drawRegisterForm()
{ ?>
  <form action="../actions/action_create_account.php" method="post" class="login">

    <input type="text" name="name" placeholder="Name">
    <input type="text" name="username" placeholder="Username">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Password">
    <input type="password" name="confirm_password" placeholder="Confirm password">
    
    <p>Already have an account? <a href="../pages/landing_page.php">Login</a></p>
    <button type="submit">Create Account</button>
  </form>
<?php } ?>

<?php function drawLandingPageHeader()
{ ?>
  <DOCTYPE html>
    <html lang="en-US">

    <head>
      <title>Trouble Solver</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="style.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>

    <body>
      <main>

      <?php } ?>