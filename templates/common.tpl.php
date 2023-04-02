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
    <link rel="stylesheet" href="test.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>
  <body>
    <header>
      <h1><a href="#">Trouble Solver</a></h1>
    </header>
    <nav>
        <ul>
            <!-- 2 options: Create Tickets and View Tickets (filters inside view tickets) -->
            <li><a href="">My tickets</a></li>
            
            <li><a href="">Assigned tickets</a></li>

<!-- For agent, only one department (assigned + unassigned [+ following]); For admin, all departments, filters available -->
            <li><a href="">All tickets</a></li>

            <li><a href="">Departments</a></li>
            <li><a href="">Users</a></li>

            <li><a href="">FAQs</a></li>
            <li><a href="">Profile</a></li>
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

