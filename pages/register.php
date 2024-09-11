<?php
  declare(strict_types = 1);

  require_once('../templates/common.tpl.php');
  require_once('../templates/profile.tpl.php');

  require_once('../session/session.php');

  if ($session->isLoggedIn()) {
    header('Location: ../index.php');
    exit();
  }

?>
<main>
  <section class="login-section">
    <div class="register-container">
    <h2>Register</h2>
    <?php drawRegisterForm($session); ?>
    </div>
  </section>
</main>
<?php
