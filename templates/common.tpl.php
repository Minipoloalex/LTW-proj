<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../utils/session.php');
?>

<?php function output_header() { ?>

<DOCTYPE html>
<html lang="en-US">
  <head>
    <title>Trouble Solver</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>
  <body>
    <header>
      <h1><a href="main_page.php">Trouble Solver</a></h1>
    </header>
    <nav>
        <ul>
          <li>Tickets
            <ul>
              <li><a href="../pages/tickets.php">My tickets</a></li>
              <li><a href="../pages/create_ticket.php">Create ticket</a></li>
              <li><a href="../pages/tickets.php">Assigned tickets</a></li>  <!--Same page: assigned and followed tickets (not assigned to them)-->
              <li><a href="../pages/tickets.php">All tickets</a></li>
            </ul>
          </li>
          
          <li><a href="../pages/admin/departments_list.php">Departments</a></li>
          <li><a href="../pages/admin/users_list.php">Users</a></li>

          <li><a href="../pages/FAQ.php">FAQs</a></li>
          <li><a href="../pages/profile.php">Profile</a></li>
        </ul>

    </nav>
    <!--
    <section id="messages">
        <article>
        </article>
      </section>
    -->
    <main>
<?php }?>

<?php function output_footer() { ?>
    </main>
  <footer>
    LTW Trouble Ticket Project &copy; 2023
  </footer>
</body>
</html>
<?php } ?>


<?php function drawLogoutForm(/* Session $session */) { ?>
  <form action="../actions/action_logout.php" method="post" class="logout">
    <a href="../pages/profile.php"><!--$session->getName()--></a>
    <button type="submit">Logout</button>
  </form>
<?php } ?>

