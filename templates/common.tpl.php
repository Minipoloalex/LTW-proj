<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
?>

<?php function output_header(Session $session, string $type)
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
    <script src="../javascript/manage_FAQ.js" defer></script>
    <script src="../javascript/add_FAQ.js" defer></script>
    <script src="../javascript/hashtag_autocomplete.js" defer></script>
    <script src="../javascript/answer_with_faq.js" defer></script>
    <script src="../javascript/messages_actions.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../javascript/charts.js" defer></script>
    <script src="../javascript/file_input.js" defer></script>

    <link rel="stylesheet" href="../css/buttons.css">
    <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="../css/individual_ticket.css">
    <link rel="stylesheet" href="../css/cards.css">
    <link rel="stylesheet" href="../css/filters.css">
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/landing_page.css">
    <link rel="stylesheet" href="../css/faqs.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/hashtags.css">


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
    <nav class="navbar">
      <ul class="navbar-nav">
        <li class="logo"><a href="../pages/main_page.php"><img src="../css/images/logo.png" alt="logo"></a></li>
        <?php
        output_nav_menu_list_item("../pages/profile.php", "person", "Profile");
        ?>
        <li class="nav-item"><span class="nav-submenu-wrapper" id="subWrapper"><span
              class="nav-submenu-header"><span class="material-symbols-outlined nav-icon">confirmation_number</span><span
                class="link-text" id="subHeaderTitle">Tickets</span></span>
            <ul class="nav-submenu" id="subMenu">
              <?php
              output_my_tickets_li();
              output_create_ticket_li();
              if ($type !== 'Client') {
                output_assigned_tickets_li();
              }
              if ($type === 'Admin') {
                output_all_tickets_li();
              } ?>
            </ul>
          </span>
        </li>
        <?php if ($type === 'Admin') {
          output_nav_menu_list_item("../pages/departments_list.php", "apartment", "Departments");
          output_nav_menu_list_item("../pages/users_list.php", "group", "Users");
        }
        output_nav_menu_list_item("../pages/FAQ.php", "quiz", "FAQs");
        output_nav_menu_list_item("../actions/action_logout.php", "logout", "Log Out");
        ?>
      </ul>
    </nav>
    <div class="nav-popup" id="navPopup">
      <ul class="nav-popmenu" id="popMenu">
        <?php
        output_my_tickets_li();
        output_create_ticket_li();
        if ($type !== 'Client') {
          output_assigned_tickets_li();
        }
        if ($type === 'Admin') {
          output_all_tickets_li();
        }
        ?>
      </ul>
    </div>
    <!-- TENTATIVA DE UM POPUP NO BOTAO DE USER PARA IR PARA A MAIN PAGE EM VERSAO MOBILE -->
    <!-- <div class="nav-popup" id="navPopupForHome">
      <ul class="nav-popupmenu" id="popupMenuForHome">
        <li class="nav-submenu-item"><a href="../pages/profile.php" class="nav-link">My Profile</a></li>
        <li class="nav-submenu-item"><a href="../pages/main_page.php" class="nav-link">Home page</a></li>
      </ul>
    </div> -->
    <main>
    <?php } ?>



    <?php function output_footer()
    { ?>
    </main>

    <div class="mobile-logo"><a href="../pages/main_page.php"><img src="../css/images/logomobile-escuro.png" alt="logo"></a></div>

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

<?php function output_centered_buttons(string $id, array $buttons_html) { ?>
  <div id="<?=$id?>" class="buttons center">
    <?php foreach ($buttons_html as $button_html) {
      echo($button_html);
    } ?>
  </div>
<?php } ?>

<?php function output_textarea(string $textarea_id, string $html_inside_label, string $input_name, int $max_length, string $data_id) { ?>
  <label for="<?=$textarea_id?>"><?=$html_inside_label?></label>
	<textarea id="<?=$textarea_id?>" data-id="<?=$data_id?>" name="<?=$input_name?>" maxlength="<?=$max_length?>" rows="1" required></textarea>
<?php } ?>
<?php function output_textarea_form(string $id, string $html_inside_label, string $input_name, array $buttons_html, int $max_length, string $data_id="") { ?>
	<form id="<?=$id?>" class="textarea-form">
	  <?php output_textarea("question-form", $html_inside_label, $input_name, $max_length, $data_id); ?>
    <?php output_centered_buttons("textarea-buttons", $buttons_html); ?>
	</form>
<?php }	?>

<?php function output_feedback_message(string $id, string $messageText, string $type) {
  if ($type === 'success') $class = 'feedback-message success-message';
  else if ($type === 'error') $class = 'feedback-message error-message';
  else $class = 'feedback-message';
  if (empty($id)) { ?>
    <div class="<?=$class?>"><?=$messageText?></div>
  <?php } else { ?>
    <div id="<?=$id?>" class="<?=$class?>"><?=$messageText?></div>
  <?php } ?>
<?php }
function output_empty_feedback_message(string $id) {
  output_feedback_message($id, "", "nothing");
}
function output_session_message(Session $session, string $id) {
  $message = $session->getMessage();
  if ($message == NULL) {
    output_empty_feedback_message($id);
  }
  else output_feedback_message($id, $message['text'], $message['type']);
} ?>

<?php function drawTitle($title, $type) { ?>
    <section id="title" data-type=<?=$type?>>
        <h2><?=$title?></h2>
    </section>
<?php } ?>

<?php function output_nav_menu_list_item(string $href, string $icon_name, string $text) { ?>
  <li class="nav-item"><a href="<?=$href?>" class="nav-link"><span
              class="material-symbols-outlined nav-icon"><?=$icon_name?></span><span class="link-text"><?=$text?></span></a></li>
<?php } ?>
<?php function output_nav_submenu_list_item(string $href, string $text) { ?>
  <li class="nav-submenu-item"><a href="<?=$href?>" class="nav-link"><?=$text?></a></li>
<?php } ?>

<?php function output_my_tickets_li() {
  output_nav_submenu_list_item("../pages/my_tickets.php", "My tickets");
} ?>
<?php function output_assigned_tickets_li() {
  output_nav_submenu_list_item("../pages/assigned_tickets.php", "Assigned tickets");
} ?>
<?php function output_all_tickets_li() {
  output_nav_submenu_list_item("../pages/tickets.php", "All tickets");
} ?>
<?php function output_create_ticket_li() {
  output_nav_submenu_list_item("../pages/create_ticket.php", "Create ticket");
} ?>

