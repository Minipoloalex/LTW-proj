<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
?>

<?php function output_header(Session $session)
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
    <script src="../javascript/cards.js" defer></script>
    <script src="../javascript/filter.js" defer></script>
    <script src="../javascript/edit_profile.js" defer></script>
    <script src="../javascript/update_ticket.js" defer></script>
    <script src="../javascript/manage_FAQ2.js" defer></script>
    <script src="../javascript/add_FAQ.js" defer></script>
    <script src="../javascript/hashtag_autocomplete.js" defer></script>
    <script src="../javascript/answer_with_faq.js" defer></script>

    <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="../css/individual_ticket.css">
    <link rel="stylesheet" href="../css/cards.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/layout.css">
    <link rel="stylesheet" href="../css/buttons.css">

    <!---navbar icons--->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <!-- -->
    <!-- <link rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" /> -->
    <!-- -->
    <!-- <link rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" /> -->
    <!--ticket-->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
      integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!---FAQ icons--->
  <!-- <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" /> -->
</head>

<body data-csrf="<?=$session->getCsrf()?>">

  <!-- <header>
        <h1><a href="main_page.php">Trouble Solver</a></h1>
      </header> -->
    <nav class="navbar">
      <ul class="navbar-nav">

        <!-- <li class="logo">Trouble Solver</li> -->
        <li class="logo"><a href="../pages/main_page.php"><img src="../css/images/logo.png" alt="logo"></a></li>
        <li class="nav-item"><a href="../pages/profile.php" class="nav-link"><span
              class="material-symbols-outlined nav-icon">person</span><span class="link-text">Profile</span></a></li>
        <li class="nav-item"><span class="nav-submenu-wrapper" id="subWrapper"><span
              class="nav-submenu-header"><span class="material-symbols-outlined nav-icon">confirmation_number</span><span
                class="link-text" id="subHeaderTitle">Tickets</span></span>
            <ul class="nav-submenu" id="subMenu">
              <li class="nav-submenu-item"><a href="../pages/my_tickets.php" class="nav-link">My tickets</a></li>
              <li class="nav-submenu-item"><a href="../pages/create_ticket.php" class="nav-link">Create ticket</a></li>
              <li class="nav-submenu-item"><a href="../pages/assigned_tickets.php" class="nav-link">Assigned tickets</a>
              </li>
              <!--Same page: assigned and followed tickets (not assigned to them)-->
              <li class="nav-submenu-item"><a href="../pages/tickets.php" class="nav-link">All tickets</a></li>
            </ul>
          </span>
        </li>
        <li class="nav-item"><a href="../pages/departments_list.php" class="nav-link"><span
              class="material-symbols-outlined nav-icon">apartment</span><span class="link-text">Departments</span></a></li>
        <li class="nav-item"><a href="../pages/users_list.php" class="nav-link"><span
              class="material-symbols-outlined nav-icon">group</span><span class="link-text">Users</span></a></li>
        <li class="nav-item"><a href="../pages/FAQ.php" class="nav-link"><span
              class="material-symbols-outlined nav-icon">quiz</span><span class="link-text">FAQs</span></a></li>
        <li class="nav-item"><a href="../actions/action_logout.php" class="nav-link"><span
              class="material-symbols-outlined nav-icon">logout</span><span class="link-text">Log Out</span></a></li>
        <!-- <span class="nav-logout"><span formaction="../actions/action_logout"
              class="material-symbols-outlined">logout</span><span class="link-text">Log Out</span></span></li> -->
        <!-- <li class="nav-item"><span class="nav-logout"><a href="../actions/action_logout" class="material-symbols-outlined">logout</span><span class = "link-text">Log Out</span></span></li> -->
      </ul>

    </nav>
    <article></article>
    <div class="nav-popup" id="navPopup">
      <ul class="nav-popmenu" id="popMenu">
        <li class="nav-submenu-item"><a href="../pages/my_tickets.php" class="nav-link">My tickets</a></li>
        <li class="nav-submenu-item"><a href="../pages/create_ticket.php" class="nav-link">Create ticket</a></li>
        <li class="nav-submenu-item"><a href="../pages/assigned_tickets.php" class="nav-link">Assigned tickets</a></li>
        <!--Same page: assigned and followed tickets (not assigned to them)-->
        <li class="nav-submenu-item"><a href="../pages/tickets.php" class="nav-link">All tickets</a></li>
      </ul>
    </div>
    <!--
    <section id="session-messages">
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


<?php function output_csrf_input(Session $session) {
  $token = $session->getCsrf();
  ?>
  <input type="hidden" name="csrf" value="<?=$token?>">
<?php } ?>

<?php function output_textarea_form(string $id, string $html_inside_label, string $input_name, array $buttons_html, string $data_id="") { ?>
	<form id='<?=$id?>' class="textarea-form">
		<label for="question-form"><?=$html_inside_label?></label>
		<textarea id="question-form" data-id="<?=$data_id?>" name="<?=$input_name?>" maxlength="100" rows="1" required></textarea>
		<div id="textarea-buttons" class="buttons center">
      <?php foreach ($buttons_html as $button_html) {
        echo($button_html);
      } ?>
    </div>
	</form>
<?php }	?>
