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

    <script src="../javascript/utils.js" defer></script>
    <script src="../javascript/add_message.js" defer></script>
    <script src="../javascript/nav.js" defer></script>
    <script src="../javascript/filter.js" defer></script>
    <script src="../javascript/edit_profile.js" defer></script>
    <script src="../javascript/update_ticket.js" defer></script>
    <script src="../javascript/add_FAQ.js" defer></script>
    <!-- <script src="../javascript/table_pagination.js" defer></script> -->
    <!-- <script src="../javascript/manage_FAQ.js" defer></script> -->
    <script src="../javascript/manage_FAQ2.js" defer></script>
    <script src="../javascript/cards.js" defer></script>
    <script src="../javascript/hashtag_autocomplete.js" defer></script>

    <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="../css/individual_ticket.css">
    <link rel="stylesheet" href="../css/cards.css">
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

    <!---FAQ icons--->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <nav class="navbar">
      <ul class="navbar-nav">
        <li class="logo"><a href="../pages/main_page.php"><img src="../css/images/logo.png" alt="logo"></a></li>
        <li class="nav-item"><a href="../pages/profile.php" class="nav-link"><span
              class="material-symbols-outlined">person</span><span class="link-text">Profile</span></a></li>
        <li class="nav-item"><span class="nav-submenu-wrapper" id="subWrapper" ><span
              class="nav-submenu-header"><span class="material-symbols-outlined">confirmation_number</span><span
                class="link-text" id="subHeaderTitle">Tickets</span></span>
            <ul class="nav-submenu" id="subMenu">
              <li class="nav-submenu-item"><a href="../pages/my_tickets.php" class="nav-link">My tickets</a></li>
              <li class="nav-submenu-item"><a href="../pages/create_ticket.php" class="nav-link">Create ticket</a></li>
              <li class="nav-submenu-item"><a href="../pages/assigned_tickets.php" class="nav-link">Assigned tickets</a>
              </li>
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
        <li class="nav-item"><a href="../actions/action_logout.php" class="nav-link"><span
              class="material-symbols-outlined">logout</span><span class="link-text">Log Out</span></a></li>
      </ul>

    </nav>
    <div class="nav-popup" id="navPopup">
      <ul class="nav-popmenu" id="popMenu">
        <li class="nav-submenu-item"><a href="../pages/my_tickets.php" class="nav-link">My tickets</a></li>
        <li class="nav-submenu-item"><a href="../pages/create_ticket.php" class="nav-link">Create ticket</a></li>
        <li class="nav-submenu-item"><a href="../pages/assigned_tickets.php" class="nav-link">Assigned tickets</a></li>
        <li class="nav-submenu-item"><a href="../pages/tickets.php" class="nav-link">All tickets</a></li>
      </ul>
    </div>
    <section id="session-messages">
      <article>
      </article>
    </section>
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
