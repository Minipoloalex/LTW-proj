<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
?>

<?php function output_header()
{ ?>

  <!DOCTYPE html>
  <html lang="en-US">

  <head>
    <title>Trouble Solver</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="../javascript/add_message.js" defer></script>
    <script src="../javascript/nav.js" defer></script>

    <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="../css/style.css">

    <!---navbar icons--->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <!-- -->
    <link rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- -->
    <link rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!--ticket-->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
      integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>

  <body>
    <!-- <header>
        <h1><a href="main_page.php">Trouble Solver</a></h1>
      </header> -->
    <nav class="navbar" >
      <ul class="navbar-nav">
        <li class="logo">Trouble Solver</li>
        <li class="nav-item"><a href="../pages/profile.php" class="nav-link"><span
              class="material-symbols-outlined">person</span><span class="link-text">Profile</span></a></li>
        <li class="nav-item"><span class="nav-submenuheader" id="subHeader" onclick="toggleSubMenu()"><span
              class="material-symbols-outlined">confirmation_number</span><span class="link-text">Tickets</span>
            <ul class="nav-submenu" id="subMenu">
              <li class="nav-submenu-item"><a href="../pages/tickets.php" class="nav-link">My tickets</a></li>
              <li class="nav-submenu-item"><a href="../pages/create_ticket.php" class="nav-link">Create ticket</a></li>
              <li class="nav-submenu-item"><a href="../pages/tickets.php" class="nav-link">Assigned tickets</a></li>
              <!--Same page: assigned and followed tickets (not assigned to them)-->
              <li class="nav-submenu-item"><a href="../pages/tickets.php" class="nav-link">All tickets</a></li>
            </ul>
          </span>
        </li>

        <li class="nav-item"><a href="../pages/departments_list.php" class="nav-link"><span
              class="material-symbols-outlined">apartment</span><span class="link-text">Departments</span></a></li>
        <li class="nav-item"><a href="../pages/users_list.php" class="nav-link"><span
              class="material-symbols-outlined">group</span><span class="link-text">Users</span></a></li>
        <li class="nav-item"><a href="../pages/FAQ.php" class="nav-link"><span
              class="material-symbols-outlined">quiz</span><span class="link-text">FAQs</span></a></li>
        <li class="nav-item"><span class="nav-logout"><span formaction="../actions/action_logout"
              class="material-symbols-outlined">logout</span><span class="link-text">Log Out</span></span></li>
        <!-- <li class="nav-item"><span class="nav-logout"><a href="../actions/action_logout" class="material-symbols-outlined">logout</span><span class = "link-text">Log Out</span></span></li> -->
      </ul>

    </nav>
    <!--
    <section id="messages">
        <article>
        </article>
      </section>
    -->
    <!-- <main id="page-container"> -->
    <main>
<?php } ?>



<?php function output_footer()
    { ?>
    </main>
    <footer>
      LTW Trouble Ticket Project &copy; 2023
    </footer>
  </body>

  </html>
<?php } ?>
  
<?php function drawLogoutForm( /* Session $session */)
{ ?>
  <form action="../actions/action_logout.php" method="post" class="logout">
    <a href="../pages/profile.php"><!--$session->getName()--></a>
    <button type="submit">Logout</button>
  </form>
<?php } ?>
