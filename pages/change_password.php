<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../session/session.php');

    require_once(__DIR__ . '/../database/connection.db.php');

    require_once(__DIR__ . '/../database/user.class.php');

    if (!($session->isLoggedIn())) {
        header('Location: ../index.php');
        exit();
    }

    $db = getDatabaseConnection();
    
    $user_type = $session->isLoggedIn()? User::getUserTypeByUsername($db, $session->getName()) : null;

    if (($user_type != 'admin') && ($session->getName() != $session->getName())) {
        header('Location: ../index.php');
        exit();
    }
?>

<?php drawHeader($session, false); ?>
<link rel="stylesheet" href="../style/registers.css">
<form id="changePasswordForm" action="../actions/action_change_password.php" method="post" >

<?php
?>
    <div class="user-container">
    <span>Old password</span>
    <input type="password" name="old" class="user-info" required>
<?php
?>
    <span>New password</span>
    <input type="password" name="new" class="user-info" required>
    <span>Confirm new password</span>
    <input type="password" name="new2" class="user-info margin-pass" required>
    <input type="hidden" name="csrf" value="<?=$session->getCSRF()?>">
    <button class="submit-button change-pass-button" type="submit">Submit</button>
    </div>
</form>
<section id="messages">
      <?php 
      $messages = $session->getMessages();
      foreach ($messages as $message) {
          echo "<div class='login-register-alert'>{$message['text']}</div>";
      }
      ?>
  </section>

